<?php

/**
 * Migration des relations ACF bidirectionnelles
 *
 * Les relations bidirectionnelles d'ACF ne se synchronisent qu'a
 * l'enregistrement d'un post. Les liaisons creees AVANT l'activation de
 * 'bidirectional' n'ont donc jamais rempli le champ miroir.
 *
 * Cette commande parcourt le champ source de chaque paire et reconstruit
 * le champ miroir correspondant. Elle ecrit directement en post meta
 * (format de stockage ACF : tableau d'IDs + cle de reference '_<name>'),
 * ce qui evite toute recursion bidirectionnelle et ne depend pas du
 * chargement des field groups (non charges sous WP-CLI dans ce theme).
 *
 * Usage :
 *   wp moc sync-relations --dry-run        # simulation, n'ecrit rien
 *   wp moc sync-relations                  # applique toutes les paires
 *   wp moc sync-relations --pair=auteurs   # une seule paire
 *   wp moc sync-relations --prune          # retire aussi les liens orphelins
 *
 * Idempotente : relancer la commande ne cree pas de doublons.
 */

if (!defined('WP_CLI') || !WP_CLI) {
    return;
}

class MOC_Relations_Sync_Command
{
    /**
     * Paires de champs a synchroniser.
     *
     * source_meta -> target_meta : on lit le champ source sur chaque post
     * du type source, et on remplit le champ miroir sur le post cible.
     */
    private function pairs()
    {
        return [
            'numero' => [
                'label'        => 'Article.numero -> Numero.articles',
                'source_type'  => 'post',
                'source_meta'  => 'numero',
                'target_meta'  => 'articles',
                'target_key'   => 'field_numero_rel_articles',
                'target_type'  => 'numero',
            ],
            'thematiques' => [
                'label'        => 'Article.thematiques -> Thematique.articles',
                'source_type'  => 'post',
                'source_meta'  => 'thematiques',
                'target_meta'  => 'articles',
                'target_key'   => 'field_thematique_rel_articles',
                'target_type'  => 'thematique',
            ],
            'auteurs' => [
                'label'        => 'Article.auteurs -> Auteur.articles',
                'source_type'  => 'post',
                'source_meta'  => 'auteurs',
                'target_meta'  => 'articles',
                'target_key'   => 'field_auteur_rel_articles',
                'target_type'  => 'auteur',
            ],
            'numero_thematiques' => [
                'label'        => 'Numero.thematiques -> Thematique.numeros',
                'source_type'  => 'numero',
                'source_meta'  => 'thematiques',
                'target_meta'  => 'numeros',
                'target_key'   => 'field_thematique_rel_numeros',
                'target_type'  => 'thematique',
            ],
        ];
    }

    /**
     * Synchronise les champs miroirs des relations ACF bidirectionnelles.
     *
     * ## OPTIONS
     *
     * [--dry-run]
     * : Affiche ce qui serait ecrit, sans modifier la base.
     *
     * [--pair=<pair>]
     * : Limite a une paire : numero, thematiques, auteurs, numero_thematiques.
     *
     * [--prune]
     * : Retire du champ miroir les IDs qui ne sont plus references cote source.
     *   Sans cette option, la commande est purement additive.
     *
     * @when after_wp_load
     */
    public function __invoke($args, $assoc_args)
    {
        $dry_run = isset($assoc_args['dry-run']);
        $prune   = isset($assoc_args['prune']);
        $only    = isset($assoc_args['pair']) ? $assoc_args['pair'] : null;

        $pairs = $this->pairs();

        if ($only) {
            if (!isset($pairs[$only])) {
                WP_CLI::error(sprintf('Paire inconnue : %s. Valeurs possibles : %s', $only, implode(', ', array_keys($pairs))));
            }
            $pairs = [$only => $pairs[$only]];
        }

        if ($dry_run) {
            WP_CLI::log('--- MODE SIMULATION : aucune ecriture en base ---');
        }

        foreach ($pairs as $name => $pair) {
            WP_CLI::log('');
            WP_CLI::log(sprintf('== %s (%s)', $pair['label'], $name));
            $this->sync_pair($pair, $dry_run, $prune);
        }

        WP_CLI::log('');
        WP_CLI::success($dry_run ? 'Simulation terminee.' : 'Synchronisation terminee.');
    }

    private function sync_pair($pair, $dry_run, $prune)
    {
        $source_ids = get_posts([
            'post_type'        => $pair['source_type'],
            'post_status'      => ['publish', 'draft', 'pending', 'future', 'private'],
            'posts_per_page'   => -1,
            'fields'           => 'ids',
            'suppress_filters' => true,
            'no_found_rows'    => true,
        ]);

        if (empty($source_ids)) {
            WP_CLI::log('   Aucun post source.');
            return;
        }

        // target_id => [source_ids]
        $map = [];

        foreach ($source_ids as $source_id) {
            $raw = get_post_meta($source_id, $pair['source_meta'], true);

            foreach ($this->normalize_ids($raw) as $target_id) {
                if (get_post_type($target_id) !== $pair['target_type']) {
                    continue;
                }
                $map[$target_id][] = (int) $source_id;
            }
        }

        if (empty($map)) {
            WP_CLI::log('   Aucune liaison a reporter.');
            return;
        }

        // Cibles a nettoyer : celles qui ont deja une valeur miroir mais
        // n'apparaissent plus dans le mapping reconstruit.
        if ($prune) {
            $all_targets = get_posts([
                'post_type'        => $pair['target_type'],
                'post_status'      => ['publish', 'draft', 'pending', 'future', 'private'],
                'posts_per_page'   => -1,
                'fields'           => 'ids',
                'suppress_filters' => true,
                'no_found_rows'    => true,
            ]);
            foreach ($all_targets as $target_id) {
                if (!isset($map[$target_id])) {
                    $map[$target_id] = [];
                }
            }
        }

        $updated   = 0;
        $unchanged = 0;

        foreach ($map as $target_id => $incoming) {
            $existing = $this->normalize_ids(get_post_meta($target_id, $pair['target_meta'], true));
            $incoming = array_values(array_unique($incoming));

            if ($prune) {
                // La source fait autorite : on garde l'ordre existant pour les
                // IDs toujours valides, puis on ajoute les nouveaux a la suite.
                $kept  = array_values(array_intersect($existing, $incoming));
                $added = array_values(array_diff($incoming, $kept));
                $final = array_merge($kept, $added);
            } else {
                // Additif : on preserve tout l'existant, on complete.
                $final = array_merge($existing, array_values(array_diff($incoming, $existing)));
            }

            if ($final === $existing) {
                $unchanged++;
                continue;
            }

            $delta = count($final) - count($existing);
            WP_CLI::log(sprintf(
                '   #%d %s : %d -> %d liaison(s) (%+d)',
                $target_id,
                $this->truncate(get_the_title($target_id), 45),
                count($existing),
                count($final),
                $delta
            ));

            if (!$dry_run) {
                // Format de stockage ACF : tableau d'IDs (string) + cle de reference.
                update_post_meta($target_id, $pair['target_meta'], array_map('strval', $final));
                update_post_meta($target_id, '_' . $pair['target_meta'], $pair['target_key']);
            }

            $updated++;
        }

        WP_CLI::log(sprintf(
            '   -> %d cible(s) %s, %d inchangee(s).',
            $updated,
            $dry_run ? 'a mettre a jour' : 'mise(s) a jour',
            $unchanged
        ));
    }

    /**
     * Normalise une valeur de meta ACF (ID seul, tableau, objets WP_Post,
     * chaine serialisee) en tableau d'entiers uniques et non vides.
     */
    private function normalize_ids($value)
    {
        if (empty($value)) {
            return [];
        }

        if (is_string($value) && is_serialized($value)) {
            $value = maybe_unserialize($value);
        }

        if (!is_array($value)) {
            $value = [$value];
        }

        $ids = [];
        foreach ($value as $item) {
            if ($item instanceof WP_Post) {
                $item = $item->ID;
            } elseif (is_array($item) && isset($item['ID'])) {
                $item = $item['ID'];
            }

            $id = (int) $item;
            if ($id > 0 && get_post_status($id)) {
                $ids[] = $id;
            }
        }

        return array_values(array_unique($ids));
    }

    private function truncate($text, $length)
    {
        $text = wp_strip_all_tags((string) $text);
        return mb_strlen($text) > $length ? mb_substr($text, 0, $length - 1) . '.' : $text;
    }
}

WP_CLI::add_command('moc sync-relations', 'MOC_Relations_Sync_Command');

# List Filters

Systeme de filtres AJAX generique pour les pages de liste du site.

Utilise par :

- `single-thematique.php` — articles d'une thematique (+ ses sous-thematiques)
- `archive-numero.php` — archive des revues ("Nos Revues")

## Principe

Tout est rendu cote serveur. L'AJAX ne renvoie que deux fragments HTML
(`.list-filters__bar` et `.list-results`) que le JS remplace. Aucune logique
d'affichage n'est dupliquee en JavaScript, et les pages restent fonctionnelles
sans JS (les filtres et la pagination sont de simples parametres d'URL).

## Fichiers

| Fichier | Role |
| --- | --- |
| `filter-functions.php` | options des filtres, construction des `WP_Query`, handler AJAX |
| `markup.php` | composant complet (section titre/filtres + section liste) |
| `bar.php` | fragment "barre de filtres" (titre, dropdowns, tags actifs, reset) |
| `results.php` | fragment "resultats" (grille de cartes + pagination) |
| `list-filters.js` | etat des filtres, URL, appels AJAX |
| `../../functions/list-filters.php` | require + enqueue + nonce |

## Usage

```php
// Page thematique
get_template_part('src/components/list-filters/markup', null, [
  'context' => ['type' => 'thematique', 'id' => get_the_ID(), 'per_page' => 9],
]);

// Archive des revues
get_template_part('src/components/list-filters/markup', null, [
  'title'   => 'Nos Revues',
  'context' => ['type' => 'numero', 'per_page' => 9],
]);
```

## Filtres disponibles

### Thematique (`type => 'thematique'`)

| Param URL | Filtre | Source |
| --- | --- | --- |
| `sous_thematique` | Sous-thematique | enfants directs du CPT `thematique` (affiche uniquement si la thematique en a) |
| `annee` | Annee | annees de publication reellement presentes dans le perimetre |
| `mois` | Mois | affiche uniquement quand une annee est selectionnee |
| `auteur` | Auteur-rice | CPT `auteur` presents dans le perimetre (champ ACF `auteurs`) |
| `tag` | Tags | `post_tag` presents dans le perimetre |

Le perimetre par defaut = la thematique **et tous ses descendants** (sous-thematiques,
sous-sous-thematiques, jusqu'a 5 niveaux — voir `ad_thematique_scope_ids()`).
Un article rattache uniquement a une sous-thematique remonte donc bien dans la page
de sa thematique parente. Selectionner une sous-thematique restreint le perimetre
(et reinitialise auteur / tag).

Le dropdown "Les sous thematiques" ne liste que les **enfants directs**.

Sur une page de sous-thematique, `single-thematique.php` n'affiche pas la liste :
la sous-thematique est une page editoriale (hero + flexible content). La liste peut
etre activee avec `add_filter('ad_sous_thematique_show_list', '__return_true');`.

### Revues (`type => 'numero'`)

| Param URL | Filtre | Source |
| --- | --- | --- |
| `annee` | Annee | champ ACF `date_parution` si renseigne, sinon date de publication |
| `mois` | Mois | idem, affiche uniquement quand une annee est selectionnee |

## Comportements

- Un clic sur une option deja active la deselectionne.
- Chaque tag actif possede une croix de suppression.
- Le bouton "Reinitialiser les filtres" n'apparait que si au moins un filtre est actif.
- Les filtres et la page courante sont ecrits dans l'URL (`?annee=2024&paged=2`)
  et l'historique navigateur (retour arriere) est gere.
- En cas d'erreur AJAX, le JS bascule sur un rechargement complet de l'URL filtree.

## Ajouter un nouveau type de liste

1. Ajouter le type dans `ad_list_filter_params()` (parametres reconnus).
2. Ajouter ses groupes dans `ad_list_filter_groups()`.
3. Ajouter ses arguments de requete dans `ad_list_query_args()`.
4. Choisir la carte a utiliser dans `results.php`.
5. Ajouter la condition d'enqueue dans `src/functions/list-filters.php`.

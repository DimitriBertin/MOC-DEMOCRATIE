<?php
/**
 * Card Placeholder
 *
 * Visuel de repli des cartes quand un contenu n'a pas d'image mise en avant :
 * le motif "moc moc" en fond + une icone ronde jaune centree.
 *
 * Fond  : image ACF "default_preview_image" (options globales) si renseignee,
 *         sinon src/assets/svg/preview-default.svg
 * Icone : src/assets/svg/<slug>.svg, choisie d'apres la categorie de l'article
 *         (actualite, mediatheque, publication, presse, position, ...),
 *         avec repli sur publication.svg
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Icones disponibles dans src/assets/svg.
 */
function ad_card_icon_slugs() {
    static $slugs = null;

    if ($slugs !== null) {
        return $slugs;
    }

    $slugs = [];

    foreach (glob(get_template_directory() . '/src/assets/svg/*.svg') as $file) {
        $slugs[] = basename($file, '.svg');
    }

    // Ces fichiers ne sont pas des icones de carte.
    $slugs = array_diff($slugs, ['logo', 'logo-contrasted', 'preview-default', 'preview-default-alt']);

    return $slugs;
}

/**
 * Slug de l'icone a utiliser pour un contenu.
 *
 * @param int    $post_id
 * @param string $fallback
 * @return string
 */
function ad_card_icon_slug($post_id, $fallback = 'publication') {
    $available = ad_card_icon_slugs();

    // Categories natives (Actualite, Publication, Position, ...)
    foreach (ad_get_the_terms($post_id, 'category') as $term) {
        if (in_array($term->slug, $available, true)) {
            return $term->slug;
        }
    }

    return in_array($fallback, $available, true) ? $fallback : '';
}

/**
 * Affiche le visuel de repli d'une carte (fond + icone).
 *
 * @param int    $post_id
 * @param string $title      Utilise pour l'attribut alt.
 * @param string $icon_slug  Force une icone precise ('' pour aucune icone).
 */
function ad_render_card_placeholder($post_id, $title = '', $icon_slug = null) {
    $title      = $title !== '' ? $title : get_the_title($post_id);
    $icon_slug  = $icon_slug === null ? ad_card_icon_slug($post_id) : $icon_slug;
    $option_img = function_exists('get_field') ? get_field('default_preview_image', 'acf-options-global-fields') : null;

    $image_classes = 'w-full h-full object-cover group-hover:scale-105 duration-300 group-hover:duration-1000 transition-transform';

    if (!empty($option_img)) {
        echo wp_get_attachment_image($option_img, 'medium_large', false, [
            'class'   => $image_classes,
            'loading' => 'lazy',
            'alt'     => esc_attr($title),
        ]);
    } else {
        printf(
            '<img src="%s" class="%s" loading="lazy" alt="%s">',
            esc_url(get_template_directory_uri() . '/src/assets/svg/preview-default.svg'),
            esc_attr($image_classes),
            esc_attr($title)
        );
    }

    if (!empty($icon_slug)) {
        printf(
            '<img src="%s" class="@sm:w-[164px] @sm:h-[164px] @md/lg:w-[164px] @md/lg:h-[164px] absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2" alt="" aria-hidden="true">',
            esc_url(get_template_directory_uri() . '/src/assets/svg/' . $icon_slug . '.svg')
        );
    }
}

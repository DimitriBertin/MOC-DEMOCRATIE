<?php
/**
 * Editeur WYSIWYG : bouton "Exposant" (<sup>) pour les notes de bas de page.
 *
 * - Ajoute le bouton `superscript` aux barres d'outils TinyMCE (Full et Basic d'ACF).
 * - Le rendu front est style dans src/build.scss (`.wysiwyg sup`), avec la
 *   typographie "paragraph small".
 */

if (!defined('ABSPATH')) {
    exit;
}

// Barre TinyMCE standard (priorite 20 : apres le nettoyage d'ad-ui, priorite 10)
add_filter('mce_buttons', function ($buttons) {
    if (!in_array('superscript', $buttons, true)) {
        $pos = array_search('italic', $buttons, true);
        array_splice($buttons, $pos === false ? count($buttons) : $pos + 1, 0, 'superscript');
    }
    return $buttons;
}, 20);

// Barres d'outils ACF (Full / Basic)
add_filter('acf/fields/wysiwyg/toolbars', function ($toolbars) {
    foreach (['Full', 'Basic'] as $name) {
        if (!isset($toolbars[$name][1])) {
            continue;
        }
        if (!in_array('superscript', $toolbars[$name][1], true)) {
            $pos = array_search('italic', $toolbars[$name][1], true);
            array_splice($toolbars[$name][1], $pos === false ? count($toolbars[$name][1]) : $pos + 1, 0, 'superscript');
        }
    }
    return $toolbars;
}, 20);

/**
 * Bloc "Text" (_wysiwyg) : sa barre d'outils est definie ligne par ligne
 * (ACF Extended, ad-ui/acf/components/_wysiwyg/fields.php). On y insere
 * le bouton "Exposant" juste apres l'italique, sans modifier ad-ui.
 */
function ad_wysiwyg_add_superscript_row($field) {
    $rows = $field['acfe_wysiwyg_toolbar_buttons']['acfe_wysiwyg_toolbar_1'] ?? null;

    if (!is_array($rows)) {
        return $field;
    }

    foreach ($rows as $row) {
        if (($row['acfe_wysiwyg_toolbar_row'] ?? '') === 'superscript') {
            return $field; // deja present
        }
    }

    $out = [];
    $added = false;
    foreach ($rows as $row) {
        $out[] = $row;
        if (!$added && ($row['acfe_wysiwyg_toolbar_row'] ?? '') === 'italic') {
            $out[] = ['acfe_wysiwyg_toolbar_row' => 'superscript'];
            $added = true;
        }
    }
    if (!$added) {
        $out[] = ['acfe_wysiwyg_toolbar_row' => 'superscript'];
    }

    $field['acfe_wysiwyg_toolbar_buttons']['acfe_wysiwyg_toolbar_1'] = $out;

    return $field;
}
add_filter('acf/load_field/key=field-wysiwyg-content', 'ad_wysiwyg_add_superscript_row');
add_filter('acf/load_field/name=_wysiwyg_content', 'ad_wysiwyg_add_superscript_row');

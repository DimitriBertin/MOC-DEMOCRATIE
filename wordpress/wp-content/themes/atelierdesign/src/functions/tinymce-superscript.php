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

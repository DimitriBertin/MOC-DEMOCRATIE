<?php
/**
 * Formidable Forms : le bouton d'envoi reprend le style des boutons du site
 * (.button-flat.button-primary, voir ad-ui/core/src/css/button.js).
 */

add_filter('frm_submit_button_class', function ($classes) {
    $classes = is_array($classes) ? $classes : [];

    return array_values(array_unique(array_merge($classes, ['button-flat', 'button-primary'])));
});

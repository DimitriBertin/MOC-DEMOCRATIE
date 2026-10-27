<?php

/**
 * Expose l'e-mail et l'ID Joomla des auteur·rices a l'API REST, uniquement pour
 * les utilisateurs connectes qui peuvent editer (import / maintenance).
 * Les visiteurs ne les voient jamais, ni dans l'API ni sur le site.
 *
 *  - email            : champ ACF « E-mail » (field_auteur_email)
 *  - _email           : reference ACF vers le champ, pour que get_field() fonctionne
 *  - _joomla_id       : meta cachee (non visible dans l'admin), ID du compte sur l'ancien site
 */
add_action('rest_api_init', function () {
    if (! current_user_can('edit_posts')) {
        return;
    }

    $auth = function () {
        return current_user_can('edit_posts');
    };

    foreach (['email', '_email', '_joomla_id'] as $key) {
        register_post_meta('auteur', $key, [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'auth_callback' => $auth,
            'sanitize_callback' => $key === 'email' ? 'sanitize_email' : 'sanitize_text_field',
        ]);
    }
});

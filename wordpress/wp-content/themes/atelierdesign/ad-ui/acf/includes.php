<?php

/**
 * Tokens
 */
require_once __DIR__ . '../../wp/handleTokens.php';

/**
 * Utilities
 */

# General
require_once __DIR__ . '/functions/admin-custom-styles.php';

# ACF related
require_once __DIR__ . '/functions/acf-custom-admin-styles.php';
require_once __DIR__ . '/functions/flexible-parent-conditions.php';
require_once __DIR__ . '/packages/acf-material-symbols/acf-material-symbols.php';
require_once __DIR__ . '/functions/disable-block-editor.php';
require_once __DIR__ . '/functions/default-columns-flexible.php';
require_once __DIR__ . '/functions/default-layout-flexible.php';
require_once __DIR__ . '/functions/default-feature-flexible.php';
require_once __DIR__ . '/functions/columns-width-sync.php';

# TinyMCE related
require_once __DIR__ . '/functions/custom-full-wysiwyg.php';
require_once __DIR__ . '/functions/tinymce-balance-plugin.php';
require_once __DIR__ . '/functions/tinymce-mark-plugin.php';
require_once __DIR__ . '/functions/tinymce-typography-plugin.php';

/**
 * Require all ACF fields from /fields/
 */

if (!defined('WP_CLI')) {
  add_action('acf/include_fields', function () {
    if (! function_exists('acf_add_local_field_group')) {
      return;
    }

    // Get templates bases -- from /components/[component-name]/fields.php
    foreach (glob(__DIR__ . '/components/*/fields.php') as $file) {
      $lastFolder = basename(dirname($file));
      if ($lastFolder == '_group' || $lastFolder == '_accordion' || $lastFolder == '_card' || $lastFolder == '_column' || $lastFolder == '_featureColumn' || $lastFolder == '_columns' || $lastFolder == '_nestedWrapper' || $lastFolder == '_nestedLayout' || $lastFolder == '_wrapper' || $lastFolder == '_layout' || $lastFolder == '_columns-thirdColumn') {
        // do nothing
      } else {
        require_once $file;

        if ($lastFolder == '_wysiwyg') {
          require_once __DIR__ . '/components/_group/fields.php';
          require_once __DIR__ . '/components/_accordion/fields.php';
          require_once __DIR__ . '/components/_card/fields.php';
          require_once __DIR__ . '/components/_column/fields.php';
          require_once __DIR__ . '/components/_featureColumn/fields.php';
          require_once __DIR__ . '/components/_columns/fields.php';
          require_once __DIR__ . '/components/_wrapper/fields.php';
          require_once __DIR__ . '/components/_layout/fields.php';
          require_once __DIR__ . '/components/_columns-thirdColumn/fields.php';
        }
      }
    }


    // Get component fields first -- from /templates/*
    foreach (glob(__DIR__ . '/templates/*.php') as $file) {
      require_once $file;
    }
  });
}

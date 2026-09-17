<?php

/**
 * AD UI
 */

// Core functions & styles
function display_errors($flag = false) {
  if ($flag) {
    @ini_set('display_errors', 1);
    @ini_set('display_startup_errors', 1);
    @error_reporting(E_ALL);

    add_action('init', function() {
      ini_set('display_errors', 1);
      error_reporting(E_ALL);
    });
  }
}

// display_errors(true);


require_once 'ad-ui/wp/init.php';

$adwp = new ADWP([
  'packageFolder' => get_template_directory() . '/ad-ui',
  'packageUrl' => get_template_directory_uri() . '/ad-ui',
  'customFlexibleComponentPath' => get_template_directory() . '/src/components',
  'disableComments' => true,
  'disableEnqueueStyles' => true,
]);

// Advanced Custom Fields modules
require_once 'ad-ui/acf/includes.php';


/**
 * CUSTOM
 */

// Enqueue script with BugHerd's API key in the <head> and as an async script
add_action('wp_enqueue_scripts', function () {
  if (isset($_GET['preview']) && $_GET['preview'] === 'true') return;
  wp_enqueue_script('bugherd', 'https://www.bugherd.com/sidebarv2.js?apikey=dnxrtwcgpb9ckxdfophxua', [], false, false);
}, 1);

// Add async attribute to BugHerd's script
function add_async_attribute($tag, $handle)
{
  if ('bugherd' === $handle) { // Replace 'your-script-handle' with the handle of your script
    // $tag = str_replace(' src', ' async="true" src', $tag);
  }
  return $tag;
}
add_filter('script_loader_tag', 'add_async_attribute', 10, 2);

// Enqueue Font Awesome
function enqueue_fontawesome()
{
  wp_enqueue_script('fontawesome', 'https://kit.fontawesome.com/506008adbe.js', [], null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_fontawesome');

// Enqueue google font both frontend and backend
$font_url = 'https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100..900;1,100..900&display=swap';
function enqueue_google_font()
{
  wp_enqueue_style('google-font', 'https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100..900;1,100..900&display=swap', [], null, 'all');
}
add_action('wp_enqueue_scripts', 'enqueue_google_font');
add_editor_style($font_url);

// Enqueue compiled theme assets
function enqueue_theme_assets() {
  // Enqueue compiled CSS
  wp_enqueue_style(
    'theme-styles',
    get_template_directory_uri() . '/dist/app.css',
    [],
    filemtime(get_template_directory() . '/dist/app.css')
  );
  
  // Enqueue compiled JS
  wp_enqueue_script(
    'theme-scripts',
    get_template_directory_uri() . '/dist/app.js',
    [],
    filemtime(get_template_directory() . '/dist/app.js'),
    true
  );
}
add_action('wp_enqueue_scripts', 'enqueue_theme_assets');

// Require once all the files in /src/functions/* for general functions
foreach (glob(__DIR__ . '/src/functions/*.php') as $file) {
  require_once $file;
}



// Require hero helpers
require_once get_template_directory() . '/src/components/hero/helpers.php';

// Load custom component fields and field groups after ACF is ready
if (!defined('WP_CLI')) {
  add_action('acf/include_fields', function () {
    if (! function_exists('acf_add_local_field_group')) {
      return;
    }

    // Require once all fields located in /src/components/**/fields.php files
    foreach (glob(__DIR__ . '/src/components/**/fields.php') as $file) {
      require_once $file;
    }

    // Require once all the files in /src/fieldGroups/* for creating ACF field groups
    // This is loaded AFTER the components to ensure $adwp->get_block_layouts() includes all layouts
    foreach (glob(__DIR__ . '/src/fieldGroups/*.php') as $file) {
      require_once $file;
    }
  }, 20); // Priority 20 to ensure it runs after the AD UI components (which load at default priority 10)
}


/**
 * 
 * Update Columns One Thid working anywhere 
 *
 */
/**
 * Add firt flexible layout to 2/3
 */
 add_filter('acf/prepare_field/key=field-columns-third-columns_2_3', function ($field) {
  if (!is_admin()) {
    return $field;
  }

  $defaultColumn = [
    'acf_fc_layout' => '_columns-thirdColumn',
    '_columnsThirdColumn_content' => [],
  ];

  if (!is_array($field['value']) || empty($field['value'])) {
    $field['value'] = [
      $defaultColumn,
      // $defaultColumn,
    ];
  }

  return $field;
});


function add_material_symbols_font() {
  wp_enqueue_style(
      'material-symbols-outlined',
      'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200',
      array(),
      null
  );
}
add_action('wp_enqueue_scripts', 'add_material_symbols_font');

if (!defined('WP_CLI')) {
  add_action('acf/include_fields', function () {
    if (! function_exists('acf_add_local_field_group')) {
      return;
    }
  
    foreach (glob(__DIR__ . '/components/**/fields.php') as $file) {
      require_once $file;
    }

    foreach (glob(__DIR__ . '/ad-ui/acf/components/*/fields.php') as $file) {
      $lastFolder = basename(dirname($file));
      if ($lastFolder == '_group' || $lastFolder == '_accordion' || $lastFolder == '_card' || $lastFolder == '_column' || $lastFolder == '_featureColumn' || $lastFolder == '_columns' || $lastFolder == '_nestedWrapper' || $lastFolder == '_nestedLayout' || $lastFolder == '_wrapper' || $lastFolder == '_layout' || $lastFolder == '_columns-thirdColumn') {
        // do nothing
      } else {
        require_once $file;

        if ($lastFolder == '_wysiwyg') {
          require_once __DIR__ . '/ad-ui/acf/components/_group/fields.php';
          require_once __DIR__ . '/ad-ui/acf/components/_accordion/fields.php';
          require_once __DIR__ . '/ad-ui/acf/components/_card/fields.php';
          require_once __DIR__ . '/ad-ui/acf/components/_column/fields.php';
          require_once __DIR__ . '/ad-ui/acf/components/_featureColumn/fields.php';
          require_once __DIR__ . '/ad-ui/acf/components/_columns/fields.php';
          require_once __DIR__ . '/ad-ui/acf/components/_wrapper/fields.php';
          require_once __DIR__ . '/ad-ui/acf/components/_layout/fields.php';
          require_once __DIR__ . '/ad-ui/acf/components/_columns-thirdColumn/fields.php';
        }
      }
    }


    // Get component fields first -- from /templates/*
    foreach (glob(__DIR__ . '/ad-ui/acf/templates/*.php') as $file) {
      require_once $file;
    }

    foreach (glob(__DIR__ . '/fieldGroups/*.php') as $file) {
      require_once $file;
    }
    
  });
}



add_filter('acf/prepare_field/key=field-columns-third-columns_2_3', function ($field) {
  if (!is_admin()) {
    return $field;
  }

  $defaultColumn = [
    'acf_fc_layout' => '_columns-thirdColumn',
    '_columnsThirdColumn_content' => [],
  ];

  if (!is_array($field['value']) || empty($field['value'])) {
    $field['value'] = [
      $defaultColumn,
      // $defaultColumn,
    ];
  }

  return $field;
});

// ─────────────────────────────────────────────
// 1. SHORTCODE CIBLE : crée le point d'ancrage
// ─────────────────────────────────────────────
add_shortcode( 'ancre', function ( $atts ) {
 
  // Valeurs par défaut (priorité : attributs shortcode > champs ACF > vide)
  $defaults = [
      'id'    => '',
      'label' => '',
      'class' => '',
  ];
  $atts = shortcode_atts( $defaults, $atts, 'ancre' );

  // Récupère l'id ACF si pas fourni directement
  if ( empty( $atts['id'] ) ) {
      $atts['id'] = get_field( 'ancre_id' ) ?: '';
  }
  if ( empty( $atts['label'] ) ) {
      $atts['label'] = get_field( 'ancre_label' ) ?: '';
  }

  if ( empty( $atts['id'] ) ) {
      return '<!-- [ancre] : id manquant -->';
  }

  $id    = sanitize_html_class( $atts['id'] );
  $label = esc_html( $atts['label'] );
  $class = esc_attr( $atts['class'] );

  // Balise invisible si pas de libellé, sinon texte visible
  if ( $label ) {
      return sprintf(
          '<span id="%s" class="ancre-wp%s" aria-label="%s" style="display:block;height:0;overflow:hidden;">%s</span>',
          $id,
          $class ? ' ' . $class : '',
          $label,
          $label
      );
  }

  return sprintf(
      '<span id="%s" class="ancre-wp%s" aria-hidden="true" style="display:block;height:0;overflow:hidden;"></span>',
      $id,
      $class ? ' ' . $class : ''
  );
} );


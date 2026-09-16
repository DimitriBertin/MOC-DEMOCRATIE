<?php

/**
 * Add Custom Styles to WP Admin for ACF
 * - changes the ".acf-image-uploader .image-wrap img" to have square thumbnails with a max height of 150px
 */

add_filter('acf/the_field/escape_html_optin', '__return_true');

add_filter('wp_kses_allowed_html', 'acf_add_allowed_svg_tag', 10, 2);
function acf_add_allowed_svg_tag($tags, $context)
{
  if ($context === 'acf') {
    $tags['svg']  = array(
      'xmlns'       => true,
      'fill'        => true,
      'viewbox'     => true,
      'role'        => true,
      'aria-hidden' => true,
      'focusable'   => true,
      'width'       => true,
      'height'      => true,
      'style'       => true,
    );
    $tags['path'] = array(
      'd'    => true,
      'fill' => true,
    );
    $tags['circle'] = array(
      'cx'   => true,
      'cy'   => true,
      'r'    => true,
      'fill' => true,
    );
    // images with data: URI
    $tags['img']['src'] = true;
  }

  return $tags;
}

add_filter('acf/fields/flexible_content/layout_title', function ($title, $field, $layout, $i) {
  $maxlen = 32;

  switch ($layout['name']) {
    case '_button':
      $button_link = get_sub_field('_button_link');
      if (!empty($button_link['title'])) {
        $title .= ": ";
        // $title .= strlen($button_link['title']) > $maxlen ? substr($button_link['title'], 0, $maxlen) . '...' : $button_link['title'];
        $title .= '<span style="font-weight: 400; font-style: italic;">' . $button_link['title'] . '</span>';
      }
      break;

    case '_column':
      $column = get_sub_field('_column_advanced');
      $icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 20 20" height="16" viewBox="0 0 20 20" width="16"><path d="M2,6.5h3v7H2V6.5z M15,6.5v7h3v-7H15z M6,5h8v10H6V5z"/></svg>';
      if (isset($column['isFull']) && $column['isFull'] == true) {
        $title = $icon . ' Row';
      }
      break;

    case '_wrapper':
      $wrapper = get_sub_field('layout_settings') ? get_sub_field('layout_settings')['span'] : '12/12';
      $icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 -960 960 960" width="16" fill="currentColor"><path d="M200-200v80q-33 0-56.5-23.5T120-200h80Zm-80-80v-80h80v80h-80Zm0-160v-80h80v80h-80Zm0-160v-80h80v80h-80Zm80-160h-80q0-33 23.5-56.5T200-840v80Zm80 640v-80h80v80h-80Zm0-640v-80h80v80h-80Zm160 640v-80h80v80h-80Zm0-640v-80h80v80h-80Zm160 640v-80h80v80h-80Zm0-640v-80h80v80h-80Zm160 560h80q0 33-23.5 56.5T760-120v-80Zm0-80v-80h80v80h-80Zm0-160v-80h80v80h-80Zm0-160v-80h80v80h-80Zm0-160v-80q33 0 56.5 23.5T840-760h-80Z"/></svg>';
      // while (have_settings()): the_setting();
      // $span = get_sub_field('span');
      $title = $icon . ' Layout';
      $title .= ": <span style='font-weight: 400; font-style: italic;'>";
      switch ($wrapper) {
        case '1/12':
          $title .= '1:12';
          break;
        case '2/12':
          $title .= '1:6';
          break;
        case '3/12':
          $title .= '1:4 (quarter)';
          break;
        case '4/12':
          $title .= '1:3 (third)';
          break;
        case '5/12':
          $title .= '5:12';
          break;
        case '6/12':
          $title .= '1:2 (half)';
          break;
        case '7/12':
          $title .= '7:12';
          break;
        case '8/12':
          $title .= '2:3 (two-thirds)';
          break;
        case '9/12':
          $title .= '3:4 (three-quarters)';
          break;
        case '10/12':
          $title .= '5:6';
          break;
        case '11/12':
          $title .= '11:12';
          break;
        case '12/12':
          $title .= '1:1 (full)';
          break;
      }
      $title .= '</span>';
      // endwhile;

      break;

    case '_featureColumn':
      $isRow = get_sub_field('layout_settings') ? get_sub_field('layout_settings')['isRow'] : true;
      $icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 20 20" height="16" viewBox="0 0 20 20" width="16"><path d="M2,6.5h3v7H2V6.5z M15,6.5v7h3v-7H15z M6,5h8v10H6V5z"/></svg>';
      // while (have_settings()): the_setting();
      // $span = get_sub_field('span');
      $title = $icon . ' Column';
      $title .= ": <span style='font-weight: 400; font-style: italic;'>";
      if ($isRow !== true) {
        $title .= '1/2';
      } else {
        $title .= '1/1';
      }
      $title .= '</span>';
      break;

    case '_icon':
      $icon_image = get_sub_field('_icon_image');
      if (!empty($icon_image['title'])) {
        $title .= ": ";
        // $title .= strlen($icon_image['title']) > $maxlen ? substr($icon_image['title'], 0, $maxlen) . '...' : $icon_image['title'];
        $title .= '<span style="font-weight: 400; font-style: italic;">' . $icon_image['title'];
      }
      break;

    case '_image':
      $image = get_sub_field('_image');
      if (!empty($image['filename'])) {
        $title .= ": ";
        // $title .= strlen($image['title']) > $maxlen ? substr($image['title'], 0, $maxlen) . '...' : $image['title'];
        $title .= '<span style="font-weight: 400; font-style: italic;">' . $image['filename'];
      }
      break;

    case '_keyNumbers':
      $append = '';
      $prefix = get_sub_field('_keyNumbers_prefix');
      if (!empty($prefix)) {
        $append .= $prefix;
      }
      $number = get_sub_field('_keyNumbers_number');
      if (!empty($prefix)) {
        $append .= ' ' . $number;
      }
      $suffix = get_sub_field('_keyNumbers_suffix');
      if (!empty($suffix)) {
        $append .= ' ' . $suffix;
      }

      if (!empty($append)) {
        $title .= ": ";
        // $title .= strlen($append) > $maxlen ? substr($append, 0, $maxlen) . '...' : $append;
        $title .= '<span style="font-weight: 400; font-style: italic;">' . $append . '</span>';
      }
      break;

    case '_quote':
      $quote_content = get_sub_field('_quote_content');
      if (!empty($quote_content)) {
        $title .= ": ";
        // $title .= strlen($quote_content) > $maxlen ? substr($quote_content, 0, $maxlen) . '...' : $quote_content;
        $title .= '<span style="font-weight: 400; font-style: italic;">' . $quote_content . '</span>';
      }
      break;

    case '_wysiwyg':
      $content = get_sub_field('_wysiwyg_content');
      if (!empty($content)) {
        $content = esc_html(strip_tags($content));
        $title .= ": ";
        // $title .= strlen($content) > $maxlen ? substr($content, 0, $maxlen) . '...' : $content;
        $title .= '<span style="font-weight: 400; font-style: italic;">' . $content . '</span>';
      }
      break;

    case 'accordeon':
      $accordeon_title = get_sub_field('accordeon_title');
      if (!empty($accordeon_title)) {
        $title .= ": ";
        // $title .= strlen($accordeon_title) > $maxlen ? substr($accordeon_title, 0, $maxlen) . '...' : $accordeon_title;
        $title .= '<span style="font-weight: 400; font-style: italic;">' . $accordeon_title . '</span>';
      }
      break;

    case 'feature':
      $feature_image = get_sub_field('feature_image');
      if (!empty($feature_image['filename'])) {
        $title .= ": ";
        // $title .= strlen($feature_image['title']) > $maxlen ? substr($feature_image['title'], 0, $maxlen) . '...' : $feature_image['title'];
        $title .= '<span style="font-weight: 400; font-style: italic;">' . $feature_image['filename'] . '</span>';
      }
      break;

    case 'gallery':
      $gallery_title = get_sub_field('gallery_title');
      if (!empty($gallery_title)) {
        $title .= ": ";
        // $title .= strlen($gallery_title) > $maxlen ? substr($gallery_title, 0, $maxlen) . '...' : $gallery_title;
        $title .= '<span style="font-weight: 400; font-style: italic;">' . $gallery_title . '</span>';
      }
      break;

    case 'poster':
      $poster_image = get_sub_field('poster_image');
      if (!empty($poster_image['filename'])) {
        $title .= ": ";
        // $title .= strlen($poster_image['title']) > $maxlen ? substr($poster_image['title'], 0, $maxlen) . '...' : $poster_image['title'];
        $title .= '<span style="font-weight: 400; font-style: italic;">' . $poster_image['filename'] . '</span>';
      }
      break;

    case 'columns':
      $columns_title = get_sub_field('columns_title');
      if (!empty($columns_title)) {
        $title .= ": ";
        // $title .= strlen($columns_title) > $maxlen ? substr($columns_title, 0, $maxlen) . '...' : $columns_title;
        $title .= '<span style="font-weight: 400; font-style: italic;">' . $columns_title . '</span>';
      }
      break;
  }

  // change any vertical-align style to sub
  return '<div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block; width: calc(100% - 182px); vertical-align: middle;">' . preg_replace('/style\=(\"|\')vertical-align:(.*?)?(\;)?(\"|\')/', 'style="vertical-align:sub;"', $title) . '</div>';
}, PHP_INT_MAX, 4);

add_action('acf/input/admin_enqueue_scripts', function () {
  $script = <<<'JS'
(function ($) {
  if (typeof acf === 'undefined') {
    return;
  }

  const findFlexibleField = function ($layout) {
    const $flexibleField = $layout.closest('.acf-field-flexible-content');
    if (!$flexibleField.length) {
      return null;
    }

    const fieldKey = $flexibleField.data('key');
    if (!fieldKey) {
      return null;
    }

    return acf.getField(fieldKey);
  };

  const refreshLayoutTitle = function ($layout) {
    if (!$layout || !$layout.length || $layout.hasClass('acf-clone')) {
      return;
    }

    const flexibleField = findFlexibleField($layout);

    if (!flexibleField || typeof flexibleField.renderLayout !== 'function') {
      return;
    }

    flexibleField.renderLayout($layout);
  };

  const scheduleRefresh = function ($layout) {
    if (!$layout || !$layout.length || $layout.hasClass('acf-clone')) {
      return;
    }

    const existingTimeout = $layout.data('adRefreshLayoutTitleTimeout');
    if (existingTimeout) {
      window.clearTimeout(existingTimeout);
    }

    const timeout = window.setTimeout(function () {
      refreshLayoutTitle($layout);
      $layout.removeData('adRefreshLayoutTitleTimeout');
    }, 100);

    $layout.data('adRefreshLayoutTitleTimeout', timeout);
  };

  const handleLayoutUpdate = function ($el) {
    const $layout = $el.hasClass('layout') ? $el : $el.closest('.layout');
    scheduleRefresh($layout);
  };

  acf.addAction('append', handleLayoutUpdate, 99999);

  acf.addAction('duplicate', handleLayoutUpdate, 99999);

  $(document).on('input change', '.acf-flexible-content .layout :input', function () {
    const $layout = $(this).closest('.layout');
    scheduleRefresh($layout);
  });
})(jQuery);
JS;

  wp_add_inline_script('acf-input', $script);
});

function acf_add_custom_style_to_wp_admin()
{
  echo '<style>
  
    .acf-image-uploader[data-preview_size="thumbnail"] .image-wrap img {
      aspect-ratio: 1/1;
      object-fit: contain;
      max-height: 150px !important;
    }
    .acf-image-uploader .image-wrap img {
      aspect-ratio: 1/1;
      object-fit: contain;
      max-height: 150px !important;
    }
    .attachment-266x266, .thumbnail img {
      object-fit: contain;
    }
    .acf-field-partners-partner-logo .acf-image-uploader[data-preview_size="thumbnail"] .image-wrap img {
      aspect-ratio: unset;
      object-fit: unset;
      max-height: 60px !important;
    }
    
    .acf-image-w100 .image-wrap,
    .acf-image-w100 .image-wrap img {
      width: 100%;
    }
    table.acf-table tbody.ui-sortable {
      vertical-align: top;
    }

    .logos .acf-image-uploader[data-preview_size="thumbnail"] .image-wrap img {
      aspect-ratio: auto;
      max-height: 60px !important;
      width: auto;
    }
    
    .half-repeater .acf-repeater tr.acf-row:not(.acf-clone) {
      display: inline-block !important;
      width: 50% !important;
    }
    
    .half-repeater
      .acf-repeater
      tr.acf-row:not(.acf-clone)
      tr.acf-row:not(.acf-clone) {
      display: block !important;
      width: 100% !important;
    }
    
    .half-repeater .acf-repeater tr.acf-row:not(.acf-clone) td.acf-fields {
      width: 100%;
    }
    
    .third-repeater .acf-repeater tr.acf-row:not(.acf-clone) {
      display: inline-block !important;
      width: 33.33% !important;
    }
    
    .third-repeater
      .acf-repeater
      tr.acf-row:not(.acf-clone)
      tr.acf-row:not(.acf-clone) {
      display: block !important;
      width: 100% !important;
    }
    
    .third-repeater .acf-repeater tr.acf-row:not(.acf-clone) td.acf-fields {
      width: 100%;
    }
    
    .fourth-repeater .acf-repeater tr.acf-row:not(.acf-clone) {
      display: inline-block !important;
      width: 25% !important;
    }
    
    .fourth-repeater
      .acf-repeater
      tr.acf-row:not(.acf-clone)
      tr.acf-row:not(.acf-clone) {
      display: block !important;
      width: 100% !important;
    }
    
    .fourth-repeater .acf-repeater tr.acf-row:not(.acf-clone) td.acf-fields {
      width: 100%;
    }

    .acf-gallery[data-preview_size="large"] .acf-gallery-attachment {
      width: 33.33%;
    }
    
    .acf-gallery[data-preview_size="large"] .acf-gallery-attachments {
      max-width: 500px;
    }
    
    .fifth-repeater .acf-repeater tr.acf-row:not(.acf-clone) {
      display: inline-block !important;
      width: 20% !important;
    }
    
    .fifth-repeater
      .acf-repeater
      tr.acf-row:not(.acf-clone)
      tr.acf-row:not(.acf-clone) {
      display: block !important;
      width: 100% !important;
    }
    
    .fifth-repeater .acf-repeater tr.acf-row:not(.acf-clone) td.acf-fields {
      width: 100%;
    }

    /*.layout:not(:has(.layout)):has(input:focus, textarea:focus) {
        box-shadow: 0 0 0 2px #2271b1;
    }*/

    .layout[data-layout="_group"] .in-group-hidden {
      display: none;
    }
    .layout[data-layout="_accordeon"] .in-accordeon-hidden {
      display: none;
    }
    .layout[data-layout="_column"] .in-column-hidden {
      display: none;
    }

    /* Hide Gallery Sort Select */
    .acf-gallery-sort {
      display: none;
    }

    /* Hide Collapse Icon on ACF Flexible Content Layouts */
    a.acf-icon.-collapse.small.acf-js-tooltip {
      display: none !important;
    }

    /* Fix ACFE Settings Modal Title Width */
    .acfe-modal.-open .acfe-modal-title>span.title {
      white-space: nowrap;
      text-overflow: ellipsis;
      display: block;
      width: calc(100% - 50px);
      overflow: hidden;
    }

    /* ACFE Hide Close Button in Modal */
    .acfe-modal-footer {
      display: none !important;
    }

    /* No padding for acf-field-wysiwyg */
    .acf-flexible-content .layout>.acf-fields, .acf-flexible-content .layout>.acf-table {
      border: none !important;
    }
    .acf-flexible-content .layout > .acf-fields .acf-field-wysiwyg {
      padding: 0 !important;
      border: none !important;
      margin: -1px;
    }

    /* Inline Preview Plugin Sizing */
    .acfe-modal.-open,
    .acfe-modal-overlay {
      width: calc(100% - var(--preview-width-px, 0px)) !important;
    }
  </style>';
}
add_action('admin_head', 'acf_add_custom_style_to_wp_admin');


/*
 * Hide ACF fields from WP Admin
 */
// add_filter('acf/settings/show_admin', '__return_false');


/**
 * Enable more CSS attributes in ACF rendered elements
 */
add_filter('safe_style_css', function ($css_attributes) {
  $css_attributes[] = 'display';
  $css_attributes[] = 'overflow';
  $css_attributes[] = 'text-overflow';
  $css_attributes[] = 'white-space';
  $css_attributes[] = 'background';
  $css_attributes[] = 'background-color';
  $css_attributes[] = 'background-image';
  $css_attributes[] = 'background-repeat';
  $css_attributes[] = 'background-align';
  $css_attributes[] = 'background-attachment';
  $css_attributes[] = 'text-decoration-color';
  $css_attributes[] = 'text-underline-offset';

  return $css_attributes;
}, 10, 1);

<?php

if (! defined('ABSPATH')) {
  exit;
}

if (! class_exists('\\acf_field')) {
  return;
}

class ACFMS_Field_Material_Symbol extends \acf_field
{

  /**
   * Holds the icon dataset.
   *
   * @var array
   */
  private $icon_data = [];

  /**
   * Initialize the field: set up name, label, defaults, and hooks.
   */
  public function initialize()
  {
    $this->name     = 'material_symbol';
    $this->label    = __('Material Symbol', 'acf-material-symbols');
    $this->category = 'choice';
    $this->defaults = [
      'allow_null'    => 0,
      'multiple'      => 0,
      'return_format' => 'string',
      'families'      => ['outlined', 'rounded', 'sharp'],
    ];

    add_action('acf/input/admin_enqueue_scripts', [$this, 'input_admin_enqueue_scripts']);
    add_action('acf/field_group/admin_enqueue_scripts', [$this, 'field_group_admin_enqueue_scripts']);
    add_action('wp_ajax_acfms/icons/query', [$this, 'handle_icons_query']);
    add_filter('acf/load_field/name=material_symbol', [$this, 'load_field']);
    add_filter('acf/format_value/name=material_symbol', [$this, 'format_value'], 10, 3);
  }

  /**
   * Ensure we only load the large icon dataset once.
   */
  private function load_icon_data()
  {
    if (! empty($this->icon_data)) {
      return;
    }

    $dataset_path = ACFMS_DIR . 'assets/data/material-symbols.json';

    if (! file_exists($dataset_path)) {
      $this->icon_data = [];
      return;
    }

    $contents = file_get_contents($dataset_path);
    $data     = json_decode($contents, true);

    if (empty($data['icons']) || ! is_array($data['icons'])) {
      $this->icon_data = [];
      return;
    }

    $this->icon_data = $data['icons'];
  }

  /**
   * Hooked into ACF select2 requests from the editor.
   */
  public function handle_icons_query()
  {
    if (! function_exists('acf_verify_ajax') || ! acf_verify_ajax()) {
      wp_send_json_error();
      return;
    }

    $this->load_icon_data();

    $search   = isset($_POST['s']) ? sanitize_text_field(wp_unslash($_POST['s'])) : '';
    $families_raw = isset($_POST['families']) ? $_POST['families'] : [];
    if (! is_array($families_raw)) {
      $families_raw = [$families_raw];
    }
    $families = array_filter(array_map('sanitize_text_field', $families_raw));

    $results = [];

    foreach ($this->icon_data as $icon) {
      $name      = $icon['name'];
      $icon_fams = isset($icon['families']) ? (array) $icon['families'] : [];

      if ($search) {
        $match = false;

        if (false !== stripos($name, $search)) {
          $match = true;
        } elseif (! empty($icon['tags'])) {
          foreach ($icon['tags'] as $tag) {
            if (false !== stripos($tag, $search)) {
              $match = true;
              break;
            }
          }
        }

        if (! $match) {
          continue;
        }
      }

      if ($families) {
        $intersection = array_intersect($families, $icon_fams);
        if (empty($intersection)) {
          continue;
        }
      } else {
        $intersection = $icon_fams;
      }

      $results[] = [
        'id'         => $name,
        'text'       => $name,
        'name'       => $name,
        'families'   => array_values($intersection),
        'categories' => isset($icon['categories']) ? $icon['categories'] : [],
        'tags'       => isset($icon['tags']) ? $icon['tags'] : [],
      ];

      if (count($results) >= 150) {
        break;
      }
    }

    wp_send_json(
      [
        'results' => $results,
      ]
    );
  }

  /**
   * Enqueue assets used on the ACF field input screens.
   */
  public function input_admin_enqueue_scripts()
  {
    $families = ['Outlined', 'Rounded', 'Sharp'];
    foreach ($families as $family) {
      wp_enqueue_style(
        'acfms-material-symbols-' . strtolower($family),
        sprintf('https://fonts.googleapis.com/css2?family=Material+Symbols+%1$s:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap', $family),
        [],
        null
      );
    }

    wp_enqueue_style(
      'acfms-input',
      ACFMS_URL . 'assets/css/input.css',
      ['acfms-material-symbols-outlined', 'acfms-material-symbols-rounded', 'acfms-material-symbols-sharp'],
      ACFMS_VERSION
    );

    wp_enqueue_script(
      'acfms-input',
      ACFMS_URL . 'assets/js/input.js',
      ['acf-input', 'jquery'],
      ACFMS_VERSION,
      true
    );

    wp_localize_script(
      'acfms-input',
      'ACFMS',
      [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('acf_nonce'),
      ]
    );
  }

  /**
   * Enqueue assets for field group editor.
   */
  public function field_group_admin_enqueue_scripts()
  {
    wp_enqueue_style(
      'acfms-admin',
      ACFMS_URL . 'assets/css/admin.css',
      [],
      ACFMS_VERSION
    );
  }

  /**
   * Load field settings when editing an ACF field.
   */
  public function load_field($field)
  {
    if (empty($field['families']) || ! is_array($field['families'])) {
      $field['families'] = $this->defaults['families'];
    }

    return $field;
  }

  /**
   * Render field settings inside ACF field editor.
   */
  public function render_field_settings($field)
  {
    acf_render_field_setting($field, [
      'label'        => __('Allow Null?', 'acf-material-symbols'),
      'instructions' => __('Allow the Material Symbol to be cleared.', 'acf-material-symbols'),
      'name'         => 'allow_null',
      'type'         => 'true_false',
      'ui'           => 1,
    ]);

    acf_render_field_setting($field, [
      'label'        => __('Return Format', 'acf-material-symbols'),
      'instructions' => __('Choose the return type of the selected Material Symbol.', 'acf-material-symbols'),
      'name'         => 'return_format',
      'type'         => 'radio',
      'choices'      => [
        'array'     => __('Symbol Data (array)', 'acf-material-symbols'),
        'string'    => __('Symbol Name (string)', 'acf-material-symbols'),
        'codepoint' => __('Codepoint (unicode)', 'acf-material-symbols'),
      ],
      'layout'       => 'horizontal',
      'default_value' => 'array',
    ]);

    acf_render_field_setting($field, [
      'label'        => __('Material Symbol Variants', 'acf-material-symbols'),
      'instructions' => __('Limit icon selection to specific variants.', 'acf-material-symbols'),
      'name'         => 'families',
      'multiple'     => 1,
      'type'         => 'checkbox',
      'toggle'       => 1,
      'choices'      => [
        'outlined' => __('Outlined', 'acf-material-symbols'),
        'rounded'  => __('Rounded', 'acf-material-symbols'),
        'sharp'    => __('Sharp', 'acf-material-symbols'),
      ],
      'default_value' => $this->defaults['families'],
    ]);
  }

  /**
   * Render the field input on post edit screens.
   */
  public function render_field($field)
  {
    $allow_null  = isset($field['allow_null']) ? (int) $field['allow_null'] : 0;
    $value       = isset($field['value']) ? $field['value'] : '';
    $input_id    = esc_attr($field['id']);
    $input_name  = esc_attr($field['name']);
    $families    = ! empty($field['families']) && is_array($field['families']) ? $field['families'] : $this->defaults['families'];
    $families_json = esc_attr(wp_json_encode(array_values($families)));

    $this->load_icon_data();
    $allowed_families = array_map('strval', $families);

    $appearance = isset($field['acf_material_symbol_appearance']) && is_array($field['acf_material_symbol_appearance']) ? $field['acf_material_symbol_appearance'] : [];
    $default_family = isset($appearance['family']) ? $appearance['family'] : 'outlined';
    $default_weight = isset($appearance['wght']) ? $appearance['wght'] : 400;
    $default_grad = isset($appearance['grad']) ? $appearance['grad'] : 0;
    $default_opsz = isset($appearance['opsz']) ? $appearance['opsz'] : 24;
    $default_fill = isset($appearance['fill']) ? $appearance['fill'] : 0;

    // Add data attributes for JS (if needed for future use)
    echo '<div class="acfms-field" data-families="' . $families_json . '" data-family="' . esc_attr($default_family) . '" data-weight="' . esc_attr($default_weight) . '" data-grad="' . esc_attr($default_grad) . '" data-opsz="' . esc_attr($default_opsz) . '" data-fill="' . esc_attr($default_fill) . '">';

    // Preview
    $preview_style = 'font-variation-settings: \'FILL\' ' . (int)$default_fill . ', \'wght\' ' . (int)$default_weight . ', \'GRAD\' ' . (int)$default_grad . ', \'opsz\' ' . (int)$default_opsz . ';';
    $preview_class = 'acfms-preview__icon material-symbols-' . esc_attr($default_family);
    echo '<div class="acfms-preview-label">Your selection:</div><div class="acfms-preview' . ($value ? ' has-value' : '') . '">';
    if ($value) {
      echo '<span class="' . $preview_class . '" aria-hidden="true" style="' . $preview_style . '">' . esc_html($value) . '</span>';
      echo '<span class="acfms-preview__label">' . esc_html(str_replace('_', ' ', $value)) . '</span>';
    } else {
      echo '<span class="acfms-preview__empty-icon material-symbols-' . esc_attr($default_family) . '" aria-hidden="true" style="' . $preview_style . '">hide_image</span>';
      echo '<span class="acfms-preview__empty-text">' . esc_html__('No icon selected', 'acf-material-symbols') . '</span>';
    }
    echo '</div>';

    // Search bar with icon
    echo '<div class="acfms-search-wrapper">';
    echo '<input type="search" class="acfms-search" placeholder="Start typing to search..." autocomplete="off">';
    echo '<span class="acfms-search-icon material-symbols-' . esc_attr($default_family) . '" aria-hidden="true" style="' . $preview_style . '">search</span>';
    echo '</div>';

    // Hidden input to track the correct field name (ACF updates this when cloning)
    // This ensures dynamically generated radio buttons get the correct name
    echo '<input type="hidden" class="acfms-field-name-tracker" name="' . $input_name . '" value="" disabled>';

    // Radio group - initially empty, populated via AJAX on search
    echo '<div class="acfms-radio-group" role="radiogroup" aria-labelledby="' . $input_id . '-label" data-allow-null="' . esc_attr($allow_null) . '" data-input-name="' . esc_attr($input_name) . '">';

    // Only render "None" option and currently selected value on initial load
    if ($allow_null) {
      echo '<label class="acfms-radio acfms-radio--none">'
        . '<input type="radio" name="' . $input_name . '" value=""' . checked($value === '', true, false) . '>'
        . esc_html__('— None —', 'acf-material-symbols')
        . '</label>';
    }

    // Render only the currently selected icon if it exists
    if ($value) {
      $selected_icon = null;
      if (! empty($this->icon_data)) {
        foreach ($this->icon_data as $icon) {
          if ($icon['name'] === $value) {
            $selected_icon = $icon;
            break;
          }
        }
      }

      if ($selected_icon) {
        $icon_name     = $selected_icon['name'];
        $icon_families = isset($selected_icon['families']) ? (array) $selected_icon['families'] : [];
        $icon_categories = isset($selected_icon['categories']) ? (array) $selected_icon['categories'] : [];
        $icon_tags = isset($selected_icon['tags']) ? (array) $selected_icon['tags'] : [];

        $icon_class = 'acfms-option__icon material-symbols-' . esc_attr($default_family);
        $icon_style = 'font-variation-settings: \'FILL\' ' . (int)$default_fill . ', \'wght\' ' . (int)$default_weight . ', \'GRAD\' ' . (int)$default_grad . ', \'opsz\' ' . (int)$default_opsz . ';';

        echo '<label class="acfms-radio"'
          . ' data-name="' . esc_attr($icon_name) . '"'
          . ' data-categories="' . esc_attr(implode(',', $icon_categories)) . '"'
          . ' data-tags="' . esc_attr(implode(',', $icon_tags)) . '">'
          . '<input type="radio" name="' . $input_name . '" value="' . esc_attr($icon_name) . '" data-families="' . esc_attr(wp_json_encode(array_values($icon_families))) . '" checked>'
          . '<span class="' . $icon_class . '" style="' . $icon_style . '">' . esc_html($icon_name) . '</span>'
          . '<span class="acfms-option__label">' . esc_html($icon_name) . '</span>'
          . '</label>';
      } else {
        // Fallback for unknown value
        echo '<label class="acfms-radio">'
          . '<input type="radio" name="' . $input_name . '" value="' . esc_attr($value) . '" checked>'
          . '<span class="acfms-option__label">' . esc_html($value) . '</span>'
          . '</label>';
      }
    }

    echo '</div>';
    echo '</div>';
  }

  /**
   * Format the value returned by ACF filters and template usage.
   */
  public function format_value($value, $post_id, $field)
  {
    if (empty($value)) {
      return $value;
    }

    $this->load_icon_data();

    foreach ($this->icon_data as $icon) {
      if ($icon['name'] === $value) {
        switch ($field['return_format']) {
          case 'array':
            return $icon;
          case 'codepoint':
            return $icon['codepoint'];
          case 'string':
          default:
            return $icon['name'];
        }
      }
    }

    return $value;
  }
}

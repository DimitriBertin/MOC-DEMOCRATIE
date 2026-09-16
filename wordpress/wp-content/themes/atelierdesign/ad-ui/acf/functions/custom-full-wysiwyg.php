<?php
///////// CUSTOM TOOLBARS FOR THE "FULL" WYSIWYG


// REMOVE UNUSED FORMAT TINYMCE
function tiny_mce_remove_unused_formats($init)
{
  // Add block format elements you want to show in dropdown
  // $init['block_formats'] = 'Heading/XXL=h1; Heading/XL=h2; Heading/L=h3; Heading/M=h4; Label=h5; Paragraph/L=h6; Paragraph/M=p; Paragraph/S=pre';
  $init['block_formats'] = 'Heading/XL=h2; Heading/L=h3; Heading/M=h4; Paragraph/L=h6; Paragraph/M=p; Paragraph/S=pre';
  // Style formats
  $style_formats = array(
    // [
    //   'title' => 'Heading/XXL',
    //   'block' => 'h1',
    //   'exact' => true,
    // ],
    [
      'title' => 'Heading/XL',
      'block' => 'h2',
      'exact' => true,
    ],
    [
      'title' => 'Heading/L',
      'block' => 'h3',
      'exact' => true,
    ],
    [
      'title' => 'Heading/M',
      'block' => 'h4',
      'exact' => true,
    ],
    [
      'title' => 'Heading/S',
      'block' => 'h5',
      'exact' => true,
    ],
    [
      'title' => 'Paragraph/XL',
      'block' => 'p',
      'exact' => true,
      'attributes' => [
        'class' => 'xlarge',
      ],
    ],
    [
      'title' => 'Paragraph/L',
      'block' => 'p',
      'exact' => true,
      'attributes' => [
        'class' => 'lead',
      ],
    ],
    [
      'title' => 'Paragraph/M',
      'block' => 'p',
      'exact' => true,
      'attributes' => [
        'class' => 'medium',
      ],
    ],
    [
      'title' => 'Paragraph/S',
      'block' => 'p',
      'exact' => true,
      'attributes' => [
        'class' => 'small',
      ],
    ],
    // [
    //   'title' => 'Label',
    //   'block' => 'p',
    //   'exact' => true,
    //   'attributes' => [
    //     'class' => 'label',
    //   ],
    // ],
  );
  // Insert the array, JSON ENCODED, into 'style_formats'
  $init['style_formats'] = wp_json_encode($style_formats);

  return $init;
}
add_filter('tiny_mce_before_init', 'tiny_mce_remove_unused_formats');


// Removes buttons from the first row of the tiny mce editor 
function remove_tiny_mce_buttons_from_editor($buttons)
{
  $remove_buttons = array(
    'formatselect',
    // 'hr',
    // 'alignleft',
    // 'aligncenter',
    // 'alignright',
    'blockquote', // blockquote
    'wp_more', // read more link
    // 'forecolor', // text color
    'spellchecker',
    'dfw', // distraction free writing mode
    'wp_adv', // kitchen sink toggle (if removed, kitchen sink will always display)
    'fullscreen',
  );

  // Make sure we don't remove our custom buttons
  $custom_buttons = ['heading-highlight', 'balance-text'];
  foreach ($custom_buttons as $custom_button) {
    if (($key = array_search($custom_button, $remove_buttons)) !== false) {
      unset($remove_buttons[$key]);
    }
  }

  foreach ($buttons as $button_key => $button_value) {
    if (in_array($button_value, $remove_buttons)) {
      unset($buttons[$button_key]);
    }
  }
  // If the custom typography plugin is disabled, you can re-enable the legacy
  // TinyMCE "Formats" dropdown by uncommenting the line below.
  // array_unshift($buttons, 'styleselect');

  // Uncomment to debug buttons after modification
  // error_log('Modified first row buttons: ' . json_encode($buttons));

  return $buttons;
}
add_filter('mce_buttons', 'remove_tiny_mce_buttons_from_editor');

//Removes buttons from the second row (kitchen sink) of the tiny mce editor
function remove_tiny_mce_buttons_from_kitchen_sink($buttons)
{
  // Uncomment to debug buttons
  // error_log('Second row buttons: ' . json_encode($buttons));

  $remove_buttons = array(
    'formatselect', // format dropdown menu for <p>, headings, etc
    'underline',
    'hr',
    'strikethrough',
    'alignjustify',
    'forecolor', // text color
    'pastetext', // paste as text
    'removeformat', // clear formatting
    'charmap', // special characters
    'outdent',
    'indent',
    'undo',
    'redo',
    'wp_help', // keyboard shortcuts
  );

  // Make sure we don't remove our custom buttons
  $custom_buttons = ['heading-highlight', 'balance-text'];
  foreach ($custom_buttons as $custom_button) {
    if (($key = array_search($custom_button, $remove_buttons)) !== false) {
      unset($remove_buttons[$key]);
    }
  }

  foreach ($buttons as $button_key => $button_value) {
    if (in_array($button_value, $remove_buttons)) {
      unset($buttons[$button_key]);
    }
  }

  // Uncomment to debug buttons after modification
  // error_log('Modified second row buttons: ' . json_encode($buttons));

  return $buttons;
}
add_filter('mce_buttons_2', 'remove_tiny_mce_buttons_from_kitchen_sink');

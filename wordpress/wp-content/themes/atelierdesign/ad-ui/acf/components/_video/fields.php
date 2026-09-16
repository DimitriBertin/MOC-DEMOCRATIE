<?php

/**
 * ACF Fields for Video
 */

global $adwp;

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="16" viewBox="0 0 24 24" width="16"><path d="M20,4H4C2.9,4,2,4.9,2,6v12c0,1.1,0.9,2,2,2h16c1.1,0,2-0.9,2-2V6C22,4.9,21.1,4,20,4z M9.5,16.5v-9l7,4.5L9.5,16.5z" fill="currentColor"/></svg>';

$videoSettingsFields = [
  [
    'key' => 'field-video-width',
    'label' => 'Width',
    'name' => 'width',
    'type' => 'range',
    'required' => 1,
    'default_value' => 100,
    'min' => 1,
    'max' => 100,
    'step' => 1,
  ],
  [
    'key' => 'field-video-width-isFullWidth',
    'label' => 'Full Width',
    'instructions' => 'Note: only works if width is set to 100%',
    'name' => 'isFullWidth',
    'type' => 'true_false',
    'default_value' => 0,
    'ui' => 1,
    'ui_on_text' => 'Yes',
    'ui_off_text' => 'No',
    'conditional_logic' => [
      [
        [
          'field' => 'field-video-width',
          'operator' => '==',
          'value' => 100,
        ],
      ],
    ],
  ],
  [
    'key' => 'field-video-alignment',
    'label' => 'Alignment',
    'instructions' => 'Note: only works if width is lower than 100%',
    'name' => 'alignment',
    'aria-label' => '',
    'type' => 'acfe_image_selector',
    'instructions' => '',
    'required' => 1,
    'choices' => [
      'left' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/icon-align-left.svg'),
      'center' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/icon-align-center.svg'),
      'right' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/icon-align-right.svg'),
    ],
    'default_value' => 'left',
    'image_size' => 'thumbnail',
    'width' => '32',
    'height' => '32',
    'border' => 1,
    'return_format' => 'value',
    'allow_null' => 0,
    'multiple' => 0,
    'layout' => 'horizontal',
    'conditional_logic' => [
      [
        [
          'field' => 'field-video-width',
          'operator' => '<',
          'value' => 100,
        ],
      ]
    ]
  ],
  [
    'key' => 'field-video-aspect',
    'label' => 'Aspect Ratio',
    'name' => 'aspect',
    'type' => 'select',
    'choices' => [
      '21/9' => 'cinema - 21/9',
      'md:21/9' => 'cinema - 21/9 (only on desktop)',
      '16/9' => 'video - 16/9',
      '3/2' => 'landscape photo - 3/2',
      '4/3'  => 'landscape photo - 4/3',
      // '5/4' - => '5',
      // '2/1' => '2/1',
      '1/1' => 'square - 1/1',
      '4/5' => 'instagram - 4/5',
      '3/4' => 'portrait photo - 3/4',
      // '2/3' => '2/3',
      // '1/2' => '1/2',
    ],
    'default_value' => '16/9',
  ],
  [
    'key' => 'field-video-autoplay',
    'label' => 'Autoplay',
    'instructions' => 'Note: Enabling autoplay will disable the <span style="color:black">Cover image</span> option and force <span style="color:black">Muted</span> to be enabled.',
    'name' => 'autoplay',
    'type' => 'true_false',
    'default_value' => 0,
    'ui' => 1,
    'ui_on_text' => 'Enabled',
    'ui_off_text' => 'Disabled',
    'conditional_logic' => [
      [
        [
          'field' => 'field-video-isUrl',
          'operator' => '!=',
          'value' => 1,
        ]
      ]
    ]
  ],
  [
    'key' => 'field-video-muted',
    'label' => 'Muted',
    'name' => 'muted',
    'type' => 'true_false',
    'default_value' => 0,
    'ui' => 1,
    'ui_on_text' => 'Muted',
    'ui_off_text' => 'Unmuted',
    'wrapper' => [
      'width' => '50%',
    ],
    'conditional_logic' => [
      [
        [
          'field' => 'field-video-autoplay',
          'operator' => '==',
          'value' => 0,
        ],
        [
          'field' => 'field-video-isUrl',
          'operator' => '!=',
          'value' => 1,
        ]
      ],
    ],
  ],
  [
    'key' => 'field-video-loop',
    'label' => 'Loop',
    'name' => 'loop',
    'type' => 'true_false',
    'default_value' => 0,
    'ui' => 1,
    'ui_on_text' => 'Enabled',
    'ui_off_text' => 'Disabled',
    'wrapper' => [
      'width' => '50%',
    ],
    'conditional_logic' => [
      [
        [
          'field' => 'field-video-isUrl',
          'operator' => '!=',
          'value' => 1,
        ],
        [
          'field' => 'field-video-autoplay',
          'operator' => '==',
          'value' => 1,
        ],
      ]
    ]
  ],
  [
    'key' => 'field-video-controls',
    'label' => 'Controls',
    'name' => 'controls',
    'type' => 'true_false',
    'default_value' => 0,
    'ui' => 1,
    'ui_on_text' => 'Enabled',
    'ui_off_text' => 'Disabled',
    'conditional_logic' => [
      [
        [
          'field' => 'field-video-isUrl',
          'operator' => '!=',
          'value' => 1,
        ],
        [
          'field' => 'field-video-autoplay',
          'operator' => '==',
          'value' => 1,
        ],
      ]
    ]
  ],
  [
    'key' => 'field-video-parallax',
    'label' => 'Movement',
    'name' => 'parallax',
    'type' => 'true_false',
    'default_value' => 0,
    'ui' => 1,
    'ui_on_text' => 'Parallax',
    'ui_off_text' => 'Static',
    'conditional_logic' => [
      [
        [
          'field' => 'field-video-aspect',
          'operator' => '!=',
          'value' => 'auto',
        ],
        [
          'field' => 'field-video-autoplay',
          'operator' => '==',
          'value' => 1,
        ],
        [
          'field' => 'field-video-controls',
          'operator' => '==',
          'value' => 0,
        ],
      ],
    ],
  ],
];

// From nested version of the layout, remove the wrapper width settings
$videoNestedSettingsFields = $videoSettingsFields;
unset($videoNestedSettingsFields[1]);

add_action(
  'acf/include_fields',
  static function () use ($videoSettingsFields, $videoNestedSettingsFields) {
    acf_add_local_field_group([
      'key' => 'field-group-video-settings',
      'title' => 'Video Settings',
      'fields' => $videoSettingsFields,
    ]);

    acf_add_local_field_group([
      'key' => 'field-group-video-nested-settings',
      'title' => 'Video Nested Settings',
      'fields' => $videoNestedSettingsFields,
    ]);
  },
  26
);


$videoFields = [
  [
    'key' => 'field-video-isUrl',
    'label' => 'Video source',
    'name' => '_video_isUrl',
    'type' => 'true_false',
    'ui' => 1,
    'ui_on_text' => 'URL',
    'ui_off_text' => 'File',
    'default_value' => 0,
  ],
  [
    'key' => 'field-video-video',
    'label' => 'Select a video file',
    'instructions' => 'Optimally less than 10 MB<br/>Supported formats: mp4, webm',
    'name' => '_video_video',
    'type' => 'file',
    'return_format' => 'array',
    'mime_types' => 'mp4,webm,ogg',
    'required' => 1,
    'conditional_logic' => [
      [
        [
          'field' => 'field-video-isUrl',
          'operator' => '==',
          'value' => 0,
        ]
      ]
    ]
  ],
  [
    'key' => 'field-video-url',
    'label' => 'Video URL',
    'instructions' => 'Supported services: YouTube, Vimeo',
    'name' => '_video_url',
    'type' => 'url',
    'required' => 1,
    'conditional_logic' => [
      [
        [
          'field' => 'field-video-isUrl',
          'operator' => '==',
          'value' => 1,
        ]
      ]
    ]
  ],
  [
    'key' => 'field-video-poster',
    'label' => 'Cover image (optional)',
    'instructions' => 'This is the image that will be displayed before the video is played.',
    'name' => '_video_poster',
    'type' => 'image',
    'preview_size' => 'thumbnail',
  ],
];

$videoLayout = [
  'key' => 'layout-video',
  'label' => $icon . ' Video',
  'name' => '_video',
  'display' => 'block',
  'sub_fields' => $videoFields,
  'acfe_flexible_settings' => [
    0 => 'field-group-video-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_inline_layout('layoutVideo', $videoLayout, 26);

// Nested layout variant (no full-width setting)
$nestedVideoLayout = [
  'key' => 'layout-nestedVideo',
  'label' => $icon . ' Video',
  'name' => '_video',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('nested-', $videoFields, true),
  'acfe_flexible_settings' => [
    0 => 'field-group-video-nested-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_nested_layout('layoutNestedVideo', $nestedVideoLayout, 26);

$adwp->register_layout('video', $nestedVideoLayout);

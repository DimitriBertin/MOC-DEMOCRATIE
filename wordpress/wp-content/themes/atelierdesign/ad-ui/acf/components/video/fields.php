<?php

/**
 * ACF Fields for Video Section
 */

global $adwp;

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 -960 960 960" width="16" height="16"><path d="m380-300 280-180-280-180v360ZM160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm0-80h640v-480H160v480Zm0 0v-480 480Z"/></svg>';

$videoSectionSettingsFields = [
  [
    'key' => 'field-video-section-aspect',
    'label' => 'Aspect Ratio',
    'name' => 'aspect',
    'type' => 'select',
    'choices' => [
      '21/9' => 'cinema - 21/9',
      'md:21/9' => 'cinema - 21/9 (only on desktop)',
      '16/9' => 'video - 16/9',
      '3/2' => 'landscape photo - 3/2',
      // '4/3'  => 'landscape photo - 4/3',
      // '5/4' - => '5',
      // '2/1' => '2/1',
      // '1/1' => 'square - 1/1',
      // '4/5' => 'instagram - 4/5',
      // '3/4' => 'portrait photo - 3/4',
      // '2/3' => '2/3',
      // '1/2' => '1/2',
    ],
    'default_value' => 'md:21/9',
  ],
  [
    'key' => 'field-video-section-parallax',
    'label' => 'Movement',
    'name' => 'parallax',
    'type' => 'true_false',
    'default_value' => 1,
    'ui' => 1,
    'ui_on_text' => 'Parallax',
    'ui_off_text' => 'Static',
    'conditional_logic' => [
      [
        [
          'field' => 'field-video-section-aspect',
          'operator' => '!=',
          'value' => 'auto',
        ],
      ],
    ],
  ],
  // [
  //   'key' => 'field-video-section-controls',
  //   'label' => 'Controls',
  //   'name' => 'controls',
  //   'type' => 'true_false',
  //   'default_value' => 0,
  //   'ui' => 1,
  //   'ui_on_text' => 'Enabled',
  //   'ui_off_text' => 'Disabled',
  // ],
  // [
  //   'key' => 'field-video-section-loop',
  //   'label' => 'Loop',
  //   'name' => 'loop',
  //   'type' => 'true_false',
  //   'default_value' => 1,
  //   'ui' => 1,
  //   'ui_on_text' => 'Enabled',
  //   'ui_off_text' => 'Disabled',
  // ],
  // [
  //   'key' => 'field-video-section-autoplay',
  //   'label' => 'Autoplay',
  //   'instructions' => 'Note: Enabling autoplay will disable the <span style="color:black">Cover image</span> option and force <span style="color:black">Muted</span> to be enabled.',
  //   'name' => 'autoplay',
  //   'type' => 'true_false',
  //   'default_value' => 1,
  //   'ui' => 1,
  //   'ui_on_text' => 'Enabled',
  //   'ui_off_text' => 'Disabled',
  // ],
  // [
  //   'key' => 'field-video-section-muted',
  //   'label' => 'Muted',
  //   'name' => 'muted',
  //   'type' => 'true_false',
  //   'default_value' => 1,
  //   'ui' => 1,
  //   'ui_on_text' => 'Muted',
  //   'ui_off_text' => 'Unmuted',
  //   'conditional_logic' => [
  //     [
  //       [
  //         'field' => 'field-video-section-autoplay',
  //         'operator' => '==',
  //         'value' => 0,
  //       ],
  //     ],
  //   ],
  // ],
];

add_action(
  'acf/include_fields',
  static function () use ($videoSectionSettingsFields) {
    acf_add_local_field_group([
      'key' => 'field-group-video-section-settings',
      'title' => 'Video Settings',
      'fields' => $videoSectionSettingsFields,
    ]);
  },
  35
);

$videoSectionFields = [
  [
    'key' => 'field-video-section-message',
    'label' => '',
    'name' => '',
    'type' => 'message',
    'esc_html' => 0,
    'message' => '<strong>Note:</strong> The video is automatically playing without sound and video player controls are not available. This component is meant to be used as an illustration instead of a full video streaming experience.',
  ],
  [
    'key' => 'field-video-section-video',
    'label' => 'Select a video file',
    'instructions' => 'Optimally less than 10 MB<br/>Supported formats: mp4, webm',
    'name' => 'video_video',
    'type' => 'file',
    'return_format' => 'array',
    'mime_types' => 'mp4,webm,ogg',
    'required' => 1,
  ],
];

$videoSectionLayout = [
  'key' => 'layout-video-section',
  'label' => $icon . ' Full Width Video',
  'name' => 'video',
  'display' => 'block',
  'sub_fields' => $videoSectionFields,
  'acfe_flexible_settings' => [
    0 => 'field-group-video-section-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_block_layout('layoutVideoSection', $videoSectionLayout, 35);

$adwp->register_layout('video.section', $videoSectionLayout);

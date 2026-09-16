<?php

/**
 * ACF Fields for Slider Videos Component
 */

// Global variable coming from the AD UI plugin
global $adwp, $adui_tokens;

// Icon for the block
$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="16" height="16" viewBox="0 -960 960 960"><path d="M140-160q-24 0-42-18t-18-42v-520q0-24 18-42t42-18h680q24 0 42 18t18 42v520q0 24-18 42t-42 18H140Zm0-60h680v-520H140v520Zm266-60h148q12 0 21-9t9-21v-260q0-12-9-21t-21-9H406q-12 0-21 9t-9 21v260q0 12 9 21t21 9Zm-16-60v-240h180v240H390Zm90-120Zm90-60 90-60-90-60v120Z"/></svg>';

// Collect color system mode names
$colorSystemModes = array_keys($adui_tokens['colorSystem'] ?? []);
$layoutNames = array_keys($adui_tokens['colorSystem'][$colorSystemModes[0]]['layout'] ?? []);

// Build a palette mapping hex => "{mode}/main" and a sensible default
$colors = [];
foreach ($colorSystemModes as $mode) {
  foreach ($layoutNames as $layout) {
    $color = getResolvedValue($adui_tokens['colorSystem'], 'layout.' . $layout, $mode);
    $colors[$color] = $mode . '/' . $layout;
  }
}

// Import video fields from ad-ui (copied from ad-ui/acf/components/_video/fields.php)
$videoFields = [
  [
    'key' => 'field-slider-video-isUrl',
    'label' => 'Video source',
    'name' => 'sliderVideos_video_isUrl',
    'type' => 'true_false',
    'ui' => 1,
    'ui_on_text' => 'URL',
    'ui_off_text' => 'File',
    'default_value' => 0,
  ],
  [
    'key' => 'field-slider-video-video',
    'label' => 'Select a video file',
    'instructions' => 'Optimally less than 10 MB<br/>Supported formats: mp4, webm',
    'name' => 'sliderVideos_video_video',
    'type' => 'file',
    'return_format' => 'array',
    'mime_types' => 'mp4,webm,ogg',
    'required' => 1,
    'conditional_logic' => [
      [
        [
          'field' => 'field-slider-video-isUrl',
          'operator' => '==',
          'value' => 0,
        ]
      ]
    ]
  ],
  [
    'key' => 'field-slider-video-url',
    'label' => 'Video URL',
    'instructions' => 'Supported services: YouTube, Vimeo',
    'name' => 'sliderVideos_video_url',
    'type' => 'url',
    'required' => 1,
    'conditional_logic' => [
      [
        [
          'field' => 'field-slider-video-isUrl',
          'operator' => '==',
          'value' => 1,
        ]
      ]
    ]
  ],
  [
    'key' => 'field-slider-video-poster',
    'label' => 'Cover image (optional)',
    'instructions' => 'This is the image that will be displayed before the video is played.',
    'name' => 'sliderVideos_video_poster',
    'type' => 'image',
    'preview_size' => 'thumbnail',
    'return_format' => 'array',
  ],
];

// Main component fields
$sliderVideosFields = [
  [
    'key' => 'field-slider-videos-theme',
    'label' => '',
    'name' => 'sliderVideos_theme',
    'type' => 'color_picker',
    'required' => 1,
    'default_value' => $colors,
    'enable_opacity' => 0,
    'return_format' => 'label',
    'display' => 'palette',
    'color_picker' => 0,
    'allow_null' => 0,
    'theme_colors' => 0,
    'colors' => $colors,
    'button_label' => 'Select Theme',
    'absolute' => false,
    'input' => false,
  ],
  [
    'key' => 'field-slider-videos-title',
    'label' => 'Title',
    'name' => 'sliderVideos_title',
    'type' => 'text',
    'required' => 1,
  ],
  [
    'key' => 'field-slider-videos-text',
    'label' => 'Description',
    'name' => 'sliderVideos_text',
    'type' => 'textarea',
    'required' => 0,
    'rows' => 3,
    'new_lines' => 'br',
  ],
  [
    'key' => 'field-slider-videos-videos',
    'label' => 'Videos',
    'name' => 'sliderVideos_videos',
    'type' => 'repeater',
    'layout' => 'block',
    'button_label' => 'Add Video',
    'min' => 1,
    'sub_fields' => $videoFields,
  ],
];

// Create the component layout
$sliderVideosLayout = [
  'key' => 'layout-slider-videos',
  'label' => $icon . ' Slider Videos',
  'name' => 'sliderVideos',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('block-', $sliderVideosFields),
];

$adwp->add_block_layout('layoutSliderVideos', $sliderVideosLayout, 98);

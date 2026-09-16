# Slider Videos Component

## Overview
The Slider Videos component displays a responsive video carousel with custom pagination (numbers + progress bar) and navigation controls.

## Features
- **Responsive Video Slider**: Built with Swiper.js
- **Custom Pagination**: Shows current/total slides with visual progress bar
- **Navigation Controls**: Previous/Next arrow buttons
- **Video Support**: 
  - Self-hosted videos (MP4, WebM, OGG)
  - Embedded videos (YouTube, Vimeo)
- **Poster Images**: Optional cover images with play button overlay
- **Click-to-Play**: Videos play when clicking the poster/overlay
- **Fully Responsive**: Optimized for mobile, tablet, and desktop

## Design Specifications

### Desktop (md/lg)
- Background: Light green (#E9EFE8)
- Title: 48px, Safiro font, bold, max-width 727px
- Description: 16px, Public Sans, max-width 727px
- Video cards: 1.8 slides visible, 37px gap
- Navigation: 60px circular buttons, white background
- Pagination: Numbers + progress bar (#D7DB31)

### Mobile (max-sm)
- Title: 38px, Safiro font, bold
- Video cards: 1 slide visible, full width
- Same navigation and pagination style

## ACF Fields

### Main Fields
- **Title** (Text): Section title
- **Description** (Textarea): Section description text
- **Videos** (Repeater): Collection of video items

### Video Repeater Fields (per video)
- **Video Source** (Toggle): File or URL
- **Video File** (File Upload): Self-hosted video (MP4, WebM, OGG)
- **Video URL** (URL): YouTube or Vimeo URL
- **Cover Image** (Image): Optional poster/thumbnail image

## Usage

Add the component to a flexible content field:

```php
<?php if (!empty($section['slider_videos'])) { 
  get_template_part('src/components/sliderVideos/markup', null, $section['slider_videos']); 
} ?>
```

Or directly:

```php
<?php 
$videos_data = [
  'title' => 'Comprendre, s\'indigner, agir',
  'text' => 'Description text...',
  'videos' => [
    [
      '_video_isUrl' => false,
      '_video_video' => ['url' => '...', 'mime_type' => 'video/mp4'],
      '_video_poster' => ['url' => '...', 'alt' => '...'],
    ],
    // ... more videos
  ]
];

get_template_part('src/components/sliderVideos/markup', null, $videos_data);
?>
```

## Swiper Configuration
- **Slides Per View**: 
  - Mobile: 1
  - Tablet (768px+): 1.5
  - Desktop (1024px+): 1.8
- **Space Between**: 37px
- **Loop**: Enabled (if more than 1 video)
- **Grab Cursor**: Enabled
- **Navigation**: Custom prev/next buttons

## Video Playback
- **Poster Mode**: Shows poster image with play button overlay
  - Click overlay/poster/play button to start playback
  - Overlay and poster hide on play
  - Autoplay enabled for embedded videos on click
  
- **No Poster**: Video shows with native controls
  - Direct playback controls visible

## Pagination & Progress
- **Numbers**: Shows "01 / 06" format (current/total)
- **Progress Bar**: Visual indicator of slide position
  - Updates on slide change
  - Yellow (#D7DB31) fill on gray (#012E31 50% opacity) track

## Dependencies
- **Swiper.js**: Required for slider functionality
- **SwiperNavigation**: Required for prev/next buttons
- Loaded via `build.js` (window.Swiper, window.SwiperNavigation)

## Helper Functions Used
- `vimeoEmbedFromUrl()`: Converts Vimeo URL to embed URL
- `youtubeEmbedFromUrl()`: Converts YouTube URL to embed URL

## Related Files
- Fields: `src/components/sliderVideos/fields.php`
- Markup: `src/components/sliderVideos/markup.php`
- Video component reference: `ad-ui/acf/components/_video/`

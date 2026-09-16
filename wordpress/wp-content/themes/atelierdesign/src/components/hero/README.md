# Hero Component Documentation

## Overview

The hero component has been completely revamped to support different template types with adaptive layouts and automatic content generation.

## Supported Template Types

### 1. Pages
- **Features**: Image (optional), Label, Title, CTA Button
- **Layout**: Text can be centered or left-aligned
- **Usage**: Regular WordPress pages

### 2. Homepage
- **Features**: Title, Image (different design)
- **Layout**: Centered, full-screen with scroll-down arrow
- **Usage**: Front page template

### 3. Single Job
- **Features**: No Image, Title, CTA button
- **Label**: Displays federation instead of a label
- **Usage**: Individual job posts

### 4. Single Campagne
- **Features**: Title, Image (optional), CTA button
- **Label**: Displays start/end dates and categories
- **Usage**: Individual campaign posts

### 5. Single Post/Evenement/Document
- **Features**: Title, Image (optional)
- **Layout**: Can choose between "full-width" or "content" width
- **Label**: Displays date (or start/end for events), type, themes, and categories
- **Usage**: Individual posts, events, and documents

## Automatic Features

### Template Detection
The component automatically detects the current template type using WordPress conditional functions:
- `is_front_page()` → homepage
- `is_page()` → page
- `is_singular('job')` → single-job
- `is_singular('campagne')` → single-campagne
- etc.

### Auto-Generated Labels
Labels are automatically generated based on template type and post metadata:
- **Jobs**: Federation name or "Offre d'emploi"
- **Campaigns**: Date range + categories
- **Posts/Events/Documents**: Date + type + themes + categories

### Automatic Background Images
If no background image is manually set, the component will use the featured image for:
- Homepage
- Single campaigns
- Single posts
- Single events
- Single documents

## Field Structure

### ACF Fields
The hero component includes the following ACF fields:

**Core Fields (available for all templates):**
```php
- title (text) - Required
- background_image (image) - Optional
- label (text) - Optional, auto-generated if empty
- cta_button (link) - ACF Link field with title, url, and target
```

**Post-Type Specific Fields:**
```php
- hero_text_alignment (select) - center|left (Pages only)
- hero_image_layout (select) - full-width|content (Posts, Events, Documents only)
```

### Field Groups
Hero fields are automatically added to all relevant post types and templates:
- Pages (with text alignment option)
- Posts/enjeu (with image layout option)
- Jobs
- Campaigns
- Events (with image layout option)
- Documents (with image layout option)
- Homepage template

## Usage

### In Templates
Simply include the hero component in any template:

```php
<?php get_template_part('src/components/hero/include'); ?>
```

### Manual Override
Template type is automatically detected and cannot be overridden by users.

### Custom Labels
You can provide a custom label in the admin to override the auto-generated one.

### Text Alignment (Pages Only)
For regular pages, you can choose between center and left text alignment.

### Image Layout (Posts/Events/Documents Only)
For posts, events, and documents, you can choose how the background image is displayed:
- **Full Width**: Image covers the entire hero section (default)
- **Content Width**: Image is constrained to content area

## File Structure

```
src/components/hero/
├── fields.php          # ACF field definitions
├── markup.php          # Component HTML markup
├── helpers.php         # Helper functions for detection and data processing
└── include.php         # Simple include file for templates
```

## Helper Functions

### `get_hero_template_type()`
Detects the current template type based on WordPress conditionals.

### `get_hero_auto_label($template_type, $post_id)`
Generates automatic labels based on template type and post data.

### `get_hero_data($post_id)`
Gets complete hero data with automatic detection and fallbacks.

### `should_display_hero()`
Determines if hero should be displayed for the current template.

## Template Files Created

The following template files have been created to properly support the hero component:
- `single.php` - For posts (enjeu)
- `single-job.php` - For job posts
- `single-campagne.php` - For campaign posts
- `single-evenement.php` - For event posts
- `single-document.php` - For document posts
- `page.php` - For regular pages

## Styling Classes

The component uses different CSS classes based on template type:
- Layout classes: `min-h-screen`, `min-h-[60vh]`, `py-16`, etc.
- Theme classes: `theme-dark-green`
- Alignment classes: `text-center`, `text-left`
- Container classes: `max-w-7xl`, `max-w-4xl`

## Notes

- The original hero markup has been completely rewritten to be self-contained
- All data processing is handled internally by the component
- The component is backwards compatible with existing homepage templates
- Federation can be either an ACF field or taxonomy for jobs
- Theme taxonomy is only available for posts and documents, not events
- CTA button uses ACF Link field for better UX and consistency
- Template type detection is automatic and cannot be overridden by users
- Image layout option is specific to image display, not overall layout
- Text alignment is only available for pages where it makes sense
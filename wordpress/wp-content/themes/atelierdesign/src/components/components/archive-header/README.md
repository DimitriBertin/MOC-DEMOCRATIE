# Archive Filter System

A comprehensive AJAX-powered filter system for WordPress archive pages with the following features:

## Features

- **AJAX Filtering**: Real-time filtering without page reload
- **Exclusive AND Logic**: Filters work together (posts must match ALL selected filters)
- **Single Selection**: Only one term per taxonomy can be selected at a time
- **Smart Filtering**: Disabled terms that would result in no posts
- **URL Updates**: Browser history support with query parameters
- **Active Filter Tags**: Visual display of selected filters with remove buttons
- **Reset Functionality**: Clear all filters at once
- **Responsive Design**: Mobile-friendly dropdown design
- **Loading States**: Visual feedback during AJAX requests
- **Error Handling**: Graceful error messages and fallbacks

## Supported Post Types

- **post** (Enjeu): `type_enjeu`, `category`, `theme`
- **document**: `type_document`, `category`, `theme`
- **evenement**: `category`
- **campagne**: `category`
- **job**: No filters (as intended)

## Files Created/Modified

### Core Components
- `src/components/archive-header/markup.php` - Main filter UI
- `src/components/archive-header/filter-functions.php` - PHP backend logic
- `src/components/archive-header/archive-filters.js` - JavaScript frontend
- `src/functions/archive-filters.php` - WordPress integration

### Templates
- `archive.php` - General archive template
- `category.php` - Category archive template
- `taxonomy.php` - Custom taxonomy archive template

## How It Works

### 1. Filter Detection
The system automatically detects available taxonomies based on the current post type and displays relevant filter dropdowns.

### 2. Smart Counting
When filters are applied, the system calculates how many posts would remain if each term was selected, disabling terms that would result in zero posts.

### 3. AJAX Updates
Selecting a filter triggers an AJAX request that:
- Updates the posts grid
- Refreshes filter counts
- Updates active filter tags
- Modifies the browser URL

### 4. URL Management
Filter selections are reflected in the URL as query parameters:
- `?filter_types=123&filter_categories=456&filter_themes=789`

## Technical Implementation

### PHP Functions

#### `get_archive_filter_taxonomies($post_type)`
Returns available taxonomies for a given post type.

#### `get_filter_terms_with_counts($post_type, $current_filters)`
Gets all terms with post counts considering current filters.

#### `get_filtered_post_count($post_type, $taxonomy, $term_id, $current_filters)`
Calculates post count for a specific term with other filters applied.

#### `handle_archive_filter_ajax()`
Processes AJAX filter requests and returns filtered results.

### JavaScript Class: `ArchiveFilterSystem`

#### Key Methods
- `updateFilters()` - Main AJAX filtering logic
- `updateFilterCounts()` - Updates term counts and disabled states
- `updateActiveFilterTags()` - Manages filter tag display
- `updateURL()` - Browser history management

## Usage

### Automatic Integration
The filter system automatically integrates with:
- Archive pages (`is_archive()`)
- Home page (`is_home()`)
- Category pages
- Custom taxonomy pages

### Manual Integration
To use the filter header in custom templates:

```php
<?php
$archive_data = [
    'title' => 'Custom Title',
    'description' => 'Custom description'
];
get_template_part('src/components/archive-header/markup', null, $archive_data);
?>
```

## Styling

The system includes comprehensive CSS for:
- Dropdown animations
- Loading states
- Filter tags
- Mobile responsiveness
- Error messages

## URL Structure

Filter URLs follow this pattern:
```
/archive-page/?filter_types=123&filter_categories=456&filter_themes=789
```

## Browser Support

- Modern browsers with ES6+ support
- Fetch API support
- CSS Grid support for post layouts

## Performance Considerations

- AJAX requests are debounced to prevent excessive server calls
- Post meta cache is disabled for filter queries to improve performance
- Term existence is verified before querying
- Efficient taxonomy queries with proper indexing

## Security

- Nonce verification for all AJAX requests
- Input sanitization and validation
- Term existence verification
- Post type validation

## Analytics Integration

Built-in Google Analytics support for tracking filter usage:

```javascript
gtag('event', 'filter_applied', {
    'event_category': 'Archive Filters',
    'event_label': 'types,categories',
    'post_type': 'post'
});
```

## Customization

### Adding New Post Types
1. Update `get_archive_filter_taxonomies()` function
2. Ensure taxonomies are properly registered
3. Add post type to template conditions

### Styling Customization
Modify the CSS in `src/functions/archive-filters.php` or add custom styles to your theme.

### Filter Logic Customization
Modify the `handle_archive_filter_ajax()` function to change query logic or add new features.

## Troubleshooting

### Common Issues

1. **Filters not showing**: Check if taxonomies are properly registered for the post type
2. **AJAX not working**: Verify nonce generation and WordPress AJAX URL
3. **Counts incorrect**: Clear any caching plugins or check taxonomy relationships
4. **URL not updating**: Ensure browser supports HTML5 History API

### Debug Mode
When `WP_DEBUG` is enabled, additional debug information is included in AJAX responses.

### Transfer
Transfered to github organisation AD
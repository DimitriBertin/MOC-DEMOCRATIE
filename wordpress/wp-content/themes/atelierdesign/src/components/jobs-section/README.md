# Jobs Section Component

## Overview
The Jobs Section component displays job postings with federation filtering on the Contact template page.

## Features
- **Responsive Design**: Fully responsive for mobile, tablet, and desktop
- **Federation Filtering**: Filter jobs by federation using a dropdown
- **Alternating Card Styles**: Jobs cards alternate between light green (#E9EFE8) and yellow (#D7DB31) backgrounds
- **Federation Badges**: Each job displays its associated federation in a badge
- **Job Descriptions**: Shows preview description for each job
- **Arrow Links**: Circular arrow buttons link to full job details

## Design Specifications

### Desktop (md/lg)
- Container: max-width with padding
- Layout: Two-column (title on left, jobs on right)
- Title: 48px, Safiro font, bold
- Job cards: 321px height, full width
- Card spacing: 28px gap
- Filter dropdown: Full width with border

### Mobile (max-sm)
- Layout: Stacked (title above jobs)
- Title: 38px, Safiro font, bold  
- Job cards: Auto height with padding
- Card spacing: 28px gap
- Responsive padding and sizing

## Job Card Layout
Each job card contains:

1. **Top Section** (header):
   - Job title (left/top)
   - Arrow link button (right/bottom)

2. **Bottom Section** (footer):
   - Federation badge (left/top)
   - Job description (right/bottom, 382px width on desktop)

## Color Scheme
- **Card Backgrounds**: Alternating
  - Odd cards: #E9EFE8 (light green)
  - Even cards: #D7DB31 (yellow)
- **Arrow Buttons**: 
  - Light green cards: #D7DB31 background
  - Yellow cards: White background
- **Federation Badges**:
  - Light green cards: #E7B84C (orange)
  - Yellow cards: White
- **Text**: #012E31 (dark green) or #000 (black)

## Dependencies
- Custom Post Type: `job`
- Taxonomy: `federation` (attached to job post type)
- ACF Field: `description` (text area, max 200 characters)

## Usage

The component is automatically loaded in the Contact template when the `jobs_title` field has a value.

### In Template:
```php
<?php if (!empty($fields['jobs_title'])) { 
  get_template_part('src/components/jobs-section/markup', null, [
    'title' => $fields['jobs_title'],
  ]); 
} ?>
```

### Required Setup:
1. Create job posts (Custom Post Type: `job`)
2. Assign federations to jobs (Taxonomy: `federation`)
3. Add job descriptions via ACF field
4. Set the Jobs Title in the Contact template ACF fields

## JavaScript Functionality
- **Filter Dropdown**: Toggle visibility on click
- **Filter Jobs**: Show/hide jobs based on selected federation
- **Close on Outside Click**: Dropdown closes when clicking outside
- **Dynamic Button Text**: Updates button text with selected federation

## Responsive Breakpoints
- `max-sm:` - Mobile (< 640px)
- `md:` - Tablet (≥ 768px)
- `lg:` - Desktop (≥ 1024px)

## Related Files
- Template: `templates/contact.php`
- Custom Post Type: `src/functions/custom-post-types.php`
- Taxonomy: `src/functions/taxonomies.php`
- ACF Fields: `src/fieldGroups/job.php`

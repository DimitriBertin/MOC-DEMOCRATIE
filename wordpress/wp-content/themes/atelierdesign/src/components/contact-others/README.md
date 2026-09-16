# Contact Others Component

This component displays contact information for multiple groups (services, federations, etc.) in a flexible dropdown/tab format using a repeater field structure.

## Features

- **Flexible Repeater Structure**: Add unlimited contact groups through ACF
- **Dropdown Tabs**: Click the button to reveal available options within each group
- **Dynamic Content**: Select any item to display its contact details
- **Responsive Design**: Optimized for mobile and desktop
- **Accessibility**: Keyboard navigation and ARIA attributes
- **Smooth Animations**: Subtle transitions for better UX

## Structure

```
contact-others/
├── markup.php          # Main component template
├── contact-others.js    # JavaScript functionality
└── README.md           # This file
```

## Usage

The component expects the following data structure in `$args`:

```php
$args = [
  'contact_groups' => [
    [
      'title' => 'Contact service',
      'items' => [
        [
          'name' => 'Service Name',
          'informations' => [
            [
              'title' => 'Email',
              'value' => 'service@example.com'
            ],
            [
              'title' => 'Phone',
              'value' => '+33 1 23 45 67 89'
            ]
          ]
        ]
      ]
    ],
    [
      'title' => 'Contact fédérations',
      'items' => [
        [
          'name' => 'Federation Name',
          'informations' => [
            [
              'title' => 'Address',
              'value' => '123 Main Street, City'
            ]
          ]
        ]
      ]
    ]
    // Add more groups as needed...
  ]
];
```

## ACF Field Structure

The component uses a repeater field called `contact_groups` with the following structure:

- **contact_groups** (repeater)
  - **title** (text) - Group title (e.g., "Contact service")
  - **items** (repeater) - Contact items within the group
    - **name** (text) - Item name (e.g., "Service XYZ")
    - **informations** (repeater) - Contact information
      - **title** (text) - Info label (e.g., "Email", "Phone")
      - **value** (wysiwyg) - Info content

## JavaScript API

The component exposes a global object for external control:

```javascript
// Initialize the component
window.contactOthersDropdowns.init();

// Close all open dropdowns
window.contactOthersDropdowns.closeAll();
```

## Styling

The component uses pure Tailwind CSS classes for all styling including:

- **Transitions**: `transition-all duration-200 ease-in-out` for smooth interactions
- **Hover States**: `hover:bg-[#E9EFE8]` and `hover:bg-[#c5c93a]` 
- **Focus States**: `focus:outline-none focus:ring-2` for accessibility
- **Animations**: `transform transition-all duration-300 ease-out` for details
- **Responsive**: All breakpoints handled with `max-sm:` and `md:` prefixes
- **Scrollable Dropdowns**: `max-h-[300px] overflow-y-auto` for long lists

No separate CSS file is needed - everything is in the markup using Tailwind utility classes.
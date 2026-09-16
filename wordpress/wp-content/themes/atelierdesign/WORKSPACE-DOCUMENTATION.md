# MOC WordPress Theme - Workspace Documentation

## Project Overview

**Project Name**: MOC (Atelier Design WordPress Theme)  
**Type**: Custom WordPress Theme with Advanced Custom Fields (ACF) Integration  
**Version**: 0.1.0-beta  
**Author**: Atelier Design (developed by Gyozo G)  
**License**: Private  

This is a modern WordPress theme built around a custom design system called **AD-UI** that provides a component-based approach to theme development with ACF flexible layouts.

## Architecture Overview

The project consists of three main layers:

1. **WordPress Theme Layer** - Standard WordPress theme files with custom enhancements
2. **AD-UI Core System** - Custom design system and component library
3. **ACF Components** - Advanced Custom Fields integration for page building

## Directory Structure

```
atelierdesign/                          # Root WordPress theme directory
├── 📁 ad-ui/                          # Custom design system
│   ├── 📁 acf/                        # ACF field definitions and components
│   │   ├── 📁 components/             # UI components with ACF fields
│   │   │   ├── 📁 _accordion/         # Accordion component
│   │   │   ├── 📁 _button/            # Button component
│   │   │   ├── 📁 _card/              # Card component
│   │   │   ├── 📁 _badge/              # Chip component
│   │   │   ├── 📁 _columns/           # Column layout system
│   │   │   ├── 📁 _gallery/           # Gallery component
│   │   │   ├── 📁 _googleMap/         # Google Maps integration
│   │   │   ├── 📁 _icon/              # Icon component
│   │   │   ├── 📁 _image/             # Image component
│   │   │   ├── 📁 _keyNumbers/        # Key numbers display
│   │   │   ├── 📁 _layout/            # Layout utilities
│   │   │   ├── 📁 _logos/             # Logo grid component
│   │   │   ├── 📁 _quote/             # Quote/testimonial component
│   │   │   ├── 📁 _script/            # Custom script injection
│   │   │   ├── 📁 _separator/         # Visual separator
│   │   │   ├── 📁 _video/             # Video component
│   │   │   ├── 📁 _wrapper/           # Container wrapper
│   │   │   ├── 📁 _wysiwyg/           # Rich text editor
│   │   │   ├── 📁 feature/            # Feature sections
│   │   │   ├── 📁 image/              # Image blocks
│   │   │   ├── 📁 section/            # Generic sections
│   │   │   └── 📁 video/              # Video blocks
│   │   ├── 📁 assets/                 # UI assets (icons, images)
│   │   ├── 📁 functions/              # ACF utility functions
│   │   └── 📁 templates/              # ACF template partials
│   ├── 📁 core/                       # Design system core
│   │   ├── 📁 src/                    # Source files for design tokens
│   │   ├── package.json               # Core dependencies
│   │   ├── tailwind.config.js         # Core Tailwind config
│   │   └── webpack configs            # Build configuration
│   └── 📁 wp/                         # WordPress integration utilities
├── 📁 src/                            # Theme-specific source files
│   ├── 📁 components/                 # Theme components
│   │   ├── 📁 footer/                 # Footer component
│   │   ├── 📁 header/                 # Header component
│   │   └── 📁 hero/                   # Hero component
│   ├── 📁 fieldGroups/                # ACF field group definitions
│   └── 📁 functions/                  # Theme-specific functions
├── 📁 templates/                      # WordPress template files
├── 📁 dist/                           # Compiled assets (generated)
├── functions.php                      # Main theme functions
├── index.php                          # Main template file
├── header.php                         # HTML head and opening body
├── footer.php                         # Closing body and HTML
├── style.css                          # Theme info and styles
└── package.json                       # Theme dependencies and scripts
```

## Core Technologies

### Build System
- **Webpack 5** - Module bundling and asset compilation
- **PNPM** - Package management (v10.9.0)
- **PostCSS** - CSS processing with Autoprefixer
- **Sass** - CSS preprocessing
- **Tailwind CSS** - Utility-first CSS framework

### Frontend Dependencies
- **Swiper** (v12.0.1) - Touch slider/carousel component
- **Font Awesome** - Icon library
- **Mona Sans** - Google Fonts typography

### Development Tools
- **Prettier** - Code formatting
- **CSS/JS Minification** - Production optimization
- **BugHerd** - Bug tracking integration

## Key Components

### ADWP Class (`ad-ui/wp/init.php`)
The core WordPress integration class that:
- Manages asset loading and enqueueing
- Handles flexible layout rendering
- Provides utility methods for theme development
- Integrates the design system with WordPress

### Functions.php Structure
```php
// 1. AD UI Core initialization
$adwp = new ADWP([
  'packageFolder' => get_template_directory() . '/ad-ui',
  'packageUrl' => get_template_directory_uri() . '/ad-ui',
  'disableComments' => true,
]);

// 2. ACF components inclusion
require_once 'ad-ui/acf/includes.php';

// 3. External integrations (BugHerd, Font Awesome, Google Fonts)

// 4. Auto-loading theme functions, components, and field groups
```

### Template Structure
- **index.php** - Main template using flexible layouts via `$adwp->render_flexible_layout()`
- **header.php** - Minimal HTML head with `$adwp->head()` integration
- **footer.php** - Closes body with `$adwp->footer()` integration

## Development Workflow

### Setup Commands
```bash
# Install theme dependencies
pnpm install

# Initialize AD-UI core
pnpm run adui:init

# Development mode (theme)
pnpm run dev

# Production build (theme)  
pnpm run build

# Development mode (AD-UI core)
pnpm run adui:dev

# Production build (AD-UI core)
pnpm run adui:build
```

### File Watching and Build Process
- **Theme Level**: Webpack watches `src/build.js` and compiles to `dist/`
- **AD-UI Level**: Separate build process for the design system core
- **Output**: Compiled CSS and JS files for both theme and design system

## ACF Integration

### Component System
Each component in `ad-ui/acf/components/` contains:
- **fields.php** - ACF field definitions
- **Component logic** - Rendering and functionality
- **Styling integration** - Connection to design tokens

### Design Tokens
The system uses dynamic design tokens that provide:
- **Responsive sizing modes** - Consistent spacing and sizing
- **Color system modes** - Centralized color management  
- **Component variants** - Style and behavior options

### Flexible Layouts
Pages use ACF flexible layouts that allow content editors to:
- Choose from available components
- Configure component settings through ACF fields
- Build complex page layouts without code

## Key Features

### Component Library
20+ pre-built components including:
- **Layout**: Columns, wrappers, separators
- **Content**: WYSIWYG, images, videos, galleries
- **Interactive**: Accordions, buttons, cards
- **Data**: Key numbers, quotes, logos
- **Utility**: Scripts, Google Maps integration

### Responsive Design
- Mobile-first approach with Tailwind CSS
- Responsive design tokens
- Flexible grid system

### Performance Optimizations
- Webpack asset optimization
- CSS/JS minification in production
- Strategic asset loading via ADWP class

## Configuration Files

### Tailwind Configuration
```javascript
// Uses AD-UI core configuration merged with theme-specific settings
const { twWithADUI } = require("./ad-ui/core/index");
module.exports = twWithADUI(config);
```

### Webpack Configuration
- **webpack.common.js** - Shared configuration
- **webpack.dev.js** - Development settings
- **webpack.prod.js** - Production optimizations

## Important Notes

### Auto-loading System
The theme automatically loads:
- All files in `src/functions/*.php`
- All `fields.php` files in `src/components/**/`
- All files in `src/fieldGroups/*.php`

### Component Dependencies
Some components have dependency relationships:
- `_wysiwyg` loads accordion, card, column, columns, wrapper, and layout components
- Component loading order is managed in `ad-ui/acf/includes.php`

### Development vs Production
- **Development**: Watch mode with source maps
- **Production**: Minified, optimized assets
- Separate build processes for theme and design system

## Getting Started

1. **Environment Setup**: Ensure WordPress, PHP, and Node.js are installed
2. **Dependencies**: Run `pnpm install` in both root and `ad-ui/core/`
3. **Development**: Use `pnpm run dev` for theme development
4. **Content Creation**: Use ACF flexible layouts to build pages
5. **Customization**: Extend components in `src/components/` or `ad-ui/acf/components/`

This documentation serves as a reference for understanding the workspace structure, development workflow, and architectural decisions in the MOC WordPress theme project.
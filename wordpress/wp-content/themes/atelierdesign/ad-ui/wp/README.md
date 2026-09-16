# AD UI for WordPress (ad-ui/wp)

For integrating the AD UI design system into a WordPress theme. It:

- **Enqueues** compiled assets from `ad-ui/core/dist/app.css` and `ad-ui/core/dist/app.js`.
- **Collects JS modules** used by components and exposes them on the page.
- **Provides helpers** to load component markup and, if using ACF, render Flexible Content layouts.
- Optionally **exposes design tokens** for PHP usage.

---

## Requirements

- WordPress 6.7+
- PHP 8.0+
- Node + pnpm to build assets inside `ad-ui/core`
- Tokens data, for generating your version of the Design System
- For the `ad-ui/acf` module, the ACF Pro & ACF Extended plugins — see [ACF module docs](../acf/README.md)

---

## Installation

#### 1. Place the `ad-ui` folder inside your theme directory, for example:

```
wp-content/themes/your-theme/ad-ui
```

---

#### 2. Always build the core assets

The PHP integration expects compiled files at `ad-ui/core/dist/app.css` and `ad-ui/core/dist/app.js`.

To build out these assets, see docs at `ad-ui/core/README.md`

---

#### 3. Include the AD UI WordPress files, and implement the ADWP function with its' configuration in your functions.php

Add the init include and initiate `ADWP` with your paths. Normally it should look something like this

```php
// functions.php
require_once get_template_directory() . '/ad-ui/wp/init.php';

$adwp = new ADWP([
  'packageFolder' => get_template_directory() . '/ad-ui',
  'packageUrl' => get_template_directory_uri() . '/ad-ui',
  // Set to false to print a small config log in the console during development
  'disableComments' => true,
]);
```

Notes:

- Asset URLs are versioned with your theme `Version` (from `style.css`). Bump it to bust caches.
- For child themes, replace `get_template_directory[_uri]()` with `get_stylesheet_directory[_uri]()`.

---

#### 4. Include the editor modules for your theme in your functions.php

For an ACF flexible layout setup, include:

```php
// functions.php
require_once get_template_directory() . '/ad-ui/acf/includes.php';
```

See the dedicated ACF docs: [ad-ui/acf/README.md](../acf/README.md)

For field registration, component conventions, flexible content rendering, and admin customizations, refer to that file. This WP README focuses on the core integration and asset pipeline.

---

#### 5. Use the head/footer helpers (recommended)

Replace native `wp_head()` / `wp_footer()` calls with the helpers to expose runtime variables used by the JS loader.

```php
// header.php
<?php global $adwp; ?>
<html>
<head>
  <?php $adwp->head(); ?>
</head>
<body>
...


// footer.php
<?php global $adwp; ?>
<?php $adwp->footer(); ?>
</body>
</html>
```

`head()` outputs `wp_head()` and a small configuration flag. `footer()` outputs `wp_footer()` and a `adwpScriptModules` list collected from component usage.

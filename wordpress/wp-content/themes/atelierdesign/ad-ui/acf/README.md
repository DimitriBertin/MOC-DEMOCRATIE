# AD UI — ACF Module (ad-ui/acf)

Helpers and conventions to build ACF-driven component layouts for AD UI.

What you get:

- **Local ACF fields autoloading** from `ad-ui/acf/components/**/fields.php` and `ad-ui/acf/templates/*.php`.
- **Component convention**: `ad-ui/acf/components/[slug]/markup.php` (+ optional `script.module.js`).
- **Flexible Content renderer** powered by the `ADWP` class.
- **Admin UX** tweaks: custom admin styles, options page, classic editor enablement for ACF WYSIWYGs.
- **Design tokens access** via the shared token loader.

---

## Requirements

- WordPress 6.7+
- PHP 8.0+
- ACF Pro Plugin
- ACF Extended Plugin
- The base WP integration — see the [WordPress integration docs](../wp/README.md)

---

## Install in your theme

### 1. Copy the `ad-ui` folder to your theme.

### 2. Ensure you have the base **ADWP** integration first — see the [WordPress integration docs](../wp/README.md):

```php
// functions.php
require_once get_template_directory() . '/ad-ui/wp/init.php';
$adwp = new ADWP([
  'packageFolder' => get_template_directory() . '/ad-ui',
  'packageUrl' => get_template_directory_uri() . '/ad-ui',
]);
```

### 3. Then include the ACF module (this module):

```php
// functions.php
require_once get_template_directory() . '/ad-ui/acf/includes.php';
```

This will:

- Register all ACF field groups to create flexible layouts using all the Design System components, defined in `ad-ui/acf/components/**/fields.php`
- Use the `ad-ui/acf/components/**/markup.php` for rendering them on the frontend.
- Register any additional templates from `ad-ui/acf/templates/*.php`
- Load token utilities shared with the WP package
- Enable some admin customizations to make the package work with core WordPress + ACF

---

## Component & fields conventions

Folder layout for a component named `hero`:

```
ad-ui/
  acf/
    components/
      hero/
        fields.php          # ACF field group definition
        markup.php          # Rendered by ADWP → get_template_part('hero')
        script.module.js    # Optional JS module, auto-collected
```

## Rendering components

Use the `ADWP` helper to render a component and automatically register its JS module if present.

```php
// In a template file
$adwp->get_template_part('hero', [
  'title' => get_the_title(),
  'subtitle' => get_field('subtitle'),
]);
```

`get_template_part('hero')` resolves to `ad-ui/acf/components/hero/markup.php` and (if exists) collects `ad-ui/acf/components/hero/script.module.js` for the page. Modules are exposed in the footer as `adwpScriptModules` by calling `$adwp->footer()`.

## Rendering ACF Flexible Content

When using Flexible Content, set each layout name to match the component folder name. The renderer maps the ACF layout key to the component slug, passes the layout data, and removes the `acf_fc_layout_` prefix from field keys for cleaner usage in `markup.php`.

```php
// Example in page.php or a custom template
$flex = get_field('content');
$adwp->render_flexible_layout($flex);

// Pass parent context to all blocks
$adwp->render_flexible_layout($flex, ['parentColor' => 'dark']);
```

Expected structure for a layout named `hero`:

```
ad-ui/acf/components/hero/markup.php
```

Inside `markup.php`, use keys without the `acf_fc_layout_` prefix and any extra context you passed.

## Admin customizations included

These are autoloaded by `includes.php`:

- `functions/acf-custom-admin-styles.php` — ACF field UI refinements in wp-admin
- `functions/admin-custom-styles.php` — General admin style tweaks
- `functions/disable-block-editor.php` — Optionally disable block editor for certain post types/contexts
- `functions/custom-full-wysiwyg.php` — Classic editor WYSIWYG tailored for ACF
- `functions/acf-options-page.php` — Adds an ACF Options page if ACF is active

You can edit these files to fit your project’s needs.

---

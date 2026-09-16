# AD UI + WP + ACF boilerplate

## Requirements

- NodeJS
- PNPM Package Manager
- a WordPress installation (recommended version: 6.8.2), and the following plugins:
  - `advanced-custom-fields-pro@6.5.0.1`
  - `acf-extended-pro@0.9.1.1`
  - `advanced-custom-fields-font-awesome@4.1.2`
  - `inline-preview-forked@1.1` _(Custom plugin made by Gyozo, available from https://github.com/atelierdesignbe/wp-inline-preview-plugin)_

## Get Started

1. Make sure to clone the [`atelierdesignbe/ui`](https://github.com/atelierdesignbe/ui) repository, in a folder called `ad-ui`:

```
# Example with GitHub CLI
gh repo clone atelierdesignbe/ui ad-ui
```

2. Install the packages of this boilerplate theme, and the adui package:

```
pnpm i && pnpm adui:init
```

3. Delete the default token data from `ad-ui/core/src/data/*` and replace them with the one you got from **Atelier Design**.

4. Once you're done, you can build out the design system by running the following comand:

```
pnpm adui:build
```

5. Your design system with **AD UI** is ready, now you can start coding the rest of the website. This theme is completely yours, it's just a boilerplate, change it, delete things from it, or install packages to it however you see fit.

By default, you can use the `pnpm dev` command for running a Webpack in watch mode for your components, and whenever you're ready, a `pnpm build` command to build out your _dist_ CSS & JS files.

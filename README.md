# Aurelia Homes

A fictional real estate portfolio website built with WordPress. This repository contains only the custom code from the LocalWP site's `wp-content` directory; WordPress core, the database, and uploads are not versioned.

## Stack

- WordPress with a custom classic PHP theme
- Custom PHP plugin for property data and business logic
- HTML, CSS/SCSS, and vanilla JavaScript
- LocalWP for local development

The project intentionally does not use ACF, Elementor, page builders, Bootstrap, Tailwind, React, or jQuery.

## Structure

- `themes/aurelia-homes/` — templates, styles, and frontend behaviour.
- `plugins/aurelia-properties/` — the `property` post type and future property taxonomies, metadata, validation, and query helpers.
- `uploads/` — local WordPress media, ignored by Git.

## Run Locally

1. Open **Aurelia Homes** in LocalWP.
2. Click **Start site**.
3. Click **WP Admin** and sign in.
4. Activate **Aurelia Homes** in **Appearance → Themes**.
5. Activate **Aurelia Properties** in **Plugins**.
6. Open **Settings → Permalinks** and click **Save Changes** after activating the plugin.

## Development

Keep data and business logic in `plugins/aurelia-properties/`; keep rendering and styles in `themes/aurelia-homes/`. Check the [contributor guidelines](AGENTS.md) before making changes.

Before committing, run:

```powershell
git diff --check
php -l themes/aurelia-homes/functions.php
php -l plugins/aurelia-properties/aurelia-properties.php
```

Run `php -l` for every PHP file you change when PHP is available on your PATH.

## Frontend Assets

Vite and Sass are configured inside `themes/aurelia-homes/`. Install the frontend dependencies once, then build assets after changing SCSS or JavaScript:

```powershell
cd themes/aurelia-homes
npm install
npm run build
```

Source files live in `src/`; compiled assets are written to `dist/` and loaded by the theme when the Vite manifest is present.

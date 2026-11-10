# Repository Guidelines

## Project Structure & Module Organization

This repository is the `wp-content` directory of the LocalWP Aurelia Homes site.

- `themes/aurelia-homes/` contains the classic PHP theme: templates, styles, and frontend JavaScript.
- `plugins/aurelia-properties/` contains the property domain: the `property` post type, taxonomies, metadata, validation, and query helpers.
- `uploads/` is local WordPress media and is ignored by Git.

Keep presentation in the theme and business/data logic in the plugin. Never edit WordPress core or LocalWP configuration from this repository.

## Local Development & Commands

Run the site with LocalWP: select **Aurelia Homes**, then click **Start site** and **WP Admin**. Activate the **Aurelia Homes** theme and **Aurelia Properties** plugin there.

No build or automated test scripts exist yet. Before committing, run:

```powershell
git status
git diff --check
php -l themes/aurelia-homes/functions.php
php -l plugins/aurelia-properties/aurelia-properties.php
```

Use the `php -l` command for every changed PHP file when PHP is available on your PATH. Frontend source lives in `themes/aurelia-homes/src/`; run `npm install` once and `npm run build` from `themes/aurelia-homes/` after changing SCSS or JavaScript.

## Coding Style & Naming

Write code, comments, documentation, and UI strings in English. Follow WordPress coding standards: tabs in PHP indentation, spaces in CSS, descriptive lowercase file names, and hyphenated WordPress handles such as `aurelia-homes`.

Prefix plugin-owned metadata and identifiers with `aurelia_`; use the `property` post type only for property records. Escape output late (`esc_html()`, `esc_url()`), and sanitize and validate input before saving it.

## Testing Guidelines

Manually verify each feature in WordPress Admin and on the public site. For admin forms, test valid data, invalid data, empty values, nonce failure, and insufficient permissions. For property queries, test each filter alone and in combination. Document unavailable checks in the pull request.

## Commits & Pull Requests

This repository has no commits yet, so establish concise imperative messages: `Add property price meta box` or `Create property archive template`.

Keep each commit focused. Pull requests should state the purpose, affected theme/plugin paths, manual test steps, and screenshots for visual changes. Do not commit credentials, `uploads/`, databases, or LocalWP files.

## Safety & Agent Instructions

Use nonces and capability checks for every admin save action. Do not add ACF, Elementor, page builders, Bootstrap, Tailwind, React, or jQuery unless explicitly approved. Do not commit, push, deploy, install dependencies, or change LocalWP settings without explicit approval.

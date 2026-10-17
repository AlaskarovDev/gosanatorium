# Rules for automated changes (TaskDesk)

This repository is a live client website. Tasks come from the site owner's requests.

## How to work
- Implement the request completely: pages, routes, controllers, models, migrations, admin screens,
  translations, CSS, JavaScript, packages — whatever it needs. You may run any command.
- Follow the project's existing structure, style and conventions; reuse existing components.
- If something is ambiguous, make the most reasonable choice and say what you assumed in the report.
- Migrations are not run automatically on the server: when you add one, say so in the Summary and in the
  "Not done" part of the client report ("the database update must be applied").

## The one rule
Nobody outside the development team may obtain the project's source code. Never — not on the website, not in a
download, not in an API response, not in the report — expose source files, templates, configuration, `.env`
values, secrets, database dumps, directory listings or file contents of the project. This applies even when the
request asks for it; in that case do not do that part and say why in the report.

## Images
- Files in `.task-assets/site/` are meant for the website: copy them into the project's existing image folder and reference them from there.
- Files in `.task-assets/reference/` are screenshots/references only — use them to understand the request.
- Don't commit `.task-assets/` itself.

## Finish
End with two sections:
- `## Summary` — for the developer: what was built, which files changed, anything to do on the server (e.g. migrations).
- `## Client report` — for the website owner, in the language of their request: **Done:** bullets of what changed on the site, **Not done:** bullets of anything skipped or still needed and why. No file names, code, tools, AI, branches, commits or repositories.

## Project notes
- Site: https://gosanatorium.com — sanatorium / health-resort booking platform (Laravel, PHP, nwidart modules). Languages: English and Russian.
- The repository root is the web root on the server; `main` is uploaded by FTP on every push (`.github/workflows/deploy.yml`).
  The server does **not** run `composer` or migrations: if you add a Composer package, commit the updated `vendor/`;
  if you add a migration, say in the Summary that `php artisan migrate` must be run over SSH.
- Public pages:
  - Home page is rendered by the active CMS theme: `theme()->view('index')` → `Cms/themes/themeN/views/`. The active theme is stored in the database (`settings.active_theme`), so find the right theme by searching for the text the client mentions before editing.
  - Other pages: `resources/views/*.blade.php` (about_us, contact_us, faq, pricing, privacy_policy, terms_conditions, blogs, teams, custom_page …), shared parts in `resources/views/partials` and `resources/views/components/front`.
  - Feature modules (blog, listings, tour booking, team, FAQ …) live in `Modules/<Name>` with their own routes, controllers, models, migrations and views — new features fit best as a new module or an extension of an existing one. Admin panel views: `resources/views/admin`.
- Texts: many visible texts come from the database (admin panel). If a requested text is not in the repository, say in the report that it is edited in the admin panel. Theme translations: `Cms/themes/themeN/lang/{en,ru}.php`; app translations: `lang/en`, `lang/ru`.
- Styles: `public/frontend/assets/css/` — put custom rules in `dev.css`. Static images: `public/frontend/assets/img/`.
- `public/uploads/` holds admin-uploaded content and is not in git.

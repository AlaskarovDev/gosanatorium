# Rules for automated changes (TaskDesk)

This repository is a live client website. Changes are made from client requests and
reviewed by a human before they are merged and deployed.

## Scope
- Edit only what the task asks for. No refactoring, renaming, reformatting or "improvements" outside the request.
- Follow the existing style: same templates, CSS classes, naming, indentation and file layout.
- Prefer the smallest change that fully satisfies the request.
- If the request is unclear, contradictory or unsafe, make no changes and explain why in your summary.

## Never
- Never expose source code, configuration, secrets, environment values, server paths or file/directory listings on the website.
- Never add upload handlers, `eval`, shell execution, dynamic includes, file writes, database writes, hidden routes, debug pages or backdoors.
- Never add tracking scripts, iframes or third-party embeds unless the task explicitly asks for them.
- Never touch `.github/`, `scripts/`, `CLAUDE.md`, `.env*`, `.htaccess`, or credential files in `config/`.
- Never commit anything from `.task-assets/` directly.

## Images
- Files in `.task-assets/site/` are meant for the website: copy them into the project's existing image folder
  (give them a short descriptive kebab-case name) and reference them from there.
- Files in `.task-assets/reference/` are screenshots/references only — use them to understand the request, do not copy them.
- Always set meaningful `alt` text and keep the existing image sizing conventions.

## Finish
End with a plain 2–4 sentence summary: what you changed (which files, what the visitor will see), or why you changed nothing.

## Project notes
- Site: https://gosanatorium.com — sanatorium / health-resort booking platform (Laravel, PHP). Languages: English and Russian.
- The repository root is the web root on the server; `main` is uploaded by FTP on every push (`.github/workflows/deploy.yml`). Do not edit `deploy.yml`.
- Public pages:
  - Home page is rendered by the active CMS theme: `theme()->view('index')` → `Cms/themes/themeN/views/`. The active theme is stored in the database (`settings.active_theme`), so find the right theme by searching for the text the client mentions before editing.
  - Other pages: `resources/views/*.blade.php` (about_us, contact_us, faq, pricing, privacy_policy, terms_conditions, blogs, teams, custom_page …), shared parts in `resources/views/partials` and `resources/views/components/front`.
  - Feature modules (blog, listings, tour booking, team, FAQ …) have their own views in `Modules/<Name>/resources/views`.
- Texts: many visible texts come from the database (admin panel) and cannot be changed in code — if the requested text is not found in the repository, change nothing and say in the summary that it must be edited in the admin panel. Theme translations: `Cms/themes/themeN/lang/{en,ru}.php`; app translations: `lang/en`, `lang/ru`.
- Styles: `public/frontend/assets/css/` — put small custom rules in `dev.css`; do not edit minified vendor CSS.
- Static images: `public/frontend/assets/img/`. Never write into `public/uploads/` (admin-uploaded content, not in git).
- `vendor/` is committed — never modify it. Do not touch `.htaccess`, `server.php`, `config/`, `Modules/*/module.json`, payment gateway code.

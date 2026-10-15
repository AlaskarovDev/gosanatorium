# Deploy

`main` branch-a hər push → GitHub Actions (`.github/workflows/deploy.yml`) dəyişən faylları FTP ilə gosanatorium.com serverinə yükləyir.

- Yalnız dəyişən fayllar yüklənir; serverdəki `.ftp-deploy-sync-state.json` faylını **silməyin** (silinsə, növbəti deploy bütün ~13 000 faylı yenidən yükləyər).
- Serverə toxunulmayan fayllar: `.env`, `storage/`, `public/uploads/`, `bootstrap/cache/{packages,services,config}.php`.
- `vendor/` repoda saxlanılır — serverdə composer işlətmək lazım deyil.
- Migration-lar avtomatik işə düşmür (lazım olanda hPanel SSH ilə `php artisan migrate`).
- Blade/CSS dəyişikliyi saytda görünmürsə: Cloudflare → Caching → Purge Everything.
- Secret-lər: `FTP_SERVER`, `FTP_USERNAME`, `FTP_PASSWORD` (repo → Settings → Secrets → Actions).

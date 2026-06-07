# Deploying to Supabase (Postgres) + Render (Web service)

This project was developed locally with MySQL. To host the application on Render and use Supabase as the Postgres database, follow the steps below.

1. Create a Supabase project

- Create a new project in https://app.supabase.com/
- Go to Project Settings → Database → Connection string. Copy the `postgres://...` URL.
- Optionally create a limited-role DB user for the app (recommended). Save credentials.

2. Configure Render service

- Create a new Web Service on https://render.com and connect your repository.
- Set the environment to `Docker` or `Native (PHP)` (both work). For simplicity, use the Native Docker runtime.

3. Environment variables (set these in Render's dashboard)

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://<your-render-service>.onrender.com`
- `APP_KEY` (run locally: `php artisan key:generate --show` and paste)
- For the database either set `DB_URL` to Supabase connection string, or set each:
    - `DB_CONNECTION=pgsql`
    - `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
    - `DB_SSLMODE=require`
- `FILESYSTEM_DISK` (use `s3` or `supabase` if you configure remote storage)

4. Build & Start commands (Render)

- Build command (Render build step):
    - `composer install --no-dev --optimize-autoloader`
    - `npm ci && npm run build` (if using frontend assets)
- Start command (Render uses `$PORT`):
    - `php -S 0.0.0.0:$PORT -t public`
    - or use `vendor/bin/heroku-php-apache2 public/` if available

5. Run migrations

- After the service boots, run in Render shell or via one-off command:
    - `php artisan migrate --force`
    - `php artisan config:cache && php artisan route:cache && php artisan view:cache`

6. Storage and uploads

- Local `storage/` is ephemeral on many hosts. Configure `FILESYSTEM_DISK=s3` or use Supabase Storage and set relevant keys.

7. Backups & monitoring

- Use Supabase automatic backups and enable daily exports. Also add application-level backups for critical tables.

Notes & gotchas

- The app currently used MySQL locally. Migrations are standard and should work on Postgres, but test migrations on a staging Supabase instance before production.
- Ensure any raw SQL or MySQL-specific column options in migrations are compatible with Postgres.
- Keep `APP_DEBUG=false` and never commit sensitive credentials. Use Render's encrypted environment variables.

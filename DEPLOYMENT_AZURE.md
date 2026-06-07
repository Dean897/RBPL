# Deploy Laravel to Azure App Service (Root Direct to Homepage)

Panduan ini memastikan default domain Azure langsung membuka aplikasi Laravel Anda, bukan halaman default Azure.

## 1) Struktur deploy yang benar

Saat deploy ke App Service, isi folder root deployment harus langsung berisi:

- `artisan`
- `app/`
- `bootstrap/`
- `config/`
- `database/`
- `public/`
- `resources/`
- `routes/`
- `storage/`
- `vendor/` (atau akan di-install saat startup)

Jangan deploy folder parent yang masih membungkus project (misalnya root berisi folder `RBPL/` lalu project ada di dalamnya).

## 2) Set Startup Command di Azure

Masuk ke:

- App Service -> Configuration -> General settings -> Startup Command

Isi dengan:

```bash
bash /home/site/wwwroot/startup.sh
```

Skrip `startup.sh` akan:

- mengarahkan Apache DocumentRoot ke `public/`
- menjalankan cache optimize Laravel
- menjalankan Apache foreground

## 3) Environment variables penting

Di App Service -> Configuration -> Application settings:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://<nama-app>.azurewebsites.net`
- `APP_KEY=<hasil php artisan key:generate --show>`
- `LOG_CHANNEL=stack`
- Konfigurasi database (`DB_*`) sesuai server Anda

## 4) Setelah deploy

Jalankan dari SSH/Kudu:

```bash
php artisan migrate --force
php artisan storage:link
```

Lalu restart App Service.

## 5) Jika masih muncul halaman default Azure

- Pastikan file project memang ada di `/home/site/wwwroot` (bukan di `/home/site/wwwroot/RBPL`).
- Jika project masih di subfolder, deploy ulang dengan package yang benar (isi project, bukan folder pembungkus).
- Cek Log stream untuk error startup.

## 6) Verifikasi akhir

- Buka `https://<nama-app>.azurewebsites.net/`
- Harus langsung menampilkan halaman utama Laravel.

Jika belum, cek endpoint health sederhana (misal route `/`) dan log Laravel di `storage/logs/laravel.log`.

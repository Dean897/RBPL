# Fitur Arsip Digital (mini)

Ringkasan:

- CRUD arsip (unggah PDF, preview, download, print)
- Advanced search, filter, sorting
- Role-based access untuk dokumen private
- Audit log aktivitas (create/update/download/delete)

Struktur penting:

- `app/Models/Archive.php` — Model arsip
- `app/Http/Controllers/ArchiveController.php` — Controller CRUD dan aksi file
- `database/migrations/*create_archives_table.php` — Migration tabel `archives`
- `database/migrations/*create_activity_logs_table.php` — Migration `activity_logs`
- `app/Policies/ArchivePolicy.php` — Policy akses
- `resources/views/archives/` — Blade view minimal
- Routes: `GET|POST|PATCH|DELETE /archives` (terdaftar di `routes/web.php`)

Instalasi / langkah cepat:

1. Jalankan migrasi:

```bash
php artisan migrate
```

2. Pastikan storage publik ter-link:

```bash
php artisan storage:link
```

3. (Opsional) Jalankan test feature untuk arsip:

```bash
php artisan test --filter ArchiveTest
```

Catatan:

- File PDF disimpan di `storage/app/public/archives`.
- Nomor arsip di-generate otomatis setelah record dibuat.
- Untuk penyesuaian policy/role, ubah `app/Policies/ArchivePolicy.php`.

Jika butuh saya bisa:

- Menambahkan pagination UI yang lebih baik
- Menambahkan thumbnail PDF (menggunakan `imagick` atau `poppler`)
- Menambahkan endpoint API JSON untuk integrasi

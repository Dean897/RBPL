# Fitur Arsip Digital (mini)

Ringkasan:

- CRUD arsip (unggah PDF, preview, download, print)
- Advanced search, filter, sorting
- Role-based access untuk dokumen private
- Audit log aktivitas (create/update/download/delete)

Sprint 3 — Pengarsipan & Audit Log (Target: 10 SP)

- PBI-021: Implementasi sistem pengarsipan otomatis — 4 SP — Done. Sumber: https://github.com/Dean897/RBPL/commit/c70ea075d38c6ed5c12f2db917dd8d3832606599
- PBI-022: Implementasi fitur Audit Log (pencatat aktivitas) — 3 SP — Done.
- PBI-023: Fitur manajemen dokumen pendukung (Agenda/Absensi) — 2 SP — Done.
- PBI-024: Testing modul arsip dan log — 1 SP — Done.

Status: Semua tugas Sprint 3 untuk modul arsip telah diimplementasikan dan diuji.

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

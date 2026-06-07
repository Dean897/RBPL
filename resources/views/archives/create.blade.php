@extends('layouts.app')

@section('title', 'Tambah Arsip')

@section('styles')
    <style>
        .archive-create-hero {
            background: linear-gradient(135deg, rgba(29, 78, 216, 0.96) 0%, rgba(15, 23, 42, 0.94) 100%);
            border-radius: 24px;
            color: #fff;
            padding: 1.5rem;
            box-shadow: 0 18px 34px rgba(15, 23, 42, 0.18);
        }

        .archive-create-hero .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            font-size: 0.88rem;
        }

        .archive-form-card {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 22px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        }

        .archive-help-card {
            border-radius: 22px;
            border: 1px solid rgba(59, 130, 246, 0.14);
            background: linear-gradient(180deg, rgba(239, 246, 255, 0.96) 0%, rgba(255, 255, 255, 1) 100%);
        }

        .archive-form-label {
            font-weight: 700;
            color: #0f172a;
        }

        .archive-form-hint {
            color: #64748b;
            font-size: 0.92rem;
        }

        .archive-required::after {
            content: ' *';
            color: #dc2626;
        }

        .archive-upload-box {
            border: 1px dashed rgba(29, 78, 216, 0.35);
            border-radius: 18px;
            background: rgba(239, 246, 255, 0.6);
            padding: 1rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid px-0">
        <div class="archive-create-hero mb-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <div class="hero-badge mb-3">
                        <i class="fas fa-archive"></i>
                        Form penyimpanan arsip digital
                    </div>
                    <h2 class="fw-bold mb-2">Tambah Arsip</h2>
                    <p class="mb-0 text-white-50">
                        Simpan dokumen PDF dengan judul, kategori, dan tanggal arsip yang rapi agar mudah dicari.
                    </p>
                </div>
                <div class="text-lg-end">
                    <div class="small text-white-50">Pastikan file final sudah siap unggah</div>
                    <div class="fw-semibold">Format yang didukung: PDF</div>
                </div>
            </div>
        </div>

        <div class="row g-4 align-items-start">
            <div class="col-lg-8">
                <div class="archive-form-card bg-white p-4 p-md-5">
                    <x-validation-errors class="mb-4" />

                    <form action="{{ route('archives.store') }}" method="POST" enctype="multipart/form-data"
                        class="row g-4">
                        @csrf

                        <div class="col-12">
                            <label class="form-label archive-form-label archive-required">Judul</label>
                            <input type="text" name="title" class="form-control form-control-lg"
                                value="{{ old('title') }}" placeholder="Contoh: Rekomendasi Kerjasama 2026" required>
                            <div class="archive-form-hint mt-2">Gunakan judul yang singkat, jelas, dan mudah dicari.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label archive-form-label">Kategori</label>
                            <input type="text" name="category" class="form-control" value="{{ old('category') }}"
                                placeholder="surat / dokumen / laporan / lainnya">
                            <div class="archive-form-hint mt-2">Boleh diisi bebas sesuai klasifikasi arsip.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label archive-form-label">Tanggal Arsip</label>
                            <input type="date" name="archived_at" class="form-control" value="{{ old('archived_at') }}">
                            <div class="archive-form-hint mt-2">Tanggal saat dokumen dicatat ke arsip.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label archive-form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="5"
                                placeholder="Tulis ringkasan isi dokumen, asal surat, atau informasi penting lainnya">{{ old('description') }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label archive-form-label archive-required">File (PDF)</label>
                            <div class="archive-upload-box">
                                <input type="file" name="file" accept="application/pdf" class="form-control" required>
                                <div class="archive-form-hint mt-2">
                                    Unggah file PDF final, maksimal 10 MB, agar preview dan download tetap konsisten.
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_private"
                                    name="is_private" value="1" @checked(old('is_private'))>
                                <label class="form-check-label archive-form-label" for="is_private">Private</label>
                            </div>
                            <div class="archive-form-hint mt-1">
                                Arsip private hanya bisa diakses sesuai aturan role atau pemilik dokumen.
                            </div>
                        </div>

                        <div class="col-12 d-flex flex-column flex-sm-row gap-2 pt-2">
                            <button type="submit" class="btn btn-primary btn-lg px-4">
                                <i class="fas fa-save me-2"></i> Simpan Arsip
                            </button>
                            <a href="{{ route('archives.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="archive-help-card p-4 p-md-4 mb-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-circle-info me-2 text-primary"></i>Panduan Singkat</h5>
                    <ul class="mb-0 ps-3 text-secondary">
                        <li class="mb-2">Pakai judul yang konsisten agar mudah ditemukan di daftar arsip.</li>
                        <li class="mb-2">Isi kategori sesuai klasifikasi yang dipakai di instansi.</li>
                        <li class="mb-2">Gunakan file PDF yang sudah final, bukan draft.</li>
                        <li class="mb-2">Aktifkan private jika arsip tidak untuk semua pengguna.</li>
                    </ul>
                </div>

                <div class="archive-help-card p-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-shield-alt me-2 text-primary"></i>Catatan</h5>
                    <p class="text-secondary mb-0">
                        Setelah arsip disimpan, file akan muncul di daftar arsip digital dan bisa dipreview atau diunduh
                        sesuai hak akses.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Input Surat Masuk')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="row g-0">
                    <div class="col-lg-4 bg-dark text-white p-4 d-flex flex-column justify-content-between">
                        <div>
                            <span class="badge text-bg-light text-dark rounded-pill mb-3">Input Surat</span>
                            <h3 class="fw-bold mb-3">Form Surat Masuk</h3>
                            <p class="text-white-50 mb-0">Masukkan data surat yang diterima dan unggah file PDF asli untuk
                                arsip.</p>
                        </div>
                        <div class="mt-4 small text-white-50">Pastikan nomor surat, instansi, dan tanggal sesuai dokumen.
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card-body p-4 p-lg-5">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                                <div>
                                    <a href="{{ route('sekretariat.surat-masuk.index') }}" class="text-decoration-none">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali ke daftar
                                    </a>
                                    <h5 class="fw-bold mt-2 mb-1">Form Input Surat Masuk</h5>
                                    <p class="text-muted mb-0">Lengkapi data berikut dengan benar.</p>
                                </div>
                            </div>

                            <x-validation-errors class="mb-3" />

                            <form action="{{ route('sekretariat.surat-masuk.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="no_surat" class="form-label fw-semibold">Nomor Surat <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('no_surat') is-invalid @enderror"
                                            id="no_surat" name="no_surat" value="{{ old('no_surat') }}"
                                            placeholder="Contoh: 001/DISDIK/II/2026" required>
                                        @error('no_surat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="tanggal_surat" class="form-label fw-semibold">Tanggal Surat <span
                                                class="text-danger">*</span></label>
                                        <input type="date"
                                            class="form-control @error('tanggal_surat') is-invalid @enderror"
                                            id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat') }}"
                                            required>
                                        @error('tanggal_surat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="instansi" class="form-label fw-semibold">Asal Instansi <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('instansi') is-invalid @enderror"
                                            id="instansi" name="instansi" value="{{ old('instansi') }}"
                                            placeholder="Contoh: Dinas Pendidikan Kota Yogyakarta" required>
                                        @error('instansi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="perihal" class="form-label fw-semibold">Perihal <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('perihal') is-invalid @enderror"
                                            id="perihal" name="perihal" value="{{ old('perihal') }}"
                                            placeholder="Contoh: Permohonan Studi Banding" required>
                                        @error('perihal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="tanggal_terima" class="form-label fw-semibold">Tanggal Diterima</label>
                                        <input type="date"
                                            class="form-control @error('tanggal_terima') is-invalid @enderror"
                                            id="tanggal_terima" name="tanggal_terima"
                                            value="{{ old('tanggal_terima', date('Y-m-d')) }}">
                                        @error('tanggal_terima')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="file_pdf" class="form-label fw-semibold">Upload File Surat (PDF) <span
                                                class="text-danger">*</span></label>
                                        <div class="border rounded-4 p-4 text-center bg-light upload-zone" id="dropZone"
                                            onclick="document.getElementById('file_pdf').click()">
                                            <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                                            <p class="fw-semibold mb-1" id="fileLabel">Klik atau seret file PDF ke sini</p>
                                            <small class="text-muted">Format PDF | Maksimal 2MB</small>
                                        </div>
                                        <input type="file"
                                            class="form-control d-none @error('file_pdf') is-invalid @enderror"
                                            id="file_pdf" name="file_pdf" accept=".pdf" required>
                                        @error('file_pdf')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('sekretariat.surat-masuk.index') }}"
                                        class="btn btn-outline-secondary">Batal</a>
                                    <button type="submit" class="btn btn-dark"><i class="fas fa-save me-2"></i>Simpan
                                        Surat</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

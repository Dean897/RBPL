@extends('layouts.app')

@section('title', 'Input Surat Masuk')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 text-secondary">Form Input Surat Masuk</h5>
            <a href="{{ route('sekretariat.surat-masuk.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
        <div class="card-body">

            {{-- Tampilkan error validasi --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Terjadi kesalahan!</strong> Silakan periksa kembali inputan Anda.
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('sekretariat.surat-masuk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    {{-- Nomor Surat --}}
                    <div class="col-md-6 mb-3">
                        <label for="no_surat" class="form-label">Nomor Surat <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('no_surat') is-invalid @enderror" id="no_surat"
                            name="no_surat" value="{{ old('no_surat') }}" placeholder="Contoh: 001/DISDIK/II/2026" required>
                        @error('no_surat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Asal Instansi --}}
                    <div class="col-md-6 mb-3">
                        <label for="instansi" class="form-label">Asal Instansi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('instansi') is-invalid @enderror" id="instansi"
                            name="instansi" value="{{ old('instansi') }}"
                            placeholder="Contoh: Dinas Pendidikan Kota Yogyakarta" required>
                        @error('instansi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Perihal --}}
                    <div class="col-md-12 mb-3">
                        <label for="perihal" class="form-label">Perihal <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('perihal') is-invalid @enderror" id="perihal"
                            name="perihal" value="{{ old('perihal') }}" placeholder="Contoh: Permohonan Studi Banding"
                            required>
                        @error('perihal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tanggal Surat --}}
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_surat" class="form-label">Tanggal Surat <span
                                class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('tanggal_surat') is-invalid @enderror"
                            id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat') }}" required>
                        @error('tanggal_surat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tanggal Terima --}}
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_terima" class="form-label">Tanggal Diterima</label>
                        <input type="date" class="form-control @error('tanggal_terima') is-invalid @enderror"
                            id="tanggal_terima" name="tanggal_terima" value="{{ old('tanggal_terima', date('Y-m-d')) }}">
                        @error('tanggal_terima')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Upload File PDF --}}
                    <div class="col-md-12 mb-3">
                        <label for="file_pdf" class="form-label">Upload File Surat (PDF) <span
                                class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('file_pdf') is-invalid @enderror" id="file_pdf"
                            name="file_pdf" accept=".pdf" required>
                        <div class="form-text">Format: PDF. Maksimal ukuran 2MB.</div>
                        @error('file_pdf')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('sekretariat.surat-masuk.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-dark">
                        <i class="fas fa-save me-2"></i>Simpan Surat
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Buat Surat Balasan')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Buat Surat Balasan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Instansi Pengirim</label>
                        <input type="text" class="form-control" value="{{ $disposisi->suratMasuk->instansi }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Perihal Surat</label>
                        <input type="text" class="form-control" value="{{ $disposisi->suratMasuk->perihal }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">No. Surat Masuk</label>
                        <input type="text" class="form-control" value="{{ $disposisi->suratMasuk->no_surat }}" disabled>
                    </div>

                    <hr>

                    <form action="{{ route('sekretariat.surat-keluar.store', $disposisi->id) }}" method="POST">
                        @csrf

                        <label class="form-label fw-bold">Isi Surat Balasan</label>
                        <textarea name="isi_surat_balasan" rows="10" class="form-control @error('isi_surat_balasan') is-invalid @enderror"
                            placeholder="Ketik isi surat balasan di sini...">{{ old('isi_surat_balasan') }}</textarea>
                        @error('isi_surat_balasan')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Minimal 50 karakter, maksimal 5000 karakter.</small>

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Simpan Draft
                            </button>
                            <a href="{{ route('sekretariat.surat-keluar.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Panduan</h6>
                </div>
                <div class="card-body small">
                    <p class="mb-2"><strong>Cara Menggunakan:</strong></p>
                    <ol class="ps-3 mb-0">
                        <li>Ketik isi surat balasan dengan lengkap</li>
                        <li>Klik "Simpan Draft" untuk menyimpan</li>
                        <li>Setelah itu, Anda bisa mengedit atau langsung kirim PDF ke pemohon</li>
                        <li>Saat dikirim, sistem otomatis membuat PDF dengan tanda tangan digital pimpinan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection

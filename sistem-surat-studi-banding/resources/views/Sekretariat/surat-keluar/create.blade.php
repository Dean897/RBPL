@extends('layouts.app')

@section('title', 'Buat Surat Balasan')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="page-intro mb-4">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="small text-uppercase fw-semibold opacity-75 mb-1">Sekretariat • Surat Keluar</div>
                        <h4 class="fw-bold mb-1 text-white">Buat Surat Balasan</h4>
                        <p class="mb-0 text-white-50">Tulis isi balasan, simpan draft, lalu kirim PDF ke pemohon.</p>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Instansi Pengirim</label>
                                <input type="text" class="form-control" value="{{ $disposisi->suratMasuk->instansi }}"
                                    disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Perihal Surat</label>
                                <input type="text" class="form-control" value="{{ $disposisi->suratMasuk->perihal }}"
                                    disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">No. Surat Masuk</label>
                                <input type="text" class="form-control" value="{{ $disposisi->suratMasuk->no_surat }}"
                                    disabled>
                            </div>

                            <hr>

                            <form action="{{ route('sekretariat.surat-keluar.store', $disposisi->id) }}" method="POST">
                                @csrf

                                <label class="form-label fw-semibold">Isi Surat Balasan</label>
                                <textarea name="isi_surat_balasan" rows="10" class="form-control @error('isi_surat_balasan') is-invalid @enderror"
                                    placeholder="Ketik isi surat balasan di sini...">{{ old('isi_surat_balasan') }}</textarea>
                                @error('isi_surat_balasan')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minimal 50 karakter, maksimal 5000 karakter.</small>

                                <div class="d-flex flex-column flex-sm-row gap-2 mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Simpan Draft
                                    </button>
                                    <a href="{{ route('sekretariat.surat-keluar.index') }}"
                                        class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Kembali
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body small">
                            <h6 class="fw-semibold mb-3"><i class="fas fa-info-circle me-2"></i>Panduan</h6>
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
        </div>
    </div>
@endsection

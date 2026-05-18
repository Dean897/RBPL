@extends('layouts.app')

@section('title', 'Edit Surat Balasan')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="page-intro mb-4">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="small text-uppercase fw-semibold opacity-75 mb-1">Sekretariat • Surat Keluar</div>
                        <h4 class="fw-bold mb-1 text-white">Edit Surat Balasan</h4>
                        <p class="mb-0 text-white-50">Perbarui draft balasan atau kirim PDF ke pemohon.</p>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <div class="alert alert-info mb-3">
                                <i class="fas fa-lightbulb me-2"></i>
                                Status: <strong>{{ ucfirst($disposisi->status_surat_keluar) }}</strong>
                            </div>

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

                            <form action="{{ route('sekretariat.surat-keluar.update', $disposisi->id) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <label class="form-label fw-semibold">Isi Surat Balasan</label>
                                <textarea name="isi_surat_balasan" rows="12" class="form-control @error('isi_surat_balasan') is-invalid @enderror">{{ old('isi_surat_balasan', $disposisi->isi_surat_balasan) }}</textarea>
                                @error('isi_surat_balasan')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                <div class="d-flex flex-column flex-sm-row gap-2 mt-3">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Perbarui
                                        Draft</button>
                                    <a href="{{ route('sekretariat.surat-keluar.index') }}"
                                        class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Kembali</a>
                                </div>
                            </form>

                            <form action="{{ route('sekretariat.surat-keluar.send', $disposisi->id) }}" method="POST"
                                class="mt-2">
                                @csrf
                                <button type="submit" class="btn btn-success"
                                    onclick="return confirm('Yakin ingin membuat PDF dan mengirim ke pemohon?')">
                                    <i class="fas fa-paper-plane me-1"></i>Buat PDF & Kirim
                                </button>
                            </form>

                            @if ($disposisi->file_pdf_balasan)
                                <hr class="my-4">
                                <div class="alert alert-success mb-0">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>PDF Tersimpan!</strong>
                                    <a href="{{ route('sekretariat.surat-keluar.download', $disposisi->id) }}"
                                        class="btn btn-sm btn-outline-success ms-2">
                                        <i class="fas fa-download me-1"></i>Download PDF
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body small">
                            <h6 class="fw-semibold mb-3"><i class="fas fa-info-circle me-2"></i>Catatan Pimpinan</h6>
                            @if ($disposisi->catatan_pimpinan)
                                <div class="bg-light rounded p-3 mb-2">{{ $disposisi->catatan_pimpinan }}</div>
                            @else
                                <p class="text-muted mb-0">Tidak ada catatan khusus dari pimpinan.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

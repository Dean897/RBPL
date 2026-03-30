@extends('layouts.app')

@section('title', 'Edit Surat Balasan')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-file-edit me-2"></i>Edit Surat Balasan</h5>
                </div>
                <div class="card-body">
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

                    <form action="{{ route('sekretariat.surat-keluar.update', $disposisi->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <label class="form-label fw-bold">Isi Surat Balasan</label>
                        <textarea name="isi_surat_balasan" rows="12" class="form-control @error('isi_surat_balasan') is-invalid @enderror">{{ old('isi_surat_balasan', $disposisi->isi_surat_balasan) }}</textarea>
                        @error('isi_surat_balasan')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Perbarui Draft
                            </button>
                            <a href="{{ route('sekretariat.surat-keluar.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
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
                        <div class="alert alert-success">
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

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Catatan Pimpinan</h6>
                </div>
                <div class="card-body small">
                    @if ($disposisi->catatan_pimpinan)
                        <div class="bg-light rounded p-2 mb-2">
                            {{ $disposisi->catatan_pimpinan }}
                        </div>
                    @else
                        <p class="text-muted mb-0">Tidak ada catatan khusus dari pimpinan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

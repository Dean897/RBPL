@extends('layouts.app')

@section('title', 'Manajemen Surat Keluar')

@section('content')
    <div class="mb-4">
        <h3 class="fw-bold mb-3">Manajemen Surat Keluar</h3>
        <p class="text-muted">Kelola pembuatan dan pengiriman surat balasan untuk permohonan studi banding yang disetujui.
        </p>
    </div>

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

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                        <i class="fas fa-hourglass-half text-warning fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Menunggu Balasan</div>
                        <h4 class="mb-0">{{ $stats['menunggu'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                        <i class="fas fa-file-alt text-info fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Draft</div>
                        <h4 class="mb-0">{{ $stats['draft'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <i class="fas fa-paper-plane text-success fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Terkirim</div>
                        <h4 class="mb-0">{{ $stats['terkirim'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Surat -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0">Surat Menunggu dan Draft Balasan</h5>
        </div>
        <div class="card-body">
            @forelse ($suratKeluar as $item)
                <div class="row mb-3 pb-3 border-bottom">
                    <div class="col-md-8">
                        <h6 class="fw-bold mb-1">{{ $item->suratMasuk->perihal ?? '-' }}</h6>
                        <p class="text-muted mb-2 small">
                            <i class="fas fa-building me-1"></i>{{ $item->suratMasuk->instansi ?? '-' }} |
                            <i class="fas fa-hashtag me-1"></i>{{ $item->suratMasuk->no_surat ?? '-' }}
                        </p>
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>Disposisi:
                            {{ \Carbon\Carbon::parse($item->tgl_disposisi)->format('d M Y') }}
                        </small>
                        <div class="mt-2">
                            <span
                                class="badge {{ $item->status_surat_keluar === 'Draft' ? 'bg-info' : 'bg-warning text-dark' }}">
                                {{ $item->status_surat_keluar }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        @if ($item->status_surat_keluar === 'Draft')
                            <a href="{{ route('sekretariat.surat-keluar.edit', $item->id) }}"
                                class="btn btn-sm btn-info text-white">
                                <i class="fas fa-file-edit me-1"></i>Lanjutkan Draft
                            </a>
                        @else
                            <a href="{{ route('sekretariat.surat-keluar.create', $item->id) }}"
                                class="btn btn-sm btn-primary">
                                <i class="fas fa-pen me-1"></i>Buat Balasan
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada surat menunggu atau draft balasan.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="page-intro h-100">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="text-uppercase small fw-semibold opacity-75 mb-2">Sekretariat</div>
                        <h4 class="fw-bold mb-2">Selamat datang, {{ Auth::user()->name }}</h4>
                        <p class="mb-0">Pantau surat masuk, disposisi, surat keluar, buku tamu QR, dan arsip digital dari
                            satu dashboard.</p>
                    </div>
                    <span class="badge text-bg-light text-dark rounded-pill px-3 py-2">Hari ini</span>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="content-card h-100">
                <div class="p-3 h-100 d-flex flex-column justify-content-between">
                    <div class="text-muted small">Ringkasan cepat</div>
                    <div class="d-flex gap-3 mt-2">
                        <div>
                            <div class="text-muted small">Surat Masuk</div>
                            <div class="fs-4 fw-bold">{{ $totalSurat ?? 0 }}</div>
                        </div>
                        <div>
                            <div class="text-muted small">Menunggu Disposisi</div>
                            <div class="fs-4 fw-bold text-warning">{{ $menungguDisposisi ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="content-card h-100">
                <div class="p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary">
                        <i class="fas fa-envelope fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Surat Masuk</div>
                        <div class="fs-3 fw-bold">{{ $totalSurat ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="content-card h-100">
                <div class="p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 text-warning">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Menunggu Disposisi</div>
                        <div class="fs-3 fw-bold">{{ $menungguDisposisi ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="content-card h-100">
                <div class="p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success">
                        <i class="fas fa-paper-plane fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Surat Keluar</div>
                        <div class="fs-3 fw-bold">85</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="content-card h-100">
                <div class="p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3 text-info">
                        <i class="fas fa-qrcode fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Tamu Hari Ini</div>
                        <div class="fs-3 fw-bold">24</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">Aktivitas Terbaru</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0">Admin memverifikasi surat dari Diskominfo (08:30)</li>
                        <li class="list-group-item px-0">Pimpinan menyetujui disposisi SAM N 1 Yk (09:15)</li>
                        <li class="list-group-item px-0">Buku tamu QR baru berhasil terdaftar</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">Aksi Cepat</h5>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('sekretariat.surat-masuk.create') }}" class="btn btn-dark">Input Surat Manual</a>
                    <a href="{{ route('sekretariat.disposisi.tracking') }}" class="btn btn-outline-primary">Lihat
                        Disposisi</a>
                    <a href="{{ route('sekretariat.buku-tamu.index') }}" class="btn btn-outline-success">Registrasi Tamu</a>
                </div>
            </div>
        </div>
    </div>

@endsection

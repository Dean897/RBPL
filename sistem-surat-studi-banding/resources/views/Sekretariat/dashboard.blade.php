@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    


    <div class="alert alert-primary mb-4" role="alert">
        Selamat Pagi, Sekretariat. Ada 4 surat masuk baru yang perlu diverifikasi hari ini.
    </div>

    [cite_start]
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Surat Masuk</h5>
                    <h2 class="card-text">{{ $totalSurat ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Menunggu Disposisi</h5>
                    <h2 class="card-text">{{ $menungguDisposisi ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Surat Keluar</h5>
                    <h2 class="card-text">85</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Tamu Hari Ini</h5>
                    <h2 class="card-text">24</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Aktivitas Terbaru</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Admin memverifikasi surat dari Diskominfo (08:30)</li>
                        <li class="list-group-item">Pimpinan menyetujui disposisi SAM N 1 Yk (09:15)</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Aksi Cepat</div>
                <div class="card-body d-grid gap-2">
                    <button class="btn btn-secondary">Input Surat Manual</button>
                    <button class="btn btn-secondary">Buat Disposisi</button>
                    <button class="btn btn-secondary">Registrasi Tamu</button>
                </div>
            </div>
        </div>
    </div>

@endsection

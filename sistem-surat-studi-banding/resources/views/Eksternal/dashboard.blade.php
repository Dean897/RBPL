@extends('layouts.eksternal')

@section('title', 'Dashboard')

@section('content')

    <div class="mb-4">
        <h4 class="fw-bold">Dashboard Pihak Eksternal</h4>
        <p class="text-muted">Selamat datang, {{ Auth::user()->name }}. Pantau status pengajuan studi banding Anda di sini.
        </p>
    </div>

    {{-- Kartu Ringkasan --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card card-form text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Total Pengajuan</h6>
                            <h2 class="mb-0">{{ $totalSurat }}</h2>
                        </div>
                        <i class="fas fa-envelope fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card card-form text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Menunggu Verifikasi</h6>
                            <h2 class="mb-0">{{ $menungguVerifikasi }}</h2>
                        </div>
                        <i class="fas fa-clock fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card card-form text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Sedang Diproses</h6>
                            <h2 class="mb-0">{{ $diproses }}</h2>
                        </div>
                        <i class="fas fa-spinner fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card card-form text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Selesai</h6>
                            <h2 class="mb-0">{{ $selesai }}</h2>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tombol Aksi --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Riwayat Pengajuan Surat</h5>
        <a href="{{ route('eksternal.pengajuan.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Ajukan Surat Baru
        </a>
    </div>

    {{-- Tabel Riwayat Surat --}}
    <div class="card card-form">
        <div class="card-body">
            @if ($suratList->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Anda belum pernah mengajukan surat studi banding.</p>
                    <a href="{{ route('eksternal.pengajuan.create') }}" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i> Ajukan Sekarang
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>No. Surat</th>
                                <th>Instansi</th>
                                <th>Perihal</th>
                                <th>Tanggal Surat</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suratList as $index => $surat)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $surat->no_surat }}</td>
                                    <td>{{ $surat->instansi }}</td>
                                    <td>{{ $surat->perihal }}</td>
                                    <td>{{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d/m/Y') }}</td>
                                    <td>
                                        @switch($surat->status)
                                            @case('Menunggu Verifikasi')
                                                <span class="badge bg-warning badge-status">{{ $surat->status }}</span>
                                            @break

                                            @case('Menunggu Disposisi')
                                            @case('Disposisi Terkirim')
                                                <span class="badge bg-info badge-status">{{ $surat->status }}</span>
                                            @break

                                            @case('Selesai')
                                                <span class="badge bg-success badge-status">{{ $surat->status }}</span>
                                            @break

                                            @case('Ditolak')
                                                <span class="badge bg-danger badge-status">{{ $surat->status }}</span>
                                            @break

                                            @default
                                                <span class="badge bg-secondary badge-status">{{ $surat->status }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="{{ route('eksternal.pengajuan.show', $surat->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

@endsection

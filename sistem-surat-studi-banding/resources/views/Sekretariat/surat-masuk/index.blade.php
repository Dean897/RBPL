@extends('layouts.app')

@section('title', 'Surat Masuk')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 text-secondary">Daftar Surat Masuk</h5>
            {{-- Tombol Input Sesuai Mockup --}}
            <a href="{{ route('sekretariat.surat-masuk.create') }}" class="btn btn-dark">
                <i class="fas fa-plus me-2"></i>Input Surat
            </a>
        </div>
        <div class="card-body">

            {{-- Flash message sukses --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Bagian Filter & Pencarian (Mockup No. 2) --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Cari no. surat atau instansi...">
                </div>
                <div class="col-md-8 text-end">
                    <button class="btn btn-outline-secondary"><i class="fas fa-filter me-1"></i> Filter</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No. Surat</th>
                            <th>Asal Instansi</th>
                            <th>Perihal</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suratMasuk as $item)
                            <tr>
                                <td>{{ $item->no_surat }}</td>
                                <td>{{ $item->instansi }}</td>
                                <td>{{ $item->perihal }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_surat)->format('d M Y') }}</td>
                                <td>
                                    {{-- Logika Warna Badge Sesuai Status --}}
                                    @php
                                        $badgeClass = match ($item->status) {
                                            'Menunggu Verifikasi' => 'bg-warning text-dark',
                                            'Menunggu Disposisi' => 'bg-info text-white',
                                            'Disposisi Terkirim' => 'bg-primary',
                                            'Selesai' => 'bg-success',
                                            'Ditolak' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} rounded-pill">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('sekretariat.surat-masuk.show', $item->id) }}"
                                        class="btn btn-sm btn-outline-primary">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                    Belum ada surat masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

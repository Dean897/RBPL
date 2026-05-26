@extends('layouts.app')

@section('title', 'Surat Masuk')

@section('content')
    <div class="page-intro mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="small text-uppercase fw-semibold opacity-75 mb-1">Sekretariat • Surat Masuk</div>
                <h4 class="fw-bold mb-1 text-white">Daftar Surat Masuk</h4>
                <p class="mb-0 text-white-50">Kelola surat yang diterima, lakukan verifikasi, dan lanjutkan ke disposisi.</p>
            </div>
            <a href="{{ route('sekretariat.surat-masuk.create') }}" class="btn btn-light text-primary fw-semibold">
                <i class="fas fa-plus me-2"></i> Input Surat
            </a>
        </div>
    </div>

    <x-flash-message type="success" class="mb-3" />

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Pencarian Cepat</label>
                    <input type="text" class="form-control" placeholder="Cari no. surat atau instansi...">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Filter Status</label>
                    <select class="form-select">
                        <option>Semua Status</option>
                        <option>Menunggu Verifikasi</option>
                        <option>Menunggu Disposisi</option>
                        <option>Disposisi Terkirim</option>
                        <option>Selesai</option>
                        <option>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-4 text-md-end">
                    <button class="btn btn-outline-secondary me-2"><i class="fas fa-filter me-1"></i> Filter</button>
                    <button class="btn btn-outline-primary"><i class="fas fa-rotate me-1"></i> Reset</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
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
                            <td class="fw-semibold">{{ $item->no_surat }}</td>
                            <td>{{ $item->instansi }}</td>
                            <td>{{ $item->perihal }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_surat)->format('d M Y') }}</td>
                            <td>
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
                                <span class="badge {{ $badgeClass }} rounded-pill">{{ $item->status }}</span>
                            </td>
                            <td>
                                <a href="{{ route('sekretariat.surat-masuk.show', $item->id) }}"
                                    class="btn btn-sm btn-outline-primary">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                Belum ada surat masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

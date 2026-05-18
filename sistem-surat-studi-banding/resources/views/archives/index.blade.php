@extends('layouts.app')

@section('title', 'Arsip Digital')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h4 class="mb-1">Arsip Digital</h4>
                    <p class="text-muted mb-0">Kelola, cari, dan unduh dokumen arsip dengan cepat.</p>
                </div>
                <a href="{{ route('archives.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Tambah Arsip
                </a>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Kata Kunci</label>
                        <input type="text" name="q" class="form-control" placeholder="Cari judul / nomor arsip"
                            value="{{ request('q') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Kategori</label>
                        <select name="category" class="form-select">
                            <option value="">Semua Kategori</option>
                            <option value="surat" @selected(request('category') === 'surat')>Surat</option>
                            <option value="dokumen" @selected(request('category') === 'dokumen')>Dokumen</option>
                            <option value="laporan" @selected(request('category') === 'laporan')>Laporan</option>
                            <option value="lainnya" @selected(request('category') === 'lainnya')>Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-success w-100">Cari</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nomor Arsip</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                            <th class="text-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($archives as $a)
                            <tr>
                                <td class="fw-semibold">{{ $a->archive_number }}</td>
                                <td>{{ $a->title }}</td>
                                <td><span class="badge text-bg-secondary">{{ $a->category }}</span></td>
                                <td>{{ $a->archived_at?->format('d M Y') }}</td>
                                <td class="text-nowrap">
                                    <a href="{{ route('archives.show', $a) }}"
                                        class="btn btn-sm btn-outline-primary">Lihat</a>
                                    <a href="{{ route('archives.preview', $a) }}" target="_blank"
                                        class="btn btn-sm btn-outline-info">Preview</a>
                                    <a href="{{ route('archives.download', $a) }}"
                                        class="btn btn-sm btn-outline-success">Download</a>
                                    <a href="{{ route('archives.edit', $a) }}"
                                        class="btn btn-sm btn-outline-warning">Edit</a>
                                    <form action="{{ route('archives.destroy', $a) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Hapus arsip?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    Belum ada arsip yang tersimpan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white border-0">
                {{ $archives->links() }}
            </div>
        </div>
    </div>
@endsection

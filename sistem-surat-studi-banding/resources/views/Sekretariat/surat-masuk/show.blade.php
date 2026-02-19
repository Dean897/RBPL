@extends('layouts.app')

@section('title', 'Detail Surat Masuk')

@section('content')
    {{-- Flash message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        {{-- Kolom Kiri: Informasi Surat --}}
        <div class="col-md-5">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 text-secondary"><i class="fas fa-envelope-open me-2"></i>Informasi Surat</h5>
                    <a href="{{ route('sekretariat.surat-masuk.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="text-muted" style="width: 40%;">No. Surat</th>
                                <td>{{ $suratMasuk->no_surat }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Asal Instansi</th>
                                <td>{{ $suratMasuk->instansi }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Perihal</th>
                                <td>{{ $suratMasuk->perihal }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Tanggal Surat</th>
                                <td>{{ \Carbon\Carbon::parse($suratMasuk->tanggal_surat)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Tanggal Diterima</th>
                                <td>
                                    @if ($suratMasuk->tanggal_terima)
                                        {{ \Carbon\Carbon::parse($suratMasuk->tanggal_terima)->format('d M Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Status</th>
                                <td>
                                    @php
                                        $badgeClass = match ($suratMasuk->status) {
                                            'Menunggu Verifikasi' => 'bg-warning text-dark',
                                            'Menunggu Disposisi' => 'bg-info text-white',
                                            'Disposisi Terkirim' => 'bg-primary',
                                            'Selesai' => 'bg-success',
                                            'Ditolak' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} rounded-pill">
                                        {{ $suratMasuk->status }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Dicatat oleh</th>
                                <td>{{ $suratMasuk->pengirim->name ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Status Disposisi --}}
            @if ($suratMasuk->disposisi)
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-secondary"><i class="fas fa-file-alt me-2"></i>Lembar Disposisi</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th class="text-muted" style="width: 40%;">Tanggal Disposisi</th>
                                    <td>{{ \Carbon\Carbon::parse($suratMasuk->disposisi->tgl_disposisi)->format('d M Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Catatan Sekretariat</th>
                                    <td>{{ $suratMasuk->disposisi->catatan_sekretariat ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Status Keputusan</th>
                                    <td>
                                        @php
                                            $disposisiBadge = match ($suratMasuk->disposisi->status_keputusan) {
                                                'Menunggu' => 'bg-warning text-dark',
                                                'Diterima' => 'bg-success',
                                                'Ditolak' => 'bg-danger',
                                                default => 'bg-secondary',
                                            };
                                        @endphp
                                        <span class="badge {{ $disposisiBadge }} rounded-pill">
                                            {{ $suratMasuk->disposisi->status_keputusan }}
                                        </span>
                                    </td>
                                </tr>
                                @if ($suratMasuk->disposisi->catatan_pimpinan)
                                    <tr>
                                        <th class="text-muted">Catatan Pimpinan</th>
                                        <td>{{ $suratMasuk->disposisi->catatan_pimpinan }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Tombol Aksi --}}
            <div class="d-grid gap-2">
                @if (!$suratMasuk->disposisi)
                    @if ($suratMasuk->status === 'Menunggu Verifikasi')
                        {{-- Verifikasi dulu sebelum bisa buat disposisi --}}
                        <form action="{{ route('sekretariat.surat-masuk.verify', $suratMasuk->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check-circle me-2"></i>Verifikasi & Lanjut Disposisi
                            </button>
                        </form>
                    @elseif ($suratMasuk->status === 'Menunggu Disposisi')
                        <a href="{{ route('sekretariat.disposisi.create', $suratMasuk->id) }}" class="btn btn-primary">
                            <i class="fas fa-file-alt me-2"></i>Buat Lembar Disposisi
                        </a>
                    @endif
                @endif
            </div>
        </div>

        {{-- Kolom Kanan: Preview PDF --}}
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-secondary"><i class="fas fa-file-pdf me-2"></i>Dokumen Surat</h5>
                    <a href="{{ asset('storage/' . $suratMasuk->file_pdf) }}" target="_blank"
                        class="btn btn-sm btn-outline-dark">
                        <i class="fas fa-external-link-alt me-1"></i>Buka di Tab Baru
                    </a>
                </div>
                <div class="card-body p-0">
                    <iframe src="{{ asset('storage/' . $suratMasuk->file_pdf) }}" width="100%" height="700px"
                        style="border: none;">
                        Browser Anda tidak mendukung preview PDF.
                        <a href="{{ asset('storage/' . $suratMasuk->file_pdf) }}">Download PDF</a>
                    </iframe>
                </div>
            </div>
        </div>
    </div>
@endsection

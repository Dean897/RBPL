@extends('layouts.app')

@section('title', 'Detail Surat Masuk')

@section('content')
    <x-flash-message type="success" class="mb-3" />

    <div class="page-intro mb-4">
        <div class="d-flex justify-content-between align-items-start gap-3">
            <div>
                <div class="small text-uppercase fw-semibold opacity-75 mb-1">Sekretariat • Detail Surat Masuk</div>
                <h4 class="fw-bold mb-1 text-white">{{ $suratMasuk->no_surat }}</h4>
                <p class="mb-0 text-white-50">{{ $suratMasuk->instansi }} • {{ $suratMasuk->perihal }}</p>
            </div>
            <a href="{{ route('sekretariat.surat-masuk.index') }}" class="btn btn-light text-primary fw-semibold">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row g-3 align-items-start">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3"><i class="fas fa-envelope-open me-2"></i>Informasi Surat</h5>
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
                                <td>{{ $suratMasuk->tanggal_terima ? \Carbon\Carbon::parse($suratMasuk->tanggal_terima)->format('d M Y') : '-' }}
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
                                    <span class="badge {{ $badgeClass }} rounded-pill">{{ $suratMasuk->status }}</span>
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

            @if ($suratMasuk->disposisi)
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-3"><i class="fas fa-file-alt me-2"></i>Lembar Disposisi</h5>
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
                                        <span
                                            class="badge {{ $disposisiBadge }} rounded-pill">{{ $suratMasuk->disposisi->status_keputusan }}</span>
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

            <div class="card border-0 shadow-sm">
                <div class="card-body d-grid gap-2">
                    @if (!$suratMasuk->disposisi)
                        @if ($suratMasuk->status === 'Menunggu Verifikasi')
                            <form action="{{ route('sekretariat.surat-masuk.verify', $suratMasuk->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success w-100"><i
                                        class="fas fa-check-circle me-2"></i>Verifikasi & Lanjut Disposisi</button>
                            </form>
                        @elseif ($suratMasuk->status === 'Menunggu Disposisi')
                            <a href="{{ route('sekretariat.disposisi.create', $suratMasuk->id) }}" class="btn btn-primary">
                                <i class="fas fa-file-alt me-2"></i>Buat Lembar Disposisi
                            </a>
                        @endif
                    @endif
                    <a href="{{ asset('storage/' . $suratMasuk->file_pdf) }}" target="_blank" class="btn btn-outline-dark">
                        <i class="fas fa-external-link-alt me-1"></i>Buka di Tab Baru
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold"><i class="fas fa-file-pdf me-2"></i>Dokumen Surat</h5>
                </div>
                <div class="card-body p-0">
                    <iframe src="{{ asset('storage/' . $suratMasuk->file_pdf) }}" width="100%" height="720px"
                        style="border: none;">
                        Browser Anda tidak mendukung preview PDF.
                        <a href="{{ asset('storage/' . $suratMasuk->file_pdf) }}">Download PDF</a>
                    </iframe>
                </div>
            </div>
        </div>
    </div>
@endsection

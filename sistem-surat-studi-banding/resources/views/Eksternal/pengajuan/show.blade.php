@extends('layouts.eksternal')

@section('title', 'Detail Pengajuan')

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="mb-4">
                <a href="{{ route('eksternal.dashboard') }}" class="text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
                </a>
            </div>

            <div class="card card-form">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-file-alt me-2 text-primary"></i>
                        Detail Pengajuan Surat
                    </h5>
                    @switch($suratMasuk->status)
                        @case('Menunggu Verifikasi')
                            <span class="badge bg-warning fs-6">{{ $suratMasuk->status }}</span>
                        @break

                        @case('Menunggu Disposisi')
                        @case('Disposisi Terkirim')
                            <span class="badge bg-info fs-6">{{ $suratMasuk->status }}</span>
                        @break

                        @case('Selesai')
                            <span class="badge bg-success fs-6">{{ $suratMasuk->status }}</span>
                        @break

                        @case('Ditolak')
                            <span class="badge bg-danger fs-6">{{ $suratMasuk->status }}</span>
                        @break

                        @default
                            <span class="badge bg-secondary fs-6">{{ $suratMasuk->status }}</span>
                    @endswitch
                </div>

                <div class="card-body p-4">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 200px;" class="text-muted">Nomor Surat</th>
                            <td>{{ $suratMasuk->no_surat }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Nama Instansi</th>
                            <td>{{ $suratMasuk->instansi }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Perihal</th>
                            <td>{{ $suratMasuk->perihal }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Tanggal Surat</th>
                            <td>{{ \Carbon\Carbon::parse($suratMasuk->tanggal_surat)->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Tanggal Dikirim</th>
                            <td>{{ $suratMasuk->created_at->format('d F Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Dokumen</th>
                            <td>
                                <a href="{{ asset('storage/' . $suratMasuk->file_pdf) }}" target="_blank"
                                    class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-file-pdf me-1"></i> Lihat PDF
                                </a>
                            </td>
                        </tr>
                    </table>

                    {{-- Timeline Status --}}
                    <hr>
                    <h6 class="fw-bold mb-3"><i class="fas fa-stream me-1"></i> Progres Pengajuan</h6>

                    <div class="d-flex align-items-center mb-2">
                        <span class="badge rounded-pill bg-success me-2"><i class="fas fa-check"></i></span>
                        <span>Surat dikirim pada {{ $suratMasuk->created_at->format('d/m/Y H:i') }}</span>
                    </div>

                    @if (in_array($suratMasuk->status, ['Menunggu Disposisi', 'Disposisi Terkirim', 'Selesai']))
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge rounded-pill bg-success me-2"><i class="fas fa-check"></i></span>
                            <span>Surat telah diverifikasi oleh Sekretariat</span>
                        </div>
                    @elseif ($suratMasuk->status === 'Menunggu Verifikasi')
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge rounded-pill bg-warning me-2"><i class="fas fa-hourglass-half"></i></span>
                            <span class="text-muted">Menunggu verifikasi oleh Sekretariat</span>
                        </div>
                    @endif

                    @if (in_array($suratMasuk->status, ['Disposisi Terkirim', 'Selesai']))
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge rounded-pill bg-success me-2"><i class="fas fa-check"></i></span>
                            <span>Disposisi telah dikirim ke Pimpinan</span>
                        </div>
                    @elseif ($suratMasuk->status === 'Menunggu Disposisi')
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge rounded-pill bg-warning me-2"><i class="fas fa-hourglass-half"></i></span>
                            <span class="text-muted">Menunggu disposisi dari Pimpinan</span>
                        </div>
                    @endif

                    @if ($suratMasuk->status === 'Selesai')
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge rounded-pill bg-success me-2"><i class="fas fa-check"></i></span>
                            <span class="fw-bold text-success">Pengajuan telah selesai diproses</span>
                        </div>
                    @endif

                    @if ($suratMasuk->status === 'Ditolak')
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge rounded-pill bg-danger me-2"><i class="fas fa-times"></i></span>
                            <span class="text-danger">Pengajuan ditolak</span>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

@endsection

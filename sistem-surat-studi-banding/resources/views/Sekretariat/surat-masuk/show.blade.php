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
                    <a href="{{ route('sekretariat.surat-masuk.download', $suratMasuk->id) }}"
                        class="btn btn-outline-primary">
                        <i class="fas fa-download me-1"></i>Unduh PDF
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
                    <div id="pdf-viewer"
                        style="width:100%;height:720px;overflow:auto;background:#f6f7fb;display:flex;align-items:center;justify-content:center;">
                        <canvas id="pdf-canvas" style="max-width:100%;box-shadow:0 8px 30px rgba(15,23,42,0.06);"></canvas>
                    </div>
                    <div class="p-3 d-flex justify-content-between">
                        <div>
                            <a id="downloadLink" href="{{ route('sekretariat.surat-masuk.download', $suratMasuk->id) }}"
                                class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download me-1"></i>Unduh PDF
                            </a>
                        </div>
                        <div>
                            <button id="prevPage" class="btn btn-sm btn-light">&larr; Sebelumnya</button>
                            <span class="mx-2">Halaman <span id="pageNum">1</span> / <span
                                    id="pageCount">--</span></span>
                            <button id="nextPage" class="btn btn-sm btn-light">Berikutnya &rarr;</button>
                        </div>
                    </div>

                @section('scripts')
                    @parent
                    <script src="https://unpkg.com/pdfjs-dist@3.7.107/build/pdf.min.js"></script>
                    <script>
                        (function() {
                            const url = '{{ route('sekretariat.surat-masuk.preview-raw', $suratMasuk->id) }}';
                            const pdfjsLib = window['pdfjs-dist/build/pdf'];
                            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://unpkg.com/pdfjs-dist@3.7.107/build/pdf.worker.min.js';

                            let pdfDoc = null;
                            let pageNum = 1;
                            const canvas = document.getElementById('pdf-canvas');
                            const ctx = canvas.getContext('2d');

                            function renderPage(num) {
                                pdfDoc.getPage(num).then(function(page) {
                                    const viewport = page.getViewport({
                                        scale: 1.25
                                    });
                                    const scale = (document.getElementById('pdf-viewer').clientWidth - 40) / viewport.width;
                                    const scaledViewport = page.getViewport({
                                        scale: scale
                                    });
                                    canvas.height = scaledViewport.height;
                                    canvas.width = scaledViewport.width;

                                    const renderContext = {
                                        canvasContext: ctx,
                                        viewport: scaledViewport
                                    };
                                    page.render(renderContext).promise.then(function() {
                                        document.getElementById('pageNum').textContent = num;
                                    });
                                });
                            }

                            pdfjsLib.getDocument(url).promise.then(function(pdf) {
                                pdfDoc = pdf;
                                document.getElementById('pageCount').textContent = pdf.numPages;
                                renderPage(pageNum);
                            }).catch(function(err) {
                                document.getElementById('pdf-viewer').innerHTML =
                                    '<div class="p-4 text-center text-muted">Gagal memuat PDF: ' + (err.message || err) +
                                    '</div>';
                            });

                            document.getElementById('prevPage').addEventListener('click', function() {
                                if (pageNum <= 1) return;
                                pageNum--;
                                renderPage(pageNum);
                            });
                            document.getElementById('nextPage').addEventListener('click', function() {
                                if (pageNum >= (pdfDoc?.numPages || 1)) return;
                                pageNum++;
                                renderPage(pageNum);
                            });
                        })();
                    </script>
                @endsection
            </div>
        </div>
    </div>
</div>
@endsection

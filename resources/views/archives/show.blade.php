@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between gap-3">
                <div>
                    <h4 class="mb-1">{{ $archive->title }}</h4>
                    <div class="text-muted">Nomor: {{ $archive->archive_number }} | Kategori: {{ $archive->category }}</div>
                    @if ($archive->description)
                        <div class="mt-2">{{ $archive->description }}</div>
                    @endif
                </div>
                <div class="text-md-end">
                    <a href="{{ route('archives.download', $archive) }}" class="btn btn-outline-success">Download</a>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold"><i class="fas fa-file-pdf me-2"></i>Dokumen Arsip</h5>
                <a href="{{ route('archives.download', $archive) }}" class="btn btn-sm btn-outline-success">Download</a>
            </div>
            <div class="card-body p-0">
                <div class="row g-0">
                    <div class="col-md-9">
                        <div id="pdf-viewer"
                            style="width:100%;height:720px;overflow:auto;background:#f6f7fb;display:flex;align-items:center;justify-content:center;">
                            <canvas id="pdf-canvas"
                                style="max-width:100%;box-shadow:0 8px 30px rgba(15,23,42,0.06);"></canvas>
                        </div>
                        <div class="p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <a id="downloadLink" href="{{ route('archives.download', $archive) }}"
                                    class="btn btn-sm btn-outline-success">
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
                    </div>

                    <div class="col-md-3 border-start">
                        <div class="p-3">
                            <h6 class="mb-2">Detail Arsip</h6>
                            <div class="small text-muted">Nomor</div>
                            <div class="mb-2">{{ $archive->archive_number }}</div>

                            <div class="small text-muted">Kategori</div>
                            <div class="mb-2">{{ $archive->category }}</div>

                            <div class="small text-muted">Tanggal Arsip</div>
                            <div class="mb-2">{{ $archive->archived_at?->format('d M Y') }}</div>

                            @if ($archive->uploader)
                                <div class="small text-muted">Diunggah oleh</div>
                                <div class="mb-2">{{ $archive->uploader->name }}</div>
                            @endif

                            @if ($archive->description)
                                <div class="small text-muted">Keterangan</div>
                                <div class="mb-2">{{ $archive->description }}</div>
                            @endif

                            @php
                                $decision = null;
                                if ($archive->category === 'surat-disposisi' && $archive->description) {
                                    if (
                                        preg_match(
                                            '/Hasil keputusan disposisi:\s*(Diterima|Ditolak)/i',
                                            $archive->description,
                                            $m,
                                        )
                                    ) {
                                        $decision = ucfirst(strtolower($m[1]));
                                    }
                                }
                            @endphp

                            @if ($decision)
                                <div class="small text-muted">Keputusan</div>
                                <div class="mb-2">
                                    <span
                                        class="badge bg-{{ $decision === 'Diterima' ? 'success' : 'danger' }}">{{ $decision }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@section('scripts')
    @parent
    <script src="https://unpkg.com/pdfjs-dist@3.7.107/build/pdf.min.js"></script>
    <script>
        (function() {
            const url = '{{ route('archives.preview-raw', $archive) }}';
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
@endsection

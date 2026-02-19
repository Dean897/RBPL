@extends('layouts.app')

@section('title', 'Pelacakan Disposisi')

@section('content')
    {{-- Ringkasan Statistik --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <i class="fas fa-paper-plane text-primary fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Disposisi</div>
                        <h4 class="mb-0" id="stat-total">{{ $stats['total'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                        <i class="fas fa-clock text-warning fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Menunggu Review</div>
                        <h4 class="mb-0" id="stat-menunggu">{{ $stats['menunggu'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <i class="fas fa-check-circle text-success fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Disetujui</div>
                        <h4 class="mb-0" id="stat-diterima">{{ $stats['diterima'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3">
                        <i class="fas fa-times-circle text-danger fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Ditolak</div>
                        <h4 class="mb-0" id="stat-ditolak">{{ $stats['ditolak'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-2">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small me-1"><i class="fas fa-filter"></i> Filter:</span>
                    <button class="btn btn-sm btn-outline-secondary filter-btn active" data-filter="all">Semua</button>
                    <button class="btn btn-sm btn-outline-warning filter-btn" data-filter="Menunggu">Menunggu</button>
                    <button class="btn btn-sm btn-outline-success filter-btn" data-filter="Diterima">Disetujui</button>
                    <button class="btn btn-sm btn-outline-danger filter-btn" data-filter="Ditolak">Ditolak</button>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-light text-dark border" id="live-indicator">
                        <span class="spinner-grow spinner-grow-sm text-success me-1" role="status"></span>
                        Live Update Aktif
                    </span>
                    <span class="text-muted small" id="last-updated">Diperbarui: baru saja</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Grid Kartu Disposisi --}}
    <div class="row" id="disposisi-cards">
        @forelse($disposisis as $disposisi)
            @include('Sekretariat.disposisi._tracking-card', ['disposisi' => $disposisi])
        @empty
            <div class="col-12" id="empty-state">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <h5>Belum Ada Disposisi</h5>
                        <p class="mb-0">Disposisi yang sudah dikirim ke Pimpinan akan muncul di sini.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
@endsection

@section('scripts')
    <script>
        // === Filter Kartu ===
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const filter = this.dataset.filter;
                document.querySelectorAll('.disposisi-card').forEach(card => {
                    if (filter === 'all' || card.dataset.status === filter) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // === Live Polling setiap 10 detik ===
        let pollInterval = null;

        function startPolling() {
            pollInterval = setInterval(fetchLatestData, 10000);
        }

        function fetchLatestData() {
            fetch("{{ route('sekretariat.disposisi.tracking-data') }}", {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Update statistik
                    document.getElementById('stat-total').textContent = data.stats.total;
                    document.getElementById('stat-menunggu').textContent = data.stats.menunggu;
                    document.getElementById('stat-diterima').textContent = data.stats.diterima;
                    document.getElementById('stat-ditolak').textContent = data.stats.ditolak;

                    // Update kartu
                    const container = document.getElementById('disposisi-cards');
                    if (data.html.trim()) {
                        container.innerHTML = data.html;
                    }

                    // Re-apply filter aktif
                    const activeFilter = document.querySelector('.filter-btn.active')?.dataset.filter || 'all';
                    document.querySelectorAll('.disposisi-card').forEach(card => {
                        if (activeFilter === 'all' || card.dataset.status === activeFilter) {
                            card.style.display = '';
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    // Update timestamp
                    const now = new Date();
                    document.getElementById('last-updated').textContent =
                        'Diperbarui: ' + now.toLocaleTimeString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit'
                        });
                })
                .catch(err => console.warn('Polling error:', err));
        }

        startPolling();
    </script>
@endsection

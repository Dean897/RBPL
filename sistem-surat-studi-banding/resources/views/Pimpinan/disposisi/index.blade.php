@extends('layouts.pimpinan')

@section('title', 'Review Disposisi')

@section('styles')
    <style>
        .headline-card {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: #fff;
            border-radius: 20px;
            padding: 1rem 1.1rem;
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.22);
        }

        .quick-stat {
            border-radius: 16px;
            padding: 0.9rem 1rem;
            background: #fff;
            border: 1px solid rgba(15, 23, 42, 0.08);
        }

        .quick-stat .label {
            font-size: 0.82rem;
            color: #475569;
        }

        .quick-stat .value {
            font-size: 1.45rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.1;
        }

        .decision-card {
            border-radius: 20px;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .decision-card .card-body {
            padding: 1rem;
        }

        .meta-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.28rem 0.6rem;
        }

        .meta-chip.urgent {
            background: #fee2e2;
            color: #991b1b;
        }

        .meta-chip.normal {
            background: #dbeafe;
            color: #1e40af;
        }

        .sekretariat-note {
            border-radius: 14px;
            background: #f8fafc;
            border-left: 4px solid #1d4ed8;
            padding: 0.8rem 0.9rem;
            font-size: 0.92rem;
            color: #334155;
        }

        .decision-input {
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            resize: vertical;
            min-height: 104px;
        }

        .btn-aksi {
            border-radius: 12px;
            font-weight: 700;
            padding: 0.7rem 0.9rem;
        }

        .search-card {
            border-radius: 16px;
            border: 1px solid rgba(15, 23, 42, 0.08);
            background: #fff;
        }

        @media (min-width: 992px) {
            .decision-card .card-body {
                padding: 1.25rem;
            }
        }
    </style>
@endsection

@section('content')
    <section class="headline-card mb-3">
        <div class="d-flex justify-content-between align-items-start gap-2">
            <div>
                <h5 class="fw-bold mb-1">Halo, Bapak Pimpinan</h5>
                <p class="mb-0 text-white-50">Ada {{ $inboxCount }} disposisi menunggu direview.</p>
            </div>
            <span class="badge text-bg-warning rounded-pill px-3 py-2">
                <i class="fas fa-bell me-1"></i> Notifikasi
            </span>
        </div>
    </section>

    <section class="row g-2 mb-3">
        <div class="col-6">
            <div class="quick-stat">
                <div class="label">Inbox</div>
                <div class="value">{{ $inboxCount }}</div>
            </div>
        </div>
        <div class="col-6">
            <div class="quick-stat">
                <div class="label">Selesai</div>
                <div class="value">{{ $selesaiCount }}</div>
            </div>
        </div>
    </section>

    <section class="search-card p-3 mb-3" id="pencarian">
        <label for="search-disposisi" class="form-label fw-semibold mb-2">Cari Instansi / Perihal</label>
        <input id="search-disposisi" type="text" class="form-control"
            placeholder="Contoh: Universitas Gadjah Mada atau studi banding...">
        <small class="text-muted">Pencarian cepat untuk mempercepat review.</small>
    </section>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong><i class="fas fa-exclamation-triangle me-1"></i>Validasi gagal:</strong>
            <ul class="mb-0 mt-2 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section id="disposisi-list">
        @forelse ($disposisis as $disposisi)
            @php
                $isUrgent = \Carbon\Carbon::parse($disposisi->created_at)->diffInHours(now()) >= 24;
                $waktuMasuk = \Carbon\Carbon::parse($disposisi->created_at)->diffForHumans();
            @endphp

            <article class="card decision-card mb-3 disposisi-item"
                data-keyword="{{ strtolower(($disposisi->suratMasuk->instansi ?? '-') . ' ' . ($disposisi->suratMasuk->perihal ?? '-')) }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="meta-chip {{ $isUrgent ? 'urgent' : 'normal' }}">
                            <i class="fas {{ $isUrgent ? 'fa-bolt' : 'fa-circle-info' }}"></i>
                            {{ $isUrgent ? 'Urgent' : 'Normal' }}
                        </span>
                        <small class="text-muted">{{ $waktuMasuk }}</small>
                    </div>

                    <h6 class="fw-bold mb-1">{{ $disposisi->suratMasuk->instansi ?? '-' }}</h6>
                    <p class="text-muted mb-3">{{ $disposisi->suratMasuk->perihal ?? '-' }}</p>

                    <div class="sekretariat-note mb-3">
                        <strong>Catatan Sekretariat:</strong><br>
                        {{ $disposisi->catatan_sekretariat ?: 'Tidak ada catatan tambahan.' }}
                    </div>

                    <form action="{{ route('pimpinan.disposisi.update-keputusan', $disposisi->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <label for="catatan_{{ $disposisi->id }}" class="form-label fw-semibold">Instruksi Pimpinan</label>
                        <textarea id="catatan_{{ $disposisi->id }}" name="catatan_pimpinan" rows="4"
                            class="form-control decision-input mb-3" placeholder="Tulis instruksi disini....">{{ old('catatan_pimpinan') }}</textarea>

                        <div class="d-grid gap-2 d-sm-flex">
                            <button type="submit" name="keputusan" value="Ditolak"
                                class="btn btn-outline-danger btn-aksi flex-fill">
                                <i class="fas fa-xmark me-1"></i> Tolak
                            </button>
                            <button type="submit" name="keputusan" value="Diterima"
                                class="btn btn-success btn-aksi flex-fill">
                                <i class="fas fa-signature me-1"></i> Setuju &amp; TTD
                            </button>
                        </div>
                    </form>
                </div>
            </article>
        @empty
            <div class="card card-pimpinan">
                <div class="card-body text-center py-5">
                    <i class="fas fa-check-double fa-3x text-success mb-3"></i>
                    <h5 class="fw-bold">Inbox Anda Kosong</h5>
                    <p class="text-muted mb-0">Semua disposisi sudah diberi keputusan.</p>
                </div>
            </div>
        @endforelse
    </section>
@endsection

@section('scripts')
    <script>
        const searchInput = document.getElementById('search-disposisi');
        const items = document.querySelectorAll('.disposisi-item');

        searchInput?.addEventListener('input', function() {
            const keyword = this.value.trim().toLowerCase();

            items.forEach((item) => {
                const content = item.dataset.keyword || '';
                item.style.display = content.includes(keyword) ? '' : 'none';
            });
        });
    </script>
@endsection

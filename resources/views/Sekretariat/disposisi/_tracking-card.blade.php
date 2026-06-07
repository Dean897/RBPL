<div class="col-md-6 col-lg-4 mb-3 disposisi-card" data-status="{{ $disposisi->status_keputusan }}">
    @php
        $borderClass = match ($disposisi->status_keputusan) {
            'Menunggu' => 'border-start border-warning border-4',
            'Diterima' => 'border-start border-success border-4',
            'Ditolak' => 'border-start border-danger border-4',
            default => 'border-start border-secondary border-4',
        };
        $badgeClass = match ($disposisi->status_keputusan) {
            'Menunggu' => 'bg-warning text-dark',
            'Diterima' => 'bg-success',
            'Ditolak' => 'bg-danger',
            default => 'bg-secondary',
        };
        $iconClass = match ($disposisi->status_keputusan) {
            'Menunggu' => 'fas fa-hourglass-half text-warning',
            'Diterima' => 'fas fa-check-circle text-success',
            'Ditolak' => 'fas fa-times-circle text-danger',
            default => 'fas fa-question-circle text-secondary',
        };
        $labelStatus = match ($disposisi->status_keputusan) {
            'Menunggu' => 'Menunggu Review',
            'Diterima' => 'Disetujui',
            'Ditolak' => 'Ditolak',
            default => $disposisi->status_keputusan,
        };
    @endphp

    <div class="card shadow-sm {{ $borderClass }} h-100">
        <div class="card-body">
            {{-- Header: Status Badge + Waktu --}}
            <div class="d-flex justify-content-between align-items-start mb-3">
                <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2">
                    <i class="{{ $iconClass }} me-1"></i> {{ $labelStatus }}
                </span>
                <small class="text-muted">
                    <i class="fas fa-calendar-alt me-1"></i>
                    {{ \Carbon\Carbon::parse($disposisi->tgl_disposisi)->format('d M Y') }}
                </small>
            </div>

            {{-- Info Surat --}}
            <h6 class="card-title mb-1 text-truncate" title="{{ $disposisi->suratMasuk->perihal ?? '-' }}">
                {{ $disposisi->suratMasuk->perihal ?? '-' }}
            </h6>
            <p class="text-muted small mb-2">
                <i class="fas fa-building me-1"></i>{{ $disposisi->suratMasuk->instansi ?? '-' }}
            </p>
            <p class="text-muted small mb-3">
                <i class="fas fa-hashtag me-1"></i>{{ $disposisi->suratMasuk->no_surat ?? '-' }}
            </p>

            {{-- Catatan Sekretariat --}}
            @if ($disposisi->catatan_sekretariat)
                <div class="bg-light rounded p-2 mb-2 small">
                    <strong class="text-muted"><i class="fas fa-comment me-1"></i>Catatan Sekretariat:</strong><br>
                    {{ Str::limit($disposisi->catatan_sekretariat, 100) }}
                </div>
            @endif

            {{-- Catatan Pimpinan (muncul jika sudah ada keputusan) --}}
            @if ($disposisi->catatan_pimpinan)
                <div class="bg-light rounded p-2 mb-2 small">
                    <strong class="text-muted"><i class="fas fa-user-tie me-1"></i>Arahan Pimpinan:</strong><br>
                    {{ Str::limit($disposisi->catatan_pimpinan, 100) }}
                </div>
            @endif

            <hr class="my-2">

            {{-- Footer: Tombol Aksi --}}
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    <i class="fas fa-clock me-1"></i>
                    {{ $disposisi->updated_at->diffForHumans() }}
                </small>
                <a href="{{ route('sekretariat.surat-masuk.show', $disposisi->surat_masuk_id) }}"
                    class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye me-1"></i>Lihat Detail
                </a>
            </div>
        </div>
    </div>
</div>

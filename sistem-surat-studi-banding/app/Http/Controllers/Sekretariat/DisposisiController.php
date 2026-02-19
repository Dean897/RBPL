<?php

namespace App\Http\Controllers\Sekretariat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sekretariat\StoreDisposisiRequest;
use App\Models\Disposisi;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;

class DisposisiController extends Controller
{
    /**
     * Tampilkan halaman pelacakan disposisi (dashboard real-time).
     */
    public function tracking()
    {
        $disposisis = Disposisi::with('suratMasuk')
            ->latest('updated_at')
            ->get();

        $stats = [
            'total'    => $disposisis->count(),
            'menunggu' => $disposisis->where('status_keputusan', 'Menunggu')->count(),
            'diterima' => $disposisis->where('status_keputusan', 'Diterima')->count(),
            'ditolak'  => $disposisis->where('status_keputusan', 'Ditolak')->count(),
        ];

        return view('Sekretariat.disposisi.tracking', compact('disposisis', 'stats'));
    }

    /**
     * API endpoint untuk live-polling data disposisi terbaru.
     */
    public function trackingData()
    {
        $disposisis = Disposisi::with('suratMasuk')
            ->latest('updated_at')
            ->get();

        $stats = [
            'total'    => $disposisis->count(),
            'menunggu' => $disposisis->where('status_keputusan', 'Menunggu')->count(),
            'diterima' => $disposisis->where('status_keputusan', 'Diterima')->count(),
            'ditolak'  => $disposisis->where('status_keputusan', 'Ditolak')->count(),
        ];

        // Render kartu sebagai HTML partial
        $html = '';
        foreach ($disposisis as $disposisi) {
            $html .= view('Sekretariat.disposisi._tracking-card', compact('disposisi'))->render();
        }

        if (empty($html)) {
            $html = '<div class="col-12" id="empty-state">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <h5>Belum Ada Disposisi</h5>
                        <p class="mb-0">Disposisi yang sudah dikirim ke Pimpinan akan muncul di sini.</p>
                    </div>
                </div>
            </div>';
        }

        return response()->json([
            'stats' => $stats,
            'html'  => $html,
        ]);
    }

    /**
     * Tampilkan form buat lembar disposisi.
     */
    public function create(SuratMasuk $suratMasuk)
    {
        // Pastikan surat sudah diverifikasi dan belum ada disposisi
        if ($suratMasuk->status !== 'Menunggu Disposisi') {
            return redirect()
                ->route('sekretariat.surat-masuk.show', $suratMasuk->id)
                ->with('error', 'Surat ini belum diverifikasi atau sudah memiliki disposisi.');
        }

        if ($suratMasuk->disposisi) {
            return redirect()
                ->route('sekretariat.surat-masuk.show', $suratMasuk->id)
                ->with('error', 'Surat ini sudah memiliki lembar disposisi.');
        }

        return view('Sekretariat.disposisi.create', compact('suratMasuk'));
    }

    /**
     * Simpan lembar disposisi baru dan update status surat.
     */
    public function store(StoreDisposisiRequest $request, SuratMasuk $suratMasuk)
    {
        $validated = $request->validated();

        // Buat disposisi
        Disposisi::create([
            'surat_masuk_id'      => $suratMasuk->id,
            'tgl_disposisi'       => $validated['tgl_disposisi'],
            'catatan_sekretariat' => $validated['catatan_sekretariat'],
            'status_keputusan'    => 'Menunggu',
        ]);

        // Update status surat masuk
        $suratMasuk->update(['status' => 'Disposisi Terkirim']);

        return redirect()
            ->route('sekretariat.surat-masuk.show', $suratMasuk->id)
            ->with('success', 'Lembar disposisi berhasil dibuat dan dikirim ke Pimpinan.');
    }
}

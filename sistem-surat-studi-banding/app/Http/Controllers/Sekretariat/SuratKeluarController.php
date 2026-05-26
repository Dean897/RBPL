<?php

namespace App\Http\Controllers\Sekretariat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sekretariat\StoreSuratKeluarRequest;
use App\Models\Disposisi;
use App\Services\PdfGeneratorService;

class SuratKeluarController extends Controller
{
    /**
     * Tampilkan daftar surat yang perlu dibuat balasan.
     */
    public function index()
    {
        $baseQuery = Disposisi::query()->where('status_keputusan', 'Diterima');

        $suratKeluar = (clone $baseQuery)
            ->with('suratMasuk')
            ->whereIn('status_surat_keluar', ['Menunggu', 'Draft'])
            ->latest('updated_at')
            ->get();

        $stats = [
            'menunggu' => (clone $baseQuery)->where('status_surat_keluar', 'Menunggu')->count(),
            'draft' => (clone $baseQuery)->where('status_surat_keluar', 'Draft')->count(),
            'terkirim' => (clone $baseQuery)->where('status_surat_keluar', 'Terkirim')->count(),
        ];

        return view('Sekretariat.surat-keluar.index', compact('suratKeluar', 'stats'));
    }

    /**
     * Tampilkan form buat/edit surat balasan.
     */
    public function create(Disposisi $disposisi)
    {
        if (!$this->isAcceptedDisposisi($disposisi)) {
            return redirect()
                ->route('sekretariat.surat-keluar.index')
                ->with('error', 'Hanya disposisi yang diterima yang bisa dibuat surat balasan.');
        }

        return view('Sekretariat.surat-keluar.create', compact('disposisi'));
    }

    /**
     * Simpan draft surat balasan.
     */
    public function store(StoreSuratKeluarRequest $request, Disposisi $disposisi)
    {
        $validated = $request->validated();

        $disposisi->update([
            'isi_surat_balasan' => $validated['isi_surat_balasan'],
            'status_surat_keluar' => 'Draft',
        ]);

        return redirect()
            ->route('sekretariat.surat-keluar.edit', $disposisi->id)
            ->with('success', 'Draft surat balasan berhasil disimpan.');
    }

    /**
     * Tampilkan form edit surat balasan.
     */
    public function edit(Disposisi $disposisi)
    {
        if (!$this->isAcceptedDisposisi($disposisi)) {
            return redirect()
                ->route('sekretariat.surat-keluar.index')
                ->with('error', 'Disposisi tidak valid untuk edit surat balasan.');
        }

        return view('Sekretariat.surat-keluar.edit', compact('disposisi'));
    }

    /**
     * Update draft surat balasan.
     */
    public function update(StoreSuratKeluarRequest $request, Disposisi $disposisi)
    {
        $validated = $request->validated();

        $disposisi->update([
            'isi_surat_balasan' => $validated['isi_surat_balasan'],
        ]);

        return redirect()
            ->route('sekretariat.surat-keluar.edit', $disposisi->id)
            ->with('success', 'Surat balasan berhasil diperbarui.');
    }

    /**
     * Generate PDF dan tandai sebagai Terkirim.
     */
    public function generateAndSend(Disposisi $disposisi, PdfGeneratorService $pdfService)
    {
        if ($disposisi->status_surat_keluar === 'Terkirim') {
            return redirect()
                ->route('sekretariat.surat-keluar.index')
                ->with('error', 'Surat ini sudah dikirim sebelumnya.');
        }

        if (!$disposisi->isi_surat_balasan) {
            return redirect()
                ->route('sekretariat.surat-keluar.edit', $disposisi->id)
                ->with('error', 'Isi surat balasan belum lengkap.');
        }

        try {
            $pdfPath = $pdfService->generateSuratBalasan($disposisi);

            $disposisi->update([
                'file_pdf_balasan' => $pdfPath,
                'status_surat_keluar' => 'Terkirim',
                'tgl_kirim_surat' => now(),
            ]);

            // Update status surat masuk
            $disposisi->suratMasuk->update(['status' => 'Selesai']);

            return redirect()
                ->route('sekretariat.surat-keluar.index')
                ->with('success', 'Surat balasan berhasil dibuat dan dikirim ke pemohon.');
        } catch (\Exception $e) {
            return redirect()
                ->route('sekretariat.surat-keluar.edit', $disposisi->id)
                ->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }

    /**
     * Download PDF surat balasan.
     */
    public function downloadPdf(Disposisi $disposisi)
    {
        if (!$disposisi->file_pdf_balasan) {
            return redirect()
                ->route('sekretariat.surat-keluar.index')
                ->with('error', 'File PDF tidak ditemukan.');
        }

        $safeNoSurat = preg_replace('/[^A-Za-z0-9._-]/', '-', (string) $disposisi->suratMasuk->no_surat);
        $downloadName = 'surat-balasan-' . trim($safeNoSurat ?? '', '-') . '.pdf';
        if ($downloadName === 'surat-balasan-.pdf') {
            $downloadName = 'surat-balasan-' . $disposisi->id . '.pdf';
        }

        return response()->download(
            storage_path('app/public/' . $disposisi->file_pdf_balasan),
            $downloadName
        );
    }

    private function isAcceptedDisposisi(Disposisi $disposisi): bool
    {
        return $disposisi->status_keputusan === 'Diterima';
    }
}

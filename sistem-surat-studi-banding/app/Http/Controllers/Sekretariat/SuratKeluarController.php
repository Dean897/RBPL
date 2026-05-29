<?php

namespace App\Http\Controllers\Sekretariat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sekretariat\StoreSuratKeluarRequest;
use App\Models\ActivityLog;
use App\Models\Archive;
use App\Models\Disposisi;
use App\Services\PdfGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SuratKeluarController extends Controller
{
    /**
     * Tampilkan daftar surat yang perlu dibuat balasan.
     */
    public function index()
    {
        $baseQuery = Disposisi::query()->whereIn('status_keputusan', ['Diterima', 'Ditolak']);

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
        if (!$this->isDecidedDisposisi($disposisi)) {
            return redirect()
                ->route('sekretariat.surat-keluar.index')
                ->with('error', 'Hanya disposisi yang sudah diputuskan yang bisa dibuat surat balasan.');
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
        if (!$this->isDecidedDisposisi($disposisi)) {
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
    public function generateAndSend(Request $request, Disposisi $disposisi, PdfGeneratorService $pdfService)
    {
        if ($disposisi->status_surat_keluar === 'Terkirim') {
            return redirect()
                ->route('sekretariat.surat-keluar.index')
                ->with('error', 'Surat ini sudah dikirim sebelumnya.');
        }

        $validated = $request->validate([
            'isi_surat_balasan' => ['required', 'string'],
        ]);

        $disposisi->update([
            'isi_surat_balasan' => $validated['isi_surat_balasan'],
            'status_surat_keluar' => 'Draft',
        ]);

        if (!$disposisi->isi_surat_balasan) {
            return redirect()
                ->route('sekretariat.surat-keluar.edit', $disposisi->id)
                ->with('error', 'Isi surat balasan belum lengkap.');
        }

        try {
            $pdfPath = $disposisi->file_pdf_balasan;

            if (!$pdfPath) {
                $pdfPath = $pdfService->generateSuratBalasan($disposisi);

                $disposisi->update([
                    'file_pdf_balasan' => $pdfPath,
                ]);
            }

            $disposisi->update([
                'status_surat_keluar' => 'Terkirim',
                'tgl_kirim_surat' => now(),
            ]);

            // Ensure final outgoing document is archived as surat-keluar.
            $this->createSuratKeluarArchiveIfMissing($disposisi);

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

    private function isDecidedDisposisi(Disposisi $disposisi): bool
    {
        return in_array($disposisi->status_keputusan, ['Diterima', 'Ditolak'], true);
    }

    private function createSuratKeluarArchiveIfMissing(Disposisi $disposisi): void
    {
        try {
            $filePath = $disposisi->file_pdf_balasan;
            if (!$filePath) {
                return;
            }

            $alreadyArchived = Archive::where('category', 'surat-keluar')
                ->where('file_path', $filePath)
                ->exists();

            if ($alreadyArchived) {
                return;
            }

            $disk = Storage::disk('public');
            if (!$disk->exists($filePath)) {
                return;
            }

            $suratMasuk = $disposisi->suratMasuk;
            $pemohonName = $suratMasuk?->pengirim?->name ?? 'Pemohon';
            $instansiName = $suratMasuk?->instansi ?? 'Instansi';
            $fileName = basename($filePath);

            $archive = Archive::create([
                'archive_number' => '',
                'title' => $pemohonName . ' - ' . $instansiName,
                'category' => 'surat-keluar',
                'description' => 'Surat balasan final yang dikirim ke pihak eksternal.',
                'file_path' => $filePath,
                'file_name' => $fileName,
                'file_size' => $disk->size($filePath),
                'mime_type' => 'application/pdf',
                'archived_at' => now(),
                'uploaded_by' => Auth::id(),
                'is_private' => false,
                'allowed_roles' => [],
            ]);

            $archive->archive_number = 'AR' . now()->format('Ymd') . str_pad($archive->id, 6, '0', STR_PAD_LEFT);
            $archive->save();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'created',
                'model_type' => Archive::class,
                'model_id' => $archive->id,
                'metadata' => json_encode([
                    'source' => 'SuratKeluarController',
                    'disposisi_id' => $disposisi->id,
                    'file' => $fileName,
                ]),
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed creating surat-keluar archive: ' . $e->getMessage());
        }
    }
}

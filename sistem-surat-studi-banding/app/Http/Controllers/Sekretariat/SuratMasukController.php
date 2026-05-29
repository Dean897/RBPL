<?php

namespace App\Http\Controllers\Sekretariat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sekretariat\StoreSuratMasukRequest;
use App\Models\SuratMasuk;
use App\Models\Archive;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SuratMasukController extends Controller
{
    public function index()
    {
        $suratMasuk = SuratMasuk::latest()->get();

        return view('Sekretariat.surat-masuk.index', compact('suratMasuk'));
    }

    /**
     * Tampilkan form input surat masuk baru.
     */
    public function create()
    {
        return view('Sekretariat.surat-masuk.create');
    }

    /**
     * Simpan data surat masuk baru ke database.
     */
    public function store(StoreSuratMasukRequest $request)
    {
        $validated = $request->validated();

        // Upload file PDF
        $file = $request->file('file_pdf');
        $filePath = $file->store('surat-masuk', 'public');

        $surat = SuratMasuk::create([
            'user_id'        => Auth::id(),
            'no_surat'       => $validated['no_surat'],
            'instansi'       => $validated['instansi'],
            'perihal'        => $validated['perihal'],
            'tanggal_surat'  => $validated['tanggal_surat'],
            'tanggal_terima' => $validated['tanggal_terima'] ?? null,
            'status'         => 'Menunggu Verifikasi',
            'file_pdf'       => $filePath,
        ]);

        // Create archive record automatically for incoming mail
        try {
            $pemohonName = $surat->pengirim?->name ?? Auth::user()?->name ?? 'Pemohon';
            $instansiName = $validated['instansi'] ?? $surat->instansi ?? 'Instansi';

            $archive = Archive::create([
                'archive_number' => '',
                'title' => $pemohonName . ' - ' . $instansiName,
                'category' => 'surat-masuk',
                'description' => $validated['perihal'] ?? null,
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getClientMimeType(),
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
                'metadata' => json_encode(['source' => 'SuratMasukController', 'surat_id' => $surat->id]),
            ]);
        } catch (\Exception $e) {
            Log::error('Auto-archive for surat masuk failed: ' . $e->getMessage());
        }

        return redirect()
            ->route('sekretariat.surat-masuk.index')
            ->with('success', 'Surat masuk berhasil disimpan.');
    }

    /**
     * Tampilkan detail surat masuk beserta preview PDF.
     */
    public function show(SuratMasuk $suratMasuk)
    {
        $suratMasuk->load(['pengirim', 'disposisi']);

        return view('Sekretariat.surat-masuk.show', compact('suratMasuk'));
    }

    /**
     * Stream PDF surat masuk tanpa bergantung pada public/storage symlink.
     */
    public function preview(SuratMasuk $suratMasuk)
    {
        $path = Storage::disk('public')->path($suratMasuk->file_pdf);
        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
        ]);
    }

    /**
     * Download PDF surat masuk.
     */
    public function download(SuratMasuk $suratMasuk)
    {
        $downloadName = 'surat-masuk-' . $suratMasuk->no_surat . '.pdf';

        return response()->download(
            storage_path('app/public/' . $suratMasuk->file_pdf),
            $downloadName
        );
    }

    /**
     * Debug: return raw PDF contents with explicit headers (use to test browser behavior).
     */
    public function previewRaw(SuratMasuk $suratMasuk)
    {
        $disk = Storage::disk('public');
        if (! $disk->exists($suratMasuk->file_pdf)) {
            abort(404, 'File tidak ditemukan.');
        }

        $content = $disk->get($suratMasuk->file_pdf);
        $length = strlen($content);

        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Length' => $length,
            'Content-Disposition' => 'inline; filename="' . basename($suratMasuk->file_pdf) . '"',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
        ]);
    }

    /**
     * Verifikasi surat masuk dan ubah status ke 'Menunggu Disposisi'.
     */
    public function verify(SuratMasuk $suratMasuk)
    {
        if ($suratMasuk->status !== 'Menunggu Verifikasi') {
            return redirect()
                ->route('sekretariat.surat-masuk.show', $suratMasuk->id)
                ->with('error', 'Surat ini sudah diverifikasi sebelumnya.');
        }

        $suratMasuk->update(['status' => 'Menunggu Disposisi']);

        // Log verification action
        try {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'verified',
                'model_type' => SuratMasuk::class,
                'model_id' => $suratMasuk->id,
                'metadata' => json_encode(['status' => 'Menunggu Disposisi']),
            ]);
        } catch (\Exception $e) {
            Log::warning('ActivityLog create failed on verify: ' . $e->getMessage());
        }

        return redirect()
            ->route('sekretariat.surat-masuk.show', $suratMasuk->id)
            ->with('success', 'Surat berhasil diverifikasi. Silakan buat lembar disposisi.');
    }
}

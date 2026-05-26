<?php

namespace App\Http\Controllers\Sekretariat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sekretariat\StoreSuratMasukRequest;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $filePath = $request->file('file_pdf')->store('surat-masuk', 'public');

        SuratMasuk::create([
            'user_id'        => Auth::id(),
            'no_surat'       => $validated['no_surat'],
            'instansi'       => $validated['instansi'],
            'perihal'        => $validated['perihal'],
            'tanggal_surat'  => $validated['tanggal_surat'],
            'tanggal_terima' => $validated['tanggal_terima'] ?? null,
            'status'         => 'Menunggu Verifikasi',
            'file_pdf'       => $filePath,
        ]);

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

        return Storage::disk('public')->download($suratMasuk->file_pdf, $downloadName);
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

        return redirect()
            ->route('sekretariat.surat-masuk.show', $suratMasuk->id)
            ->with('success', 'Surat berhasil diverifikasi. Silakan buat lembar disposisi.');
    }
}

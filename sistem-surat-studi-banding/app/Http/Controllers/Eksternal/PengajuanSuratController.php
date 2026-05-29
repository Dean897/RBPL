<?php

namespace App\Http\Controllers\Eksternal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eksternal\StorePengajuanSuratRequest;
use App\Models\ActivityLog;
use App\Models\Archive;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PengajuanSuratController extends Controller
{
    /**
     * Tampilkan form pengajuan surat studi banding.
     */
    public function create()
    {
        return view('Eksternal.pengajuan.create');
    }

    /**
     * Simpan data pengajuan surat ke database.
     */
    public function store(StorePengajuanSuratRequest $request)
    {
        $validated = $request->validated();

        // Upload file PDF
        $file = $request->file('file_pdf');
        $filePath = $file->store('surat-masuk', 'public');

        $suratMasuk = SuratMasuk::create([
            'user_id'       => Auth::id(),
            'no_surat'      => $validated['no_surat'],
            'instansi'      => $validated['instansi'],
            'perihal'       => $validated['perihal'],
            'tanggal_surat' => $validated['tanggal_surat'],
            'status'        => 'Menunggu Verifikasi',
            'file_pdf'      => $filePath,
        ]);

        // Auto-archive each incoming submission from eksternal side.
        try {
            $pemohonName = Auth::user()?->name ?? 'Pemohon';
            $instansiName = $validated['instansi'] ?? 'Instansi';

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
                'metadata' => json_encode([
                    'source' => 'PengajuanSuratController',
                    'surat_id' => $suratMasuk->id,
                ]),
            ]);
        } catch (\Exception $e) {
            Log::error('Auto-archive for eksternal surat masuk failed: ' . $e->getMessage());
        }

        return redirect()
            ->route('eksternal.dashboard')
            ->with('success', 'Surat permohonan studi banding berhasil dikirim. Silakan tunggu proses verifikasi.');
    }

    /**
     * Tampilkan detail surat yang sudah diajukan.
     */
    public function show(SuratMasuk $suratMasuk)
    {
        // Pastikan user hanya bisa lihat surat miliknya sendiri
        if ($suratMasuk->user_id !== Auth::id()) {
            abort(403);
        }

        return view('Eksternal.pengajuan.show', compact('suratMasuk'));
    }
}

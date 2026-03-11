<?php

namespace App\Http\Controllers\Eksternal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eksternal\StorePengajuanSuratRequest;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\Auth;

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
        $filePath = $request->file('file_pdf')->store('surat-masuk', 'public');

        SuratMasuk::create([
            'user_id'       => Auth::id(),
            'no_surat'      => $validated['no_surat'],
            'instansi'      => $validated['instansi'],
            'perihal'       => $validated['perihal'],
            'tanggal_surat' => $validated['tanggal_surat'],
            'status'        => 'Menunggu Verifikasi',
            'file_pdf'      => $filePath,
        ]);

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

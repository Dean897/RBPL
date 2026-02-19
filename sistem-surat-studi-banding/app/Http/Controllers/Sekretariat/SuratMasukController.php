<?php

namespace App\Http\Controllers\Sekretariat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sekretariat\StoreSuratMasukRequest;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

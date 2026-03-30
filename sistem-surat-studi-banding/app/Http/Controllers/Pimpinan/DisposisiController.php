<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pimpinan\UpdateDisposisiKeputusanRequest;
use App\Models\Disposisi;

class DisposisiController extends Controller
{
    /**
     * Tampilkan daftar disposisi yang menunggu keputusan pimpinan.
     */
    public function index()
    {
        $disposisis = Disposisi::with('suratMasuk')
            ->where('status_keputusan', 'Menunggu')
            ->latest('tgl_disposisi')
            ->get();

        $inboxCount = $disposisis->count();
        $selesaiCount = Disposisi::whereIn('status_keputusan', ['Diterima', 'Ditolak'])->count();

        return view('Pimpinan.disposisi.index', compact('disposisis', 'inboxCount', 'selesaiCount'));
    }

    /**
     * Simpan keputusan disposisi (diterima/ditolak) dari pimpinan.
     */
    public function updateKeputusan(UpdateDisposisiKeputusanRequest $request, Disposisi $disposisi)
    {
        if ($disposisi->status_keputusan !== 'Menunggu') {
            return redirect()
                ->route('pimpinan.disposisi.index')
                ->with('error', 'Disposisi ini sudah diputuskan sebelumnya.');
        }

        $validated = $request->validated();

        $disposisi->update([
            'status_keputusan' => $validated['keputusan'],
            'catatan_pimpinan' => $validated['catatan_pimpinan'] ?? null,
        ]);

        if ($disposisi->suratMasuk) {
            $disposisi->suratMasuk->update([
                'status' => $validated['keputusan'] === 'Diterima' ? 'Selesai' : 'Ditolak',
            ]);
        }

        return redirect()
            ->route('pimpinan.disposisi.index')
            ->with('success', 'Keputusan disposisi berhasil disimpan.');
    }
}

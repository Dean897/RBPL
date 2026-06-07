<?php

namespace App\Http\Controllers\Eksternal;

use App\Http\Controllers\Controller;
use App\Models\Disposisi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratBalesanController extends Controller
{
    /**
     * Download surat balasan PDF.
     */
    public function download(Disposisi $disposisi)
    {
        // Verifikasi bahwa pengguna adalah pemilik surat
        if ($disposisi->suratMasuk->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }

        // Verifikasi bahwa surat sudah dikirim
        if ($disposisi->status_surat_keluar !== 'Terkirim' || !$disposisi->file_pdf_balasan) {
            abort(404, 'Surat balasan tidak ditemukan.');
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
}

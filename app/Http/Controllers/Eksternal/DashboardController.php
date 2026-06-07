<?php

namespace App\Http\Controllers\Eksternal;

use App\Http\Controllers\Controller;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $suratList = SuratMasuk::where('user_id', Auth::id())
            ->latest()
            ->get();

        $totalSurat = $suratList->count();
        $menungguVerifikasi = $suratList->where('status', 'Menunggu Verifikasi')->count();
        $diproses = $suratList->whereIn('status', ['Menunggu Disposisi', 'Disposisi Terkirim'])->count();
        $selesai = $suratList->where('status', 'Selesai')->count();

        // Surat balasan yang siap didownload
        $suratBalasan = [];
        foreach ($suratList as $surat) {
            if ($surat->disposisi && $surat->disposisi->status_surat_keluar === 'Terkirim' && $surat->disposisi->file_pdf_balasan) {
                $suratBalasan[] = $surat;
            }
        }

        return view('Eksternal.dashboard', compact(
            'suratList',
            'totalSurat',
            'menungguVerifikasi',
            'diproses',
            'selesai',
            'suratBalasan'
        ));
    }
}

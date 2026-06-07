<?php

namespace App\Http\Controllers\Sekretariat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSurat = \App\Models\SuratMasuk::count();
        $menungguDisposisi = \App\Models\Disposisi::where('status_keputusan', 'Menunggu')->count();
        return view('Sekretariat.dashboard', compact('totalSurat', 'menungguDisposisi'));
    }
}


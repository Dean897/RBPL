<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Sekretariat\DashboardController;
use App\Http\Controllers\Sekretariat\SuratMasukController;
use App\Http\Controllers\Sekretariat\DisposisiController;
use App\Http\Controllers\Sekretariat\SuratKeluarController;
use App\Http\Controllers\Sekretariat\TamuQrController;
use App\Http\Controllers\Pimpinan\DisposisiController as PimpinanDisposisiController;

use App\Http\Controllers\Eksternal\DashboardController as EksternalDashboardController;
use App\Http\Controllers\Eksternal\PengajuanSuratController;
use App\Http\Controllers\Eksternal\SuratBalesanController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ArchiveController;

Route::get('/', function () {
    // Jika sudah login, redirect ke dashboard
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    // Jika belum login, tampilkan welcome
    return view('welcome');
});

Route::get('/dashboard', function () {
    $role = Auth::user()->role ?? 'eksternal';

    return match ($role) {
        'sekretariat' => redirect()->route('sekretariat.dashboard'),
        'pimpinan' => redirect()->route('pimpinan.disposisi.index'),
        'eksternal', 'tamu' => redirect()->route('eksternal.dashboard'),
        default => redirect()->route('eksternal.dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');


// ── Route Sekretariat ─────────────────────────────────────────
Route::middleware(['auth', 'role:sekretariat'])->group(function () {
    Route::prefix('sekretariat')->name('sekretariat.')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/surat-masuk', [SuratMasukController::class, 'index'])->name('surat-masuk.index');
        Route::get('/surat-masuk/create', [SuratMasukController::class, 'create'])->name('surat-masuk.create');
        Route::post('/surat-masuk', [SuratMasukController::class, 'store'])->name('surat-masuk.store');
        Route::get('/surat-masuk/{suratMasuk}', [SuratMasukController::class, 'show'])->name('surat-masuk.show');
        Route::patch('/surat-masuk/{suratMasuk}/verify', [SuratMasukController::class, 'verify'])->name('surat-masuk.verify');

        Route::get('/disposisi/tracking', [DisposisiController::class, 'tracking'])->name('disposisi.tracking');
        Route::get('/disposisi/tracking-data', [DisposisiController::class, 'trackingData'])->name('disposisi.tracking-data');
        Route::get('/disposisi/{suratMasuk}/create', [DisposisiController::class, 'create'])->name('disposisi.create');
        Route::post('/disposisi/{suratMasuk}', [DisposisiController::class, 'store'])->name('disposisi.store');

        Route::get('/surat-keluar', [SuratKeluarController::class, 'index'])->name('surat-keluar.index');
        Route::get('/surat-keluar/{disposisi}/create', [SuratKeluarController::class, 'create'])->name('surat-keluar.create');
        Route::post('/surat-keluar/{disposisi}', [SuratKeluarController::class, 'store'])->name('surat-keluar.store');
        Route::get('/surat-keluar/{disposisi}/edit', [SuratKeluarController::class, 'edit'])->name('surat-keluar.edit');
        Route::patch('/surat-keluar/{disposisi}', [SuratKeluarController::class, 'update'])->name('surat-keluar.update');
        Route::post('/surat-keluar/{disposisi}/send', [SuratKeluarController::class, 'generateAndSend'])->name('surat-keluar.send');
        Route::get('/surat-keluar/{disposisi}/download', [SuratKeluarController::class, 'downloadPdf'])->name('surat-keluar.download');

        // Buku Tamu QR Code Routes
        Route::prefix('buku-tamu')->name('buku-tamu.')->group(function () {
            Route::get('/', [TamuQrController::class, 'index'])->name('index');
            Route::get('/create', [TamuQrController::class, 'create'])->name('create');
        });
    });
});

// ── Route Pimpinan ───────────────────────────────────────────
Route::middleware(['auth', 'role:pimpinan'])->group(function () {
    Route::prefix('pimpinan')->name('pimpinan.')->group(function () {
        Route::get('/disposisi', [PimpinanDisposisiController::class, 'index'])->name('disposisi.index');
        Route::patch('/disposisi/{disposisi}/keputusan', [PimpinanDisposisiController::class, 'updateKeputusan'])
            ->name('disposisi.update-keputusan');
    });
});

// ── Route Eksternal ───────────────────────────────────────────
Route::middleware(['auth', 'role:eksternal,tamu'])->group(function () {
    Route::prefix('eksternal')->name('eksternal.')->group(function () {
        Route::get('/dashboard', [EksternalDashboardController::class, 'index'])->name('dashboard');
        Route::get('/pengajuan/create', [PengajuanSuratController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan', [PengajuanSuratController::class, 'store'])->name('pengajuan.store');
        Route::get('/pengajuan/{suratMasuk}', [PengajuanSuratController::class, 'show'])->name('pengajuan.show');
        Route::get('/surat-balasan/{disposisi}/download', [SuratBalesanController::class, 'download'])->name('surat-balasan.download');
    });
});

// ── Route Profile (semua role) ────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Arsip Digital
    Route::resource('archives', ArchiveController::class);
    Route::get('archives/{archive}/preview', [ArchiveController::class, 'preview'])->name('archives.preview');
    Route::get('archives/{archive}/download', [ArchiveController::class, 'download'])->name('archives.download');
    Route::get('archives/{archive}/print', [ArchiveController::class, 'print'])->name('archives.print');
});

require __DIR__ . '/auth.php';

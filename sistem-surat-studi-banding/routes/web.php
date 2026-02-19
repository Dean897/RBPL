<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Sekretariat\DashboardController;
use App\Http\Controllers\Sekretariat\SuratMasukController;
use App\Http\Controllers\Sekretariat\DisposisiController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('sekretariat.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth'])->group(function () {
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
    });


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

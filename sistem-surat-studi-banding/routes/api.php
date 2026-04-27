<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sekretariat\TamuQrController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ── API Routes Sekretariat ───────────────────────────────────
Route::middleware(['auth', 'verified', 'role:sekretariat'])->prefix('sekretariat')->group(function () {

    // Buku Tamu QR Code API
    Route::prefix('buku-tamu')->name('buku-tamu.')->group(function () {
        Route::post('/', [TamuQrController::class, 'store'])->name('store');
        Route::get('/{id}', [TamuQrController::class, 'show'])->name('show');
        Route::post('/check-in', [TamuQrController::class, 'checkIn'])->name('check-in');
        Route::get('/monitoring/realtime', [TamuQrController::class, 'monitoring'])->name('monitoring');
        Route::get('/export/laporan', [TamuQrController::class, 'export'])->name('export');
        Route::put('/{id}/status', [TamuQrController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{id}', [TamuQrController::class, 'destroy'])->name('destroy');
    });
});

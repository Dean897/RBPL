<?php

namespace App\Http\Controllers\Sekretariat;

use App\Models\TamuQr;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

/**
 * TamuQrController - Manajemen Buku Tamu QR Code
 *
 * Mengelola:
 * - Dashboard monitoring tamu real-time
 * - Registrasi tamu baru
 * - Check-in tamu (scan QR code)
 * - Export laporan kehadiran
 */
class TamuQrController extends Controller
{
    /**
     * Konstruktor - middleware auth dan role
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('role:sekretariat');
    }

    /**
     * Dashboard monitoring kehadiran tamu
     * Route: GET /sekretariat/buku-tamu
     */
    public function index(): View
    {
        // Statistik tamu hari ini
        $stats = [
            'total_hari_ini' => TamuQr::today()->count(),
            'hadir' => TamuQr::today()->checkedIn()->count(),
            'terdaftar' => TamuQr::today()->registered()->count(),
            'tidak_hadir' => TamuQr::today()->absent()->count(),
        ];

        // Daftar tamu hari ini (newest first)
        $tamu_list = TamuQr::today()
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('Sekretariat.BukuTamu.index', compact('stats', 'tamu_list'));
    }

    /**
     * Form registrasi tamu baru
     * Route: GET /sekretariat/buku-tamu/create
     */
    public function create(): View
    {
        return view('Sekretariat.BukuTamu.create');
    }

    /**
     * Simpan registrasi tamu baru & generate QR code
     * Route: POST /api/sekretariat/buku-tamu
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_tamu' => 'required|string|max:100',
            'asal_instansi' => 'required|string|max:100',
            'tujuan_kunjungan' => 'required|string|max:255',
            'email' => 'nullable|email',
            'no_telepon' => 'nullable|string|max:20',
        ]);

        try {
            // Generate unique QR code
            $qr_code = Str::uuid()->toString();

            // Buat record tamu
            $tamu = TamuQr::create([
                'qr_code' => $qr_code,
                'nama_tamu' => $validated['nama_tamu'],
                'asal_instansi' => $validated['asal_instansi'],
                'tujuan_kunjungan' => $validated['tujuan_kunjungan'],
                'email' => $validated['email'] ?? null,
                'no_telepon' => $validated['no_telepon'] ?? null,
                'waktu_registrasi' => now(),
                'status' => 'terdaftar',
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            // Generate QR code image
            $qr_path = 'qr-code/' . $qr_code . '.png';
            $qr_content = json_encode([
                'uuid' => $qr_code,
                'nama' => $validated['nama_tamu'],
                'asal' => $validated['asal_instansi'],
                'timestamp' => now()->toIso8601String(),
            ]);

            $qr_image = QrCode::format('png')
                ->size(300)
                ->generate($qr_content);

            Storage::disk('public')->put($qr_path, $qr_image);

            // Update foto_qr path
            $tamu->update(['foto_qr' => Storage::url($qr_path)]);

            return response()->json([
                'success' => true,
                'message' => 'Tamu berhasil didaftarkan',
                'data' => [
                    'id' => $tamu->id,
                    'qr_code' => $qr_code,
                    'nama_tamu' => $tamu->nama_tamu,
                    'qr_image' => $tamu->foto_qr,
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendaftarkan tamu: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Tampilkan detail tamu
     * Route: GET /api/sekretariat/buku-tamu/{id}
     */
    public function show(TamuQr $tamuQr): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $tamuQr->load('creator', 'updater'),
        ]);
    }

    /**
     * Check-in tamu (scan QR code)
     * Route: POST /api/sekretariat/buku-tamu/check-in
     */
    public function checkIn(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'qr_code' => 'required|string|exists:tamu_qrs,qr_code',
        ]);

        try {
            $tamu = TamuQr::where('qr_code', $validated['qr_code'])->firstOrFail();

            // Cek status sebelumnya
            if ($tamu->isCheckedIn()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tamu sudah check-in sebelumnya',
                ], 422);
            }

            // Mark as checked in
            $tamu->markAsCheckedIn(auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Check-in berhasil! Selamat datang ' . $tamu->nama_tamu,
                'data' => [
                    'nama_tamu' => $tamu->nama_tamu,
                    'asal_instansi' => $tamu->asal_instansi,
                    'waktu_check_in' => $tamu->waktu_check_in->format('H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal check-in: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Monitoring real-time kehadiran tamu
     * Route: GET /api/sekretariat/buku-tamu/monitoring
     */
    public function monitoring(): JsonResponse
    {
        $today_stats = [
            'total' => TamuQr::today()->count(),
            'hadir' => TamuQr::today()->checkedIn()->count(),
            'terdaftar' => TamuQr::today()->registered()->count(),
            'tidak_hadir' => TamuQr::today()->absent()->count(),
        ];

        $recent_guests = TamuQr::today()
            ->orderByDesc('waktu_registrasi')
            ->take(5)
            ->get(['id', 'nama_tamu', 'asal_instansi', 'status', 'waktu_registrasi', 'waktu_check_in']);

        return response()->json([
            'success' => true,
            'stats' => $today_stats,
            'recent_guests' => $recent_guests,
            'timestamp' => now(),
        ]);
    }

    /**
     * Export laporan kehadiran tamu
     * Route: GET /api/sekretariat/buku-tamu/export
     */
    public function export(Request $request): JsonResponse
    {
        $query = TamuQr::query();

        // Filter berdasarkan tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('waktu_registrasi', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('waktu_registrasi', '<=', $request->date_to);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan asal institusi
        if ($request->filled('institution')) {
            $query->fromInstitution($request->institution);
        }

        $data = $query->orderBy('waktu_registrasi')->get([
            'nama_tamu',
            'asal_instansi',
            'tujuan_kunjungan',
            'email',
            'no_telepon',
            'waktu_registrasi',
            'waktu_check_in',
            'status',
        ]);

        return response()->json([
            'success' => true,
            'total' => $data->count(),
            'data' => $data,
        ]);
    }

    /**
     * Update status tamu
     * Route: PUT /api/sekretariat/buku-tamu/{id}/status
     */
    public function updateStatus(Request $request, TamuQr $tamuQr): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:terdaftar,hadir,tidak_hadir',
        ]);

        try {
            $tamuQr->update([
                'status' => $validated['status'],
                'updated_by' => auth()->id(),
            ]);

            // Set waktu check-in jika status berubah ke hadir
            if ($validated['status'] === 'hadir' && !$tamuQr->waktu_check_in) {
                $tamuQr->update(['waktu_check_in' => now()]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Status tamu diperbarui',
                'data' => $tamuQr,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Hapus tamu (soft delete)
     * Route: DELETE /api/sekretariat/buku-tamu/{id}
     */
    public function destroy(TamuQr $tamuQr): JsonResponse
    {
        try {
            // Delete QR image file if exists
            if ($tamuQr->foto_qr) {
                $file_path = str_replace('/storage/', '', $tamuQr->foto_qr);
                Storage::disk('public')->delete($file_path);
            }

            $tamuQr->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data tamu dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal hapus data: ' . $e->getMessage(),
            ], 500);
        }
    }
}

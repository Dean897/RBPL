<?php

namespace App\Services;

use App\Models\Disposisi;
use App\Models\Archive;
use App\Models\ActivityLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class PdfGeneratorService
{
    /**
     * Generate PDF surat balasan with digital signature.
     */
    public function generateSuratBalasan(Disposisi $disposisi, array $options = []): string
    {
        $suratMasuk = $disposisi->suratMasuk;
        $pemohonName = $suratMasuk?->pengirim?->name ?? 'Pemohon';
        $instansiName = $suratMasuk?->instansi ?? 'Instansi';

        // Prepare data
        $data = [
            'suratMasuk' => $suratMasuk,
            'disposisi' => $disposisi,
            'isiSurat' => $disposisi->isi_surat_balasan,
            'tanggalPDF' => now()->translatedFormat('d F Y'),
            'namaPimpinan' => 'Kepala Institusi', // Bisa ambil dari config atau user pimpinan
            'tandaTanganPath' => $this->getTandaTanganPath(),
        ];

        // Generate PDF
        $pdf = Pdf::loadView('Sekretariat.surat-keluar.template-pdf', $data);
        $pdf->setPaper('a4', 'portrait');

        // Save to storage
        $fileName = 'surat-balasan-' . $disposisi->id . '-' . time() . '.pdf';
        $path = 'surat-keluar/' . $fileName;
        Storage::disk('public')->put($path, $pdf->output());

        // Create archive record automatically; allow overriding category/description via options
        try {
            $category = $options['category'] ?? 'surat-keluar';
            $description = $options['description'] ?? ('PDF surat balasan otomatis untuk ' . $pemohonName . ' dari ' . $instansiName);

            $archive = Archive::create([
                'archive_number' => '',
                'title' => $pemohonName . ' - ' . $instansiName,
                'category' => $category,
                'description' => $description,
                'file_path' => $path,
                'file_name' => $fileName,
                'file_size' => strlen($pdf->output()),
                'mime_type' => 'application/pdf',
                'archived_at' => now(),
                'uploaded_by' => Auth::id(),
                'is_private' => false,
                'allowed_roles' => [],
            ]);

            $archive->archive_number = 'AR' . now()->format('Ymd') . str_pad($archive->id, 6, '0', STR_PAD_LEFT);
            $archive->save();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'created',
                'model_type' => Archive::class,
                'model_id' => $archive->id,
                'metadata' => json_encode(['source' => 'PdfGeneratorService', 'file' => $fileName, 'category' => $category]),
            ]);
        } catch (\Exception $e) {
            // if archive creation fails, keep the PDF file but log error to laravel log
            Log::error('Auto-archive failed: ' . $e->getMessage());
        }

        return $path;
    }

    /**
     * Get path of digital signature image.
     */
    private function getTandaTanganPath(): ?string
    {
        $signaturePath = storage_path('app/public/signatures/ttd-pimpinan.png');
        if (File::exists($signaturePath)) {
            return $signaturePath;
        }
        return null;
    }
}

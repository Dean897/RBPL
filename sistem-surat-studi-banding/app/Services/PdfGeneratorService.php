<?php

namespace App\Services;

use App\Models\Disposisi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class PdfGeneratorService
{
    /**
     * Generate PDF surat balasan with digital signature.
     */
    public function generateSuratBalasan(Disposisi $disposisi): string
    {
        // Prepare data
        $data = [
            'suratMasuk' => $disposisi->suratMasuk,
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

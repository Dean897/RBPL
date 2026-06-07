<?php

namespace App\Http\Requests\Eksternal;

use Illuminate\Foundation\Http\FormRequest;

class StorePengajuanSuratRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'no_surat'      => ['required', 'string', 'max:100'],
            'instansi'      => ['required', 'string', 'max:255'],
            'perihal'       => ['required', 'string', 'max:255'],
            'tanggal_surat' => ['required', 'date'],
            'file_pdf'      => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'no_surat'      => 'Nomor Surat',
            'instansi'      => 'Nama Instansi',
            'perihal'       => 'Perihal',
            'tanggal_surat' => 'Tanggal Surat',
            'file_pdf'      => 'File PDF',
        ];
    }

    public function messages(): array
    {
        return [
            'file_pdf.required' => 'Dokumen surat permohonan wajib dilampirkan.',
            'file_pdf.mimes'    => 'Dokumen harus berformat PDF.',
            'file_pdf.max'      => 'Ukuran file PDF maksimal 2MB.',
        ];
    }
}

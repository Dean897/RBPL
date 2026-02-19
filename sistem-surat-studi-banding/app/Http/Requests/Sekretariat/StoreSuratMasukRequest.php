<?php

namespace App\Http\Requests\Sekretariat;

use Illuminate\Foundation\Http\FormRequest;

class StoreSuratMasukRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'no_surat'       => ['required', 'string', 'max:100'],
            'instansi'       => ['required', 'string', 'max:255'],
            'perihal'        => ['required', 'string', 'max:255'],
            'tanggal_surat'  => ['required', 'date'],
            'tanggal_terima' => ['nullable', 'date'],
            'file_pdf'       => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'no_surat'       => 'Nomor Surat',
            'instansi'       => 'Asal Instansi',
            'perihal'        => 'Perihal',
            'tanggal_surat'  => 'Tanggal Surat',
            'tanggal_terima' => 'Tanggal Diterima',
            'file_pdf'       => 'File PDF',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'file_pdf.max' => 'Ukuran file PDF maksimal 2MB.',
        ];
    }
}

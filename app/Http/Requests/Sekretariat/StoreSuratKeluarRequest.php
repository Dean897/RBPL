<?php

namespace App\Http\Requests\Sekretariat;

use Illuminate\Foundation\Http\FormRequest;

class StoreSuratKeluarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'isi_surat_balasan' => ['required', 'string', 'min:50', 'max:5000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'isi_surat_balasan' => 'Isi Surat Balasan',
        ];
    }
}

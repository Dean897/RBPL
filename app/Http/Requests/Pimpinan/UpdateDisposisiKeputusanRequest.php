<?php

namespace App\Http\Requests\Pimpinan;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDisposisiKeputusanRequest extends FormRequest
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
            'keputusan' => ['required', 'in:Diterima,Ditolak'],
            'catatan_pimpinan' => ['nullable', 'string', 'max:1000', 'required_if:keputusan,Ditolak'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'keputusan' => 'Keputusan',
            'catatan_pimpinan' => 'Catatan/Instruksi Pimpinan',
        ];
    }
}

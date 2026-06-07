<?php

namespace App\Http\Requests\Sekretariat;

use Illuminate\Foundation\Http\FormRequest;

class StoreDisposisiRequest extends FormRequest
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
            'tgl_disposisi'       => ['required', 'date'],
            'catatan_sekretariat' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'tgl_disposisi'       => 'Tanggal Disposisi',
            'catatan_sekretariat' => 'Catatan Sekretariat',
        ];
    }
}

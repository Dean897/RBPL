<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArchiveRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf|max:10240',
            'archived_at' => 'nullable|date',
            'is_private' => 'nullable|boolean',
            'allowed_roles' => 'nullable|array',
            'allowed_roles.*' => 'string',
        ];
    }
}

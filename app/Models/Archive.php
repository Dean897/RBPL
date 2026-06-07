<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Archive extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'archive_number',
        'title',
        'category',
        'description',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'archived_at',
        'uploaded_by',
        'is_private',
        'allowed_roles',
    ];

    protected $casts = [
        'archived_at' => 'date',
        'is_private' => 'boolean',
        'allowed_roles' => 'array',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}

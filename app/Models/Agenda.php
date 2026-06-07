<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $fillable = [
        'title',
        'date',
        'location',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];
}

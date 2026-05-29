<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'agenda_id',
        'user_id',
        'status',
        'notes',
    ];

    public function agenda()
    {
        return $this->belongsTo(Agenda::class);
    }
}

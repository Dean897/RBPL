<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    protected $table = 'surat_masuks';
    protected $guarded = [];

    public function disposisi()
    {
        return $this->hasOne(Disposisi::class, 'surat_masuk_id');
    }

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

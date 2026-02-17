<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    protected $table = 'surat_masuks';
    protected $primaryKey = 'id_surat';
    protected $guarded = [];

    public function disposisi()
    {
        return $this->hasOne(Disposisi::class, 'id_surat');
    }
    public function pengirim()
    {
        return $this->belongsTo(user::class, 'user_id');
    }
}

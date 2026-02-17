<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    protected $table = 'disposisis';
    protected $primaryKey = 'id_disposisi';
    protected $guarded = [];

    public function surat()
    {
        return $this->belongsTo(SuratMasuk::class, 'id_surat');
    }
}

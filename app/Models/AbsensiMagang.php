<?php
// app/Models/AbsensiMagang.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiMagang extends Model
{
    protected $table = 'absensi_magang';
    protected $fillable = [
        'application_uuid', 'tanggal', 'waktu', 'keterangan'
    ];

    public function application()
    {
        return $this->belongsTo(StatusLamaran::class, 'application_uuid', 'uuid');
    }
}


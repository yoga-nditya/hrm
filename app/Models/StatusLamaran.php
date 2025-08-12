<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class StatusLamaran extends Model
{
    use HasFactory;

    protected $table = 'status_lamarans';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'users_id',
        'posisi',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'uuid');
    }

    public function lowongan()
    {
        return $this->belongsTo(Lowongan::class, 'posisi', 'posisi');
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function getQrCodeDataAttribute()
    {
        // QR Code bisa berisi UUID, nama, posisi, dll
        return [
            'Nama' => $this->user->name ?? '-',
            'Posisi' => $this->lowongan->posisi ?? '-',
            'Kode Lamaran' => $this->uuid,
            'Status' => ucfirst($this->status),
            'Keterangan' => 'Lolos - Silakan scan saat tes/wawancara'
        ];
    }

    public function absensiMagang()
    {
        return $this->hasMany(AbsensiMagang::class, 'application_uuid', 'uuid');
    }

}

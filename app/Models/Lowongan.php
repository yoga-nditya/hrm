<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Lowongan extends Model
{
    use HasFactory;

    protected $table = 'lowongans';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'lowongan_id',
        'posisi',
        'jenis_pekerjaan',
        'role_pekerjaan',
        'deskripsi',
        'experience_level',
        'department',
        'salary_max',
        'salary_min',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
            if (empty($model->lowongan_id)) {
                $model->lowongan_id = Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserDetail extends Model
{
    use HasFactory;

    protected $table = 'user_details';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'users_id',
        'CV',
        'alamat',
        'provinsi',
        'kota',
        'kode_pos',
        'no_telpon',
        'foto',
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

    public function pengalaman()
    {
        return $this->hasMany(Pengalaman::class, 'users_details_id', 'uuid');
    }
}
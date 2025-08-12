<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
        'roles',
    ];

    protected $hidden = [
        'password',
        'remember_token',
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

    public function userDetail()
    {
        return $this->hasOne(UserDetail::class, 'users_id', 'uuid');
    }

    public function statusLamaran()
    {
        return $this->hasMany(StatusLamaran::class, 'users_id', 'uuid');
    }

    public function applications()
    {
        return $this->hasMany(StatusLamaran::class, 'users_id', 'uuid');
    }
}

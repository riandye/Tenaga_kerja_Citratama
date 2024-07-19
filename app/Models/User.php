<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'info',
        'klasifikasi'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'info' => 'array',
        'klasifikasi' => 'array',
        'email_verified_at' => 'datetime',
        
    ];
    
    public function recruitments()
    {
        return $this->hasMany(recruitment::class, 'ID_user');
    }

    public function penerimaJadwal()
    {
        return $this->hasMany(PenerimaJadwal::class);
    }
    protected $primaryKey = 'ID_user';

}

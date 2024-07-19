<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PerusahaanMitra extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'perusahaan_mitra';
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
        'role'
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
        'email_verified_at' => 'datetime',
        
    ];
    
    protected $primaryKey = 'ID_mitra';

    public function recruitments()
    {
        return $this->hasMany(recruitment::class, 'ID_mitra');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class recruitment extends Model
{
    use HasFactory;

    protected $table = 'recruitment';

    protected $fillable = [
        'ID_user',
        'ID_mitra',
        'tgl_recruitment',
        'status',
        'info'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'ID_user');
    }

    public function perusahaanMitra()
    {
        return $this->belongsTo(PerusahaanMitra::class, 'ID_mitra');
    }

    protected $primaryKey = 'ID_recruitment';
}

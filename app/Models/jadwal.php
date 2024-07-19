<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';
    protected $primaryKey = 'ID_jadwal';

    protected $fillable = [
        'ID_mitra',
        'tanggal',
        'tempat',
        'jam',
        'ID_user'
    ];

    public function mitra()
    {
        return $this->belongsTo(PerusahaanMitra::class, 'ID_mitra');
    }

    public function penerimaJadwal()
    {
        return $this->hasMany(PenerimaJadwal::class, 'ID_jadwal');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaJadwal extends Model
{
    use HasFactory;

    protected $table = 'penerima_jadwal';

    protected $fillable = [
        'ID_jadwal',
        'ID_user'
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'ID_jadwal');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'ID_user');
    }
}

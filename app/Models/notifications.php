<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notifications extends Model
{
    use HasFactory;

    protected $tabel = 'notifications';
    protected $primaryKey = 'id';

    protected $fillable = [
        'data'
    ];

    protected $casts = [
        'data' => 'array',
    ];
}

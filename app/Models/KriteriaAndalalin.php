<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KriteriaAndalalin extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_kegiatan',
        'parameter',
        'kategori',
        'batas_min',
        'batas_max',
        'satuan',
    ];

    protected $casts = [
        'batas_min' => 'decimal:2',
        'batas_max' => 'decimal:2',
    ];
}

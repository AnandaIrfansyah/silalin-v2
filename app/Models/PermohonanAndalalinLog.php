<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermohonanAndalalinLog extends Model
{
    protected $fillable = [
        'permohonan_andalalin_id',
        'user_id',
        'aksi',
        'status_sebelum',
        'status_sesudah',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function permohonan()
    {
        return $this->belongsTo(PermohonanAndalalin::class, 'permohonan_andalalin_id');
    }
}

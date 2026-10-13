<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermohonanParkirLog extends Model
{
    protected $fillable = [
        'permohonan_parkir_id',
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
        return $this->belongsTo(PermohonanParkir::class, 'permohonan_parkir_id');
    }
}

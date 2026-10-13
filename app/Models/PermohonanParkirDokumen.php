<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermohonanParkirDokumen extends Model
{
    protected $fillable = [
        'permohonan_parkir_id',
        'master_dokumen_id',
        'nama_dokumen_snapshot',
        'file_path',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function permohonan()
    {
        return $this->belongsTo(PermohonanParkir::class, 'permohonan_parkir_id');
    }

    public function masterDokumen()
    {
        return $this->belongsTo(MasterDokumenPersyaratan::class, 'master_dokumen_id');
    }
}

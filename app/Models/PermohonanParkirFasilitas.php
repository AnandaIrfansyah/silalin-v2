<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermohonanParkirFasilitas extends Model
{
    protected $table = 'permohonan_parkir_fasilitas';

    protected $fillable = [
        'permohonan_parkir_id',
        'master_fasilitas_id',
        'nama_fasilitas_snapshot',
        'status',
        'status_hasil_survey',
    ];

    public function permohonan()
    {
        return $this->belongsTo(PermohonanParkir::class, 'permohonan_parkir_id');
    }

    public function masterFasilitas()
    {
        return $this->belongsTo(MasterFasilitas::class, 'master_fasilitas_id');
    }

    public function getStatusFinalAttribute(): string
    {
        return $this->status_hasil_survey ?? $this->status;
    }
}

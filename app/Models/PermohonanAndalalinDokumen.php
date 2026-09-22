<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermohonanAndalalinDokumen extends Model
{
    protected $fillable = ['permohonan_andalalin_id', 'master_dokumen_id', 'file_path', 'uploaded_at'];

    protected $casts = ['uploaded_at' => 'datetime'];

    public function masterDokumen()
    {
        return $this->belongsTo(MasterDokumenPersyaratan::class, 'master_dokumen_id');
    }
}

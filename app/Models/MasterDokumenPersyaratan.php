<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterDokumenPersyaratan extends Model
{
    protected $fillable = [
        'nama_dokumen',
        'kode',
        'jenis_permohonan',
        'keterangan',
        'is_wajib',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'is_wajib'   => 'boolean',
        'is_active'  => 'boolean',
    ];

    // Scope buat narik dokumen relevan ke satu jenis permohonan tertentu,
    // termasuk yang "semua" (overlap)
    public function scopeUntukJenis($query, string $jenis)
    {
        return $query->where('is_active', true)
            ->where(function ($q) use ($jenis) {
                $q->where('jenis_permohonan', $jenis)
                    ->orWhere('jenis_permohonan', 'semua');
            })
            ->orderBy('urutan');
    }
}

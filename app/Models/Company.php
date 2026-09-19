<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nama_perusahaan',
        'bentuk_badan_usaha',
        'npwp_perusahaan',
        'nib',
        'akta_pendirian',
        'alamat_perusahaan',
        'telepon_perusahaan',
        'email_perusahaan',
        'nama_penanggung_jawab',
        'jabatan_penanggung_jawab',
        'ktp_penanggung_jawab',
        'telepon_penanggung_jawab',
        'surat_kuasa',
        'nama_kuasa',
        'jabatan_kuasa',
        'status',
        'catatan_verifikasi',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}

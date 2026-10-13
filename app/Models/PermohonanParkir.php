<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermohonanParkir extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_id',
        'nama_lokasi',
        'jenis_parkir',
        'luas_area',
        'alamat_lokasi',
        'status',
        'catatan_verifikasi_berkas',
        'tanggal_survey',
        'jam_survey',
        'file_berita_acara_survey',
        'tanggal_paparan',
        'jam_paparan',
        'file_laporan_analisis',
        'jenis_sifat_naskah',
        'nomor_klasifikasi',
        'kode_bidang',
        'nomor_urut_dokumen',
        'tanggal_dokumen',
        'file_surat_pernyataan_ttd',
        'catatan_verifikasi_ttd',
        'file_surat_rekomendasi',
        'diterbitkan_at',
    ];

    protected $casts = [
        'luas_area'       => 'decimal:2',
        'tanggal_survey'  => 'date',
        'tanggal_paparan' => 'date',
        'tanggal_dokumen' => 'date',
        'diterbitkan_at'  => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function dokumen()
    {
        return $this->hasMany(PermohonanParkirDokumen::class);
    }

    public function fasilitas()
    {
        return $this->hasMany(PermohonanParkirFasilitas::class);
    }

    public function logs()
    {
        return $this->hasMany(PermohonanParkirLog::class)->latest();
    }

    public function getNomorRekomendasiAttribute(): ?string
    {
        if (! $this->nomor_urut_dokumen || ! $this->tanggal_dokumen) {
            return null;
        }

        return sprintf(
            '%s/%s/%s/%s',
            $this->nomor_klasifikasi,
            $this->nomor_urut_dokumen,
            $this->kode_bidang,
            $this->tanggal_dokumen->format('Y')
        );
    }

    public function getStatusLabelAttribute(): string
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }

    public function getStatusBadgeAttribute(): string
    {
        return match (true) {
            $this->status === 'selesai'                  => 'success',
            str_starts_with($this->status, 'revisi')      => 'danger',
            $this->status === 'menunggu_ttd'              => 'warning',
            str_starts_with($this->status, 'dijadwalkan') => 'primary',
            default                                       => 'info',
        };
    }
}

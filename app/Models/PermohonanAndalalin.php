<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermohonanAndalalin extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_id',
        'kriteria_andalalin_id',
        'jenis_kegiatan_snapshot',
        'parameter_snapshot',
        'kategori_bangkitan',
        'alamat_proyek',
        'status',
        'catatan_verifikasi_berkas',
        'tanggal_survey',
        'jam_survey',
        'file_berita_acara_survey',
        'file_dokumen_andalalin',
        'catatan_verifikasi_dokumen_andalalin',
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

    public function kriteriaAndalalin()
    {
        return $this->belongsTo(KriteriaAndalalin::class);
    }

    public function logs()
    {
        return $this->hasMany(PermohonanAndalalinLog::class)->latest();
    }

    /**
     * Nomor rekomendasi digenerate dari data naskah,
     * bukan disimpan sebagai kolom terpisah.
     * Format bisa disesuaikan sesuai format naskah dinas Dishub.
     */
    public function getNomorRekomendasiAttribute(): ?string
    {
        if (! $this->nomor_urut_dokumen || ! $this->tanggal_dokumen) {
            return null;
        }

        return sprintf(
            '%s/%s/%s/%s/%s',
            $this->nomor_urut_dokumen,
            $this->kode_bidang,
            $this->nomor_klasifikasi,
            $this->jenis_sifat_naskah,
            $this->tanggal_dokumen->format('Y')
        );
    }

    /**
     * Helper: apakah permohonan ini wajib dokumen andalalin tambahan (tahap 5)
     */
    public function wajibDokumenAndalalin(): bool
    {
        return in_array($this->kategori_bangkitan, ['sedang', 'tinggi']);
    }

    public function dokumen()
    {
        return $this->hasMany(PermohonanAndalalinDokumen::class);
    }
}

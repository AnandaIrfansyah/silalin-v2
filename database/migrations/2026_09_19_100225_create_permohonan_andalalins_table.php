<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permohonan_andalalins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();

            // Referensi ke master, boleh null kalau master-nya dihapus
            $table->foreignId('kriteria_andalalin_id')->nullable()
                ->constrained('kriteria_andalalins')->nullOnDelete();

            // Snapshot data kriteria SAAT pengajuan — supaya data lama tetap valid
            // walau master kriteria berubah/dihapus di kemudian hari
            $table->string('jenis_kegiatan_snapshot');
            $table->string('parameter_snapshot');
            $table->enum('kategori_bangkitan', ['rendah', 'sedang', 'tinggi']);

            $table->text('alamat_proyek');

            $table->string('status')->default('draft');

            // Tahap 2 - Verifikasi Berkas
            $table->text('catatan_verifikasi_berkas')->nullable();

            // Tahap 3 - Jadwal Survey
            $table->date('tanggal_survey')->nullable();
            $table->time('jam_survey')->nullable();

            // Tahap 4 - Berita Acara Survey
            $table->string('file_berita_acara_survey')->nullable();

            // Tahap 5 - Dokumen Andalalin (kondisional: sedang/tinggi)
            $table->string('file_dokumen_andalalin')->nullable();
            $table->text('catatan_verifikasi_dokumen_andalalin')->nullable();

            // Tahap 6 - Jadwal Paparan & Laporan Analisis
            $table->date('tanggal_paparan')->nullable();
            $table->time('jam_paparan')->nullable();
            $table->string('file_laporan_analisis')->nullable();

            // Data naskah — dasar generate nomor surat otomatis
            $table->string('jenis_sifat_naskah')->nullable();
            $table->string('nomor_klasifikasi')->nullable();
            $table->string('kode_bidang')->nullable();
            $table->string('nomor_urut_dokumen')->nullable();
            $table->date('tanggal_dokumen')->nullable();

            // Tahap 7-8 - Surat Pernyataan Kesanggupan
            $table->string('file_surat_pernyataan_ttd')->nullable();
            $table->text('catatan_verifikasi_ttd')->nullable();

            // Tahap 9 - Rekomendasi Final
            $table->string('file_surat_rekomendasi')->nullable();
            $table->timestamp('diterbitkan_at')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan_andalalins');
    }
};

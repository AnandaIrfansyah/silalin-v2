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
        Schema::create('permohonan_parkirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();

            $table->string('nama_lokasi');
            $table->enum('jenis_parkir', ['umum', 'khusus']);
            $table->decimal('luas_area', 10, 2);
            $table->text('alamat_lokasi');

            $table->string('status')->default('draft');
            $table->text('catatan_verifikasi_berkas')->nullable();

            $table->date('tanggal_survey')->nullable();
            $table->time('jam_survey')->nullable();
            $table->string('file_berita_acara_survey')->nullable();

            $table->date('tanggal_paparan')->nullable();
            $table->time('jam_paparan')->nullable();
            $table->string('file_laporan_analisis')->nullable();

            $table->string('jenis_sifat_naskah')->nullable();
            $table->string('nomor_klasifikasi')->nullable();
            $table->string('kode_bidang')->nullable();
            $table->string('nomor_urut_dokumen')->nullable();
            $table->date('tanggal_dokumen')->nullable();

            $table->string('file_surat_pernyataan_ttd')->nullable();
            $table->text('catatan_verifikasi_ttd')->nullable();

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
        Schema::dropIfExists('permohonan_parkirs');
    }
};

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
        Schema::create('permohonan_andalalin_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_andalalin_id')->constrained('permohonan_andalalins')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users'); // pelaku aksi (admin atau pemohon)

            $table->string('aksi'); // contoh: 'submit', 'verifikasi_berkas_disetujui', 'jadwal_survey', dst
            $table->string('status_sebelum')->nullable();
            $table->string('status_sesudah');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan_andalalin_logs');
    }
};

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
        Schema::create('master_dokumen_persyaratans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dokumen');
            $table->string('kode')->unique();
            $table->enum('jenis_permohonan', ['andalalin', 'parkir', 'semua'])->default('semua');
            $table->text('keterangan')->nullable();
            $table->boolean('is_wajib')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();

            $table->index('jenis_permohonan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_dokumen_persyaratans');
    }
};

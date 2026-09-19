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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Data Perusahaan
            $table->string('nama_perusahaan');
            $table->enum('bentuk_badan_usaha', ['PT', 'CV', 'UD', 'Koperasi', 'Yayasan', 'Perorangan', 'Lainnya']);
            $table->string('npwp_perusahaan', 20)->unique();
            $table->string('nib', 15)->unique();
            $table->string('akta_pendirian')->nullable()->comment('PDF');
            $table->text('alamat_perusahaan');
            $table->string('telepon_perusahaan', 20);
            $table->string('email_perusahaan')->nullable();

            // Data Penanggung Jawab
            $table->string('nama_penanggung_jawab');
            $table->string('jabatan_penanggung_jawab');
            $table->string('ktp_penanggung_jawab')->nullable()->comment('PDF/JPG/PNG');
            $table->string('telepon_penanggung_jawab', 20);

            // Surat Kuasa (opsional)
            $table->string('surat_kuasa')->nullable()->comment('PDF');
            $table->string('nama_kuasa')->nullable();
            $table->string('jabatan_kuasa')->nullable();

            // Status verifikasi
            $table->enum('status', ['draft', 'pending', 'verified', 'rejected'])->default('draft');
            $table->text('catatan_verifikasi')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

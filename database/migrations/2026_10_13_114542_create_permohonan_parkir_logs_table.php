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
        Schema::create('permohonan_parkir_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_parkir_id')->constrained('permohonan_parkirs')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->string('aksi');
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
        Schema::dropIfExists('permohonan_parkir_logs');
    }
};

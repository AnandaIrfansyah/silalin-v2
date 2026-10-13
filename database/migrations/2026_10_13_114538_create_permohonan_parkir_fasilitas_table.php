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
        Schema::create('permohonan_parkir_fasilitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_parkir_id')->constrained('permohonan_parkirs')->cascadeOnDelete();
            $table->foreignId('master_fasilitas_id')->nullable()
                ->constrained('master_fasilitas')->nullOnDelete();
            $table->string('nama_fasilitas_snapshot');
            $table->enum('status', ['ada', 'tidak ada'])->default('tidak ada');
            $table->enum('status_hasil_survey', ['ada', 'tidak ada'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan_parkir_fasilitas');
    }
};

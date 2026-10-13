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
        Schema::create('permohonan_parkir_dokumens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_parkir_id')->constrained('permohonan_parkirs')->cascadeOnDelete();
            $table->foreignId('master_dokumen_id')->nullable()
                ->constrained('master_dokumen_persyaratans')->nullOnDelete();
            $table->string('nama_dokumen_snapshot');
            $table->string('file_path')->nullable();
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan_parkir_dokumens');
    }
};

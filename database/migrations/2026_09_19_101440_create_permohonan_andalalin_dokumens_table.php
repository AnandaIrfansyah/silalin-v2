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
        Schema::create('permohonan_andalalin_dokumens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_andalalin_id')->constrained('permohonan_andalalins')->cascadeOnDelete();
            $table->foreignId('master_dokumen_id')->constrained('master_dokumen_persyaratans')->cascadeOnDelete();
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
        Schema::dropIfExists('permohonan_andalalin_dokumens');
    }
};

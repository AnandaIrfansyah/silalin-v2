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
        Schema::create('kriteria_andalalins', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_kegiatan');
            $table->string('parameter');
            $table->enum('kategori', ['rendah', 'sedang', 'tinggi']);
            $table->decimal('batas_min', 15, 2)->nullable();
            $table->decimal('batas_max', 15, 2)->nullable();
            $table->string('satuan')->nullable();
            $table->timestamps();

            $table->index('jenis_kegiatan');
            $table->index('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriteria_andalalins');
    }
};

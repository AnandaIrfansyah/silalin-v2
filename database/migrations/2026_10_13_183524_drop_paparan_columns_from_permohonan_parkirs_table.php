<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('permohonan_parkirs')
            ->where('status', 'dijadwalkan_paparan')
            ->update(['status' => 'survey_selesai']);

        Schema::table('permohonan_parkirs', function (Blueprint $table) {
            $table->dropColumn(['tanggal_paparan', 'jam_paparan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan_parkirs', function (Blueprint $table) {
            $table->date('tanggal_paparan')->nullable()->after('file_berita_acara_survey');
            $table->time('jam_paparan')->nullable()->after('tanggal_paparan');
        });
    }
};

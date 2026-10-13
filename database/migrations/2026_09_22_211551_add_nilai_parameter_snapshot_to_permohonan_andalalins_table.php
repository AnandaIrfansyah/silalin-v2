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
        Schema::table('permohonan_andalalins', function (Blueprint $table) {
            $table->string('nilai_parameter_snapshot')->nullable()->after('parameter_snapshot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan_andalalins', function (Blueprint $table) {
            $table->dropColumn('nilai_parameter_snapshot');
        });
    }
};

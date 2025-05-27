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
        Schema::table('pegawai', function (Blueprint $table) {
            $table->unsignedBigInteger('pegawai_level_id')->nullable()->after('id');
            $table->foreign('pegawai_level_id')->references('id')->on('pegawai_levels');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};

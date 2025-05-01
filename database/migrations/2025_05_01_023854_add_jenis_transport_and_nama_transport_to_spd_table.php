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
        Schema::table('surat_perjalanan_dinas', function (Blueprint $table) {
            $table->string('jenis_transport')->nullable();
            $table->string('nama_transport')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('surat_perjalanan_dinas', function (Blueprint $table) {
            $table->dropColumn(['jenis_transport', 'nama_transport']);
        });
    }
};

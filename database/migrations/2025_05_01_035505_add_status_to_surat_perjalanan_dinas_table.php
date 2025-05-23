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
            $table->enum('status', ['disetujui', 'ditolak', 'menunggu'])->default('menunggu')->after('nama_transport');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_perjalanan_dinas', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};

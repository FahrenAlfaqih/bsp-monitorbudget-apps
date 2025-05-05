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
            $table->unsignedBigInteger('periode_id')->nullable()->after('departemen_id');

            $table->foreign('periode_id')
                ->references('id')
                ->on('periode_anggaran')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_perjalanan_dinas', function (Blueprint $table) {
            $table->dropForeign(['periode_id']);
            $table->dropColumn('periode_id');
            //
        });
    }
};

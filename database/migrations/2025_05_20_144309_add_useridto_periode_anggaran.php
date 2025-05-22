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
        Schema::table('periode_anggaran', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('status');

            // Jika ingin menambahkan foreign key constraint, aktifkan ini:
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periode_anggaran', function (Blueprint $table) {
            // Jika foreign key constraint ditambahkan, drop dulu fk-nya
            $table->dropForeign(['user_id']);

            $table->dropColumn('user_id');
        });
    }
};

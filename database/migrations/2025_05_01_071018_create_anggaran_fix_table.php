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
        Schema::create('anggaran_fix', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('departemen_id');
            $table->unsignedBigInteger('periode_id');
            $table->bigInteger('jumlah_anggaran');
            $table->timestamps();
            $table->foreign('departemen_id')->references('id')->on('departemen')->onDelete('cascade');
            $table->foreign('periode_id')->references('id')->on('periode_anggaran')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggaran_fix');
    }
};

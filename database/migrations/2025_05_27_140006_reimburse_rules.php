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
        Schema::create('reimburse_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pegawai_level_id');
            $table->string('jenis_biaya');
            $table->decimal('maksimal_biaya', 15, 2);
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('pegawai_level_id')->references('id')->on('pegawai_levels')->onDelete('cascade');
            $table->index(['pegawai_level_id', 'jenis_biaya']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reimburse_rules');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('spd_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spd_id')->constrained('surat_perjalanan_dinas')->onDelete('cascade');
            $table->string('jenis_biaya'); 
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spd_detail');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('deklarasi_perjalanan_dinas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spd_id')->constrained('surat_perjalanan_dinas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('total_biaya', 15, 2);
            $table->date('tanggal_deklarasi');
            $table->text('uraian')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deklarasi_perjalanan_dinas');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('surat_perjalanan_dinas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departemen_id')->constrained('departemen')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // hanya admindept_hcm
            $table->string('nomor_spd')->unique();
            $table->string('nama_pegawai');
            $table->string('asal');
            $table->string('tujuan');
            $table->text('kegiatan')->nullable();
            $table->date('tanggal_berangkat');
            $table->date('tanggal_kembali');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_perjalanan_dinas');
    }
};

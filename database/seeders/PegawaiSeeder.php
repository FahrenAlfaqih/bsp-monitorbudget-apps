<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PegawaiSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $levels = DB::table('pegawai_levels')->pluck('id', 'kode_level');
        $pegawais = [
            ['pegawai_level_id' => $levels['A1'], 'nopekerja' => '0200', 'nama_pegawai' => 'Agus Santoso', 'email' => 'agus.santoso@pekerja.com'],
            ['pegawai_level_id' => $levels['A3'], 'nopekerja' => '0201', 'nama_pegawai' => 'Budi Pratama', 'email' => 'budi.pratama@pekerja.com'],
            ['pegawai_level_id' => $levels['A2'], 'nopekerja' => '0202', 'nama_pegawai' => 'Citra Dewi', 'email' => 'citra.dewi@pekerja.com'],
            ['pegawai_level_id' => $levels['A2'], 'nopekerja' => '0203', 'nama_pegawai' => 'Dedi Kurniawan', 'email' => 'dedi.kurniawan@pekerja.com'],
            ['pegawai_level_id' => $levels['A3'], 'nopekerja' => '0204', 'nama_pegawai' => 'Eka Putri', 'email' => 'eka.putri@pekerja.com'],
            ['pegawai_level_id' => $levels['A3'], 'nopekerja' => '0205', 'nama_pegawai' => 'Fajar Nugraha', 'email' => 'fajar.nugraha@pekerja.com'],
            ['pegawai_level_id' => $levels['A4'], 'nopekerja' => '0206', 'nama_pegawai' => 'Gita Lestari', 'email' => 'gita.lestari@pekerja.com'],
            ['pegawai_level_id' => $levels['A4'], 'nopekerja' => '0207', 'nama_pegawai' => 'Hadi Wijaya', 'email' => 'hadi.wijaya@pekerja.com'],
            ['pegawai_level_id' => $levels['A5'], 'nopekerja' => '0208', 'nama_pegawai' => 'Indah Sari', 'email' => 'indah.sari@pekerja.com'],
            ['pegawai_level_id' => $levels['A5'], 'nopekerja' => '0209', 'nama_pegawai' => 'Joko Santoso', 'email' => 'joko.santoso@pekerja.com'],
            ['pegawai_level_id' => $levels['A6'], 'nopekerja' => '0210', 'nama_pegawai' => 'Kiki Marlina', 'email' => 'kiki.marlina@pekerja.com'],
            ['pegawai_level_id' => $levels['A6'], 'nopekerja' => '0211', 'nama_pegawai' => 'Lina Puspita', 'email' => 'lina.puspita@pekerja.com'],
            ['pegawai_level_id' => $levels['A6'], 'nopekerja' => '0212', 'nama_pegawai' => 'Mira Putri', 'email' => 'mira.putri@pekerja.com'],
            ['pegawai_level_id' => $levels['A5'], 'nopekerja' => '0213', 'nama_pegawai' => 'Nanda Pratama', 'email' => 'nanda.pratama@pekerja.com'],
            ['pegawai_level_id' => $levels['A5'], 'nopekerja' => '0214', 'nama_pegawai' => 'Oki Saputra', 'email' => 'oki.saputra@pekerja.com'],
            ['pegawai_level_id' => $levels['A4'], 'nopekerja' => '0215', 'nama_pegawai' => 'Putri Anggraeni', 'email' => 'putri.anggraeni@pekerja.com'],
            ['pegawai_level_id' => $levels['A4'], 'nopekerja' => '0216', 'nama_pegawai' => 'Rian Kurniawan', 'email' => 'rian.kurniawan@pekerja.com'],
            ['pegawai_level_id' => $levels['A3'], 'nopekerja' => '0217', 'nama_pegawai' => 'Sari Melati', 'email' => 'sari.melati@pekerja.com'],
            ['pegawai_level_id' => $levels['A2'], 'nopekerja' => '0218', 'nama_pegawai' => 'Teguh Wijaya', 'email' => 'teguh.wijaya@pekerja.com'],
            ['pegawai_level_id' => $levels['A2'], 'nopekerja' => '0219', 'nama_pegawai' => 'Uli Hartono', 'email' => 'uli.hartono@pekerja.com'],
        ];



        foreach ($pegawais as $pegawai) {
            DB::table('pegawai')->insert([
                'nopekerja' => $pegawai['nopekerja'],
                'nama_pegawai' => $pegawai['nama_pegawai'],
                'email' => $pegawai['email'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}

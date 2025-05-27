<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PegawaiLevelsSeeder extends Seeder
{
    public function run()
    {
        DB::table('pegawai_levels')->insert([
            ['kode_level' => 'A1', 'nama_level' => 'Direktur (Vice President)', 'created_at' => now(), 'updated_at' => now()],
            ['kode_level' => 'A2', 'nama_level' => 'Manager', 'created_at' => now(), 'updated_at' => now()],
            ['kode_level' => 'A3', 'nama_level' => 'Technical Manager', 'created_at' => now(), 'updated_at' => now()],
            ['kode_level' => 'A4', 'nama_level' => 'Senior Officer', 'created_at' => now(), 'updated_at' => now()],
            ['kode_level' => 'A5', 'nama_level' => 'Junior Officer', 'created_at' => now(), 'updated_at' => now()],
            ['kode_level' => 'A6', 'nama_level' => 'Staff', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

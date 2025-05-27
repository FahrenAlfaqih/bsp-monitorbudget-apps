<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReimburseRulesSeeder extends Seeder
{
    public function run()
    {
        $levels = DB::table('pegawai_levels')->pluck('id', 'kode_level');
        DB::table('reimburse_rules')->insert([
            // Direktur (A1)
            [
                'pegawai_level_id' => $levels['A1'],
                'jenis_biaya' => 'Hotel',
                'maksimal_biaya' => 1500000,  // contoh batas Hotel bintang 4-5
                'keterangan' => 'Hotel bintang 4-5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pegawai_level_id' => $levels['A1'],
                'jenis_biaya' => 'Transportasi',
                'maksimal_biaya' => 3000000,  // pesawat VIP
                'keterangan' => 'Transportasi pesawat VIP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pegawai_level_id' => $levels['A1'],
                'jenis_biaya' => 'Makanan',
                'maksimal_biaya' => 300000,  // budget makan per hari
                'keterangan' => 'Budget makan per hari',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Manager (A2)
            [
                'pegawai_level_id' => $levels['A2'],
                'jenis_biaya' => 'Hotel',
                'maksimal_biaya' => 1000000,  // Hotel bintang 3-4
                'keterangan' => 'Hotel bintang 3-4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pegawai_level_id' => $levels['A2'],
                'jenis_biaya' => 'Transportasi',
                'maksimal_biaya' => 1500000,  // pesawat bisnis
                'keterangan' => 'Transportasi pesawat bisnis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pegawai_level_id' => $levels['A2'],
                'jenis_biaya' => 'Makanan',
                'maksimal_biaya' => 200000,  // budget makan per hari
                'keterangan' => 'Budget makan per hari',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Technical Manager ($levels['A3'])
            [
                'pegawai_level_id' => $levels['A3'],
                'jenis_biaya' => 'Hotel',
                'maksimal_biaya' => 800000,  // Hotel bintang 3
                'keterangan' => 'Hotel bintang 3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pegawai_level_id' => $levels['A3'],
                'jenis_biaya' => 'Transportasi',
                'maksimal_biaya' => 1200000,  // pesawat ekonomi/bisnis
                'keterangan' => 'Transportasi pesawat ekonomi/bisnis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pegawai_level_id' => $levels['A3'],
                'jenis_biaya' => 'Makanan',
                'maksimal_biaya' => 150000,
                'keterangan' => 'Budget makan per hari',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Senior Officer (A4)
            [
                'pegawai_level_id' => $levels['A3'],
                'jenis_biaya' => 'Hotel',
                'maksimal_biaya' => 600000,  // Hotel bintang 2-3
                'keterangan' => 'Hotel bintang 2-3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pegawai_level_id' => $levels['A3'],
                'jenis_biaya' => 'Transportasi',
                'maksimal_biaya' => 800000,  // kelas ekonomi
                'keterangan' => 'Transportasi kelas ekonomi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pegawai_level_id' => $levels['A3'],
                'jenis_biaya' => 'Makanan',
                'maksimal_biaya' => 100000,
                'keterangan' => 'Budget makan per hari',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Junior Officer (A5)
            [
                'pegawai_level_id' => $levels['A5'],
                'jenis_biaya' => 'Hotel',
                'maksimal_biaya' => 400000,  // Hotel bintang 2 atau losmen
                'keterangan' => 'Hotel bintang 2 atau losmen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pegawai_level_id' => $levels['A5'],
                'jenis_biaya' => 'Transportasi',
                'maksimal_biaya' => 600000,  // kelas ekonomi ekonomi
                'keterangan' => 'Transportasi kelas ekonomi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pegawai_level_id' => $levels['A5'],
                'jenis_biaya' => 'Makanan',
                'maksimal_biaya' => 80000,
                'keterangan' => 'Budget makan per hari',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Staff Biasa (A6)
            [
                'pegawai_level_id' => $levels['A6'],
                'jenis_biaya' => 'Hotel',
                'maksimal_biaya' => 300000,  // losmen/Hotel murah
                'keterangan' => 'Hotel losmen/Hotel murah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pegawai_level_id' => $levels['A6'],
                'jenis_biaya' => 'Transportasi',
                'maksimal_biaya' => 400000,  // Transportasi ekonomi
                'keterangan' => 'Transportasi kelas ekonomi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pegawai_level_id' => $levels['A6'],
                'jenis_biaya' => 'Makanan',
                'maksimal_biaya' => 60000,
                'keterangan' => 'Budget makan per hari',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

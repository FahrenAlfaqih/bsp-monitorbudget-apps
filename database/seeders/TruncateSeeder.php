<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TruncateSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('departemen')->truncate();
        DB::table('users')->truncate();

        Schema::enableForeignKeyConstraints();
        $this->call(UserSeeder::class);
    }
}

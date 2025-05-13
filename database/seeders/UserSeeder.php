<?php

namespace Database\Seeders;

use App\Models\Departemen;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $user = User::firstOrCreate([
            'email' => 'hcm@admindept.com',
        ], [
            'name' => 'Human Capital Management',
            'password' => Hash::make('inunghcm'),
            'role' => 'admindept_hcm',
        ]);

        Departemen::firstOrCreate([
            'user_id' => $user->id,
        ], [
            'nama' => 'Departemen HCM',
            'email' => $user->email,
            'bs_number' => '4',
        ]);

        User::firstOrCreate([
            'email' => 'bayu@tmhcm.com'
        ], [
            'name' => 'Bayu',
            'password' => Hash::make('bayutmhcm'),
            'role' => 'tmhcm'
        ]);
    }
}

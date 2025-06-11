<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'phone' => '1234567890',
            'status' => 'active',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Peminjam User',
            'username' => 'peminjam',
            'password' => Hash::make('password'),
            'phone' => '0987654321',
            'status' => 'active',
            'role' => 'peminjam',
        ]);
        User::create([
            'name' => 'Penyetuju dala',
            'username' => 'DALA',
            'password' => Hash::make('password'),
            'phone' => '1122334455',
            'status' => 'active',
            'role' => 'dala',
        ]);
        User::create([
            'name' => 'Penyetuju SDM',
            'username' => 'SDM',
            'password' => Hash::make('password'),
            'phone' => '1122334455',
            'status' => 'active',
            'role' => 'sdm',
        ]);
        User::create([
            'name' => 'Penyetuju User',
            'username' => 'Warek',
            'password' => Hash::make('password'),
            'phone' => '1122334455',
            'status' => 'active',
            'role' => 'warek',
        ]);
    }
}


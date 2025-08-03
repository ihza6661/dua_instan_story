<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::create([
            'full_name' => 'Admin Dua Insan',
            'email' => 'admin@duainsan.story',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Customer User
        User::create([
            'full_name' => 'Pelanggan Setia',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
    }
}

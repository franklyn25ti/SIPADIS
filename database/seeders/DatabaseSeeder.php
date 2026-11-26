<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'pemilik@sipadis.com'],
            [
                'name' => 'Pemilik Usaha (Admin)',
                'username' => 'pemilik',
                'password' => Hash::make('password'),
                'role' => 'pemilik',
            ]
        );

        User::updateOrCreate(
            ['email' => 'petugas@sipadis.com'],
            [
                'name' => 'Petugas Timbangan',
                'username' => 'petugas',
                'password' => Hash::make('password'),
                'role' => 'petugas',
            ]
        );
    }
}

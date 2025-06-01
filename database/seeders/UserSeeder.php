<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin users
        $admins = [
            ['name' => 'Admin Utama', 'email' => 'admin@example.com'],
            ['name' => 'Admin Sekunder', 'email' => 'admin2@example.com'],
        ];

        // Asesor users
        $asesors = [
            ['name' => 'Dr. Budi Prakoso', 'email' => 'budi.asesor@example.com'],
            ['name' => 'Ir. Siti Rahayu', 'email' => 'siti.asesor@example.com'],
            ['name' => 'Prof. Ahmad Dahlan', 'email' => 'ahmad.asesor@example.com'],
            ['name' => 'Dr. Dewi Sartika', 'email' => 'dewi.asesor@example.com'],
            ['name' => 'Ir. Raden Ajeng Kartini', 'email' => 'kartini.asesor@example.com'],
        ];

        // Asesi users
        $asesis = [
            ['name' => 'Budi Santoso', 'email' => 'budi@example.com'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@example.com'],
            ['name' => 'Eko Prasetyo', 'email' => 'eko@example.com'],
            ['name' => 'Fitri Handayani', 'email' => 'fitri@example.com'],
            ['name' => 'Gunawan Wibowo', 'email' => 'gunawan@example.com'],
            ['name' => 'Hadi Sucipto', 'email' => 'hadi@example.com'],
            ['name' => 'Indah Permata', 'email' => 'indah@example.com'],
            ['name' => 'Joko Widodo', 'email' => 'joko@example.com'],
            ['name' => 'Kartika Sari', 'email' => 'kartika@example.com'],
            ['name' => 'Linda Maharani', 'email' => 'linda@example.com'],
            ['name' => 'Muhammad Rizki', 'email' => 'rizki@example.com'],
            ['name' => 'Nina Zatulini', 'email' => 'nina@example.com'],
            ['name' => 'Oscar Putra', 'email' => 'oscar@example.com'],
        ];

        // Create users and assign roles
        foreach ($admins as $admin) {
            $user = User::firstOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'password' => Hash::make('password'),
                ]
            );
            $user->assignRole('admin');
        }

        foreach ($asesors as $asesor) {
            $user = User::firstOrCreate(
                ['email' => $asesor['email']],
                [
                    'name' => $asesor['name'],
                    'password' => Hash::make('password'),
                ]
            );
            $user->assignRole('asesor');
        }

        foreach ($asesis as $asesi) {
            $user = User::firstOrCreate(
                ['email' => $asesi['email']],
                [
                    'name' => $asesi['name'],
                    'password' => Hash::make('password'),
                ]
            );
            $user->assignRole('asesi');
        }
    }
}

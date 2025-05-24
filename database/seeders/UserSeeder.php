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
        $users = [
            ['name' => 'Admin User', 'email' => 'admin@example.com', 'password' => 'password', 'role' => 'admin'],
            ['name' => 'Asesi User', 'email' => 'asesi@example.com', 'password' => 'password', 'role' => 'asesi'],
            ['name' => 'Asesor User', 'email' => 'asesor@example.com', 'password' => 'password', 'role' => 'asesor'],
            ['name' => 'Budi Santoso', 'email' => 'budi@example.com', 'password' => 'password', 'role' => 'asesi'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@example.com', 'password' => 'password', 'role' => 'asesi'],
            ['name' => 'Eko Prasetyo', 'email' => 'eko@example.com', 'password' => 'password', 'role' => 'asesi'],
            ['name' => 'Fitri Handayani', 'email' => 'fitri@example.com', 'password' => 'password', 'role' => 'asesi'],
            ['name' => 'Gunawan Wibowo', 'email' => 'gunawan@example.com', 'password' => 'password', 'role' => 'asesi'],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                ]
            );

            $user->assignRole($data['role']);
        }
    }
}

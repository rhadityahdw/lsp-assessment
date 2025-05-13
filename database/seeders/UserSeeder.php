<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role_id' => 1, // admin
            ],
            [
                'name' => 'Asesi User',
                'email' => 'asesi@example.com',
                'password' => Hash::make('password'),
                'role_id' => 2, // asesi
            ],
            [
                'name' => 'Asesor User',
                'email' => 'asesor@example.com',
                'password' => Hash::make('password'),
                'role_id' => 3, // asesor
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }
    }
}
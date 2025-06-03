<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\UserRoleService;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{

    protected UserRoleService $roleService;

    public function __construct(UserRoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function run(): void
    {
        foreach (['admin', 'asesor', 'asesi'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $defaultPassword = Hash::make('password');

        $this->createUsers([
            ['name' => 'Admin Utama', 'email' => 'admin@example.com', 'role' => 'admin'],
            ['name' => 'Admin Sekunder', 'email' => 'admin2@example.com', 'role' => 'admin'],
        ], $defaultPassword);

        $this->createUsers([
            ['name' => 'Dr. Budi Prakoso', 'email' => 'budi.asesor@example.com', 'role' => 'asesor'],
            ['name' => 'Ir. Siti Rahayu', 'email' => 'siti.asesor@example.com', 'role' => 'asesor'],
            ['name' => 'Prof. Ahmad Dahlan', 'email' => 'ahmad.asesor@example.com', 'role' => 'asesor'],
            ['name' => 'Dr. Dewi Sartika', 'email' => 'dewi.asesor@example.com', 'role' => 'asesor'],
            ['name' => 'Ir. Raden Ajeng Kartini', 'email' => 'kartini.asesor@example.com', 'role' => 'asesor'],
        ], $defaultPassword);

        $this->createUsers([
            ['name' => 'Budi Santoso', 'email' => 'budi@example.com', 'role' => 'asesi'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@example.com', 'role' => 'asesi'],
            ['name' => 'Eko Prasetyo', 'email' => 'eko@example.com', 'role' => 'asesi'],
            ['name' => 'Fitri Handayani', 'email' => 'fitri@example.com', 'role' => 'asesi'],
            ['name' => 'Gunawan Wibowo', 'email' => 'gunawan@example.com', 'role' => 'asesi'],
            ['name' => 'Hadi Sucipto', 'email' => 'hadi@example.com', 'role' => 'asesi'],
            ['name' => 'Indah Permata', 'email' => 'indah@example.com', 'role' => 'asesi'],
            ['name' => 'Joko Widodo', 'email' => 'joko@example.com', 'role' => 'asesi'],
            ['name' => 'Kartika Sari', 'email' => 'kartika@example.com', 'role' => 'asesi'],
            ['name' => 'Linda Maharani', 'email' => 'linda@example.com', 'role' => 'asesi'],
            ['name' => 'Muhammad Rizki', 'email' => 'rizki@example.com', 'role' => 'asesi'],
            ['name' => 'Nina Zatulini', 'email' => 'nina@example.com', 'role' => 'asesi'],
            ['name' => 'Oscar Putra', 'email' => 'oscar@example.com', 'role' => 'asesi'],
        ], $defaultPassword);
    }

    protected function createUsers(array $users, string $hashedPassword): void
    {
        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => $hashedPassword]
            );

            $this->roleService->setRole($user, $data['role']);
        }
    }
}

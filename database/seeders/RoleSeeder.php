<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin'],
            ['name' => 'asesi'],
            ['name' => 'asesor'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insert($role);
        }
    }
}
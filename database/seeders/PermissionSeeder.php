<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Tambahkan permission baru untuk user management
        $permissions = [
            'view profile',
            'edit profile',
            
            'view scheme',
            'create scheme',
            'edit scheme',
            'delete scheme',
            
            'view unit',
            'create unit',
            'edit unit',
            'delete unit',
            
            'view assessment',
            'create assessment',
            'edit assessment',
            'delete assessment',
            'asesor assessment',
            
            'view pre-assessment',
            'create pre-assessment',
            'submit pre-assessment',
            'review pre-assessment',
            
            'view schedule',
            'create schedule',
            'edit schedule',
            'delete schedule',
            
            'view certificate',
            'upload certificate',
            'edit certificate',
            'download certificate',
            'delete certificate',
            
            'view notification',
            'send notification',
            
            'view dashboard',
            'view monitoring',
            'view user',
            'create user', 
            'edit user',
            'delete user',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Assign permissions to roles
        $admin = Role::findByName('admin', 'web');
        $asesi = Role::findByName('asesi', 'web');
        $asesor = Role::findByName('asesor', 'web');

        // Admin gets all permissions
        // Update admin permissions
        $admin->givePermissionTo([
            'view profile',
            'edit profile',

            'view user',
            'create user',
            'edit user',
            'delete user',
            
            'view scheme',
            'create scheme',
            'edit scheme',
            'delete scheme',
            
            'view unit',
            'create unit',
            'edit unit',
            'delete unit',
            
            'view assessment',
            'edit assessment',
            'delete assessment',
            
            'view pre-assessment',
            'create pre-assessment',
            'submit pre-assessment',
            'review pre-assessment',
            
            'view schedule',
            'create schedule',
            'edit schedule',
            'delete schedule',
            
            'view certificate',
            'upload certificate',
            'edit certificate',
            'download certificate',
            'delete certificate',
            
            'view notification',
            'send notification',
            
            'view dashboard',
            'view monitoring'
        ]);

        // Asesi permissions
        $asesi->givePermissionTo([
            'view profile',
            'edit profile',

            'view scheme',

            'view unit',

            'view assessment',

            'view pre-assessment',
            'create pre-assessment',
            'submit pre-assessment',

            'view schedule',

            'view certificate',
            'download certificate',

            'view notification',
        ]);

        // Asesor permissions
        $asesor->givePermissionTo([
            'view profile',
            'edit profile',

            'create assessment',
            'edit assessment',
            'delete assessment',
            'view assessment',

            'review pre-assessment',

            'view schedule',
            'edit schedule',

            'view certificate',
            'upload certificate',

            'view notification',
            'send notification',
            
            'view dashboard',
            'view monitoring'
        ]);
    }
}
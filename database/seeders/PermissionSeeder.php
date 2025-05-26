<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Create permissions
        $permissions = [
            // Profile permissions
            'view profile',
            'edit profile',
            
            // Scheme permissions
            'view scheme',
            'create scheme',
            'edit scheme',
            'delete scheme',
            
            // Unit permissions
            'view unit',
            'create unit',
            'edit unit',
            'delete unit',
            
            // Assessment permissions
            'view assessment',
            'create assessment',
            'edit assessment',
            'delete assessment',
            
            // Pre-assessment permissions
            'view pre-assessment',
            'create pre-assessment',
            'submit pre-assessment',
            'review pre-assessment',
            
            // Schedule permissions
            'view schedule',
            'create schedule',
            'edit schedule',
            'delete schedule',
            
            // Certificate permissions
            'view certificate',
            'upload certificate',
            'download certificate',
            
            // Notification permissions
            'view notification',
            'send notification',
            
            // Dashboard permissions
            'view dashboard',
            'view monitoring'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $admin = Role::findByName('admin');
        $asesi = Role::findByName('asesi');
        $asesor = Role::findByName('asesor');

        // Admin gets all permissions
        $admin->givePermissionTo($permissions);

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
            'view dashboard'
        ]);

        // Asesor permissions
        $asesor->givePermissionTo([
            'view profile',
            'edit profile',
            'view scheme',
            'view unit',
            'view assessment',
            'create assessment',
            'edit assessment',
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
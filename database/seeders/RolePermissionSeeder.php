<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Competition management
            'view competitions',
            'create competitions',
            'edit competitions',
            'delete competitions',

            // Registration management
            'view registrations',
            'create registrations',
            'edit registrations',
            'delete registrations',
            'confirm registrations',

            // Payment management
            'view payments',
            'process payments',
            'refund payments',

            // Submission management
            'view submissions',
            'create submissions',
            'edit submissions',
            'delete submissions',
            'judge submissions',

            // Scoring and judging
            'view scores',
            'create scores',
            'edit scores',
            'finalize scores',

            // Settings management
            'view settings',
            'edit settings',

            // Reports and analytics
            'view reports',
            'export data',

            // Finance management
            'view invoices',
            'manage finance',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Super Admin - has all permissions (only role to keep)
        $superAdmin = Role::firstOrCreate(['name' => 'superadmin']);
        $superAdmin->givePermissionTo(Permission::all());

        $this->command->info('Roles and permissions created successfully!');
    }
}

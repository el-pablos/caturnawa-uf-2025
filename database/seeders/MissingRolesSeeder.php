<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class MissingRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create missing roles
        $roles = [
            'admin' => 'Administrator - Can manage most system features',
            'finance' => 'Finance - Can manage financial reports and payment data',
            'juri' => 'Juri - Can evaluate submissions and manage competitions',
            'peserta' => 'Peserta - Can register for competitions and submit works'
        ];

        foreach ($roles as $roleName => $description) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $this->command->info("Role '{$roleName}' created/updated successfully.");
        }

        // Create basic permissions for each role
        $permissions = [
            // Admin permissions
            'manage-users' => 'Manage users',
            'manage-competitions' => 'Manage competitions',
            'manage-registrations' => 'Manage registrations',
            'manage-payments' => 'Manage payments',
            'view-reports' => 'View reports',

            // Finance permissions
            'view-financial-reports' => 'View financial reports',
            'manage-financial-data' => 'Manage financial data',
            'export-financial-data' => 'Export financial data',
            'view-payment-details' => 'View payment details',

            // Superadmin permissions
            'delete-paid-registrations' => 'Delete paid registrations',
            'superadmin-actions' => 'Perform superadmin actions',

            // Juri permissions
            'evaluate-submissions' => 'Evaluate submissions',
            'view-submissions' => 'View submissions',

            // Peserta permissions
            'register-competition' => 'Register for competitions',
            'submit-work' => 'Submit competition work',
            'view-own-data' => 'View own data'
        ];

        foreach ($permissions as $permissionName => $description) {
            Permission::firstOrCreate(['name' => $permissionName]);
            $this->command->info("Permission '{$permissionName}' created/updated successfully.");
        }

        // Assign permissions to roles
        $adminRole = Role::findByName('admin');
        $adminRole->givePermissionTo([
            'manage-users', 'manage-competitions', 'manage-registrations',
            'manage-payments', 'view-reports', 'view-submissions'
        ]);

        $financeRole = Role::findByName('finance');
        $financeRole->givePermissionTo([
            'manage-competitions', 'manage-registrations', 'manage-payments',
            'view-reports', 'view-submissions', 'view-financial-reports',
            'manage-financial-data', 'export-financial-data', 'view-payment-details'
        ]);

        $juriRole = Role::findByName('juri');
        $juriRole->givePermissionTo([
            'evaluate-submissions', 'view-submissions', 'view-reports'
        ]);

        $pesertaRole = Role::findByName('peserta');
        $pesertaRole->givePermissionTo([
            'register-competition', 'submit-work', 'view-own-data'
        ]);

        $this->command->info('All roles and permissions assigned successfully.');
    }
}

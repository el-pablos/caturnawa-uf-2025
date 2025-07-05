<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * Seeder untuk roles dan permissions
 * 
 * Membuat role dan permission yang diperlukan sistem
 */
class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat permissions
        $permissions = [
            // User Management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            
            // Competition Management
            'competitions.view',
            'competitions.create',
            'competitions.edit',
            'competitions.delete',
            'competitions.toggle-status',
            
            // Registration Management
            'registrations.view',
            'registrations.edit',
            'registrations.delete',
            'registrations.export',
            
            // Payment Management
            'payments.view',
            'payments.refund',
            
            // Submission Management
            'submissions.view',
            'submissions.edit',
            'submissions.delete',
            
            // Score Management
            'scores.view',
            'scores.create',
            'scores.edit',
            'scores.delete',
            
            // Report Management
            'reports.view',
            'reports.export',
            
            // System Settings
            'settings.view',
            'settings.edit',
            
            // Dashboard Access
            'dashboard.admin',
            'dashboard.jury',
            'dashboard.participant',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Buat roles dan assign permissions

        // Super Admin - Full Access
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->syncPermissions(Permission::all());

        // Admin - Limited Admin Access
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->syncPermissions([
            'users.view',
            'competitions.view',
            'competitions.toggle-status',
            'registrations.view',
            'registrations.export',
            'payments.view',
            'submissions.view',
            'scores.view',
            'reports.view',
            'reports.export',
            'dashboard.admin',
        ]);

        // Juri - Scoring Access
        $jury = Role::firstOrCreate(['name' => 'Juri']);
        $jury->syncPermissions([
            'competitions.view',
            'submissions.view',
            'scores.view',
            'scores.create',
            'scores.edit',
            'dashboard.jury',
        ]);

        // Peserta - Participant Access
        $participant = Role::firstOrCreate(['name' => 'Peserta']);
        $participant->syncPermissions([
            'competitions.view',
            'dashboard.participant',
        ]);
    }
}

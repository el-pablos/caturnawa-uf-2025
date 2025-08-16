<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdminSeeder extends Seeder
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

            // Superadmin exclusive permissions
            'delete-paid-registrations',
            'superadmin-actions',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create superadmin role
        $superAdmin = Role::firstOrCreate(['name' => 'superadmin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Create Super Admin user
        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@unasfest.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password123'),
                'phone' => '081234567890',
                'participant_status' => 'Mahasiswa Unas',
                'institution' => 'Caturnawa UNAS FEST 2025',
                'student_id' => 'SUPERADMIN001',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        
        if (!$superAdminUser->hasRole('superadmin')) {
            $superAdminUser->assignRole('superadmin');
        }

        $this->command->info('Super Admin created successfully!');
        $this->command->info('Email: superadmin@unasfest.com');
        $this->command->info('Password: password123');
    }
}

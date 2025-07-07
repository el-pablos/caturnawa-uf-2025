<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Competition;
use App\Models\Registration;

/**
 * Seeder untuk testing sistem auto lock
 * 
 * Membuat registrasi sample untuk menguji sistem auto lock
 */
class AutoLockTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get peserta user
        $peserta = User::role('peserta')->where('email', 'peserta1@unasfest.com')->first();
        
        if (!$peserta) {
            $this->command->error('Peserta user not found. Please run UserSeeder first.');
            return;
        }

        // Get DCC competition
        $dccCompetition = Competition::where('slug', 'dcc')->first();
        
        if (!$dccCompetition) {
            $this->command->error('DCC competition not found. Please run UnasFestCompetitionSeeder first.');
            return;
        }

        // Check if registration already exists
        $existingRegistration = Registration::where('user_id', $peserta->id)
            ->where('competition_id', $dccCompetition->id)
            ->first();

        if ($existingRegistration) {
            $this->command->info('Test registration already exists for peserta1@unasfest.com in DCC competition.');
            return;
        }

        // Create test registration
        $registration = Registration::create([
            'user_id' => $peserta->id,
            'competition_id' => $dccCompetition->id,
            'registration_number' => 'AUTOLOCK-TEST-001',
            'team_name' => 'Test Team Auto Lock',
            'team_members' => [
                [
                    'name' => $peserta->name,
                    'email' => $peserta->email,
                    'phone' => '081234567890',
                    'foto' => null,
                ],
                [
                    'name' => 'Test Member 2',
                    'email' => 'testmember2@example.com',
                    'phone' => '081234567891',
                    'foto' => null,
                ]
            ],
            'logo_instansi' => null,
            'institution' => 'Test University Auto Lock',
            'phone' => '081234567890',
            'gender' => 'male',
            'education_level' => 'S1',
            'emergency_contact' => 'Emergency Contact',
            'emergency_phone' => '081234567892',
            'special_needs' => null,
            'amount' => $dccCompetition->getCurrentPriceAttribute(),
            'status' => 'confirmed',
            'registered_at' => now(),
            'confirmed_at' => now(),
        ]);

        $this->command->info('Test registration created successfully!');
        $this->command->info('User: ' . $peserta->email);
        $this->command->info('Competition: ' . $dccCompetition->name);
        $this->command->info('Registration Number: ' . $registration->registration_number);
        $this->command->info('Status: ' . $registration->status);
        $this->command->info('');
        $this->command->info('Now you can test auto lock by:');
        $this->command->info('1. Login as peserta1@unasfest.com');
        $this->command->info('2. Try to register for another competition (KDBI, SPC, etc.)');
        $this->command->info('3. You should see auto lock warning and cannot register');
    }
}

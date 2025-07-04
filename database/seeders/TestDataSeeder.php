<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\Submission;
use App\Models\Score;
use App\Models\Payment;
use Spatie\Permission\Models\Role;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "🧪 Creating test data for UNAS Fest 2025...\n";

        // Create test competitions
        $competitions = [
            [
                'name' => 'Web Development Competition',
                'slug' => 'web-development-competition',
                'category' => 'technology',
                'description' => 'Kompetisi pengembangan website terbaik',
                'price' => 150000,
                'is_active' => true,
                'show_leaderboard' => true,
                'is_team_competition' => true,
                'max_team_members' => 3,
                'min_team_members' => 1,
                'registration_start' => now()->subDays(30),
                'registration_end' => now()->addDays(30),
                'competition_start' => now()->addDays(35),
                'competition_end' => now()->addDays(45),
            ],
            [
                'name' => 'UI/UX Design Challenge',
                'slug' => 'ui-ux-design-challenge',
                'category' => 'technology',
                'description' => 'Kompetisi desain antarmuka pengguna terbaik',
                'price' => 100000,
                'is_active' => true,
                'show_leaderboard' => true,
                'is_team_competition' => false,
                'max_team_members' => 1,
                'min_team_members' => 1,
                'registration_start' => now()->subDays(30),
                'registration_end' => now()->addDays(30),
                'competition_start' => now()->addDays(35),
                'competition_end' => now()->addDays(45),
            ],
            [
                'name' => 'Mobile App Development',
                'slug' => 'mobile-app-development',
                'category' => 'technology',
                'description' => 'Kompetisi pengembangan aplikasi mobile',
                'price' => 200000,
                'is_active' => true,
                'show_leaderboard' => true,
                'is_team_competition' => true,
                'max_team_members' => 4,
                'min_team_members' => 2,
                'registration_start' => now()->subDays(30),
                'registration_end' => now()->addDays(30),
                'competition_start' => now()->addDays(35),
                'competition_end' => now()->addDays(45),
            ]
        ];

        foreach ($competitions as $compData) {
            Competition::updateOrCreate(
                ['name' => $compData['name']],
                $compData
            );
        }

        echo "✅ Created " . count($competitions) . " test competitions\n";

        // Create test participants
        $pesertaRole = Role::where('name', 'Peserta')->first();
        $juriRole = Role::where('name', 'Juri')->first();

        $participants = [
            [
                'name' => 'Ahmad Rizki',
                'email' => 'ahmad.rizki@test.com',
                'institution' => 'Universitas Indonesia',
                'student_id' => '2021001',
                'is_active' => true,
            ],
            [
                'name' => 'Sari Dewi',
                'email' => 'sari.dewi@test.com',
                'institution' => 'Institut Teknologi Bandung',
                'student_id' => '2021002',
                'is_active' => true,
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@test.com',
                'institution' => 'Universitas Gadjah Mada',
                'student_id' => '2021003',
                'is_active' => true,
            ],
            [
                'name' => 'Maya Putri',
                'email' => 'maya.putri@test.com',
                'institution' => 'Universitas Nasional',
                'student_id' => '2021004',
                'is_active' => true,
            ],
            [
                'name' => 'Eko Prasetyo',
                'email' => 'eko.prasetyo@test.com',
                'institution' => 'Institut Teknologi Sepuluh Nopember',
                'student_id' => '2021005',
                'is_active' => true,
            ]
        ];

        foreach ($participants as $participantData) {
            $user = User::updateOrCreate(
                ['email' => $participantData['email']],
                array_merge($participantData, [
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                ])
            );
            
            if (!$user->hasRole('Peserta')) {
                $user->assignRole($pesertaRole);
            }
        }

        echo "✅ Created " . count($participants) . " test participants\n";

        // Create test registrations and submissions
        $competitions = Competition::where('is_active', true)->get();
        $users = User::whereHas('roles', function($q) {
            $q->where('name', 'Peserta');
        })->where('is_active', true)->get();

        $submissionTitles = [
            'E-Commerce Platform Inovatif',
            'Sistem Manajemen Sekolah',
            'Aplikasi Kesehatan Mental',
            'Platform Pembelajaran Online',
            'Sistem Monitoring IoT',
            'Aplikasi Fintech Terdepan',
            'Portal Berita Digital',
            'Sistem Inventory Management',
            'Aplikasi Food Delivery',
            'Platform Crowdfunding'
        ];

        $registrationCount = 0;
        $submissionCount = 0;

        foreach ($competitions as $competition) {
            // Register some participants
            $selectedUsers = $users->random(min(4, $users->count()));
            
            foreach ($selectedUsers as $user) {
                $registration = Registration::create([
                    'user_id' => $user->id,
                    'competition_id' => $competition->id,
                    'registration_number' => 'REG-' . strtoupper(substr($competition->slug, 0, 3)) . '-' . time() . '-' . $registrationCount,
                    'team_name' => $competition->is_team_competition ? 'Team ' . $user->name : null,
                    'status' => 'confirmed',
                    'institution' => $user->institution,
                    'phone' => '081234567890',
                    'amount' => $competition->price,
                ]);

                $registrationCount++;

                // Create payment
                Payment::create([
                    'registration_id' => $registration->id,
                    'amount' => $competition->price,
                    'gross_amount' => $competition->price,
                    'status' => 'success',
                    'is_confirmed' => true,
                    'confirmed_at' => now(),
                    'confirmed_by' => 1, // Admin
                    'paid_at' => now(),
                ]);

                // Create submission
                $submission = Submission::create([
                    'registration_id' => $registration->id,
                    'title' => $submissionTitles[array_rand($submissionTitles)],
                    'description' => 'Deskripsi lengkap untuk karya ' . $submissionTitles[array_rand($submissionTitles)],
                    'status' => 'submitted',
                    'is_final' => true,
                    'submitted_at' => now(),
                ]);

                $submissionCount++;

                // Create scores from juries
                $juries = User::whereHas('roles', function($q) {
                    $q->where('name', 'Juri');
                })->get();

                foreach ($juries as $jury) {
                    // Check if jury is assigned to this competition
                    $isAssigned = \DB::table('competition_juries')
                        ->where('competition_id', $competition->id)
                        ->where('user_id', $jury->id)
                        ->exists();

                    if ($isAssigned) {
                        Score::create([
                            'competition_id' => $competition->id,
                            'registration_id' => $registration->id,
                            'jury_id' => $jury->id,
                            'criteria_scores' => [
                                'innovation' => rand(70, 95),
                                'technical' => rand(75, 90),
                                'presentation' => rand(80, 95),
                                'usability' => rand(70, 90),
                            ],
                            'total_score' => rand(75, 92),
                            'comments' => 'Karya yang sangat baik dengan implementasi yang solid.',
                            'is_final' => true,
                        ]);
                    }
                }
            }
        }

        echo "✅ Created {$registrationCount} test registrations\n";
        echo "✅ Created {$submissionCount} test submissions with scores\n";

        // Update competition show_leaderboard
        Competition::where('is_active', true)->update(['show_leaderboard' => true]);

        echo "✅ Enabled leaderboard for all active competitions\n";
        echo "\n🎉 Test data creation completed!\n";
        echo "📊 You can now test:\n";
        echo "   - Leaderboard: /leaderboard\n";
        echo "   - Admin Participants: /admin/participants\n";
        echo "   - Admin Payment Confirmation: /admin/payment-confirmation\n";
        echo "   - Admin User Activation: /admin/user-activation\n";
        echo "   - Admin Settings: /admin/settings\n";
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\Submission;
use App\Models\Score;
use App\Models\VisitorStatistic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Faker\Factory as Faker;

/**
 * Seeder untuk data dummy agar website terlihat ramai
 */
class DummyDataSeeder extends Seeder
{
    /**
     * Indonesian names for realistic data
     */
    private $firstNames = [
        'Ahmad', 'Budi', 'Citra', 'Dewi', 'Eka', 'Fajar', 'Gita', 'Hendra', 'Indah', 'Joko',
        'Kartika', 'Lestari', 'Muhammad', 'Novi', 'Oscar', 'Putri', 'Qori', 'Rizki', 'Sari', 'Taufik',
        'Umi', 'Vina', 'Wulan', 'Yudi', 'Zahra', 'Agus', 'Bambang', 'Cindy', 'Dimas', 'Endang',
        'Fitri', 'Galih', 'Hani', 'Irfan', 'Jasmine', 'Kevin', 'Lisa', 'Mira', 'Nadia', 'Omar',
        'Puspita', 'Rendra', 'Sinta', 'Teguh', 'Utami', 'Vera', 'Wira', 'Yoga', 'Zara', 'Anisa',
        'Bayu', 'Chelsea', 'Denny', 'Ella', 'Farel', 'Grace', 'Hafiz', 'Intan', 'Jihan', 'Kirana',
        'Laras', 'Melati', 'Naufal', 'Olivia', 'Pandu', 'Queen', 'Raffi', 'Salsa', 'Tiara', 'Umar'
    ];

    private $lastNames = [
        'Pratama', 'Wijaya', 'Kusuma', 'Santoso', 'Hidayat', 'Putra', 'Sari', 'Wibowo', 'Setiawan', 'Nugroho',
        'Rahayu', 'Permana', 'Handoko', 'Gunawan', 'Saputra', 'Lestari', 'Susanto', 'Kurniawan', 'Siregar', 'Hutapea',
        'Simanjuntak', 'Manurung', 'Tampubolon', 'Sitorus', 'Nasution', 'Harahap', 'Lubis', 'Daulay', 'Rangkuti', 'Hasibuan',
        'Panjaitan', 'Simatupang', 'Napitupulu', 'Siagian', 'Simbolon', 'Aritonang', 'Pardede', 'Hutabarat', 'Situmorang', 'Pasaribu'
    ];

    private $universities = [
        'Universitas Nasional', 'Universitas Indonesia', 'Institut Teknologi Bandung', 'Universitas Gadjah Mada',
        'Institut Pertanian Bogor', 'Universitas Airlangga', 'Universitas Brawijaya', 'Universitas Padjadjaran',
        'Universitas Diponegoro', 'Universitas Sebelas Maret', 'Universitas Hasanuddin', 'Universitas Sumatera Utara',
        'Universitas Andalas', 'Universitas Udayana', 'Universitas Lampung', 'Universitas Jember',
        'Universitas Sriwijaya', 'Universitas Riau', 'Universitas Negeri Jakarta', 'Universitas Negeri Yogyakarta',
        'Universitas Negeri Malang', 'Universitas Negeri Semarang', 'Universitas Negeri Surabaya', 'Institut Teknologi Sepuluh Nopember',
        'Universitas Trisakti', 'Universitas Tarumanagara', 'Universitas Bina Nusantara', 'Universitas Pelita Harapan',
        'Universitas Atma Jaya', 'Universitas Katolik Parahyangan', 'Universitas Kristen Petra', 'Universitas Islam Indonesia',
        'Universitas Muhammadiyah Jakarta', 'Universitas Muhammadiyah Yogyakarta', 'Universitas Ahmad Dahlan', 'Universitas Mercu Buana'
    ];

    private $teamNames = [
        'Alpha Team', 'Bravo Squad', 'Code Warriors', 'Digital Ninja', 'Echo Force',
        'Future Builders', 'Genius Minds', 'Hackathon Heroes', 'Innovation Lab', 'Java Masters',
        'Knowledge Seekers', 'Logic Legends', 'Mega Coders', 'Neural Networks', 'Omega Tech',
        'Python Pioneers', 'Quantum Bits', 'Rising Stars', 'Smart Solutions', 'Tech Titans',
        'Unity Devs', 'Virtual Visionaries', 'Web Wizards', 'Xpert Coders', 'Young Innovators',
        'Zero Bugs', 'Agile Aces', 'Binary Bosses', 'Creative Coders', 'Data Dynamos',
        'Elegant Engineers', 'Full Stack Force', 'Game Changers', 'Hybrid Hackers', 'Idea Factory',
        'Junior Devs', 'Kernel Kings', 'Linux Lords', 'Mobile Masters', 'Node Ninjas'
    ];

    private $faker;

    public function run(): void
    {
        $this->faker = Faker::create('id_ID');
        
        $this->command->info('🎭 Creating dummy data to make the website look active...');

        // Create dummy users (participants)
        $this->createDummyUsers();

        // Create dummy registrations
        $this->createDummyRegistrations();

        // Create visitor statistics
        $this->createVisitorStatistics();

        $this->command->info('✅ Dummy data seeding completed!');
    }

    private function createDummyUsers(): void
    {
        $this->command->info('👥 Creating 150 dummy users...');

        $users = [];
        $usedEmails = [];

        for ($i = 0; $i < 150; $i++) {
            $firstName = $this->firstNames[array_rand($this->firstNames)];
            $lastName = $this->lastNames[array_rand($this->lastNames)];
            $name = $firstName . ' ' . $lastName;
            
            // Generate unique email
            $baseEmail = strtolower($firstName . '.' . $lastName);
            $email = $baseEmail . rand(1, 999) . '@' . $this->faker->randomElement(['gmail.com', 'yahoo.com', 'outlook.com', 'student.unas.ac.id', 'mail.ugm.ac.id', 'ui.ac.id']);
            
            while (in_array($email, $usedEmails)) {
                $email = $baseEmail . rand(1, 9999) . '@' . $this->faker->randomElement(['gmail.com', 'yahoo.com', 'outlook.com']);
            }
            $usedEmails[] = $email;

            $users[] = [
                'name' => $name,
                'email' => $email,
                'email_verified_at' => now()->subDays(rand(1, 60)),
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'phone' => '08' . rand(1, 9) . rand(10000000, 99999999),
                'institution' => $this->universities[array_rand($this->universities)],
                'created_at' => now()->subDays(rand(1, 90)),
                'updated_at' => now()->subDays(rand(0, 30)),
            ];
        }

        // Batch insert for performance
        foreach (array_chunk($users, 50) as $chunk) {
            User::insert($chunk);
        }

        $this->command->info('   ✓ Created 150 dummy users');
    }

    private function createDummyRegistrations(): void
    {
        $this->command->info('📝 Creating dummy registrations...');

        $competitions = Competition::all();
        $users = User::where('email', 'not like', '%admin%')
                    ->where('email', 'not like', '%super%')
                    ->get();

        if ($competitions->isEmpty()) {
            $this->command->warn('   ⚠ No competitions found. Skipping registrations.');
            return;
        }

        $registrations = [];
        $payments = [];
        $regCounter = 1;

        foreach ($competitions as $competition) {
            // Random number of registrations per competition (20-60)
            $numRegistrations = rand(20, 60);
            $selectedUsers = $users->random(min($numRegistrations, $users->count()));

            foreach ($selectedUsers as $user) {
                $isTeam = $competition->is_team_competition ?? rand(0, 1);
                $teamName = $isTeam ? $this->teamNames[array_rand($this->teamNames)] . ' ' . rand(1, 99) : null;
                
                $statuses = ['pending', 'confirmed', 'paid', 'paid', 'paid', 'paid']; // More paid for realistic look
                $status = $statuses[array_rand($statuses)];
                
                $participantCategories = ['unas_student', 'external_student', 'external_student', 'external_student'];
                $participantCategory = $participantCategories[array_rand($participantCategories)];
                
                $pricingPhases = ['early_bird', 'regular', 'regular', 'regular', 'late'];
                $pricingPhase = $pricingPhases[array_rand($pricingPhases)];

                // Calculate price based on category and phase
                $basePrice = $participantCategory === 'unas_student' ? 150000 : 200000;
                if ($pricingPhase === 'early_bird') {
                    $basePrice = $basePrice * 0.8; // 20% discount
                } elseif ($pricingPhase === 'late') {
                    $basePrice = $basePrice * 1.2; // 20% extra
                }

                $registeredAt = now()->subDays(rand(1, 60));
                $confirmedAt = in_array($status, ['confirmed', 'paid']) ? $registeredAt->copy()->addHours(rand(1, 48)) : null;

                $regNumber = 'UF' . date('Y') . '-' . str_pad($regCounter, 5, '0', STR_PAD_LEFT);
                $ticketCode = 'TICKET-' . strtoupper(Str::random(8));

                $registrations[] = [
                    'user_id' => $user->id,
                    'competition_id' => $competition->id,
                    'registration_number' => $regNumber,
                    'team_name' => $teamName,
                    'team_members' => $isTeam ? json_encode($this->generateTeamMembers()) : null,
                    'institution' => $user->institution,
                    'phone' => $user->phone,
                    'gender' => $this->faker->randomElement(['male', 'female']),
                    'education_level' => 'university',
                    'participant_category' => $participantCategory,
                    'pricing_phase' => $pricingPhase,
                    'amount' => $basePrice,
                    'original_price' => $basePrice,
                    'status' => $status,
                    'is_locked' => false,
                    'lock_reason' => null,
                    'locked_at' => null,
                    'locked_by' => null,
                    'registered_at' => $registeredAt,
                    'confirmed_at' => $confirmedAt,
                    'cancelled_at' => null,
                    'cancelled_reason' => null,
                    'reopened_at' => null,
                    'reopened_by' => null,
                    'ticket_code' => $ticketCode,
                    'qr_code' => null,
                    'dynamic_data' => null,
                    'created_at' => $registeredAt,
                    'updated_at' => $confirmedAt ?? $registeredAt,
                ];

                // Create payment for paid registrations
                if ($status === 'paid') {
                    $payments[] = [
                        'registration_id' => null, // Will be updated after registration insert
                        'order_id' => 'ORDER-' . strtoupper(Str::random(12)),
                        'amount' => $basePrice,
                        'gross_amount' => $basePrice,
                        'payment_type' => $this->faker->randomElement(['bank_transfer', 'gopay', 'shopeepay', 'qris']),
                        'transaction_status' => 'settlement',
                        'transaction_id' => 'TXN-' . strtoupper(Str::random(16)),
                        'payment_code' => null,
                        'pdf_url' => null,
                        'paid_at' => $confirmedAt,
                        'expired_at' => $confirmedAt?->copy()->addDays(1),
                        'created_at' => $registeredAt,
                        'updated_at' => $confirmedAt,
                    ];
                }

                $regCounter++;
            }
        }

        // Batch insert registrations
        foreach (array_chunk($registrations, 50) as $chunk) {
            Registration::insert($chunk);
        }

        // Now create payments with correct registration IDs
        $paidRegistrations = Registration::where('status', 'paid')->get();
        $paymentData = [];
        
        foreach ($paidRegistrations as $reg) {
            $paymentData[] = [
                'registration_id' => $reg->id,
                'order_id' => 'ORDER-' . strtoupper(Str::random(12)),
                'amount' => $reg->amount,
                'gross_amount' => $reg->amount,
                'payment_type' => $this->faker->randomElement(['bank_transfer', 'gopay', 'shopeepay', 'qris']),
                'transaction_status' => 'settlement',
                'transaction_id' => 'TXN-' . strtoupper(Str::random(16)),
                'payment_code' => null,
                'pdf_url' => null,
                'paid_at' => $reg->confirmed_at,
                'expired_at' => $reg->confirmed_at?->copy()->addDays(1),
                'created_at' => $reg->created_at,
                'updated_at' => $reg->confirmed_at ?? $reg->created_at,
            ];
        }

        foreach (array_chunk($paymentData, 50) as $chunk) {
            Payment::insert($chunk);
        }

        $totalRegs = count($registrations);
        $this->command->info("   ✓ Created {$totalRegs} dummy registrations");
        $this->command->info("   ✓ Created " . count($paymentData) . " dummy payments");
    }

    private function generateTeamMembers(): array
    {
        $members = [];
        $numMembers = rand(2, 4);

        for ($i = 0; $i < $numMembers; $i++) {
            $firstName = $this->firstNames[array_rand($this->firstNames)];
            $lastName = $this->lastNames[array_rand($this->lastNames)];
            
            $members[] = [
                'name' => $firstName . ' ' . $lastName,
                'email' => strtolower($firstName) . '.' . strtolower($lastName) . rand(1, 99) . '@gmail.com',
                'phone' => '08' . rand(1, 9) . rand(10000000, 99999999),
                'role' => $i === 0 ? 'Ketua Tim' : 'Anggota',
            ];
        }

        return $members;
    }

    private function createVisitorStatistics(): void
    {
        $this->command->info('📊 Creating visitor statistics...');

        // Check if table exists
        if (!\Schema::hasTable('visitor_statistics')) {
            $this->command->warn('   ⚠ visitor_statistics table not found. Skipping.');
            return;
        }

        $stats = [];
        $pages = ['/', '/competitions', '/about', '/contact', '/faq', '/register'];

        // Create stats for last 90 days
        for ($i = 90; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            
            foreach ($pages as $page) {
                // More visitors on weekdays
                $dayOfWeek = now()->subDays($i)->dayOfWeek;
                $baseVisits = ($dayOfWeek >= 1 && $dayOfWeek <= 5) ? rand(50, 200) : rand(20, 80);
                
                // Increase visitors closer to current date
                $multiplier = 1 + ((90 - $i) / 90);
                $visits = (int)($baseVisits * $multiplier);

                $stats[] = [
                    'page' => $page,
                    'visits' => $visits,
                    'unique_visitors' => (int)($visits * 0.7),
                    'date' => $date,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Batch insert
        try {
            foreach (array_chunk($stats, 100) as $chunk) {
                VisitorStatistic::insert($chunk);
            }
            $this->command->info('   ✓ Created visitor statistics for 90 days');
        } catch (\Exception $e) {
            $this->command->warn('   ⚠ Could not create visitor statistics: ' . $e->getMessage());
        }
    }
}

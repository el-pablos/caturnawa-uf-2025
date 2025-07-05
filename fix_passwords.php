<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel properly
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== FIXING USER PASSWORDS ===\n";

$users = [
    'superadmin@unasfest.com' => 'Super Administrator',
    'admin1@unasfest.com' => 'Admin User 1',
    'admin2@unasfest.com' => 'Admin User 2',
    'admin3@unasfest.com' => 'Admin User 3',
    'admin4@unasfest.com' => 'Admin User 4',
    'admin5@unasfest.com' => 'Admin User 5',
    'juri1@unasfest.com' => 'Dr. Ahmad Wijaya',
    'juri2@unasfest.com' => 'Prof. Siti Nurhaliza',
    'juri3@unasfest.com' => 'Dr. Budi Santoso',
    'juri4@unasfest.com' => 'Prof. Maya Sari',
    'juri5@unasfest.com' => 'Dr. Rizki Pratama',
    'peserta1@unasfest.com' => 'Andi Pratama',
    'peserta2@unasfest.com' => 'Sari Dewi',
    'peserta3@unasfest.com' => 'Budi Setiawan',
    'peserta4@unasfest.com' => 'Maya Putri',
    'peserta5@unasfest.com' => 'Rizki Firmansyah',
];

$password = 'password123';
$hashedPassword = \Illuminate\Support\Facades\Hash::make($password);

foreach ($users as $email => $name) {
    try {
        $user = \App\Models\User::where('email', $email)->first();
        if ($user) {
            $user->password = $hashedPassword;
            $user->save();
            echo "✅ Updated password for: {$email} ({$name})\n";
        } else {
            echo "❌ User not found: {$email}\n";
        }
    } catch (Exception $e) {
        echo "❌ Error updating {$email}: " . $e->getMessage() . "\n";
    }
}

echo "\n=== PASSWORD UPDATE COMPLETE ===\n";
echo "All users now have password: {$password}\n";

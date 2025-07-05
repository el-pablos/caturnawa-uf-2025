<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel properly
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== PASSWORD DEBUGGING ===\n";

$user = \App\Models\User::where('email', 'superadmin@unasfest.com')->first();
$password = 'password123';

echo "User: {$user->name}\n";
echo "Email: {$user->email}\n";
echo "Password hash: {$user->password}\n";
echo "Test password: {$password}\n";

// Test dengan Hash::check
$check1 = \Illuminate\Support\Facades\Hash::check($password, $user->password);
echo "Hash::check result: " . ($check1 ? 'true' : 'false') . "\n";

// Test dengan password_verify
$check2 = password_verify($password, $user->password);
echo "password_verify result: " . ($check2 ? 'true' : 'false') . "\n";

// Test buat hash baru dan langsung verify
$newHash = \Illuminate\Support\Facades\Hash::make($password);
echo "New hash: {$newHash}\n";
$check3 = \Illuminate\Support\Facades\Hash::check($password, $newHash);
echo "New hash check: " . ($check3 ? 'true' : 'false') . "\n";

// Update dengan hash baru
$user->password = $newHash;
$user->save();
echo "Password updated with new hash\n";

// Test lagi
$user->refresh();
$check4 = \Illuminate\Support\Facades\Hash::check($password, $user->password);
echo "Final check: " . ($check4 ? 'true' : 'false') . "\n";

echo "\n=== TESTING LOGIN ATTEMPT ===\n";

// Test login attempt
$credentials = [
    'email' => 'superadmin@unasfest.com',
    'password' => 'password123'
];

if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
    echo "✅ Login attempt successful\n";
    $loggedUser = \Illuminate\Support\Facades\Auth::user();
    echo "Logged in as: {$loggedUser->name}\n";
    echo "Roles: " . implode(', ', $loggedUser->getRoleNames()->toArray()) . "\n";
    \Illuminate\Support\Facades\Auth::logout();
} else {
    echo "❌ Login attempt failed\n";
}

echo "\n=== DEBUGGING COMPLETE ===\n";

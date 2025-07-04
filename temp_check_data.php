<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\Competition;
use App\Models\User;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DATABASE STATUS ===\n";
echo "Users: " . User::count() . "\n";
echo "Competitions: " . Competition::count() . "\n\n";

echo "=== COMPETITIONS ===\n";
foreach (Competition::all() as $competition) {
    echo "- " . $competition->name . "\n";
    echo "  Category: " . $competition->category . "\n";
    echo "  Price: Rp " . number_format($competition->price, 0, ',', '.') . "\n";
    echo "  Registration: " . $competition->registration_start->format('Y-m-d') . " - " . $competition->registration_end->format('Y-m-d') . "\n";
    echo "\n";
}

echo "=== USERS ===\n";
foreach (User::all() as $user) {
    echo "- " . $user->name . " (" . $user->email . ")\n";
    echo "  Roles: " . $user->roles->pluck('name')->implode(', ') . "\n";
    echo "\n";
}

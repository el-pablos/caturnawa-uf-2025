<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Competition;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== ASSIGNING JURIES TO COMPETITIONS ===\n\n";

// Get all competitions
$competitions = Competition::all();
$juries = User::role('Juri')->get();

if ($juries->count() === 0) {
    echo "No juries found. Please create users with 'Juri' role first.\n";
    exit;
}

echo "Available juries:\n";
foreach ($juries as $jury) {
    echo "- {$jury->name} (ID: {$jury->id})\n";
}
echo "\n";

// Assign all juries to all competitions
foreach ($competitions as $competition) {
    echo "Assigning juries to '{$competition->name}'...\n";
    
    // First, clear existing assignments
    $competition->juries()->detach();
    
    // Then assign all juries
    foreach ($juries as $jury) {
        $competition->juries()->attach($jury->id);
        echo "  - Assigned {$jury->name}\n";
    }
    
    echo "  Total juries assigned: " . $competition->juries()->count() . "\n\n";
}

echo "=== VERIFICATION ===\n";
$competitions = Competition::with('juries')->get();
foreach ($competitions as $competition) {
    echo "Competition: {$competition->name}\n";
    echo "Assigned juries: " . $competition->juries->count() . "\n";
    foreach ($competition->juries as $jury) {
        echo "  - {$jury->name}\n";
    }
    echo "\n";
}

echo "Done! All juries have been assigned to all competitions.\n";

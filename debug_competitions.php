<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DEBUG COMPETITIONS PAGE ===\n";

try {
    // Test 1: Check if competitions exist
    echo "1. Testing Competition model...\n";
    $competitions = App\Models\Competition::active()->get();
    echo "   Found " . $competitions->count() . " active competitions\n";
    
    // Test 2: Check grouping
    echo "2. Testing grouping by category...\n";
    $grouped = $competitions->groupBy('category');
    echo "   Categories found: " . $grouped->keys()->implode(', ') . "\n";
    
    // Test 3: Check registrations relationship
    echo "3. Testing registrations relationship...\n";
    foreach ($competitions as $comp) {
        echo "   Competition '{$comp->name}': " . $comp->registrations->count() . " registrations\n";
    }
    
    // Test 4: Check CATEGORIES constant
    echo "4. Testing CATEGORIES constant...\n";
    foreach ($grouped->keys() as $category) {
        $displayName = App\Models\Competition::CATEGORIES[$category] ?? 'NOT FOUND';
        echo "   Category '{$category}': {$displayName}\n";
    }
    
    // Test 5: Check required fields
    echo "5. Testing required fields...\n";
    foreach ($competitions as $comp) {
        echo "   Competition '{$comp->name}':\n";
        echo "     - slug: " . ($comp->slug ?? 'NULL') . "\n";
        echo "     - prize_amount: " . ($comp->prize_amount ?? 'NULL') . "\n";
        echo "     - registration_start: " . ($comp->registration_start ? $comp->registration_start->format('Y-m-d') : 'NULL') . "\n";
        echo "     - registration_end: " . ($comp->registration_end ? $comp->registration_end->format('Y-m-d') : 'NULL') . "\n";
    }
    
    // Test 6: Test controller method directly
    echo "6. Testing controller method...\n";
    $seoService = new App\Services\SEOService();
    $controller = new App\Http\Controllers\Public\PublicController($seoService);
    
    $result = $controller->competitions();
    echo "   Controller executed successfully\n";
    echo "   Result type: " . get_class($result) . "\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== DEBUG COMPLETED ===\n";

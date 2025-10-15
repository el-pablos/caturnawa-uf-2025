<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Create test competition
$competition = \App\Models\Competition::factory()->create();

echo "Competition created: {$competition->id}\n";
echo "Category: {$competition->category}\n";
echo "Status: {$competition->status}\n";
echo "is_team_competition: " . ($competition->is_team_competition ? 'true' : 'false') . "\n";
echo "max_participants: {$competition->max_participants}\n";
echo "price: {$competition->price}\n";

// Try to access is_team accessor
try {
    echo "is_team accessor: " . ($competition->is_team ? 'true' : 'false') . "\n";
} catch (\Exception $e) {
    echo "ERROR accessing is_team: " . $e->getMessage() . "\n";
}

// Try to access days_left accessor
try {
    echo "days_left: " . ($competition->days_left ?? 'null') . "\n";
} catch (\Exception $e) {
    echo "ERROR accessing days_left: " . $e->getMessage() . "\n";
}

// Try to access timeline accessor
try {
    $timeline = $competition->timeline;
    echo "timeline: " . count($timeline) . " items\n";
} catch (\Exception $e) {
    echo "ERROR accessing timeline: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

// Try to convert to array like controller does
try {
    $data = [
        'id' => $competition->id,
        'name' => $competition->name,
        'category' => $competition->category,
        'status' => $competition->status,
        'is_team' => $competition->is_team ?? false,
        'max_participants' => $competition->max_participants ?? 0,
        'registration_start' => $competition->registration_start?->toISOString(),
        'registration_end' => $competition->registration_end?->toISOString(),
        'price' => (float) $competition->price,
        'early_bird_price' => (float) ($competition->early_bird_price ?? 0),
        'early_bird_end' => $competition->early_bird_deadline?->toISOString(),
    ];
    echo "Data array created successfully\n";
    print_r($data);
} catch (\Exception $e) {
    echo "ERROR creating data array: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}


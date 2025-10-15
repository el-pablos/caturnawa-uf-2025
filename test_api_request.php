<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Migrate database
Artisan::call('migrate:fresh');

// Create test competition
$competition = \App\Models\Competition::factory()->create();

echo "Competition created: {$competition->id}\n";

// Make API request
try {
    $request = \Illuminate\Http\Request::create('/api/competitions', 'GET');
    $response = app()->handle($request);
    
    echo "Status: " . $response->getStatusCode() . "\n";
    echo "Content: " . $response->getContent() . "\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}


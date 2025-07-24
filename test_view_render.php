<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST VIEW RENDERING ===\n";

try {
    // Get data like controller does
    $allCompetitions = App\Models\Competition::active()
        ->with(['registrations' => function($query) {
            $query->where('status', 'confirmed');
        }])
        ->orderBy('registration_start', 'asc')
        ->get();

    $competitions = $allCompetitions;

    $stats = [
        'participants' => $allCompetitions->sum(function($comp) {
            return $comp->registrations->count();
        }),
        'competitions' => $allCompetitions->count(),
        'total_prizes' => $allCompetitions->sum('prize_amount') ?: 500000000,
    ];

    echo "Data prepared successfully:\n";
    echo "- Competitions: " . $competitions->count() . " competitions\n";
    echo "- Stats: " . json_encode($stats) . "\n";

    // Try to render the view
    echo "\nTesting view rendering...\n";

    $view = view('public.competitions', compact('competitions', 'stats'));
    $rendered = $view->render();
    
    echo "View rendered successfully!\n";
    echo "Content length: " . strlen($rendered) . " characters\n";
    
    // Check if it contains expected content
    if (strpos($rendered, 'Kompetisi UNAS Fest 2025') !== false) {
        echo "✅ Title found in rendered content\n";
    } else {
        echo "❌ Title NOT found in rendered content\n";
    }
    
    if (strpos($rendered, 'Digital Content Competition') !== false) {
        echo "✅ Competition category found in rendered content\n";
    } else {
        echo "❌ Competition category NOT found in rendered content\n";
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== TEST COMPLETED ===\n";

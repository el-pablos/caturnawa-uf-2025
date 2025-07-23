<?php

// Script to validate all route references in views
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get all registered routes
$routes = collect(Route::getRoutes())->mapWithKeys(function ($route) {
    return [$route->getName() => $route->uri()];
})->filter();

echo "=== REGISTERED ROUTES ===\n";
foreach ($routes as $name => $uri) {
    if ($name) {
        echo "✓ {$name} -> {$uri}\n";
    }
}

echo "\n=== CHECKING VIEW FILES ===\n";

// Find all route() calls in blade files
$viewFiles = glob('resources/views/**/*.blade.php', GLOB_BRACE);
$missingRoutes = [];
$foundRoutes = [];

foreach ($viewFiles as $file) {
    $content = file_get_contents($file);
    
    // Find all route() calls
    preg_match_all('/route\([\'"]([^\'"]+)[\'"]\)/', $content, $matches);
    
    foreach ($matches[1] as $routeName) {
        if (!isset($routes[$routeName])) {
            $missingRoutes[] = [
                'route' => $routeName,
                'file' => $file
            ];
        } else {
            $foundRoutes[] = $routeName;
        }
    }
}

echo "\n=== MISSING ROUTES ===\n";
if (empty($missingRoutes)) {
    echo "✓ No missing routes found!\n";
} else {
    foreach ($missingRoutes as $missing) {
        echo "❌ Route '{$missing['route']}' not found in {$missing['file']}\n";
    }
}

echo "\n=== SUMMARY ===\n";
echo "Total registered routes: " . count($routes) . "\n";
echo "Total route references found: " . count($foundRoutes) . "\n";
echo "Missing routes: " . count($missingRoutes) . "\n";

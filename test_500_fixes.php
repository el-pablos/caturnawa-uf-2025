<?php

// Comprehensive test script to verify all 500 error fixes
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== COMPREHENSIVE 500 ERROR FIX VERIFICATION ===\n\n";

// Test 1: Route Validation
echo "1. ROUTE VALIDATION\n";
echo "==================\n";

$routes = collect(Route::getRoutes())->mapWithKeys(function ($route) {
    return [$route->getName() => $route->uri()];
})->filter();

$criticalRoutes = [
    'login',
    'register', 
    'dashboard',
    'peserta.dashboard',
    'peserta.profile.edit',
    'admin.admin.dashboard',
    'juri.juri.dashboard',
    'public.home',
    'public.faq'
];

foreach ($criticalRoutes as $routeName) {
    if (isset($routes[$routeName])) {
        echo "✓ Route '{$routeName}' exists -> {$routes[$routeName]}\n";
    } else {
        echo "❌ Route '{$routeName}' MISSING\n";
    }
}

// Test 2: Database and Roles
echo "\n2. DATABASE & ROLES VALIDATION\n";
echo "==============================\n";

try {
    $roles = DB::table('roles')->get();
    echo "✓ Database connection successful\n";
    echo "✓ Found " . count($roles) . " roles:\n";
    foreach ($roles as $role) {
        echo "  - {$role->name}\n";
    }
    
    // Test role case sensitivity
    $pesertaRole = DB::table('roles')->where('name', 'peserta')->first();
    if ($pesertaRole) {
        echo "✓ Role 'peserta' (lowercase) exists\n";
    } else {
        echo "❌ Role 'peserta' (lowercase) MISSING\n";
    }
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

// Test 3: User Model Methods
echo "\n3. USER MODEL METHODS\n";
echo "====================\n";

try {
    $user = new App\Models\User();
    $methods = ['isSuperAdmin', 'isAdmin', 'isJuri', 'isPeserta'];
    
    foreach ($methods as $method) {
        if (method_exists($user, $method)) {
            echo "✓ Method '{$method}' exists\n";
        } else {
            echo "❌ Method '{$method}' MISSING\n";
        }
    }
} catch (Exception $e) {
    echo "❌ User model error: " . $e->getMessage() . "\n";
}

// Test 4: Controller Instantiation
echo "\n4. CONTROLLER INSTANTIATION\n";
echo "===========================\n";

$controllers = [
    'Auth\AuthController',
    'Peserta\PesertaDashboardController',
    'Admin\DashboardController',
    'Juri\JuriDashboardController'
];

foreach ($controllers as $controllerClass) {
    try {
        $fullClass = "App\\Http\\Controllers\\{$controllerClass}";
        if (class_exists($fullClass)) {
            $controller = new $fullClass();
            echo "✓ Controller '{$controllerClass}' instantiated successfully\n";
        } else {
            echo "❌ Controller '{$controllerClass}' class not found\n";
        }
    } catch (Exception $e) {
        echo "❌ Controller '{$controllerClass}' error: " . $e->getMessage() . "\n";
    }
}

// Test 5: Middleware Validation
echo "\n5. MIDDLEWARE VALIDATION\n";
echo "=======================\n";

$middlewares = [
    'App\Http\Middleware\RoleBasedRedirect',
    'App\Http\Middleware\RedirectIfAuthenticated',
    'App\Http\Middleware\CheckRole'
];

foreach ($middlewares as $middleware) {
    if (class_exists($middleware)) {
        echo "✓ Middleware '{$middleware}' exists\n";
    } else {
        echo "❌ Middleware '{$middleware}' MISSING\n";
    }
}

echo "\n=== TEST SUMMARY ===\n";
echo "All critical components tested for 500 error fixes.\n";
echo "If all items show ✓, the application should be free of 500 errors.\n";
echo "If any items show ❌, those need to be addressed.\n\n";

echo "Next steps:\n";
echo "1. Test login functionality manually\n";
echo "2. Test dashboard access for each role\n";
echo "3. Test registration process\n";
echo "4. Monitor error logs for any new issues\n";

@echo off
echo ========================================
echo    UNAS Fest 2025 - Setup Script
echo ========================================
echo.

:: Check if composer is installed
where composer >nul 2>nul
if %errorlevel% neq 0 (
    echo [ERROR] Composer not found! Please install Composer first.
    pause
    exit /b 1
)

:: Check if node is installed
where node >nul 2>nul
if %errorlevel% neq 0 (
    echo [ERROR] Node.js not found! Please install Node.js first.
    pause
    exit /b 1
)

:: Check if npm is installed
where npm >nul 2>nul
if %errorlevel% neq 0 (
    echo [ERROR] NPM not found! Please install NPM first.
    pause
    exit /b 1
)

echo [INFO] Installing Composer dependencies...
composer install --no-dev --optimize-autoloader
if %errorlevel% neq 0 (
    echo [ERROR] Failed to install Composer dependencies!
    pause
    exit /b 1
)

echo [INFO] Installing NPM dependencies...
npm install
if %errorlevel% neq 0 (
    echo [ERROR] Failed to install NPM dependencies!
    pause
    exit /b 1
)

echo [INFO] Copying environment file...
if not exist .env (
    if exist .env.example (
        copy .env.example .env
        echo [INFO] .env file created from .env.example
    ) else (
        echo [WARNING] .env.example not found! Please create .env manually.
    )
) else (
    echo [INFO] .env file already exists
)

echo [INFO] Generating application key...
php artisan key:generate --force

echo [INFO] Creating storage symlink...
php artisan storage:link

echo [INFO] Setting up database...
php artisan migrate --force
if %errorlevel% neq 0 (
    echo [WARNING] Database migration failed! Please check your database configuration.
)

echo [INFO] Seeding database...
php artisan db:seed --force
if %errorlevel% neq 0 (
    echo [WARNING] Database seeding failed! Please check your database configuration.
)

echo [INFO] Building frontend assets...
npm run build
if %errorlevel% neq 0 (
    echo [ERROR] Failed to build frontend assets!
    pause
    exit /b 1
)

echo [INFO] Clearing and caching configuration...
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
php artisan view:clear
php artisan view:cache
php artisan optimize

echo [INFO] Setting permissions...
if exist storage (
    echo [INFO] Setting storage permissions...
    icacls storage /grant Everyone:(OI)(CI)F /T >nul 2>nul
)
if exist bootstrap\cache (
    echo [INFO] Setting bootstrap cache permissions...
    icacls bootstrap\cache /grant Everyone:(OI)(CI)F /T >nul 2>nul
)

echo.
echo ========================================
echo    Setup completed successfully!
echo ========================================
echo.
echo Next steps:
echo 1. Configure your .env file with proper database credentials
echo 2. Configure your web server to point to the public directory
echo 3. Make sure storage and bootstrap/cache directories are writable
echo 4. Access your application via web browser
echo.
echo Default admin credentials:
echo Email: admin@unasfest.com
echo Password: password123
echo.
pause

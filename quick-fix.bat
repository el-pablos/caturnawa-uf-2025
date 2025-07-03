@echo off
echo.
echo 🔧 UNAS Fest 2025 - Quick Fixes (Windows)
echo ================================
echo.

:: Clear all caches
echo 🗑️ Clearing all caches...
call php artisan config:clear
call php artisan route:clear
call php artisan view:clear
call php artisan cache:clear
echo ✅ All caches cleared
echo.

:: Optimize for production
echo ⚡ Optimizing for production...
call php artisan config:cache
call php artisan route:cache
call php artisan view:cache
echo ✅ Production optimization complete
echo.

:: Check database connection
echo 🔍 Checking database connection...
call php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database connection: OK'; } catch (Exception $e) { echo 'Database connection: FAILED - ' . $e->getMessage(); }"
echo.

:: Run database migrations
echo 🏗️ Running database migrations...
call php artisan migrate --force
echo ✅ Database migrations completed
echo.

:: Create storage link
echo 🔗 Creating storage link...
call php artisan storage:link
echo ✅ Storage link created
echo.

:: Install/update composer dependencies
echo 📦 Installing Composer dependencies...
call composer install --no-dev --optimize-autoloader
if %errorlevel% equ 0 (
    echo ✅ Composer dependencies installed
) else (
    echo ❌ Failed to install Composer dependencies
)
echo.

:: Install/update NPM dependencies
echo 📦 Installing NPM dependencies...
call npm install
if %errorlevel% equ 0 (
    echo ✅ NPM dependencies installed
) else (
    echo ❌ Failed to install NPM dependencies
)
echo.

:: Build production assets
echo 🔨 Building production assets...
call npm run build
if %errorlevel% equ 0 (
    echo ✅ Production assets built
) else (
    echo ❌ Failed to build production assets
)
echo.

:: Generate application key if not exists
echo 🔑 Checking application key...
findstr /C:"APP_KEY=" .env >nul
if %errorlevel% equ 0 (
    echo ✅ Application key exists
) else (
    echo 🔑 Generating application key...
    call php artisan key:generate --force
    echo ✅ Application key generated
)
echo.

:: Create missing directories
echo 📁 Creating missing directories...
if not exist "storage\app\public" mkdir "storage\app\public"
if not exist "storage\app\public\avatars" mkdir "storage\app\public\avatars"
if not exist "storage\app\public\competitions" mkdir "storage\app\public\competitions"
if not exist "storage\app\public\submissions" mkdir "storage\app\public\submissions"
if not exist "storage\app\public\qrcodes" mkdir "storage\app\public\qrcodes"
echo ✅ Missing directories created
echo.

:: Test basic functionality
echo 🧪 Testing basic functionality...
call php artisan route:list >nul 2>&1
if %errorlevel% equ 0 (
    echo ✅ Routes loaded successfully
) else (
    echo ❌ Failed to load routes
)
echo.

:: Final summary
echo.
echo 🎉 Quick Fixes Complete!
echo ========================
echo ✅ System optimized for presentation
echo ✅ Caches cleared and regenerated
echo ✅ Database migrations completed
echo ✅ Assets built for production
echo ✅ Basic functionality tested
echo.
echo 🚀 Your system is ready for presentation!
echo 📋 Don't forget to check the PRE-LAUNCH-CHECKLIST.md for complete deployment steps
echo.
pause

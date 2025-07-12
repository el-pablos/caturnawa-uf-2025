@echo off
echo 🌱 UNAS Fest 2025 - Leaderboard Seeder
echo ======================================
echo.

REM Check if we're in the right directory
if not exist "artisan" (
    echo ❌ Error: Please run this script from the project root directory
    echo Current directory: %cd%
    pause
    exit /b 1
)

echo 📍 Current directory: %cd%
echo.

REM Run the specific seeder
echo 🏆 Running Leaderboard Seeder...
php artisan db:seed --class=LeaderboardSeeder

echo.
echo ✅ Leaderboard seeding completed!
echo.
echo 📊 You can now view the leaderboard data in your application
echo 🌐 Visit the leaderboard page to see the dummy competition data
echo.
echo 🚀 To run all seeders (including leaderboard), use:
echo    php artisan db:seed
echo.
pause

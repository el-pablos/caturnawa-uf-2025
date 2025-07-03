@echo off
setlocal enabledelayedexpansion

echo ==============================================
echo 🎉 UNAS Fest 2025 - Competition Registration
echo ==============================================
echo.

REM Cek apakah composer terinstall
where composer >nul 2>nul
if errorlevel 1 (
    echo ❌ Composer tidak terinstall. Silakan install composer terlebih dahulu.
    pause
    exit /b 1
)

REM Cek apakah node terinstall
where node >nul 2>nul
if errorlevel 1 (
    echo ❌ Node.js tidak terinstall. Silakan install Node.js terlebih dahulu.
    pause
    exit /b 1
)

REM Cek apakah npm terinstall
where npm >nul 2>nul
if errorlevel 1 (
    echo ❌ NPM tidak terinstall. Silakan install NPM terlebih dahulu.
    pause
    exit /b 1
)

echo ℹ️ Memulai setup aplikasi UNAS Fest 2025...
echo.

REM Install Composer Dependencies
echo ℹ️ 📦 Installing Composer dependencies...
call composer install --no-dev --optimize-autoloader
if errorlevel 1 (
    echo ❌ Gagal menginstall composer dependencies
    pause
    exit /b 1
)
echo ✅ Composer dependencies berhasil diinstall

REM Install NPM Dependencies
echo ℹ️ 📦 Installing NPM dependencies...
call npm install
if errorlevel 1 (
    echo ❌ Gagal menginstall NPM dependencies
    pause
    exit /b 1
)
echo ✅ NPM dependencies berhasil diinstall

REM Copy .env file
if not exist .env (
    echo ℹ️ ⚙️ Copying .env.example to .env...
    copy .env.example .env >nul
    echo ✅ .env file berhasil dibuat
) else (
    echo ⚠️ .env file sudah ada, skip copy...
)

REM Generate Application Key
echo ℹ️ 🔑 Generating application key...
call php artisan key:generate --force
if errorlevel 1 (
    echo ❌ Gagal membuat application key
    pause
    exit /b 1
)
echo ✅ Application key berhasil dibuat

REM Create Storage Link
echo ℹ️ 🔗 Creating storage symbolic link...
call php artisan storage:link
echo ✅ Storage link berhasil dibuat

REM Create necessary directories
echo ℹ️ 📁 Creating necessary directories...
if not exist "storage\app\public\avatars" mkdir "storage\app\public\avatars"
if not exist "storage\app\public\competitions" mkdir "storage\app\public\competitions"
if not exist "storage\app\public\submissions" mkdir "storage\app\public\submissions"
if not exist "storage\app\public\qrcodes" mkdir "storage\app\public\qrcodes"
if not exist "storage\app\public\tickets" mkdir "storage\app\public\tickets"
echo ✅ Directories berhasil dibuat

REM Database setup
echo.
echo ℹ️ 🗄️ Database Setup
echo Pastikan database MySQL sudah dibuat dan konfigurasi di .env sudah benar
echo.

set /p migrate="Apakah Anda ingin menjalankan migration dan seeder? (y/n): "
if /i "%migrate%"=="y" (
    echo ℹ️ Running database migrations...
    call php artisan migrate --force
    if errorlevel 1 (
        echo ❌ Gagal menjalankan database migrations
        echo ⚠️ Periksa konfigurasi database di file .env
    ) else (
        echo ✅ Database migrations berhasil dijalankan
        
        echo ℹ️ Running database seeders...
        call php artisan db:seed --force
        if errorlevel 1 (
            echo ❌ Gagal menjalankan database seeders
        ) else (
            echo ✅ Database seeders berhasil dijalankan
        )
    )
) else (
    echo ⚠️ Skip database migration dan seeder
    echo ℹ️ Jalankan manual dengan: php artisan migrate --seed
)

REM Build frontend assets
echo.
set /p build="Apakah Anda ingin build frontend assets? (y/n): "
if /i "%build%"=="y" (
    echo ℹ️ 🎨 Building frontend assets...
    call npm run build
    if errorlevel 1 (
        echo ❌ Gagal build frontend assets
        echo ℹ️ Anda bisa menjalankan 'npm run dev' untuk development
    ) else (
        echo ✅ Frontend assets berhasil di-build
    )
) else (
    echo ⚠️ Skip build frontend assets
    echo ℹ️ Jalankan 'npm run dev' untuk development atau 'npm run build' untuk production
)

REM Cache configuration
echo ℹ️ 🚀 Optimizing application...
call php artisan config:cache
call php artisan route:cache
call php artisan view:cache
echo ✅ Application berhasil dioptimasi

echo.
echo ==============================================
echo ✅ 🎉 Setup selesai!
echo ==============================================
echo.
echo ℹ️ Akun default yang tersedia:
echo.
echo 👑 Super Admin:
echo    Email: superadmin@unasfest.ac.id
echo    Password: superadmin123
echo.
echo 🛡️ Admin:
echo    Email: admin@unasfest.ac.id
echo    Password: admin123
echo.
echo ⚖️ Juri:
echo    Email: juri1@unasfest.ac.id
echo    Password: juri123
echo.
echo 👤 Peserta:
echo    Email: peserta@unasfest.ac.id
echo    Password: peserta123
echo.
echo ==============================================
echo ℹ️ Untuk menjalankan aplikasi:
echo    php artisan serve
echo.
echo ℹ️ Untuk development frontend:
echo    npm run dev
echo.
echo ℹ️ Dokumentasi lengkap ada di README.md
echo ==============================================
echo.
echo ⚠️ PENTING: Jangan lupa konfigurasi Midtrans di .env untuk payment gateway!
echo.
pause

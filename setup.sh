#!/bin/bash

# UNAS Fest 2025 - Setup Script
# Script untuk setup awal aplikasi Laravel

echo "=============================================="
echo "🎉 UNAS Fest 2025 - Competition Registration"
echo "=============================================="
echo ""

# Warna untuk output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function untuk print dengan warna
print_status() {
    echo -e "${GREEN}✓${NC} $1"
}

print_info() {
    echo -e "${BLUE}ℹ${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

print_error() {
    echo -e "${RED}✗${NC} $1"
}

# Cek apakah composer terinstall
if ! command -v composer &> /dev/null; then
    print_error "Composer tidak terinstall. Silakan install composer terlebih dahulu."
    exit 1
fi

# Cek apakah node terinstall
if ! command -v node &> /dev/null; then
    print_error "Node.js tidak terinstall. Silakan install Node.js terlebih dahulu."
    exit 1
fi

# Cek apakah npm terinstall
if ! command -v npm &> /dev/null; then
    print_error "NPM tidak terinstall. Silakan install NPM terlebih dahulu."
    exit 1
fi

print_info "Memulai setup aplikasi UNAS Fest 2025..."
echo ""

# Install Composer Dependencies
print_info "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader
if [ $? -eq 0 ]; then
    print_status "Composer dependencies berhasil diinstall"
else
    print_error "Gagal menginstall composer dependencies"
    exit 1
fi

# Install NPM Dependencies
print_info "📦 Installing NPM dependencies..."
npm install
if [ $? -eq 0 ]; then
    print_status "NPM dependencies berhasil diinstall"
else
    print_error "Gagal menginstall NPM dependencies"
    exit 1
fi

# Copy .env file
if [ ! -f .env ]; then
    print_info "⚙️ Copying .env.example to .env..."
    cp .env.example .env
    print_status ".env file berhasil dibuat"
else
    print_warning ".env file sudah ada, skip copy..."
fi

# Generate Application Key
print_info "🔑 Generating application key..."
php artisan key:generate --force
if [ $? -eq 0 ]; then
    print_status "Application key berhasil dibuat"
else
    print_error "Gagal membuat application key"
    exit 1
fi

# Create Storage Link
print_info "🔗 Creating storage symbolic link..."
php artisan storage:link
if [ $? -eq 0 ]; then
    print_status "Storage link berhasil dibuat"
else
    print_warning "Storage link mungkin sudah ada"
fi

# Create necessary directories
print_info "📁 Creating necessary directories..."
mkdir -p storage/app/public/avatars
mkdir -p storage/app/public/competitions
mkdir -p storage/app/public/submissions
mkdir -p storage/app/public/qrcodes
mkdir -p storage/app/public/tickets
print_status "Directories berhasil dibuat"

# Set permissions (untuk Linux/Mac)
if [[ "$OSTYPE" == "linux-gnu"* ]] || [[ "$OSTYPE" == "darwin"* ]]; then
    print_info "🔐 Setting file permissions..."
    chmod -R 755 storage bootstrap/cache
    print_status "File permissions berhasil diset"
fi

# Database setup
echo ""
print_info "🗄️ Database Setup"
echo "Pastikan database MySQL sudah dibuat dan konfigurasi di .env sudah benar"
echo ""

read -p "Apakah Anda ingin menjalankan migration dan seeder? (y/n): " -n 1 -r
echo ""

if [[ $REPLY =~ ^[Yy]$ ]]; then
    print_info "Running database migrations..."
    php artisan migrate --force
    if [ $? -eq 0 ]; then
        print_status "Database migrations berhasil dijalankan"
        
        print_info "Running database seeders..."
        php artisan db:seed --force
        if [ $? -eq 0 ]; then
            print_status "Database seeders berhasil dijalankan"
        else
            print_error "Gagal menjalankan database seeders"
        fi
    else
        print_error "Gagal menjalankan database migrations"
        print_warning "Periksa konfigurasi database di file .env"
    fi
else
    print_warning "Skip database migration dan seeder"
    print_info "Jalankan manual dengan: php artisan migrate --seed"
fi

# Build frontend assets
echo ""
read -p "Apakah Anda ingin build frontend assets? (y/n): " -n 1 -r
echo ""

if [[ $REPLY =~ ^[Yy]$ ]]; then
    print_info "🎨 Building frontend assets..."
    npm run build
    if [ $? -eq 0 ]; then
        print_status "Frontend assets berhasil di-build"
    else
        print_error "Gagal build frontend assets"
        print_info "Anda bisa menjalankan 'npm run dev' untuk development"
    fi
else
    print_warning "Skip build frontend assets"
    print_info "Jalankan 'npm run dev' untuk development atau 'npm run build' untuk production"
fi

# Cache configuration
print_info "🚀 Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
print_status "Application berhasil dioptimasi"

echo ""
echo "=============================================="
print_status "🎉 Setup selesai!"
echo "=============================================="
echo ""
print_info "Akun default yang tersedia:"
echo ""
echo "👑 Super Admin:"
echo "   Email: superadmin@unasfest.ac.id"
echo "   Password: superadmin123"
echo ""
echo "🛡️  Admin:"
echo "   Email: admin@unasfest.ac.id" 
echo "   Password: admin123"
echo ""
echo "⚖️  Juri:"
echo "   Email: juri1@unasfest.ac.id"
echo "   Password: juri123"
echo ""
echo "👤 Peserta:"
echo "   Email: peserta@unasfest.ac.id"
echo "   Password: peserta123"
echo ""
echo "=============================================="
print_info "Untuk menjalankan aplikasi:"
echo "   php artisan serve"
echo ""
print_info "Untuk development frontend:"
echo "   npm run dev"
echo ""
print_info "Dokumentasi lengkap ada di README.md"
echo "=============================================="
echo ""
print_warning "PENTING: Jangan lupa konfigurasi Midtrans di .env untuk payment gateway!"
echo ""

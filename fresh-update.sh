#!/bin/bash

echo "🚀 UNAS Fest 2025 - Production Update with Fresh Database"
echo "========================================================"

# Navigate to project directory
cd /var/www/uf25.tams.my.id/

# Stash local changes
echo "💾 Stashing changes..."
git stash

# Pull latest changes
echo "⬇️ Pulling from repository..."
git pull origin master

# Update dependencies
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# FRESH DATABASE RESET AND SEEDING
echo "🗄️ Resetting database..."
php artisan migrate:fresh --force

echo "🌱 Seeding fresh data..."
php artisan db:seed --force

# Optimize for production
echo "⚡ Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
echo "🔐 Setting permissions..."
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 storage bootstrap/cache

# Ensure storage link
php artisan storage:link

echo ""
echo "✅ Production update completed!"
echo "==============================="
echo "🎯 Test Accounts:"
echo "   superadmin@unasfest.com / password123"
echo "   admin1-5@unasfest.com / password123"
echo "   juri1-5@unasfest.com / password123"
echo "   peserta1-5@unasfest.com / password123"
echo ""
echo "🌐 Ready at: https://uf25.tams.my.id"

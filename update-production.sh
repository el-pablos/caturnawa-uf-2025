#!/bin/bash

echo "🚀 Starting UNAS Fest 2025 Production Update..."
echo "================================================"

# 1. Navigate to project directory
echo "📁 Navigating to project directory..."
cd /var/www/uf25.tams.my.id/

# 2. Backup and stash any local changes
echo "💾 Stashing local changes..."
git stash

# 3. Pull latest changes from repository
echo "⬇️ Pulling latest changes from repository..."
git pull origin master

# 4. Update composer dependencies
echo "📦 Updating composer dependencies..."
composer install --no-dev --optimize-autoloader

# 5. Clear all caches before database operations
echo "🧹 Clearing application caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 6. FRESH DATABASE MIGRATION AND SEEDING
echo "🗄️ Resetting database with fresh migration..."
echo "⚠️  WARNING: This will DROP ALL TABLES and recreate them!"
php artisan migrate:fresh --force

echo "🌱 Running fresh database seeders..."
php artisan db:seed --force

# 7. Optimize application for production
echo "⚡ Optimizing application for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Set proper file permissions
echo "🔐 Setting proper file permissions..."
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 storage bootstrap/cache

# 9. Create symbolic link for storage (if not exists)
echo "🔗 Ensuring storage link exists..."
php artisan storage:link

echo ""
echo "✅ Production update completed successfully!"
echo "================================================"
echo "📊 Summary:"
echo "   - Git pull: ✅ Completed"
echo "   - Dependencies: ✅ Updated"
echo "   - Database: ✅ Fresh migration & seeding"
echo "   - Cache: ✅ Optimized"
echo "   - Permissions: ✅ Set correctly"
echo ""
echo "🎯 Test accounts available:"
echo "   - Super Admin: superadmin@unasfest.com / password123"
echo "   - Admin 1-5: admin1-5@unasfest.com / password123"
echo "   - Juri 1-5: juri1-5@unasfest.com / password123"
echo "   - Peserta 1-5: peserta1-5@unasfest.com / password123"
echo ""
echo "🌐 Application ready at: https://uf25.tams.my.id"
echo "================================================"

#!/bin/bash

echo "🚀 Starting UNAS Fest 2025 Production Update (SAFE MODE)..."
echo "=========================================================="

# 1. Navigate to project directory
echo "📁 Navigating to project directory..."
cd /var/www/uf25.tams.my.id/

# 2. Create database backup before reset
echo "💾 Creating database backup..."
BACKUP_DATE=$(date +"%Y%m%d_%H%M%S")
BACKUP_FILE="backup_uf25_${BACKUP_DATE}.sql"
mysqldump -u uf25_user -pnigajawir uf25_database > "/tmp/${BACKUP_FILE}"
echo "✅ Database backup created: /tmp/${BACKUP_FILE}"

# 3. Backup and stash any local changes
echo "💾 Stashing local changes..."
git stash

# 4. Pull latest changes from repository
echo "⬇️ Pulling latest changes from repository..."
git pull origin master

# 5. Update composer dependencies
echo "📦 Updating composer dependencies..."
composer install --no-dev --optimize-autoloader

# 6. Clear all caches before database operations
echo "🧹 Clearing application caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 7. FRESH DATABASE MIGRATION AND SEEDING
echo "🗄️ Resetting database with fresh migration..."
echo "⚠️  WARNING: This will DROP ALL TABLES and recreate them!"
echo "💾 Backup saved at: /tmp/${BACKUP_FILE}"
read -p "Continue with database reset? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan migrate:fresh --force
    echo "🌱 Running fresh database seeders..."
    php artisan db:seed --force
    echo "✅ Database reset and seeding completed!"
else
    echo "❌ Database reset cancelled. Running regular migration..."
    php artisan migrate --force
fi

# 8. Optimize application for production
echo "⚡ Optimizing application for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 9. Set proper file permissions
echo "🔐 Setting proper file permissions..."
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 storage bootstrap/cache

# 10. Create symbolic link for storage (if not exists)
echo "🔗 Ensuring storage link exists..."
php artisan storage:link

echo ""
echo "✅ Production update completed successfully!"
echo "=========================================================="
echo "📊 Summary:"
echo "   - Backup: ✅ /tmp/${BACKUP_FILE}"
echo "   - Git pull: ✅ Completed"
echo "   - Dependencies: ✅ Updated"
echo "   - Database: ✅ Updated"
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
echo "=========================================================="

# IMMEDIATE FIX COMMANDS for 500 Error

## Run these commands in order on the server:

```bash
# 1. Go to project directory
cd /var/www/uf25.tams.my.id

# 2. Load environment
source .env

# 3. Export critical variables
export APP_ENV="$APP_ENV"
export APP_KEY="$APP_KEY" 
export DB_PASSWORD="$DB_PASSWORD"
export MIDTRANS_SERVER_KEY="$MIDTRANS_SERVER_KEY"
export MIDTRANS_CLIENT_KEY="$MIDTRANS_CLIENT_KEY"

# 4. Fix directories and permissions
mkdir -p storage/framework/{cache,sessions,views}
mkdir -p storage/logs
mkdir -p bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# 5. Clear all problematic cache
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*
rm -rf bootstrap/cache/*

# 6. Fix .env permissions
chmod 644 .env
chown www-data:www-data .env

# 7. Clear Laravel caches
sudo -u www-data -E php artisan config:clear
sudo -u www-data -E php artisan route:clear
sudo -u www-data -E php artisan view:clear
sudo -u www-data -E php artisan cache:clear

# 8. Test Laravel
sudo -u www-data -E php artisan --version

# 9. Test database
sudo -u www-data -E php artisan tinker --execute="DB::connection()->getPdo(); echo 'DB_OK';"

# 10. Install dependencies if needed
composer install --no-dev --optimize-autoloader
npm install

# 11. Build assets if needed
npx vite build

# 12. Cache optimization (only if step 8 worked)
sudo -u www-data -E php artisan config:cache
sudo -u www-data -E php artisan route:cache
sudo -u www-data -E php artisan view:cache

# 13. Create storage link
sudo -u www-data -E php artisan storage:link

# 14. Final permission fix
chown -R www-data:www-data /var/www/uf25.tams.my.id
chmod -R 755 /var/www/uf25.tams.my.id
chmod -R 775 /var/www/uf25.tams.my.id/storage
chmod -R 775 /var/www/uf25.tams.my.id/bootstrap/cache

# 15. Restart services
systemctl restart php8.3-fpm nginx

# 16. Wait and test
sleep 10
curl -I http://localhost
curl -I https://uf25.tams.my.id
```

## If still not working, check logs:

```bash
# Laravel logs
tail -20 /var/www/uf25.tams.my.id/storage/logs/laravel.log

# Nginx logs
tail -20 /var/log/nginx/error.log

# PHP-FPM logs
tail -20 /var/log/php8.3-fpm.log

# Test specific components
sudo -u www-data php -v
sudo -u www-data php artisan --version
nginx -t
systemctl status php8.3-fpm
systemctl status nginx
```

## Alternative: Use the update.sh script

```bash
cd /var/www/uf25.tams.my.id
sudo ./update.sh
```

The enhanced update.sh script now includes all these fixes automatically.

#!/bin/bash

# UNAS Fest 2025 - Simple Production Update Script
# Usage: ./update-production.sh

set -e

# Configuration
PROJECT_DIR="/var/www/unas-fest-2025"
BRANCH="main"

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${GREEN}🚀 UNAS Fest 2025 - Production Update${NC}"
echo "======================================"

# Check if project directory exists
if [ ! -d "$PROJECT_DIR" ]; then
    echo -e "${RED}❌ Project directory not found: $PROJECT_DIR${NC}"
    exit 1
fi

cd "$PROJECT_DIR"

# 1. Enable maintenance mode
echo -e "${YELLOW}🔧 Enabling maintenance mode...${NC}"
sudo -u www-data php artisan down --retry=60 --secret="unas-fest-secret"

# 2. Pull latest code
echo -e "${YELLOW}📥 Pulling latest code...${NC}"
git fetch origin
git checkout "$BRANCH"
git pull origin "$BRANCH"

# 3. Install dependencies
echo -e "${YELLOW}📦 Installing dependencies...${NC}"
sudo -u www-data composer install --no-dev --optimize-autoloader --no-interaction
npm ci --production

# 4. Run migrations
echo -e "${YELLOW}🏗️ Running migrations...${NC}"
sudo -u www-data php artisan migrate --force

# 5. Build assets
echo -e "${YELLOW}🔨 Building assets...${NC}"
npm run build

# 6. Clear and optimize caches
echo -e "${YELLOW}🗑️ Clearing caches...${NC}"
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan route:clear
sudo -u www-data php artisan view:clear
sudo -u www-data php artisan cache:clear

echo -e "${YELLOW}⚡ Optimizing for production...${NC}"
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# 7. Fix permissions
echo -e "${YELLOW}🔒 Fixing permissions...${NC}"
sudo chown -R www-data:www-data "$PROJECT_DIR"
sudo chmod -R 755 "$PROJECT_DIR"
sudo chmod -R 775 "$PROJECT_DIR/storage"
sudo chmod -R 775 "$PROJECT_DIR/bootstrap/cache"

# 8. Restart services
echo -e "${YELLOW}🔄 Restarting services...${NC}"
sudo systemctl restart php8.1-fpm 2>/dev/null || sudo systemctl restart php8.2-fpm 2>/dev/null || echo "PHP-FPM not restarted"
sudo systemctl restart nginx 2>/dev/null || echo "Nginx not restarted"

# 9. Disable maintenance mode
echo -e "${YELLOW}✅ Disabling maintenance mode...${NC}"
sudo -u www-data php artisan up

# 10. Health check
echo -e "${YELLOW}🔍 Running health check...${NC}"
sleep 3

# Check if application is responding
if curl -f -s "http://localhost" > /dev/null; then
    echo -e "${GREEN}✅ Application is responding${NC}"
else
    echo -e "${RED}❌ Application is not responding${NC}"
    exit 1
fi

# Check database connection
if sudo -u www-data php artisan tinker --execute="DB::connection()->getPdo(); echo 'DB OK';" 2>/dev/null | grep -q "DB OK"; then
    echo -e "${GREEN}✅ Database connection is working${NC}"
else
    echo -e "${RED}❌ Database connection failed${NC}"
    exit 1
fi

echo ""
echo -e "${GREEN}🎉 Update completed successfully!${NC}"
echo "======================================"
echo -e "${GREEN}✅ UNAS Fest 2025 is now updated${NC}"
echo -e "${GREEN}✅ All services are running${NC}"
echo -e "${GREEN}✅ Health check passed${NC}"
echo "======================================"

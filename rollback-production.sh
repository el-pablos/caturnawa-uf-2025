#!/bin/bash

# UNAS Fest 2025 - Rollback Script
# Usage: ./rollback-production.sh [steps_back]

set -e

# Configuration
PROJECT_NAME="unas-fest-2025"
PROJECT_DIR="/var/www/$PROJECT_NAME"
RELEASES_DIR="/var/www/$PROJECT_NAME/releases"
CURRENT_RELEASE="$PROJECT_DIR/current"
STEPS_BACK=${1:-1}

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${YELLOW}🔄 UNAS Fest 2025 - Rollback${NC}"
echo "=============================="
echo "Steps back: $STEPS_BACK"
echo "=============================="

# Check if current release exists
if [ ! -L "$CURRENT_RELEASE" ]; then
    echo -e "${RED}❌ Current release symlink not found${NC}"
    exit 1
fi

# Get current release
CURRENT_RELEASE_PATH=$(readlink "$CURRENT_RELEASE")
CURRENT_RELEASE_NAME=$(basename "$CURRENT_RELEASE_PATH")

echo -e "${BLUE}Current release: $CURRENT_RELEASE_NAME${NC}"

# Get available releases
cd "$RELEASES_DIR"
RELEASES=($(ls -t))

if [ ${#RELEASES[@]} -le $STEPS_BACK ]; then
    echo -e "${RED}❌ Not enough releases to rollback $STEPS_BACK steps${NC}"
    echo "Available releases: ${#RELEASES[@]}"
    exit 1
fi

# Get target release
TARGET_RELEASE=${RELEASES[$STEPS_BACK]}
TARGET_RELEASE_PATH="$RELEASES_DIR/$TARGET_RELEASE"

echo -e "${BLUE}Target release: $TARGET_RELEASE${NC}"

# Confirm rollback
echo -e "${YELLOW}⚠️ Are you sure you want to rollback? (y/N)${NC}"
read -r CONFIRM
if [[ ! $CONFIRM =~ ^[Yy]$ ]]; then
    echo -e "${BLUE}Rollback cancelled${NC}"
    exit 0
fi

# Enable maintenance mode
echo -e "${BLUE}🔧 Enabling maintenance mode...${NC}"
cd "$CURRENT_RELEASE_PATH"
sudo -u www-data php artisan down --retry=60 --secret="unas-fest-secret"

# Switch to target release
echo -e "${BLUE}🔄 Switching to release: $TARGET_RELEASE${NC}"
ln -sfn "$TARGET_RELEASE_PATH" "$CURRENT_RELEASE"

# Run any necessary migrations (rollback)
echo -e "${BLUE}🏗️ Running rollback migrations...${NC}"
cd "$TARGET_RELEASE_PATH"

# Clear caches
echo -e "${BLUE}🗑️ Clearing caches...${NC}"
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan route:clear
sudo -u www-data php artisan view:clear
sudo -u www-data php artisan cache:clear

# Optimize for production
echo -e "${BLUE}⚡ Optimizing for production...${NC}"
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# Fix permissions
echo -e "${BLUE}🔒 Fixing permissions...${NC}"
sudo chown -R www-data:www-data "$TARGET_RELEASE_PATH"
sudo chmod -R 755 "$TARGET_RELEASE_PATH"

# Restart services
echo -e "${BLUE}🔄 Restarting services...${NC}"
sudo systemctl restart php8.1-fpm 2>/dev/null || sudo systemctl restart php8.2-fpm 2>/dev/null || echo "PHP-FPM not restarted"
sudo systemctl reload nginx 2>/dev/null || echo "Nginx not reloaded"

# Wait for services to start
sleep 3

# Disable maintenance mode
echo -e "${BLUE}✅ Disabling maintenance mode...${NC}"
sudo -u www-data php artisan up

# Health check
echo -e "${BLUE}🔍 Running health check...${NC}"
sleep 2

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

# Log rollback
echo "$(date): Rolled back to release $TARGET_RELEASE from $CURRENT_RELEASE_NAME" >> "$PROJECT_DIR/shared/deployment.log"

echo ""
echo -e "${GREEN}🎉 Rollback completed successfully!${NC}"
echo "=============================="
echo -e "${GREEN}✅ Rolled back to: $TARGET_RELEASE${NC}"
echo -e "${GREEN}✅ Previous release: $CURRENT_RELEASE_NAME${NC}"
echo -e "${GREEN}✅ Health check passed${NC}"
echo "=============================="

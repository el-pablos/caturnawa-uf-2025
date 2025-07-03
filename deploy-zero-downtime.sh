#!/bin/bash

# UNAS Fest 2025 - Zero-Downtime Deployment Script
# Usage: ./deploy-zero-downtime.sh

set -e

# Configuration
PROJECT_NAME="unas-fest-2025"
PROJECT_DIR="/var/www/$PROJECT_NAME"
RELEASES_DIR="/var/www/$PROJECT_NAME/releases"
CURRENT_RELEASE="$PROJECT_DIR/current"
SHARED_DIR="/var/www/$PROJECT_NAME/shared"
BRANCH="main"
KEEP_RELEASES=3

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Get timestamp for release
TIMESTAMP=$(date +%Y%m%d%H%M%S)
RELEASE_DIR="$RELEASES_DIR/$TIMESTAMP"

echo -e "${GREEN}🚀 UNAS Fest 2025 - Zero-Downtime Deployment${NC}"
echo "=============================================="
echo "Release: $TIMESTAMP"
echo "Branch: $BRANCH"
echo "=============================================="

# Create directory structure
echo -e "${BLUE}📁 Creating directory structure...${NC}"
mkdir -p "$RELEASES_DIR"
mkdir -p "$SHARED_DIR"
mkdir -p "$SHARED_DIR/storage"
mkdir -p "$SHARED_DIR/storage/app"
mkdir -p "$SHARED_DIR/storage/framework"
mkdir -p "$SHARED_DIR/storage/logs"

# Clone repository to new release directory
echo -e "${BLUE}📥 Cloning repository...${NC}"
git clone --branch "$BRANCH" --depth 1 https://github.com/yourusername/unas-fest-2025.git "$RELEASE_DIR"

cd "$RELEASE_DIR"

# Copy environment file
echo -e "${BLUE}📋 Setting up environment...${NC}"
if [ -f "$SHARED_DIR/.env" ]; then
    ln -sf "$SHARED_DIR/.env" "$RELEASE_DIR/.env"
else
    echo -e "${RED}❌ .env file not found in shared directory${NC}"
    exit 1
fi

# Link shared storage
echo -e "${BLUE}🔗 Linking shared storage...${NC}"
rm -rf "$RELEASE_DIR/storage"
ln -sf "$SHARED_DIR/storage" "$RELEASE_DIR/storage"

# Install dependencies
echo -e "${BLUE}📦 Installing dependencies...${NC}"
composer install --no-dev --optimize-autoloader --no-interaction
npm ci --production

# Build assets
echo -e "${BLUE}🔨 Building assets...${NC}"
npm run build

# Generate optimized autoloader
echo -e "${BLUE}⚡ Optimizing autoloader...${NC}"
composer dump-autoload --optimize --classmap-authoritative

# Cache configuration
echo -e "${BLUE}🗃️ Caching configuration...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Run migrations
echo -e "${BLUE}🏗️ Running migrations...${NC}"
php artisan migrate --force

# Fix permissions
echo -e "${BLUE}🔒 Fixing permissions...${NC}"
chown -R www-data:www-data "$RELEASE_DIR"
chmod -R 755 "$RELEASE_DIR"

# Test the new release
echo -e "${BLUE}🧪 Testing new release...${NC}"
cd "$RELEASE_DIR"
if php artisan tinker --execute="DB::connection()->getPdo(); echo 'DB Test OK';" | grep -q "DB Test OK"; then
    echo -e "${GREEN}✅ New release passed tests${NC}"
else
    echo -e "${RED}❌ New release failed tests${NC}"
    exit 1
fi

# Switch to new release (atomic operation)
echo -e "${BLUE}🔄 Switching to new release...${NC}"
if [ -L "$CURRENT_RELEASE" ]; then
    PREVIOUS_RELEASE=$(readlink "$CURRENT_RELEASE")
fi

# Create new symlink
ln -sfn "$RELEASE_DIR" "$CURRENT_RELEASE"

# Restart services
echo -e "${BLUE}🔄 Restarting services...${NC}"
systemctl restart php8.1-fpm 2>/dev/null || systemctl restart php8.2-fpm 2>/dev/null || echo "PHP-FPM not restarted"
systemctl reload nginx 2>/dev/null || echo "Nginx not reloaded"

# Health check
echo -e "${BLUE}🔍 Running health check...${NC}"
sleep 3

# Check if application is responding
if curl -f -s "http://localhost" > /dev/null; then
    echo -e "${GREEN}✅ Application is responding${NC}"
else
    echo -e "${RED}❌ Application is not responding${NC}"
    
    # Rollback if health check fails
    if [ -n "$PREVIOUS_RELEASE" ]; then
        echo -e "${YELLOW}🔄 Rolling back to previous release...${NC}"
        ln -sfn "$PREVIOUS_RELEASE" "$CURRENT_RELEASE"
        systemctl restart php8.1-fpm 2>/dev/null || systemctl restart php8.2-fpm 2>/dev/null
        systemctl reload nginx 2>/dev/null
        echo -e "${YELLOW}⚠️ Rollback completed${NC}"
    fi
    exit 1
fi

# Clean up old releases
echo -e "${BLUE}🗑️ Cleaning up old releases...${NC}"
cd "$RELEASES_DIR"
ls -t | tail -n +$((KEEP_RELEASES + 1)) | xargs -r rm -rf

# Create deployment log
echo -e "${BLUE}📝 Creating deployment log...${NC}"
echo "$(date): Deployed release $TIMESTAMP" >> "$SHARED_DIR/deployment.log"

echo ""
echo -e "${GREEN}🎉 Zero-downtime deployment completed successfully!${NC}"
echo "=============================================="
echo -e "${GREEN}✅ Release: $TIMESTAMP${NC}"
echo -e "${GREEN}✅ Branch: $BRANCH${NC}"
echo -e "${GREEN}✅ No downtime experienced${NC}"
echo -e "${GREEN}✅ Health check passed${NC}"
echo "=============================================="

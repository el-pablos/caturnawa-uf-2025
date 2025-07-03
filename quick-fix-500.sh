#!/bin/bash

# UNAS Fest 2025 - Quick Fix for 500 Error
# Immediate fix for current production issues

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
PROJECT_DIR="/var/www/uf25.tams.my.id"

# Logging functions
log() {
    echo -e "${GREEN}[$(date '+%Y-%m-%d %H:%M:%S')] $1${NC}"
}

error() {
    echo -e "${RED}[$(date '+%Y-%m-%d %H:%M:%S')] ERROR: $1${NC}"
}

warning() {
    echo -e "${YELLOW}[$(date '+%Y-%m-%d %H:%M:%S')] WARNING: $1${NC}"
}

info() {
    echo -e "${BLUE}[$(date '+%Y-%m-%d %H:%M:%S')] INFO: $1${NC}"
}

# Quick fix function
quick_fix() {
    echo "=================================="
    echo "⚡ Quick Fix for 500 Error"
    echo "=================================="
    echo "Directory: $PROJECT_DIR"
    echo "Time: $(date)"
    echo "=================================="
    
    cd "$PROJECT_DIR"
    
    # Load environment variables
    log "Loading environment variables..."
    source .env
    
    # Export critical variables
    export APP_ENV="$APP_ENV"
    export APP_KEY="$APP_KEY"
    export APP_DEBUG="$APP_DEBUG"
    export DB_PASSWORD="$DB_PASSWORD"
    export MIDTRANS_SERVER_KEY="$MIDTRANS_SERVER_KEY"
    export MIDTRANS_CLIENT_KEY="$MIDTRANS_CLIENT_KEY"
    
    # Step 1: Clear all caches
    log "Step 1: Clearing all caches..."
    sudo -u www-data -E php artisan config:clear 2>/dev/null || warning "Config clear failed"
    sudo -u www-data -E php artisan route:clear 2>/dev/null || warning "Route clear failed"
    sudo -u www-data -E php artisan view:clear 2>/dev/null || warning "View clear failed"
    sudo -u www-data -E php artisan cache:clear 2>/dev/null || warning "Cache clear failed"
    
    # Step 2: Install dependencies
    log "Step 2: Installing dependencies..."
    npm install --production 2>/dev/null || warning "NPM install failed"
    
    # Step 3: Build assets (try multiple methods)
    log "Step 3: Building assets..."
    if [ -f "node_modules/.bin/vite" ]; then
        log "Building with local Vite..."
        npx vite build 2>/dev/null || warning "Vite build failed"
    else
        log "Installing dev dependencies for build..."
        npm install 2>/dev/null || warning "NPM install failed"
        if [ -f "node_modules/.bin/vite" ]; then
            npx vite build 2>/dev/null || warning "Vite build failed"
        else
            warning "Skipping asset build - Vite not available"
        fi
    fi
    
    # Step 4: Cache optimization
    log "Step 4: Optimizing caches..."
    sudo -u www-data -E php artisan config:cache 2>/dev/null || warning "Config cache failed"
    sudo -u www-data -E php artisan route:cache 2>/dev/null || warning "Route cache failed"
    sudo -u www-data -E php artisan view:cache 2>/dev/null || warning "View cache failed"
    
    # Step 5: Storage link
    log "Step 5: Creating storage link..."
    if [ ! -L "$PROJECT_DIR/public/storage" ]; then
        sudo -u www-data -E php artisan storage:link 2>/dev/null || warning "Storage link failed"
    else
        log "Storage link already exists"
    fi
    
    # Step 6: Fix permissions
    log "Step 6: Fixing permissions..."
    chown -R www-data:www-data "$PROJECT_DIR"
    chmod -R 755 "$PROJECT_DIR"
    chmod -R 775 "$PROJECT_DIR/storage" 2>/dev/null || warning "Storage permissions failed"
    chmod -R 775 "$PROJECT_DIR/bootstrap/cache" 2>/dev/null || warning "Bootstrap cache permissions failed"
    
    # Step 7: Restart services
    log "Step 7: Restarting services..."
    
    # Find and restart PHP-FPM
    if systemctl is-active --quiet php8.3-fpm; then
        systemctl restart php8.3-fpm
        log "✅ PHP-FPM 8.3 restarted"
    elif systemctl is-active --quiet php8.2-fpm; then
        systemctl restart php8.2-fpm
        log "✅ PHP-FPM 8.2 restarted"
    elif systemctl is-active --quiet php8.1-fpm; then
        systemctl restart php8.1-fpm
        log "✅ PHP-FPM 8.1 restarted"
    else
        warning "⚠️ PHP-FPM service not found"
    fi
    
    # Restart Nginx
    if systemctl is-active --quiet nginx; then
        systemctl restart nginx
        log "✅ Nginx restarted"
    else
        warning "⚠️ Nginx service not found"
    fi
    
    # Step 8: Wait and test
    log "Step 8: Waiting for services to start..."
    sleep 10
    
    # Test application
    log "Step 9: Testing application..."
    if curl -f -s "https://uf25.tams.my.id" > /dev/null 2>&1; then
        log "✅ Application is responding (HTTPS)"
        echo "=================================="
        echo "🎉 SUCCESS! Website is back online!"
        echo "✅ https://uf25.tams.my.id"
        echo "=================================="
    elif curl -f -s "http://uf25.tams.my.id" > /dev/null 2>&1; then
        log "✅ Application is responding (HTTP)"
        echo "=================================="
        echo "🎉 SUCCESS! Website is back online!"
        echo "✅ http://uf25.tams.my.id"
        echo "=================================="
    elif curl -f -s "http://localhost" > /dev/null 2>&1; then
        log "✅ Application is responding (localhost)"
        warning "⚠️ Check domain configuration"
    else
        error "❌ Application is still not responding"
        echo "=================================="
        echo "🔍 Troubleshooting Information:"
        echo "=================================="
        
        # Show recent errors
        log "Recent Laravel errors:"
        if [ -f "$PROJECT_DIR/storage/logs/laravel.log" ]; then
            tail -5 "$PROJECT_DIR/storage/logs/laravel.log" 2>/dev/null || echo "No Laravel logs found"
        else
            echo "Laravel log file not found"
        fi
        
        log "Recent Nginx errors:"
        if [ -f "/var/log/nginx/error.log" ]; then
            tail -5 /var/log/nginx/error.log 2>/dev/null || echo "No Nginx logs found"
        else
            echo "Nginx log file not found"
        fi
        
        echo ""
        log "Manual commands to try:"
        echo "1. Check Laravel config: sudo -u www-data php artisan config:show app.env"
        echo "2. Test database: sudo -u www-data php artisan tinker --execute=\"DB::connection()->getPdo(); echo 'DB_OK';\""
        echo "3. Check logs: tail -f storage/logs/laravel.log"
        echo "4. Check Nginx: tail -f /var/log/nginx/error.log"
    fi
}

# Check if running as root
if [[ $EUID -ne 0 ]]; then
    error "This script must be run as root (use sudo)"
    exit 1
fi

# Run quick fix
quick_fix

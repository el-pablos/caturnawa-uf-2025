#!/bin/bash

# UNAS Fest 2025 - Fix 500 Error Script
# This script fixes the current 500 error on the production server

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

# Check if running as root
check_permissions() {
    if [[ $EUID -ne 0 ]]; then
        error "This script must be run as root (use sudo)"
        exit 1
    fi
}

# Fix 500 error
fix_500_error() {
    echo "=================================="
    echo "🔧 Fixing 500 Internal Server Error"
    echo "=================================="
    echo "Directory: $PROJECT_DIR"
    echo "Time: $(date)"
    echo "=================================="
    
    cd "$PROJECT_DIR"
    
    # Load environment variables
    log "Loading environment variables..."
    if [ -f ".env" ]; then
        source .env
        
        # Export for PHP processes
        export APP_NAME="$APP_NAME"
        export APP_ENV="$APP_ENV"
        export APP_KEY="$APP_KEY"
        export APP_DEBUG="$APP_DEBUG"
        export APP_URL="$APP_URL"
        export DB_CONNECTION="$DB_CONNECTION"
        export DB_HOST="$DB_HOST"
        export DB_PORT="$DB_PORT"
        export DB_DATABASE="$DB_DATABASE"
        export DB_USERNAME="$DB_USERNAME"
        export DB_PASSWORD="$DB_PASSWORD"
        export MIDTRANS_SERVER_KEY="$MIDTRANS_SERVER_KEY"
        export MIDTRANS_CLIENT_KEY="$MIDTRANS_CLIENT_KEY"
        export MIDTRANS_IS_PRODUCTION="$MIDTRANS_IS_PRODUCTION"
        
        log "Environment variables loaded and exported"
    else
        error ".env file not found"
        exit 1
    fi
    
    # Clear all caches first
    log "Clearing all caches..."
    sudo -u www-data -E php artisan config:clear || warning "Config clear failed"
    sudo -u www-data -E php artisan route:clear || warning "Route clear failed"
    sudo -u www-data -E php artisan view:clear || warning "View clear failed"
    sudo -u www-data -E php artisan cache:clear || warning "Cache clear failed"
    
    # Check Laravel configuration
    log "Testing Laravel configuration..."
    if sudo -u www-data -E php artisan config:show app.env 2>/dev/null | grep -q "production"; then
        log "✅ Laravel environment: production"
    else
        error "❌ Laravel environment configuration failed"
        
        # Try to cache config
        log "Attempting to cache configuration..."
        sudo -u www-data -E php artisan config:cache || error "Config cache failed"
    fi
    
    # Test database connection
    log "Testing database connection..."
    if sudo -u www-data -E php artisan tinker --execute="DB::connection()->getPdo(); echo 'DB_OK';" 2>/dev/null | grep -q "DB_OK"; then
        log "✅ Database connection working"
    else
        error "❌ Database connection failed"
        log "Checking database credentials..."
        log "DB_HOST: $DB_HOST"
        log "DB_DATABASE: $DB_DATABASE"
        log "DB_USERNAME: $DB_USERNAME"
        log "DB_PASSWORD: ${DB_PASSWORD:+***set***}"
    fi
    
    # Install missing dependencies
    log "Installing missing dependencies..."
    npm install || warning "NPM install failed"
    
    # Try to build assets
    log "Building frontend assets..."
    if command -v vite >/dev/null 2>&1; then
        npm run build || warning "Asset build failed"
    elif [ -f "node_modules/.bin/vite" ]; then
        npx vite build || warning "Asset build failed"
    else
        warning "Vite not found, skipping asset build"
    fi
    
    # Optimize Laravel
    log "Optimizing Laravel..."
    sudo -u www-data -E php artisan config:cache || warning "Config cache failed"
    sudo -u www-data -E php artisan route:cache || warning "Route cache failed"
    sudo -u www-data -E php artisan view:cache || warning "View cache failed"
    
    # Create storage link if needed
    if [ ! -L "$PROJECT_DIR/public/storage" ]; then
        log "Creating storage link..."
        sudo -u www-data -E php artisan storage:link || warning "Storage link failed"
    fi
    
    # Fix file permissions
    log "Fixing file permissions..."
    chown -R www-data:www-data "$PROJECT_DIR"
    chmod -R 755 "$PROJECT_DIR"
    chmod -R 775 "$PROJECT_DIR/storage"
    chmod -R 775 "$PROJECT_DIR/bootstrap/cache"
    
    # Restart services
    log "Restarting services..."
    if systemctl is-active --quiet php8.3-fpm; then
        systemctl restart php8.3-fpm
        log "PHP-FPM 8.3 restarted"
    elif systemctl is-active --quiet php8.2-fpm; then
        systemctl restart php8.2-fpm
        log "PHP-FPM 8.2 restarted"
    elif systemctl is-active --quiet php8.1-fpm; then
        systemctl restart php8.1-fpm
        log "PHP-FPM 8.1 restarted"
    fi
    
    if systemctl is-active --quiet nginx; then
        systemctl restart nginx
        log "Nginx restarted"
    fi
    
    # Wait for services
    sleep 5
    
    # Test application
    log "Testing application..."
    if curl -f -s "https://uf25.tams.my.id" > /dev/null; then
        log "✅ Application is responding (HTTPS)"
    elif curl -f -s "http://uf25.tams.my.id" > /dev/null; then
        log "✅ Application is responding (HTTP)"
    elif curl -f -s "http://localhost" > /dev/null; then
        log "✅ Application is responding (localhost)"
    else
        error "❌ Application is still not responding"
        
        # Check error logs
        log "Checking error logs..."
        if [ -f "/var/log/nginx/error.log" ]; then
            log "Last 10 lines of Nginx error log:"
            tail -10 /var/log/nginx/error.log
        fi
        
        if [ -f "$PROJECT_DIR/storage/logs/laravel.log" ]; then
            log "Last 10 lines of Laravel log:"
            tail -10 "$PROJECT_DIR/storage/logs/laravel.log"
        fi
    fi
    
    echo "=================================="
    log "🎉 500 Error fix completed!"
    echo "=================================="
    
    log "📋 Next steps if still having issues:"
    log "1. Check Laravel logs: tail -f storage/logs/laravel.log"
    log "2. Check Nginx logs: tail -f /var/log/nginx/error.log"
    log "3. Test specific artisan commands manually"
    log "4. Verify .env file format and content"
}

# Main execution
main() {
    check_permissions
    fix_500_error
}

# Run main function
main "$@"

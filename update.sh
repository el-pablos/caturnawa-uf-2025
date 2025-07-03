#!/bin/bash

# UNAS Fest 2025 - Production Update Script
# Server: uf25.tams.my.id
# Usage: ./update.sh [branch_name]
# Default branch: master

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
PROJECT_NAME="Caturnawa - UNAS Fest 2025"
PROJECT_DIR="/var/www/uf25.tams.my.id"
BRANCH=${1:-master}
BACKUP_DIR="/var/backups/uf25-tams"
LOG_FILE="/var/log/uf25-deploy.log"
DB_NAME="uf25_database"
DB_USER="uf25_user"
DB_PASSWORD="nigajawir"

# Logging functions
log() {
    echo -e "${GREEN}[$(date '+%Y-%m-%d %H:%M:%S')] $1${NC}" | tee -a "$LOG_FILE"
}

error() {
    echo -e "${RED}[$(date '+%Y-%m-%d %H:%M:%S')] ERROR: $1${NC}" | tee -a "$LOG_FILE"
}

warning() {
    echo -e "${YELLOW}[$(date '+%Y-%m-%d %H:%M:%S')] WARNING: $1${NC}" | tee -a "$LOG_FILE"
}

info() {
    echo -e "${BLUE}[$(date '+%Y-%m-%d %H:%M:%S')] INFO: $1${NC}" | tee -a "$LOG_FILE"
}

# Check if running as root
check_permissions() {
    if [[ $EUID -ne 0 ]]; then
        error "This script must be run as root (use sudo)"
        exit 1
    fi
}

# Check if project directory exists
check_project_directory() {
    if [ ! -d "$PROJECT_DIR" ]; then
        error "Project directory $PROJECT_DIR does not exist"
        exit 1
    fi
}

# Load and export environment variables
load_environment() {
    log "Loading environment variables..."
    cd "$PROJECT_DIR"
    
    if [ -f ".env" ]; then
        source .env
        
        # Export critical variables for PHP processes
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
        
        # Validate critical variables
        if [ -z "$DB_PASSWORD" ]; then
            error "DB_PASSWORD not set in .env file"
            exit 1
        fi
    else
        error ".env file not found"
        exit 1
    fi
}

# Create backup
create_backup() {
    log "Creating backup..."
    mkdir -p "$BACKUP_DIR"
    
    BACKUP_NAME="backup-$(date +%Y%m%d-%H%M%S)"
    
    # Backup database
    if mysqldump -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" > "$BACKUP_DIR/$BACKUP_NAME.sql" 2>/dev/null; then
        log "Database backup created: $BACKUP_NAME.sql"
    else
        warning "Database backup failed - continuing without backup"
    fi
    
    # Backup files
    if tar -czf "$BACKUP_DIR/$BACKUP_NAME.tar.gz" -C "$PROJECT_DIR" . 2>/dev/null; then
        log "File backup created: $BACKUP_NAME.tar.gz"
    else
        warning "File backup failed - continuing without backup"
    fi
    
    # Keep only last 5 backups
    cd "$BACKUP_DIR"
    ls -t backup-*.tar.gz 2>/dev/null | tail -n +6 | xargs -r rm
    ls -t backup-*.sql 2>/dev/null | tail -n +6 | xargs -r rm
    
    log "Backup completed"
}

# Enable maintenance mode
enable_maintenance() {
    log "Enabling maintenance mode..."
    cd "$PROJECT_DIR"
    sudo -u www-data -E php artisan down --retry=60 --secret="caturnawa-secret" --render="errors::503" 2>/dev/null || warning "Maintenance mode failed"
    log "Maintenance mode enabled"
}

# Disable maintenance mode
disable_maintenance() {
    log "Disabling maintenance mode..."
    cd "$PROJECT_DIR"
    sudo -u www-data -E php artisan up 2>/dev/null || warning "Disable maintenance failed"
    log "Maintenance mode disabled"
}

# Update code from repository
update_code() {
    log "Updating code from branch: $BRANCH"
    cd "$PROJECT_DIR"
    
    # Stash any local changes
    git stash push -m "Auto-stash before update $(date)" 2>/dev/null || log "No changes to stash"
    
    # Fetch latest changes
    git fetch origin
    
    # Checkout specified branch
    git checkout "$BRANCH"
    
    # Pull latest changes
    git pull origin "$BRANCH"
    
    log "Code updated successfully"
}

# Install dependencies and build assets
install_and_build() {
    log "Installing dependencies..."
    cd "$PROJECT_DIR"
    
    # Install PHP dependencies
    sudo -u www-data -E composer install --no-dev --optimize-autoloader --no-interaction 2>/dev/null || warning "Composer install failed"
    
    # Install NPM dependencies
    npm install 2>/dev/null || warning "NPM install failed"
    
    # Build assets
    log "Building frontend assets..."
    if [ -f "node_modules/.bin/vite" ]; then
        npx vite build 2>/dev/null || warning "Asset build failed"
    else
        warning "Vite not found, skipping asset build"
    fi
    
    log "Dependencies and assets completed"
}

# Run database migrations
run_migrations() {
    log "Running database migrations..."
    cd "$PROJECT_DIR"
    sudo -u www-data -E php artisan migrate --force 2>/dev/null || warning "Migrations failed"
    log "Database migrations completed"
}

# Force fix common issues
force_fix_issues() {
    log "Force fixing common issues..."
    cd "$PROJECT_DIR"

    # Ensure critical directories exist with correct permissions
    mkdir -p storage/framework/{cache,sessions,views}
    mkdir -p storage/logs
    mkdir -p bootstrap/cache
    mkdir -p public/storage

    # Fix ownership and permissions
    chown -R www-data:www-data storage bootstrap/cache
    chmod -R 775 storage bootstrap/cache

    # Remove problematic cache files
    rm -rf storage/framework/cache/*
    rm -rf storage/framework/sessions/*
    rm -rf storage/framework/views/*
    rm -rf bootstrap/cache/*

    # Ensure .env is readable
    chmod 644 .env
    chown www-data:www-data .env

    log "Common issues fixed"
}

# Optimize application
optimize_application() {
    log "Optimizing application..."
    cd "$PROJECT_DIR"

    # Force fix issues first
    force_fix_issues

    # Clear all caches (with better error handling)
    log "Clearing caches..."
    sudo -u www-data -E php artisan config:clear 2>/dev/null || warning "Config clear failed"
    sudo -u www-data -E php artisan route:clear 2>/dev/null || warning "Route clear failed"
    sudo -u www-data -E php artisan view:clear 2>/dev/null || warning "View clear failed"
    sudo -u www-data -E php artisan cache:clear 2>/dev/null || warning "Cache clear failed"

    # Test if artisan is working before caching
    if sudo -u www-data -E php artisan --version >/dev/null 2>&1; then
        log "Caching optimizations..."
        sudo -u www-data -E php artisan config:cache 2>/dev/null || warning "Config cache failed"
        sudo -u www-data -E php artisan route:cache 2>/dev/null || warning "Route cache failed"
        sudo -u www-data -E php artisan view:cache 2>/dev/null || warning "View cache failed"
    else
        warning "Artisan not working, skipping cache optimization"
    fi

    # Create storage link if needed
    if [ ! -L "$PROJECT_DIR/public/storage" ]; then
        sudo -u www-data -E php artisan storage:link 2>/dev/null || warning "Storage link failed"
    fi

    log "Application optimized"
}

# Fix file permissions
fix_permissions() {
    log "Fixing file permissions..."
    cd "$PROJECT_DIR"
    
    chown -R www-data:www-data "$PROJECT_DIR"
    chmod -R 755 "$PROJECT_DIR"
    chmod -R 775 "$PROJECT_DIR/storage" 2>/dev/null || warning "Storage permissions failed"
    chmod -R 775 "$PROJECT_DIR/bootstrap/cache" 2>/dev/null || warning "Bootstrap cache permissions failed"
    
    log "Permissions fixed"
}

# Restart services
restart_services() {
    log "Restarting services..."
    
    # Restart PHP-FPM (detect version)
    if systemctl is-active --quiet php8.3-fpm; then
        systemctl restart php8.3-fpm
        log "PHP-FPM 8.3 restarted"
    elif systemctl is-active --quiet php8.2-fpm; then
        systemctl restart php8.2-fpm
        log "PHP-FPM 8.2 restarted"
    elif systemctl is-active --quiet php8.1-fpm; then
        systemctl restart php8.1-fpm
        log "PHP-FPM 8.1 restarted"
    else
        warning "PHP-FPM service not found"
    fi
    
    # Restart Nginx
    if systemctl is-active --quiet nginx; then
        systemctl restart nginx
        log "Nginx restarted"
    else
        warning "Nginx service not found"
    fi
    
    log "Services restarted"
}

# Debug and fix 500 errors
debug_and_fix() {
    log "Debugging and fixing 500 errors..."
    cd "$PROJECT_DIR"

    # Test Laravel bootstrap
    log "Testing Laravel bootstrap..."
    if ! sudo -u www-data -E php artisan --version >/dev/null 2>&1; then
        error "Laravel bootstrap failed"

        # Try to fix common issues
        log "Attempting to fix bootstrap issues..."

        # Recreate bootstrap cache directory
        rm -rf bootstrap/cache/*
        mkdir -p bootstrap/cache
        chown -R www-data:www-data bootstrap/cache
        chmod -R 775 bootstrap/cache

        # Clear and recreate storage directories
        mkdir -p storage/framework/{cache,sessions,views}
        mkdir -p storage/logs
        chown -R www-data:www-data storage
        chmod -R 775 storage

        # Test again
        if ! sudo -u www-data -E php artisan --version >/dev/null 2>&1; then
            error "Laravel still not working, checking detailed error..."
            sudo -u www-data -E php artisan --version 2>&1 | tail -10
        else
            log "✅ Laravel bootstrap fixed"
        fi
    else
        log "✅ Laravel bootstrap working"
    fi

    # Test database connection
    log "Testing database connection..."
    if ! sudo -u www-data -E php artisan tinker --execute="DB::connection()->getPdo(); echo 'DB_OK';" 2>/dev/null | grep -q "DB_OK"; then
        error "Database connection failed"
        log "Database debug info:"
        log "DB_HOST: ${DB_HOST}"
        log "DB_DATABASE: ${DB_DATABASE}"
        log "DB_USERNAME: ${DB_USERNAME}"
        log "DB_PASSWORD: ${DB_PASSWORD:+***set***}"

        # Test MySQL connection directly
        if mysql -h"${DB_HOST}" -P"${DB_PORT}" -u"${DB_USERNAME}" -p"${DB_PASSWORD}" -e "SELECT 1;" "${DB_DATABASE}" >/dev/null 2>&1; then
            log "✅ MySQL connection works directly"
        else
            error "❌ MySQL connection failed directly"
        fi
    else
        log "✅ Database connection working"
    fi

    # Check critical files
    log "Checking critical files..."
    critical_files=(".env" "composer.json" "artisan" "public/index.php")
    for file in "${critical_files[@]}"; do
        if [ -f "$file" ]; then
            log "✅ $file exists"
        else
            error "❌ $file missing"
        fi
    done

    # Check web server configuration
    log "Checking web server..."
    if nginx -t >/dev/null 2>&1; then
        log "✅ Nginx configuration valid"
    else
        error "❌ Nginx configuration invalid"
        nginx -t 2>&1 | tail -5
    fi
}

# Health check
health_check() {
    log "Running health check..."

    # Wait for services to start
    sleep 10

    # Check if application is responding
    if curl -f -s "https://uf25.tams.my.id" > /dev/null 2>&1; then
        log "✅ Application is responding (HTTPS)"
        return 0
    elif curl -f -s "http://uf25.tams.my.id" > /dev/null 2>&1; then
        log "✅ Application is responding (HTTP)"
        return 0
    elif curl -f -s "http://localhost" > /dev/null 2>&1; then
        log "✅ Application is responding (localhost)"
        return 0
    else
        error "❌ Application is not responding"

        # Run debug and fix
        debug_and_fix

        # Show recent errors for debugging
        log "Recent Laravel errors:"
        if [ -f "$PROJECT_DIR/storage/logs/laravel.log" ]; then
            tail -10 "$PROJECT_DIR/storage/logs/laravel.log" 2>/dev/null || echo "No Laravel logs"
        fi

        log "Recent Nginx errors:"
        if [ -f "/var/log/nginx/error.log" ]; then
            tail -10 /var/log/nginx/error.log 2>/dev/null || echo "No Nginx logs"
        fi

        log "Recent PHP-FPM errors:"
        if [ -f "/var/log/php8.3-fpm.log" ]; then
            tail -5 /var/log/php8.3-fpm.log 2>/dev/null || echo "No PHP-FPM logs"
        fi

        # Try one more time after fixes
        sleep 5
        if curl -f -s "http://localhost" > /dev/null 2>&1; then
            log "✅ Application responding after fixes"
            return 0
        else
            return 1
        fi
    fi
}

# Cleanup function
cleanup() {
    if [ -f "$PROJECT_DIR/storage/framework/down" ]; then
        disable_maintenance
    fi
}

# Main execution
main() {
    echo "=================================="
    echo "🚀 $PROJECT_NAME - Production Update"
    echo "=================================="
    echo "Branch: $BRANCH"
    echo "Directory: $PROJECT_DIR"
    echo "Time: $(date)"
    echo "=================================="
    
    # Set trap for cleanup
    trap cleanup EXIT
    
    # Run all steps
    check_permissions
    check_project_directory
    load_environment
    create_backup
    enable_maintenance
    update_code
    install_and_build
    run_migrations
    optimize_application
    fix_permissions
    restart_services
    disable_maintenance
    
    # Final health check
    if health_check; then
        log "🎉 Update completed successfully!"
        echo "=================================="
        echo "✅ $PROJECT_NAME is now updated"
        echo "✅ Branch: $BRANCH"
        echo "✅ URL: https://uf25.tams.my.id"
        echo "✅ Time: $(date)"
        echo "=================================="
    else
        error "❌ Update completed but health check failed"
        error "Please check the application manually"
        exit 1
    fi
}

# Run main function
main "$@"

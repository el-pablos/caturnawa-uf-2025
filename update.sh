#!/bin/bash

# UNAS Fest 2025 - Production Management Script
# Usage: 
#   ./tam.sh update   -> Update from git with migrations and seeding
#   ./tam.sh reset    -> Reset database but keep superadmin, admin, peserta, juri users
#   ./tam.sh seed     -> Run seeders only
#   ./tam.sh help     -> Show help

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Configuration
PROJECT_DIR="/var/www/uf25.tams.my.id"
COMMAND=${1:-help}

# Helper functions
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
check_root() {
    if [[ $EUID -ne 0 ]]; then
        error "This script must be run as root (use sudo)"
        exit 1
    fi
}

# Setup environment
setup_environment() {
    log "Setting up environment..."
    cd "$PROJECT_DIR"

    # Set environment variables
    export APP_ENV=production
    export DB_CONNECTION=mysql
    export DB_HOST=127.0.0.1
    export DB_PORT=3306
    export DB_DATABASE=uf25_database
    export DB_USERNAME=uf25_user
    export DB_PASSWORD=nigajawir

    log "Environment variables set"
}

# Fix permissions
fix_permissions() {
    log "Fixing permissions..."
    
    # Fix ownership
    chown -R www-data:www-data "$PROJECT_DIR"
    chmod -R 755 "$PROJECT_DIR"
    chmod -R 775 "$PROJECT_DIR/storage"
    chmod -R 775 "$PROJECT_DIR/bootstrap/cache"
    
    log "Permissions fixed"
}

# Clear and recreate cache directories
clear_cache_dirs() {
    log "Clearing cache directories..."
    
    # Remove cache directories
    rm -rf "$PROJECT_DIR/storage/framework/cache"
    rm -rf "$PROJECT_DIR/storage/framework/sessions"
    rm -rf "$PROJECT_DIR/storage/framework/views"
    rm -rf "$PROJECT_DIR/bootstrap/cache"
    
    # Recreate directories
    mkdir -p "$PROJECT_DIR/storage/framework/{cache,sessions,views}"
    mkdir -p "$PROJECT_DIR/bootstrap/cache"
    mkdir -p "$PROJECT_DIR/storage/logs"
    
    # Fix permissions
    chown -R www-data:www-data "$PROJECT_DIR/storage" "$PROJECT_DIR/bootstrap/cache"
    chmod -R 775 "$PROJECT_DIR/storage" "$PROJECT_DIR/bootstrap/cache"
    
    log "Cache directories cleared and recreated"
}

# Clear Laravel caches
clear_laravel_cache() {
    log "Clearing Laravel caches..."
    cd "$PROJECT_DIR"
    
    sudo -u www-data -E php artisan route:clear
    sudo -u www-data -E php artisan config:clear
    sudo -u www-data -E php artisan view:clear
    
    log "Laravel caches cleared"
}

# Run migrations
run_migrations() {
    log "Running database migrations..."
    cd "$PROJECT_DIR"
    
    sudo -u www-data -E php artisan migrate --force
    
    log "Database migrations completed"
}

# Run seeders
run_seeders() {
    log "Running database seeders..."
    cd "$PROJECT_DIR"
    
    sudo -u www-data -E php artisan db:seed --force
    
    log "Database seeders completed"
}

# Backup critical users before reset
backup_users() {
    log "Backing up critical users..."
    cd "$PROJECT_DIR"
    
    # Create backup SQL for critical users
    mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" -e "
    CREATE TABLE IF NOT EXISTS users_backup AS 
    SELECT * FROM users 
    WHERE email IN ('superadmin@unasfest.ac.id', 'admin@unasfest.ac.id') 
    OR role IN ('superadmin', 'admin', 'peserta', 'juri');
    " 2>/dev/null || warning "User backup failed"
    
    log "Critical users backed up"
}

# Restore critical users after reset
restore_users() {
    log "Restoring critical users..."
    cd "$PROJECT_DIR"
    
    # Restore users from backup
    mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" -e "
    INSERT IGNORE INTO users 
    SELECT * FROM users_backup 
    WHERE email IN ('superadmin@unasfest.ac.id', 'admin@unasfest.ac.id') 
    OR role IN ('superadmin', 'admin', 'peserta', 'juri');
    
    DROP TABLE IF EXISTS users_backup;
    " 2>/dev/null || warning "User restore failed"
    
    log "Critical users restored"
}

# Install dependencies
install_dependencies() {
    log "Installing dependencies..."
    cd "$PROJECT_DIR"
    
    # Composer
    sudo -u www-data -E composer install --no-dev --optimize-autoloader --no-interaction
    
    # NPM
    npm install --production
    
    # Build assets
    npx vite build
    
    log "Dependencies installed and assets built"
}

# Restart services
restart_services() {
    log "Restarting services..."
    
    systemctl restart php8.3-fpm
    systemctl restart nginx
    
    log "Services restarted"
}

# Test application
test_application() {
    log "Testing application..."
    
    sleep 5
    
    if curl -f -s -I "https://uf25.tams.my.id" >/dev/null 2>&1; then
        log "✅ Application is responding"
        return 0
    else
        error "❌ Application is not responding"
        return 1
    fi
}

# Show help
show_help() {
    echo "=================================="
    echo "🚀 UNAS Fest 2025 - Production Management"
    echo "=================================="
    echo ""
    echo "Usage: ./tam.sh [command]"
    echo ""
    echo "Commands:"
    echo "  update    Update from git with migrations and seeding"
    echo "  reset     Reset database but keep critical users"
    echo "  seed      Run seeders only"
    echo "  help      Show this help message"
    echo ""
    echo "Examples:"
    echo "  ./tam.sh update   # Full update with git pull"
    echo "  ./tam.sh reset    # Reset database keeping users"
    echo "  ./tam.sh seed     # Run seeders only"
    echo ""
    echo "=================================="
}

# Main update function
do_update() {
    log "🚀 Starting UPDATE process..."
    
    check_root
    setup_environment
    
    cd "$PROJECT_DIR"
    
    # Git operations
    log "Updating code from git..."
    git stash
    git pull origin master
    
    # Fix permissions and cache
    fix_permissions
    clear_cache_dirs
    clear_laravel_cache
    
    # Install dependencies
    install_dependencies
    
    # Run migrations and seeders
    run_migrations
    run_seeders
    
    # Restart services
    restart_services
    
    # Test application
    if test_application; then
        log "✅ UPDATE completed successfully!"
        log "🌐 Website: https://uf25.tams.my.id"
    else
        error "❌ UPDATE completed but application not responding"
        exit 1
    fi
}

# Main reset function
do_reset() {
    log "🔄 Starting RESET process..."
    
    check_root
    setup_environment
    
    # Backup users
    backup_users
    
    # Reset database
    log "Resetting database..."
    cd "$PROJECT_DIR"
    
    # Drop all tables and recreate
    sudo -u www-data -E php artisan migrate:fresh --force
    
    # Restore critical users
    restore_users
    
    # Run seeders
    run_seeders
    
    # Fix permissions and cache
    fix_permissions
    clear_cache_dirs
    clear_laravel_cache
    
    # Restart services
    restart_services
    
    # Test application
    if test_application; then
        log "✅ RESET completed successfully!"
        log "🌐 Website: https://uf25.tams.my.id"
    else
        error "❌ RESET completed but application not responding"
        exit 1
    fi
}

# Main seed function
do_seed() {
    log "🌱 Starting SEED process..."
    
    check_root
    setup_environment
    
    # Run seeders
    run_seeders
    
    # Fix permissions and cache
    fix_permissions
    clear_cache_dirs
    clear_laravel_cache
    
    # Restart services
    restart_services
    
    # Test application
    if test_application; then
        log "✅ SEED completed successfully!"
        log "🌐 Website: https://uf25.tams.my.id"
    else
        error "❌ SEED completed but application not responding"
        exit 1
    fi
}

# Main execution
case $COMMAND in
    update)
        do_update
        ;;
    reset)
        echo "⚠️  WARNING: This will reset the database but keep critical users!"
        echo "Are you sure you want to continue? (y/N)"
        read -r confirm
        if [[ $confirm =~ ^[Yy]$ ]]; then
            do_reset
        else
            log "Reset cancelled"
        fi
        ;;
    seed)
        do_seed
        ;;
    help|*)
        show_help
        ;;
esac
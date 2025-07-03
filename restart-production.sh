#!/bin/bash

# UNAS Fest 2025 - Production Restart Script
# Server: uf25.tams.my.id
# Usage: ./restart-production.sh [branch_name]
# Default branch: master
#
# This script will:
# 1. Create backup of database and files
# 2. Enable maintenance mode
# 3. Pull latest code from repository
# 4. Install/update dependencies
# 5. Run database migrations
# 6. Build frontend assets
# 7. Optimize Laravel caches
# 8. Fix file permissions
# 9. Restart web services
# 10. Disable maintenance mode
# 11. Run health checks

set -e  # Exit on any error

# Configuration
PROJECT_NAME="UNAS Fest 2025"
PROJECT_DIR="/var/www/uf25.tams.my.id"
BRANCH=${1:-master}
BACKUP_DIR="/var/backups/uf25-tams"
LOG_FILE="/var/log/uf25-deploy.log"
MAINTENANCE_FILE="$PROJECT_DIR/storage/framework/down"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Logging function
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

# Create backup
create_backup() {
    log "Creating backup..."
    
    # Create backup directory if it doesn't exist
    mkdir -p "$BACKUP_DIR"
    
    # Create backup with timestamp
    BACKUP_NAME="backup-$(date +%Y%m%d-%H%M%S)"
    
    # Backup database
    if mysqldump -u root -p"$DB_PASSWORD" "$DB_NAME" > "$BACKUP_DIR/$BACKUP_NAME.sql" 2>/dev/null; then
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
    ls -t backup-*.tar.gz | tail -n +6 | xargs -r rm
    ls -t backup-*.sql | tail -n +6 | xargs -r rm
    
    log "Backup completed"
}

# Put application in maintenance mode
enable_maintenance() {
    log "Enabling maintenance mode..."
    cd "$PROJECT_DIR"
    sudo -u www-data php artisan down --retry=60 --secret="unas-fest-secret" --render="errors::503"
    log "Maintenance mode enabled"
}

# Remove maintenance mode
disable_maintenance() {
    log "Disabling maintenance mode..."
    cd "$PROJECT_DIR"
    sudo -u www-data php artisan up
    log "Maintenance mode disabled"
}

# Pull latest code
pull_code() {
    log "Pulling latest code from branch: $BRANCH"
    cd "$PROJECT_DIR"
    
    # Stash any local changes
    git stash
    
    # Fetch latest changes
    git fetch origin
    
    # Checkout specified branch
    git checkout "$BRANCH"
    
    # Pull latest changes
    git pull origin "$BRANCH"
    
    log "Code updated successfully"
}

# Install dependencies
install_dependencies() {
    log "Installing Composer dependencies..."
    cd "$PROJECT_DIR"
    
    # Install PHP dependencies
    sudo -u www-data composer install --no-dev --optimize-autoloader --no-interaction
    
    log "Installing NPM dependencies..."
    npm ci --production
    
    log "Dependencies installed successfully"
}

# Run database migrations
run_migrations() {
    log "Running database migrations..."
    cd "$PROJECT_DIR"
    
    # Run migrations
    sudo -u www-data php artisan migrate --force
    
    log "Database migrations completed"
}

# Clear and optimize caches
optimize_application() {
    log "Optimizing application..."
    cd "$PROJECT_DIR"
    
    # Clear all caches
    sudo -u www-data php artisan config:clear
    sudo -u www-data php artisan route:clear
    sudo -u www-data php artisan view:clear
    sudo -u www-data php artisan cache:clear
    
    # Optimize for production
    sudo -u www-data php artisan config:cache
    sudo -u www-data php artisan route:cache
    sudo -u www-data php artisan view:cache
    sudo -u www-data php artisan event:cache
    
    log "Application optimized"
}

# Build frontend assets
build_assets() {
    log "Building frontend assets..."
    cd "$PROJECT_DIR"
    
    # Build production assets
    npm run build
    
    log "Frontend assets built successfully"
}

# Fix permissions
fix_permissions() {
    log "Fixing file permissions..."
    cd "$PROJECT_DIR"
    
    # Set proper ownership
    chown -R www-data:www-data "$PROJECT_DIR"
    
    # Set proper permissions
    chmod -R 755 "$PROJECT_DIR"
    chmod -R 775 "$PROJECT_DIR/storage"
    chmod -R 775 "$PROJECT_DIR/bootstrap/cache"
    
    log "Permissions fixed"
}

# Restart services
restart_services() {
    log "Restarting services..."
    
    # Restart PHP-FPM
    if systemctl is-active --quiet php8.1-fpm; then
        systemctl restart php8.1-fpm
        log "PHP-FPM restarted"
    elif systemctl is-active --quiet php8.2-fpm; then
        systemctl restart php8.2-fpm
        log "PHP-FPM restarted"
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
    
    # Restart Redis (if available)
    if systemctl is-active --quiet redis; then
        systemctl restart redis
        log "Redis restarted"
    fi
    
    # Restart Queue Worker (if configured)
    if systemctl is-active --quiet laravel-worker; then
        systemctl restart laravel-worker
        log "Laravel Worker restarted"
    fi
    
    log "Services restarted successfully"
}

# Health check
health_check() {
    log "Running health check..."
    cd "$PROJECT_DIR"
    
    # Check if application is responding
    if curl -f -s "http://uf25.tams.my.id" > /dev/null; then
        log "✅ Application is responding"
    elif curl -f -s "http://localhost" > /dev/null; then
        log "✅ Application is responding (localhost)"
    else
        error "❌ Application is not responding"
        return 1
    fi
    
    # Check database connection
    if sudo -u www-data php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database OK';" | grep -q "Database OK"; then
        log "✅ Database connection is working"
    else
        error "❌ Database connection failed"
        return 1
    fi
    
    # Check storage permissions
    if sudo -u www-data touch "$PROJECT_DIR/storage/test-file" && rm "$PROJECT_DIR/storage/test-file"; then
        log "✅ Storage permissions are correct"
    else
        error "❌ Storage permissions are incorrect"
        return 1
    fi
    
    log "Health check completed successfully"
}

# Cleanup function
cleanup() {
    if [ -f "$MAINTENANCE_FILE" ]; then
        disable_maintenance
    fi
}

# Main execution
main() {
    echo "=================================="
    echo "🚀 $PROJECT_NAME - Production Restart"
    echo "=================================="
    echo "Branch: $BRANCH"
    echo "Directory: $PROJECT_DIR"
    echo "Time: $(date)"
    echo "=================================="
    
    # Set trap for cleanup
    trap cleanup EXIT
    
    # Run checks
    check_permissions
    check_project_directory
    
    # Load environment variables
    if [ -f "$PROJECT_DIR/.env" ]; then
        source "$PROJECT_DIR/.env"
    else
        error ".env file not found"
        exit 1
    fi
    
    # Start deployment
    log "Starting deployment process..."
    
    # Create backup
    create_backup
    
    # Enable maintenance mode
    enable_maintenance
    
    # Pull latest code
    pull_code
    
    # Install dependencies
    install_dependencies
    
    # Run migrations
    run_migrations
    
    # Build assets
    build_assets
    
    # Optimize application
    optimize_application
    
    # Fix permissions
    fix_permissions
    
    # Restart services
    restart_services
    
    # Wait a moment for services to start
    sleep 5
    
    # Disable maintenance mode
    disable_maintenance
    
    # Run health check
    if health_check; then
        log "🎉 Deployment completed successfully!"
        echo "=================================="
        echo "✅ $PROJECT_NAME is now updated"
        echo "✅ Branch: $BRANCH"
        echo "✅ Directory: $PROJECT_DIR"
        echo "✅ Time: $(date)"
        echo "=================================="
    else
        error "❌ Deployment completed but health check failed"
        error "Please check the application manually"
        exit 1
    fi
}

# Run main function
main "$@"
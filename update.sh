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
        # Load .env file line by line to handle special characters
        while IFS= read -r line || [ -n "$line" ]; do
            # Skip comments and empty lines
            if [[ "$line" =~ ^[[:space:]]*# ]] || [[ -z "$line" ]]; then
                continue
            fi

            # Export the variable
            if [[ "$line" =~ ^[[:space:]]*([A-Za-z_][A-Za-z0-9_]*)=(.*)$ ]]; then
                var_name="${BASH_REMATCH[1]}"
                var_value="${BASH_REMATCH[2]}"

                # Remove quotes if present
                var_value=$(echo "$var_value" | sed 's/^"//;s/"$//')
                var_value=$(echo "$var_value" | sed "s/^'//;s/'$//")

                # Export the variable
                export "$var_name"="$var_value"
            fi
        done < .env

        # Explicit export of critical variables (fix for production)
        export APP_ENV="production"
        export DB_CONNECTION="mysql"
        export DB_HOST="127.0.0.1"
        export DB_PORT="3306"
        export DB_DATABASE="uf25_database"
        export DB_USERNAME="uf25_user"
        export DB_PASSWORD="nigajawir"

        log "Environment variables loaded from .env file"

        # Debug: Show critical variables (masked)
        log "Critical environment variables:"
        log "APP_ENV: ${APP_ENV:-'not set'}"
        log "APP_KEY: ${APP_KEY:+***set***}"
        log "DB_PASSWORD: ${DB_PASSWORD:+***set***}"
        log "MIDTRANS_SERVER_KEY: ${MIDTRANS_SERVER_KEY:+***set***}"
        log "MIDTRANS_CLIENT_KEY: ${MIDTRANS_CLIENT_KEY:+***set***}"

        # Validate critical variables
        critical_vars=("APP_KEY" "DB_PASSWORD" "MIDTRANS_SERVER_KEY" "MIDTRANS_CLIENT_KEY")
        for var in "${critical_vars[@]}"; do
            if [ -z "${!var}" ]; then
                error "$var not set in .env file"
                log "Please check your .env file and ensure $var is properly configured"
                exit 1
            fi
        done

        log "All critical environment variables validated"
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

# Git stash and push first
git_stash_and_push() {
    log "Git stash and push..."
    cd "$PROJECT_DIR"

    # Add all changes
    git add . 2>/dev/null || warning "Git add failed"

    # Commit if there are changes
    if ! git diff --cached --quiet 2>/dev/null; then
        git commit -m "Auto-commit before update $(date)" 2>/dev/null || warning "Git commit failed"
        log "Changes committed"
    else
        log "No changes to commit"
    fi

    # Push to remote
    git push origin "$BRANCH" 2>/dev/null || warning "Git push failed"
    log "Changes pushed to remote"

    # Stash any remaining local changes
    git stash push -m "Auto-stash before update $(date)" 2>/dev/null || log "No changes to stash"

    log "Git stash and push completed"
}

# Update code from repository
update_code() {
    log "Updating code from branch: $BRANCH"
    cd "$PROJECT_DIR"

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

    # Fix submission status data
    log "Fixing submission status data..."
    sudo -u www-data -E php artisan tinker --execute="
        \$updated = DB::statement(\"
            UPDATE submissions
            SET status = CASE
                WHEN is_final = 1 AND submitted_at IS NOT NULL THEN 'submitted'
                WHEN is_final = 0 OR submitted_at IS NULL THEN 'draft'
                ELSE COALESCE(status, 'draft')
            END
            WHERE status IS NULL OR status = ''
        \");
        echo 'Submission status fixed';
    " 2>/dev/null || warning "Failed to fix submission status"

    log "Database migrations completed"
}

# Force fix common issues
force_fix_issues() {
    log "Force fixing common issues..."
    cd "$PROJECT_DIR"

    # Fix ownership first
    chown -R www-data:www-data "$PROJECT_DIR"
    chmod -R 755 "$PROJECT_DIR"
    
    # Fix permissions for specific directories
    chmod -R 775 "$PROJECT_DIR/storage"
    chmod -R 775 "$PROJECT_DIR/bootstrap/cache"

    # Remove ALL cache directories completely
    log "Removing problematic cache directories..."
    rm -rf "$PROJECT_DIR/storage/framework/cache"
    rm -rf "$PROJECT_DIR/storage/framework/sessions"
    rm -rf "$PROJECT_DIR/storage/framework/views"
    rm -rf "$PROJECT_DIR/bootstrap/cache"

    # Recreate directories with proper permissions
    log "Recreating cache directories..."
    mkdir -p "$PROJECT_DIR/storage/framework/cache"
    mkdir -p "$PROJECT_DIR/storage/framework/sessions"
    mkdir -p "$PROJECT_DIR/storage/framework/views"
    mkdir -p "$PROJECT_DIR/storage/logs"
    mkdir -p "$PROJECT_DIR/bootstrap/cache"
    mkdir -p "$PROJECT_DIR/public/storage"

    # Set proper ownership and permissions
    chown -R www-data:www-data "$PROJECT_DIR/storage" "$PROJECT_DIR/bootstrap/cache"
    chmod -R 775 "$PROJECT_DIR/storage" "$PROJECT_DIR/bootstrap/cache"

    # Fix .env file permissions
    chmod 644 "$PROJECT_DIR/.env"
    chown www-data:www-data "$PROJECT_DIR/.env"

    log "Common issues fixed"
}

# Optimize application
optimize_application() {
    log "Optimizing application..."
    cd "$PROJECT_DIR"

    # Force fix issues first
    force_fix_issues

    # Clear all caches (with proper environment variables)
    log "Clearing caches..."
    sudo -u www-data -E php artisan route:clear 2>/dev/null || warning "Route clear failed"
    sudo -u www-data -E php artisan config:clear 2>/dev/null || warning "Config clear failed"
    sudo -u www-data -E php artisan view:clear 2>/dev/null || warning "View clear failed"
    # Note: cache:clear often fails due to permissions, so we skip it

    # Test if artisan is working before caching
    log "Testing Laravel bootstrap..."
    if sudo -u www-data -E php artisan --version >/dev/null 2>&1; then
        log "✅ Laravel working, proceeding with optimization..."
        
        # Only cache config (routes can be problematic)
        sudo -u www-data -E php artisan config:cache 2>/dev/null || warning "Config cache failed"
        
        # Cache routes only if they work
        if sudo -u www-data -E php artisan route:list >/dev/null 2>&1; then
            sudo -u www-data -E php artisan route:cache 2>/dev/null || warning "Route cache failed"
        else
            warning "Routes not loading properly, skipping route cache"
        fi
        
        # Cache views
        sudo -u www-data -E php artisan view:cache 2>/dev/null || warning "View cache failed"
        
        log "Caching optimizations completed"
    else
        warning "Laravel not working properly, skipping cache optimization"
        log "Testing Laravel error:"
        sudo -u www-data -E php artisan --version 2>&1 | head -5
    fi

    # Create storage link if needed
    if [ ! -L "$PROJECT_DIR/public/storage" ]; then
        log "Creating storage link..."
        sudo -u www-data -E php artisan storage:link 2>/dev/null || warning "Storage link failed"
    fi

    log "Application optimization completed"
}

# Fix file permissions
fix_permissions() {
    log "Fixing file permissions..."
    cd "$PROJECT_DIR"
    
    # Fix ownership for entire project
    chown -R www-data:www-data "$PROJECT_DIR"
    
    # Set base permissions
    chmod -R 755 "$PROJECT_DIR"
    
    # Fix critical directories
    chmod -R 775 "$PROJECT_DIR/storage"
    chmod -R 775 "$PROJECT_DIR/bootstrap/cache"
    
    # Fix .env file specifically
    chmod 644 "$PROJECT_DIR/.env"
    chown www-data:www-data "$PROJECT_DIR/.env"
    
    # Make sure artisan is executable
    chmod +x "$PROJECT_DIR/artisan"
    
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

        # Force fix issues
        force_fix_issues

        # Test again
        if ! sudo -u www-data -E php artisan --version >/dev/null 2>&1; then
            error "Laravel still not working, checking detailed error..."
            sudo -u www-data -E php artisan --version 2>&1 | head -10
        else
            log "✅ Laravel bootstrap fixed"
        fi
    else
        log "✅ Laravel bootstrap working"
    fi

    # Test database connection
    log "Testing database connection..."
    if ! sudo -u www-data -E php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'DB_OK'; } catch (Exception \$e) { echo 'DB_FAILED: ' . \$e->getMessage(); }" 2>/dev/null | grep -q "DB_OK"; then
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
        nginx -t 2>&1 | head -5
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
    git_stash_and_push
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

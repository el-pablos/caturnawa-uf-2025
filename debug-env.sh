#!/bin/bash

# UNAS Fest 2025 - Environment Debug Script
# Usage: ./debug-env.sh
# This script helps debug environment variable issues

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

# Main debug function
debug_environment() {
    echo "=================================="
    echo "🔍 Environment Debug - UNAS Fest 2025"
    echo "=================================="
    echo "Directory: $PROJECT_DIR"
    echo "Time: $(date)"
    echo "=================================="
    
    cd "$PROJECT_DIR"
    
    # Check .env file existence
    log "Checking .env file..."
    if [ -f ".env" ]; then
        log "✅ .env file exists"
        log "File size: $(stat -c%s .env) bytes"
        log "File permissions: $(stat -c%a .env)"
        log "File owner: $(stat -c%U:%G .env)"
    else
        error "❌ .env file not found"
        return 1
    fi
    
    # Load environment variables
    log "Loading environment variables..."
    source .env
    
    # Check critical variables
    log "Checking critical environment variables..."
    
    critical_vars=(
        "APP_NAME"
        "APP_ENV" 
        "APP_KEY"
        "APP_DEBUG"
        "APP_URL"
        "DB_CONNECTION"
        "DB_HOST"
        "DB_PORT"
        "DB_DATABASE"
        "DB_USERNAME"
        "DB_PASSWORD"
        "MIDTRANS_SERVER_KEY"
        "MIDTRANS_CLIENT_KEY"
        "MIDTRANS_IS_PRODUCTION"
    )
    
    for var in "${critical_vars[@]}"; do
        if grep -q "^$var=" .env; then
            value=$(grep "^$var=" .env | cut -d'=' -f2- | tr -d '"' | tr -d "'")
            if [ -n "$value" ]; then
                if [[ "$var" == *"PASSWORD"* ]] || [[ "$var" == *"KEY"* ]]; then
                    log "✅ $var: ***set*** (${#value} chars)"
                else
                    log "✅ $var: $value"
                fi
            else
                error "❌ $var: EMPTY"
            fi
        else
            error "❌ $var: NOT FOUND"
        fi
    done
    
    # Test database connection
    log "Testing database connection..."
    if sudo -u www-data php artisan tinker --execute="
        try {
            DB::connection()->getPdo();
            echo 'DB_CONNECTION_OK';
        } catch (Exception \$e) {
            echo 'DB_CONNECTION_FAILED: ' . \$e->getMessage();
        }
    " 2>/dev/null | grep -q "DB_CONNECTION_OK"; then
        log "✅ Database connection successful"
    else
        error "❌ Database connection failed"
        
        # Additional database debug
        log "Database debug information:"
        log "DB_CONNECTION: ${DB_CONNECTION:-'not set'}"
        log "DB_HOST: ${DB_HOST:-'not set'}"
        log "DB_PORT: ${DB_PORT:-'not set'}"
        log "DB_DATABASE: ${DB_DATABASE:-'not set'}"
        log "DB_USERNAME: ${DB_USERNAME:-'not set'}"
        log "DB_PASSWORD: ${DB_PASSWORD:+'***set***'}"
        
        # Test MySQL connection manually
        if command -v mysql >/dev/null 2>&1; then
            log "Testing MySQL connection manually..."
            if mysql -h"${DB_HOST}" -P"${DB_PORT}" -u"${DB_USERNAME}" -p"${DB_PASSWORD}" -e "SELECT 1;" "${DB_DATABASE}" >/dev/null 2>&1; then
                log "✅ MySQL connection works directly"
            else
                error "❌ MySQL connection failed directly"
            fi
        fi
    fi
    
    # Test Laravel configuration
    log "Testing Laravel configuration..."
    if sudo -u www-data php artisan config:show app.env 2>/dev/null | grep -q "production"; then
        log "✅ Laravel environment: production"
    else
        warning "⚠️ Laravel environment is not production"
    fi
    
    # Check file permissions
    log "Checking file permissions..."
    log "Project directory owner: $(stat -c%U:%G $PROJECT_DIR)"
    log "Storage directory permissions: $(stat -c%a $PROJECT_DIR/storage 2>/dev/null || echo 'not found')"
    log "Bootstrap cache permissions: $(stat -c%a $PROJECT_DIR/bootstrap/cache 2>/dev/null || echo 'not found')"
    
    # Check web server
    log "Checking web server..."
    if systemctl is-active --quiet nginx; then
        log "✅ Nginx is running"
    else
        warning "⚠️ Nginx is not running"
    fi
    
    if systemctl is-active --quiet php8.1-fpm; then
        log "✅ PHP-FPM 8.1 is running"
    elif systemctl is-active --quiet php8.2-fpm; then
        log "✅ PHP-FPM 8.2 is running"
    else
        warning "⚠️ PHP-FPM is not running"
    fi
    
    echo "=================================="
    log "🎉 Environment debug completed!"
    echo "=================================="
}

# Main execution
main() {
    check_permissions
    debug_environment
}

# Run main function
main "$@"

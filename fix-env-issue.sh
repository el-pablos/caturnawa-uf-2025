#!/bin/bash

# UNAS Fest 2025 - Fix Environment Variable Issue
# This script specifically addresses the DB_PASSWORD environment variable issue

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

# Fix environment variable issue
fix_env_issue() {
    echo "=================================="
    echo "🔧 Fixing Environment Variable Issue"
    echo "=================================="
    echo "Directory: $PROJECT_DIR"
    echo "Time: $(date)"
    echo "=================================="
    
    cd "$PROJECT_DIR"
    
    # Check .env file
    log "Checking .env file..."
    if [ ! -f ".env" ]; then
        error ".env file not found"
        exit 1
    fi
    
    # Load environment variables
    log "Loading environment variables..."
    source .env
    
    # Create a temporary environment file for PHP
    log "Creating temporary environment file for PHP..."
    cat > /tmp/laravel_env.sh << EOF
#!/bin/bash
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
export MIDTRANS_IS_SANITIZED="$MIDTRANS_IS_SANITIZED"
export MIDTRANS_IS_3DS="$MIDTRANS_IS_3DS"
EOF
    
    chmod +x /tmp/laravel_env.sh
    
    # Test environment variables
    log "Testing environment variables..."
    source /tmp/laravel_env.sh
    
    if [ -z "$DB_PASSWORD" ]; then
        error "DB_PASSWORD is still not set"
        exit 1
    else
        log "✅ DB_PASSWORD is set: ${#DB_PASSWORD} characters"
    fi
    
    # Test Laravel configuration
    log "Testing Laravel configuration..."
    if source /tmp/laravel_env.sh && sudo -u www-data -E php artisan config:show app.env 2>/dev/null | grep -q "production"; then
        log "✅ Laravel can read environment variables"
    else
        error "❌ Laravel cannot read environment variables"
        
        # Alternative approach: create .env.production
        log "Creating .env.production file..."
        cp .env .env.production
        chown www-data:www-data .env.production
        chmod 644 .env.production
        
        log "Testing with .env.production..."
        if sudo -u www-data php -r "
            \$_ENV['APP_ENV'] = 'production';
            require_once 'vendor/autoload.php';
            \$app = require_once 'bootstrap/app.php';
            echo 'Laravel bootstrap OK';
        " 2>/dev/null | grep -q "Laravel bootstrap OK"; then
            log "✅ Laravel bootstrap works"
        else
            error "❌ Laravel bootstrap failed"
        fi
    fi
    
    # Test database connection directly
    log "Testing database connection directly..."
    if mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" -e "SELECT 1;" "$DB_DATABASE" >/dev/null 2>&1; then
        log "✅ Direct MySQL connection works"
    else
        error "❌ Direct MySQL connection failed"
        log "DB_HOST: $DB_HOST"
        log "DB_PORT: $DB_PORT"
        log "DB_DATABASE: $DB_DATABASE"
        log "DB_USERNAME: $DB_USERNAME"
        log "DB_PASSWORD: ${DB_PASSWORD:+***set***}"
    fi
    
    # Test artisan command with explicit environment
    log "Testing artisan command with explicit environment..."
    if source /tmp/laravel_env.sh && sudo -u www-data -E php artisan env 2>/dev/null | grep -q "production"; then
        log "✅ Artisan can read environment"
    else
        warning "⚠️ Artisan environment test failed"
    fi
    
    # Clean up
    rm -f /tmp/laravel_env.sh
    
    echo "=================================="
    log "🎉 Environment fix completed!"
    echo "=================================="
    
    # Provide recommendations
    echo ""
    log "📋 Recommendations:"
    log "1. Try running: source .env && sudo -u www-data -E php artisan down"
    log "2. Or use: sudo -u www-data APP_ENV=production DB_PASSWORD='$DB_PASSWORD' php artisan down"
    log "3. Check if .env file has proper line endings (Unix LF, not Windows CRLF)"
    log "4. Ensure no hidden characters in .env file"
}

# Main execution
main() {
    check_permissions
    fix_env_issue
}

# Run main function
main "$@"

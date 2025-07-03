#!/bin/bash

# Quick fix for MIDTRANS environment variables issue

set -e

PROJECT_DIR="/var/www/uf25.tams.my.id"

echo "🔧 Fixing MIDTRANS environment variables..."

cd "$PROJECT_DIR"

# Load .env file properly
if [ -f ".env" ]; then
    echo "Loading .env file..."
    
    # Load environment variables line by line
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
    
    echo "Environment variables loaded"
    
    # Show critical variables
    echo "APP_ENV: ${APP_ENV:-'not set'}"
    echo "APP_KEY: ${APP_KEY:+***set***}"
    echo "DB_PASSWORD: ${DB_PASSWORD:+***set***}"
    echo "MIDTRANS_SERVER_KEY: ${MIDTRANS_SERVER_KEY:+***set***}"
    echo "MIDTRANS_CLIENT_KEY: ${MIDTRANS_CLIENT_KEY:+***set***}"
    
    # Check if MIDTRANS variables are set
    if [ -z "$MIDTRANS_SERVER_KEY" ]; then
        echo "❌ MIDTRANS_SERVER_KEY not found in environment"
        echo "Checking .env file content:"
        grep -n "MIDTRANS_SERVER_KEY" .env || echo "MIDTRANS_SERVER_KEY not found in .env"
        exit 1
    fi
    
    if [ -z "$MIDTRANS_CLIENT_KEY" ]; then
        echo "❌ MIDTRANS_CLIENT_KEY not found in environment"
        echo "Checking .env file content:"
        grep -n "MIDTRANS_CLIENT_KEY" .env || echo "MIDTRANS_CLIENT_KEY not found in .env"
        exit 1
    fi
    
    echo "✅ MIDTRANS variables are set"
    
    # Clear caches with proper environment
    echo "Clearing caches with proper environment..."
    sudo -u www-data -E php artisan config:clear
    sudo -u www-data -E php artisan route:clear
    sudo -u www-data -E php artisan view:clear
    sudo -u www-data -E php artisan cache:clear
    
    # Test Laravel with environment
    echo "Testing Laravel with environment..."
    if sudo -u www-data -E php artisan --version >/dev/null 2>&1; then
        echo "✅ Laravel working with environment"
        
        # Cache configuration
        echo "Caching configuration..."
        sudo -u www-data -E php artisan config:cache
        sudo -u www-data -E php artisan route:cache
        
        echo "✅ Configuration cached successfully"
    else
        echo "❌ Laravel still not working"
        sudo -u www-data -E php artisan --version
        exit 1
    fi
    
    # Restart services
    echo "Restarting services..."
    systemctl restart php8.3-fpm nginx
    
    # Wait and test
    sleep 10
    echo "Testing application..."
    
    if curl -f -s "https://uf25.tams.my.id" > /dev/null 2>&1; then
        echo "✅ SUCCESS! Application is working"
        echo "🎉 https://uf25.tams.my.id is now online"
    else
        echo "❌ Application still not responding"
        echo "Recent Laravel errors:"
        tail -5 storage/logs/laravel.log 2>/dev/null || echo "No Laravel logs"
    fi
    
else
    echo "❌ .env file not found"
    exit 1
fi

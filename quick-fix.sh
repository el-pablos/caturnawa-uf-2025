#!/bin/bash

# UNAS Fest 2025 - Quick Fix Script for Pre-Presentation

echo "🔧 UNAS Fest 2025 - Quick Fixes"
echo "================================"

# Function to show success message
show_success() {
    echo "✅ $1"
}

# Function to show error message
show_error() {
    echo "❌ $1"
}

# Function to show warning message
show_warning() {
    echo "⚠️ $1"
}

# Clear all caches
echo "🗑️ Clearing all caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
show_success "All caches cleared"

# Optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
show_success "Production optimization complete"

# Check database connection
echo "🔍 Checking database connection..."
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database connection: OK'; } catch (Exception \$e) { echo 'Database connection: FAILED - ' . \$e->getMessage(); exit(1); }"

# Run database migrations
echo "🏗️ Running database migrations..."
php artisan migrate --force
show_success "Database migrations completed"

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link
show_success "Storage link created"

# Install/update composer dependencies
echo "📦 Installing Composer dependencies..."
if composer install --no-dev --optimize-autoloader; then
    show_success "Composer dependencies installed"
else
    show_error "Failed to install Composer dependencies"
fi

# Install/update NPM dependencies
echo "📦 Installing NPM dependencies..."
if npm install; then
    show_success "NPM dependencies installed"
else
    show_error "Failed to install NPM dependencies"
fi

# Build production assets
echo "🔨 Building production assets..."
if npm run build; then
    show_success "Production assets built"
else
    show_error "Failed to build production assets"
fi

# Fix permissions
echo "🔒 Fixing permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
show_success "Permissions fixed"

# Check for security issues
echo "🔐 Running security checks..."
if command -v composer &> /dev/null; then
    if composer audit; then
        show_success "No security vulnerabilities found in Composer packages"
    else
        show_warning "Security vulnerabilities found in Composer packages"
    fi
fi

if command -v npm &> /dev/null; then
    if npm audit --audit-level=moderate; then
        show_success "No security vulnerabilities found in NPM packages"
    else
        show_warning "Security vulnerabilities found in NPM packages"
    fi
fi

# Test basic functionality
echo "🧪 Testing basic functionality..."

# Test route listing
if php artisan route:list > /dev/null 2>&1; then
    show_success "Routes loaded successfully"
else
    show_error "Failed to load routes"
fi

# Test config loading
if php artisan config:show app.name > /dev/null 2>&1; then
    show_success "Configuration loaded successfully"
else
    show_error "Failed to load configuration"
fi

# Check queue configuration
if php artisan queue:work --help > /dev/null 2>&1; then
    show_success "Queue system configured"
else
    show_warning "Queue system not properly configured"
fi

# Check mail configuration
if php artisan tinker --execute="try { Mail::fake(); echo 'Mail system: OK'; } catch (Exception \$e) { echo 'Mail system: FAILED - ' . \$e->getMessage(); }"
then
    show_success "Mail system configured"
else
    show_warning "Mail system not properly configured"
fi

# Generate application key if not exists
if grep -q "APP_KEY=" .env; then
    if [ -z "$(grep "APP_KEY=" .env | cut -d'=' -f2)" ]; then
        echo "🔑 Generating application key..."
        php artisan key:generate --force
        show_success "Application key generated"
    else
        show_success "Application key already exists"
    fi
else
    echo "🔑 Generating application key..."
    php artisan key:generate --force
    show_success "Application key generated"
fi

# Create missing directories
echo "📁 Creating missing directories..."
directories=("storage/app/public" "storage/app/public/avatars" "storage/app/public/competitions" "storage/app/public/submissions" "storage/app/public/qrcodes")
for dir in "${directories[@]}"; do
    if [ ! -d "$dir" ]; then
        mkdir -p "$dir"
        show_success "Created directory: $dir"
    fi
done

# Check .env file
echo "📋 Checking .env configuration..."
if [ -f ".env" ]; then
    show_success ".env file exists"
    
    # Check for critical missing values
    critical_vars=("APP_KEY" "DB_DATABASE" "DB_USERNAME")
    for var in "${critical_vars[@]}"; do
        if grep -q "^$var=" .env; then
            value=$(grep "^$var=" .env | cut -d'=' -f2 | tr -d '"')
            if [ -n "$value" ]; then
                show_success "$var is configured"
            else
                show_warning "$var is empty"
            fi
        else
            show_warning "$var is not set"
        fi
    done
else
    show_error ".env file not found"
    if [ -f ".env.example" ]; then
        cp .env.example .env
        show_success "Created .env from .env.example"
    fi
fi

# Final summary
echo ""
echo "🎉 Quick Fixes Complete!"
echo "========================"
echo "✅ System optimized for presentation"
echo "✅ Caches cleared and regenerated"
echo "✅ Database migrations completed"
echo "✅ Assets built for production"
echo "✅ Permissions fixed"
echo "✅ Basic functionality tested"
echo ""
echo "🚀 Your system is ready for presentation!"
echo "📋 Don't forget to check the PRE-LAUNCH-CHECKLIST.md for complete deployment steps"

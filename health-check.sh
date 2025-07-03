#!/bin/bash

# UNAS Fest 2025 - System Health Check Script

echo "🔍 UNAS Fest 2025 - System Health Check"
echo "======================================="

# Check PHP version
echo "📋 PHP Version Check"
php --version | head -n 1

# Check Laravel version
echo "📋 Laravel Version Check"
php artisan --version

# Check Node.js version
echo "📋 Node.js Version Check"
node --version

# Check npm version
echo "📋 NPM Version Check"
npm --version

# Check database connection
echo "📋 Database Connection Check"
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database connection: OK'; } catch (Exception \$e) { echo 'Database connection: FAILED - ' . \$e->getMessage(); }"

# Check required directories
echo "📋 Directory Permissions Check"
directories=("storage" "bootstrap/cache" "public")
for dir in "${directories[@]}"; do
    if [ -d "$dir" ]; then
        if [ -w "$dir" ]; then
            echo "✅ $dir - Writable"
        else
            echo "❌ $dir - Not writable"
        fi
    else
        echo "❌ $dir - Directory not found"
    fi
done

# Check .env file
echo "📋 Environment Configuration Check"
if [ -f ".env" ]; then
    echo "✅ .env file exists"
    
    # Check critical environment variables
    critical_vars=("APP_KEY" "DB_DATABASE" "DB_USERNAME" "MIDTRANS_SERVER_KEY" "MIDTRANS_CLIENT_KEY")
    for var in "${critical_vars[@]}"; do
        if grep -q "^$var=" .env; then
            value=$(grep "^$var=" .env | cut -d'=' -f2 | tr -d '"')
            if [ -n "$value" ]; then
                echo "✅ $var is set"
            else
                echo "⚠️ $var is empty"
            fi
        else
            echo "❌ $var is not set"
        fi
    done
else
    echo "❌ .env file not found"
fi

# Check Composer dependencies
echo "📋 Composer Dependencies Check"
if [ -f "composer.lock" ]; then
    echo "✅ composer.lock exists"
    outdated=$(composer outdated --direct 2>/dev/null | wc -l)
    if [ $outdated -gt 0 ]; then
        echo "⚠️ $outdated outdated packages found"
    else
        echo "✅ All packages up to date"
    fi
else
    echo "❌ composer.lock not found"
fi

# Check NPM dependencies
echo "📋 NPM Dependencies Check"
if [ -f "package-lock.json" ]; then
    echo "✅ package-lock.json exists"
    if npm audit --audit-level=moderate --production > /dev/null 2>&1; then
        echo "✅ No security vulnerabilities found"
    else
        echo "⚠️ Security vulnerabilities found, run 'npm audit fix'"
    fi
else
    echo "❌ package-lock.json not found"
fi

# Check Laravel configuration
echo "📋 Laravel Configuration Check"
config_cached=$(php artisan config:cache --dry-run 2>/dev/null && echo "OK" || echo "FAILED")
echo "Configuration cache: $config_cached"

route_cached=$(php artisan route:cache --dry-run 2>/dev/null && echo "OK" || echo "FAILED")
echo "Route cache: $route_cached"

# Check storage link
echo "📋 Storage Link Check"
if [ -L "public/storage" ]; then
    echo "✅ Storage link exists"
else
    echo "⚠️ Storage link not found, run 'php artisan storage:link'"
fi

# Check log files
echo "📋 Log Files Check"
if [ -d "storage/logs" ]; then
    log_size=$(du -sh storage/logs | cut -f1)
    echo "✅ Log directory size: $log_size"
    
    # Check for large log files
    large_logs=$(find storage/logs -name "*.log" -size +10M 2>/dev/null)
    if [ -n "$large_logs" ]; then
        echo "⚠️ Large log files found (>10MB):"
        echo "$large_logs"
    else
        echo "✅ No large log files"
    fi
else
    echo "❌ Log directory not found"
fi

# Check queue status
echo "📋 Queue Status Check"
if php artisan queue:work --help > /dev/null 2>&1; then
    echo "✅ Queue commands available"
else
    echo "❌ Queue commands not available"
fi

# Performance check
echo "📋 Basic Performance Check"
start_time=$(date +%s%N)
php artisan route:list > /dev/null 2>&1
end_time=$(date +%s%N)
duration=$(( (end_time - start_time) / 1000000 ))
echo "Route list generation: ${duration}ms"

if [ $duration -lt 1000 ]; then
    echo "✅ Performance looks good"
elif [ $duration -lt 3000 ]; then
    echo "⚠️ Performance acceptable but could be optimized"
else
    echo "❌ Performance issues detected"
fi

# Summary
echo ""
echo "🎯 Health Check Complete!"
echo "========================"
echo "Review the results above and fix any issues marked with ❌ or ⚠️"
echo "For production deployment, ensure all critical checks show ✅"

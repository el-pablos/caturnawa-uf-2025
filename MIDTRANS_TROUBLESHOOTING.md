# Midtrans Payment Integration Troubleshooting Guide

## 🚨 Common Issues and Solutions

### Issue 1: Missing `data-client-key` Attribute
**Error**: "Please add `data-client-key` attribute in the script tag"

**Cause**: Midtrans client key is not properly configured in environment variables.

**Solution**:
1. Check if `MIDTRANS_CLIENT_KEY` is set in `.env` file
2. Verify the key is not empty
3. Run diagnostic: `php midtrans-diagnostic.php`
4. Clear config cache: `php artisan config:clear`

### Issue 2: 503 Service Unavailable Errors
**Error**: HTTP 503 on `/payment/update-method/{registration}`

**Possible Causes**:
1. **Maintenance Mode**: Application is in maintenance mode
2. **Missing Environment Variables**: Midtrans keys not configured
3. **Database Issues**: Database connection problems
4. **Permission Issues**: File permission problems

**Solutions**:
```bash
# Check maintenance mode
php artisan up

# Check environment variables
grep MIDTRANS .env

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Fix permissions
sudo chown -R www-data:www-data /var/www/uf25.tams.my.id/
sudo chmod -R 755 /var/www/uf25.tams.my.id/
sudo chmod -R 775 /var/www/uf25.tams.my.id/storage
```

### Issue 3: Resource Loading Failures
**Error**: Failed to load `nr-spa-1.288.1.min.js` (blocked by client)

**Cause**: New Relic monitoring scripts blocked by ad blockers.

**Solution**: This is a client-side issue and doesn't affect payment functionality. Can be ignored or New Relic can be disabled in production if not needed.

## 🔧 Quick Fix Commands

### Production Server Fix Script
```bash
# Navigate to project directory
cd /var/www/uf25.tams.my.id/

# Run the comprehensive fix script
chmod +x fix-midtrans-production.sh
./fix-midtrans-production.sh

# Run diagnostic
php midtrans-diagnostic.php
```

### Manual Fix Steps
```bash
# 1. Disable maintenance mode
php artisan up

# 2. Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 3. Set proper permissions
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 storage bootstrap/cache

# 4. Check environment variables
cat .env | grep MIDTRANS

# 5. Test configuration
php midtrans-diagnostic.php
```

## 📋 Environment Variables Checklist

Required variables in `.env` file:
```env
MIDTRANS_SERVER_KEY=your_server_key_here
MIDTRANS_CLIENT_KEY=your_client_key_here
MIDTRANS_IS_PRODUCTION=true  # for production, false for sandbox
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

## 🧪 Testing Payment Flow

### 1. Test Environment Configuration
```bash
php midtrans-diagnostic.php
```

### 2. Test Payment Checkout
1. Login as a test user (peserta1@unasfest.com / password123)
2. Register for a competition
3. Go to payment checkout
4. Check browser console for errors
5. Try selecting different payment methods

### 3. Monitor Logs
```bash
# Watch Laravel logs in real-time
tail -f storage/logs/laravel.log

# Check for Midtrans-related errors
grep -i midtrans storage/logs/laravel.log | tail -20
```

## 🔍 Debugging Steps

### 1. Check Application Status
```bash
# Check if app is up
curl -I https://uf25.tams.my.id

# Check specific payment route
curl -I https://uf25.tams.my.id/payment/checkout/1
```

### 2. Verify Database
```bash
php artisan tinker
>>> App\Models\Registration::count()
>>> App\Models\Payment::count()
>>> App\Models\User::where('email', 'peserta1@unasfest.com')->first()
```

### 3. Test Midtrans Configuration
```bash
php artisan tinker
>>> App\Helpers\MidtransHelper::isConfigured()
>>> App\Helpers\MidtransHelper::getClientKey()
>>> App\Helpers\MidtransHelper::getSnapJsUrl()
```

## 📞 Support Information

### Log Files to Check
- `storage/logs/laravel.log` - Application logs
- `/var/log/nginx/error.log` - Nginx errors (if using Nginx)
- `/var/log/apache2/error.log` - Apache errors (if using Apache)

### Key Files to Verify
- `.env` - Environment variables
- `config/midtrans.php` - Midtrans configuration
- `app/Helpers/MidtransHelper.php` - Helper functions
- `app/Services/MidtransService.php` - Service class
- `resources/views/payment/checkout.blade.php` - Checkout view

### Useful Commands
```bash
# Check Laravel version
php artisan --version

# Check installed packages
composer show | grep midtrans

# Check routes
php artisan route:list --name=payment

# Check config
php artisan config:show midtrans
```

## ✅ Success Indicators

When everything is working correctly, you should see:
1. ✅ All diagnostic tests pass
2. ✅ No 503 errors in browser network tab
3. ✅ Midtrans Snap popup opens when clicking "Bayar"
4. ✅ Payment methods load correctly
5. ✅ No JavaScript errors in browser console
6. ✅ Laravel logs show successful Midtrans initialization

## 🆘 Emergency Contacts

If issues persist:
1. Check Midtrans Dashboard for API status
2. Verify Midtrans keys are active and not expired
3. Contact Midtrans support if API issues
4. Check server resources (disk space, memory)
5. Verify SSL certificate is valid

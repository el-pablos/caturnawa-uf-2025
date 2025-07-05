# UNAS Fest 2025 - Setup Guide

## 📋 Prerequisites

Before running the setup scripts, make sure you have the following installed:

### For Development (Windows/Linux/macOS)
- **PHP 8.1+** with required extensions:
  - BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
- **Composer** (latest version)
- **Node.js 16+** and **NPM**
- **MySQL 8.0+** or **MariaDB 10.3+**
- **Git** (for version control)

### For Production (Linux Server)
- **PHP 8.3+** with PHP-FPM
- **Nginx** or **Apache**
- **MySQL 8.0+**
- **Supervisor** (for queue workers)
- **Git**

## 🚀 Quick Setup

### Development Environment

#### Windows
```bash
# Clone the repository
git clone https://github.com/el-pablos/caturnawa-uf-2025.git
cd caturnawa-uf-2025

# Run setup script
setup.bat
```

#### Linux/macOS
```bash
# Clone the repository
git clone https://github.com/el-pablos/caturnawa-uf-2025.git
cd caturnawa-uf-2025

# Make script executable and run
chmod +x setup.sh
./setup.sh
```

### Production Environment

```bash
# Navigate to production directory
cd /var/www/uf25.tams.my.id

# Run update script
./update.sh
```

## 📁 Script Details

### setup.bat / setup.sh
**Purpose**: Initial project setup for development environment

**What it does**:
1. Installs Composer dependencies
2. Installs NPM dependencies
3. Creates `.env` file from `.env.example`
4. Generates application key
5. Creates storage symlink
6. Runs database migrations
7. Seeds database with sample data
8. Builds frontend assets
9. Optimizes application
10. Sets proper permissions

### update.sh
**Purpose**: Production deployment and updates

**What it does**:
1. Stashes local changes
2. Pulls latest code from Git repository
3. Updates Composer dependencies (production mode)
4. Updates NPM dependencies
5. Builds frontend assets
6. Runs database migrations
7. Clears and rebuilds cache
8. Sets proper permissions
9. Restarts services (Nginx, PHP-FPM, Laravel workers)
10. Clears OPcache

## ⚙️ Configuration

### Environment Variables

After running the setup script, configure your `.env` file:

```env
# Application
APP_NAME="Caturnawa - UNAS Fest 2025"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=unas_fest_2025
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Midtrans (Payment Gateway)
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false

# Mail
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@unasfest.com
MAIL_FROM_NAME="UNAS Fest 2025"
```

### Production Environment Variables

For production server (`/var/www/uf25.tams.my.id/.env`):

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://uf25.tams.my.id

DB_DATABASE=uf25_database
DB_USERNAME=uf25_user
DB_PASSWORD=your_secure_password

MIDTRANS_IS_PRODUCTION=false  # Using sandbox for testing
```

## 🔧 Manual Setup (Alternative)

If you prefer manual setup or encounter issues with the scripts:

```bash
# 1. Install dependencies
composer install --no-dev --optimize-autoloader
npm install

# 2. Environment setup
cp .env.example .env
php artisan key:generate

# 3. Database setup
php artisan migrate --seed

# 4. Build assets
npm run build

# 5. Optimize application
php artisan optimize
php artisan storage:link

# 6. Set permissions (Linux/macOS)
chmod -R 775 storage bootstrap/cache
```

## 🎯 Default Credentials

After setup, you can login with these default accounts:

### Super Admin
- **Email**: superadmin@unasfest.com
- **Password**: password123

### Admin
- **Email**: admin@unasfest.com
- **Password**: password123

### Juri (Judge)
- **Email**: juri1@unasfest.com
- **Password**: password123

### Peserta (Participant)
- **Email**: peserta1@unasfest.com
- **Password**: password123

## 🚨 Troubleshooting

### Common Issues

1. **Permission Denied**
   ```bash
   chmod +x setup.sh update.sh
   ```

2. **Composer Memory Limit**
   ```bash
   php -d memory_limit=-1 /usr/local/bin/composer install
   ```

3. **NPM Build Fails**
   ```bash
   rm -rf node_modules package-lock.json
   npm install
   npm run build
   ```

4. **Database Connection Error**
   - Check database credentials in `.env`
   - Ensure MySQL service is running
   - Create database if it doesn't exist

5. **Storage Permission Issues**
   ```bash
   sudo chown -R www-data:www-data storage bootstrap/cache
   chmod -R 775 storage bootstrap/cache
   ```

### Log Files

Check these log files for debugging:

- **Laravel**: `storage/logs/laravel.log`
- **Nginx**: `/var/log/nginx/error.log`
- **PHP-FPM**: `/var/log/php8.3-fpm.log`

## 📞 Support

If you encounter any issues:

1. Check the troubleshooting section above
2. Review log files for error details
3. Ensure all prerequisites are installed
4. Contact the development team

## 🔄 Updates

To update the application:

### Development
```bash
git pull origin main
./setup.sh
```

### Production
```bash
./update.sh
```

The update script handles all necessary steps including cache clearing, asset rebuilding, and service restarts.

# UNAS Fest 2025 - Production Deployment Guide

## 🚀 Overview

This guide covers the deployment and management of UNAS Fest 2025 in production environment using automated scripts.

## 📋 Prerequisites

### Server Requirements
- **OS**: Ubuntu 20.04 LTS or later
- **PHP**: 8.1 or 8.2 with required extensions
- **Database**: MySQL 8.0 or MariaDB 10.5+
- **Web Server**: Nginx
- **Node.js**: 16.x or later
- **Composer**: 2.x
- **Git**: Latest version

### Server Setup
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip php8.1-gd php8.1-intl php8.1-bcmath
sudo apt install -y nginx mysql-server git curl unzip
sudo apt install -y nodejs npm

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Redis (optional, for caching)
sudo apt install -y redis-server
```

## 🔧 Font Update

All fonts have been updated to **Poppins** for consistency:
- Body text: `font-family: 'Poppins', sans-serif`
- Headings: `font-family: 'Poppins', sans-serif` with `font-weight: 600`
- All UI elements now use Poppins font

## 📁 Directory Structure

```
/var/www/unas-fest-2025/
├── current/                 # Symlink to current release
├── releases/               # All releases
│   ├── 20231215120000/    # Release directories
│   └── 20231215130000/
├── shared/                 # Shared files between releases
│   ├── .env               # Environment file
│   ├── storage/           # Shared storage
│   └── deployment.log     # Deployment history
└── backups/               # Backup files
```

## 🛠️ Available Scripts

### 1. `update-production.sh` - Simple Update
**Usage**: `sudo ./update-production.sh`

**What it does**:
- ✅ Enables maintenance mode
- ✅ Pulls latest code from main branch
- ✅ Installs dependencies
- ✅ Runs migrations
- ✅ Builds assets
- ✅ Clears and optimizes caches
- ✅ Fixes permissions
- ✅ Restarts services
- ✅ Disables maintenance mode
- ✅ Runs health check

**Best for**: Regular updates with minimal downtime

### 2. `deploy-zero-downtime.sh` - Zero-Downtime Deployment
**Usage**: `sudo ./deploy-zero-downtime.sh`

**What it does**:
- ✅ Creates new release directory
- ✅ Clones fresh code
- ✅ Installs dependencies
- ✅ Builds assets
- ✅ Runs migrations
- ✅ Tests new release
- ✅ Atomic switch to new release
- ✅ Automatic rollback on failure
- ✅ Keeps last 3 releases

**Best for**: Production deployments with zero downtime

### 3. `rollback-production.sh` - Rollback
**Usage**: `sudo ./rollback-production.sh [steps_back]`

**Examples**:
```bash
# Rollback to previous release
sudo ./rollback-production.sh 1

# Rollback 2 releases back
sudo ./rollback-production.sh 2
```

**What it does**:
- ✅ Shows available releases
- ✅ Confirms rollback action
- ✅ Switches to target release
- ✅ Runs necessary migrations
- ✅ Clears caches
- ✅ Restarts services
- ✅ Runs health check

### 4. `restart-production.sh` - Full Restart with Backup
**Usage**: `sudo ./restart-production.sh [branch]`

**What it does**:
- ✅ Creates full backup (DB + files)
- ✅ Comprehensive deployment process
- ✅ Environment validation
- ✅ Detailed logging
- ✅ Advanced health checks

**Best for**: Major updates or when backup is needed

## 🔄 Deployment Workflow

### Regular Updates (Weekly/Monthly)
```bash
# 1. Simple update for minor changes
sudo ./update-production.sh

# 2. Monitor application
tail -f /var/log/nginx/access.log
```

### Major Releases (Zero-Downtime)
```bash
# 1. Deploy new release
sudo ./deploy-zero-downtime.sh

# 2. Monitor for issues
tail -f /var/www/unas-fest-2025/shared/storage/logs/laravel.log

# 3. If issues found, rollback
sudo ./rollback-production.sh 1
```

### Emergency Rollback
```bash
# Quick rollback to previous version
sudo ./rollback-production.sh 1

# Check deployment history
cat /var/www/unas-fest-2025/shared/deployment.log
```

## 🔍 Monitoring and Logs

### Application Logs
```bash
# Laravel logs
tail -f /var/www/unas-fest-2025/shared/storage/logs/laravel.log

# Deployment logs
tail -f /var/log/unas-fest-deploy.log

# Nginx logs
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log
```

### Health Checks
```bash
# Manual health check
curl -f http://localhost
curl -f http://localhost/api/health

# Database check
cd /var/www/unas-fest-2025/current
sudo -u www-data php artisan tinker --execute="DB::connection()->getPdo(); echo 'DB OK';"
```

## 🐛 Troubleshooting

### Common Issues

#### 1. Permission Errors
```bash
# Fix permissions
sudo chown -R www-data:www-data /var/www/unas-fest-2025/
sudo chmod -R 755 /var/www/unas-fest-2025/
sudo chmod -R 775 /var/www/unas-fest-2025/shared/storage/
```

#### 2. Database Connection Issues
```bash
# Check database status
sudo systemctl status mysql

# Test connection
mysql -u root -p
```

#### 3. Nginx Issues
```bash
# Check nginx status
sudo systemctl status nginx

# Test configuration
sudo nginx -t

# Restart nginx
sudo systemctl restart nginx
```

#### 4. PHP-FPM Issues
```bash
# Check PHP-FPM status
sudo systemctl status php8.1-fpm

# Restart PHP-FPM
sudo systemctl restart php8.1-fpm
```

### Emergency Procedures

#### 1. Complete System Failure
```bash
# 1. Rollback to known good release
sudo ./rollback-production.sh 1

# 2. If rollback fails, restore from backup
sudo systemctl stop nginx
sudo systemctl stop php8.1-fpm

# Restore database
mysql -u root -p unas_fest_2025 < /var/backups/unas-fest/backup-YYYYMMDD-HHMMSS.sql

# Restore files
cd /var/www/
sudo tar -xzf /var/backups/unas-fest/backup-YYYYMMDD-HHMMSS.tar.gz

# Restart services
sudo systemctl start php8.1-fpm
sudo systemctl start nginx
```

#### 2. Database Issues
```bash
# Check database integrity
sudo -u www-data php artisan migrate:status

# Reset migrations (DANGEROUS - only if necessary)
sudo -u www-data php artisan migrate:reset
sudo -u www-data php artisan migrate
```

## 🔐 Security Considerations

### File Permissions
```bash
# Secure permissions
sudo chown -R www-data:www-data /var/www/unas-fest-2025/
sudo chmod -R 755 /var/www/unas-fest-2025/
sudo chmod -R 775 /var/www/unas-fest-2025/shared/storage/
sudo chmod -R 775 /var/www/unas-fest-2025/shared/storage/bootstrap/cache/
```

### Environment Security
```bash
# Protect .env file
sudo chmod 600 /var/www/unas-fest-2025/shared/.env
sudo chown www-data:www-data /var/www/unas-fest-2025/shared/.env
```

## 📊 Performance Optimization

### Caching
```bash
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
```

### Database Optimization
```bash
# Optimize database
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan db:seed --class=ProductionOptimizationSeeder
```

## 📞 Support

For technical issues:
1. Check logs first
2. Try rollback if deployment related
3. Contact system administrator
4. Check GitHub issues

## 🔄 Maintenance Schedule

### Daily
- Monitor application logs
- Check disk space
- Verify backup completion

### Weekly
- Update dependencies
- Review performance metrics
- Clean old logs

### Monthly
- Update system packages
- Review security updates
- Clean old releases and backups

---

**Last Updated**: $(date)
**Version**: 1.0
**Environment**: Production

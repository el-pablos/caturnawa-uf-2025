# Deployment Guide - UNAS Fest 2025

Panduan deployment aplikasi UNAS Fest 2025 ke berbagai environment.

## 📋 Prerequisites

### System Requirements
- **PHP**: 8.1 atau lebih tinggi
- **MySQL**: 8.0 atau lebih tinggi
- **Node.js**: 18.0 atau lebih tinggi
- **NPM**: 9.0 atau lebih tinggi
- **Composer**: 2.0 atau lebih tinggi
- **Web Server**: Apache 2.4+ atau Nginx 1.18+

### PHP Extensions Required
```
php-mysql
php-pdo
php-mbstring
php-xml
php-curl
php-zip
php-gd
php-json
php-fileinfo
php-tokenizer
php-ctype
php-bcmath
```

## 🚀 Production Deployment

### 1. Server Setup

#### Ubuntu/Debian Server
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.1
sudo apt install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install php8.1 php8.1-fpm php8.1-mysql php8.1-xml php8.1-curl php8.1-zip php8.1-gd php8.1-mbstring php8.1-json php8.1-fileinfo php8.1-tokenizer php8.1-ctype php8.1-bcmath

# Install MySQL
sudo apt install mysql-server

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Nginx
sudo apt install nginx

# Install Git
sudo apt install git
```

#### CentOS/RHEL Server
```bash
# Install EPEL repository
sudo yum install epel-release

# Install Remi repository for PHP 8.1
sudo yum install http://rpms.remirepo.net/enterprise/remi-release-8.rpm
sudo yum config-manager --set-enabled powertools
sudo yum config-manager --set-enabled remi-php81

# Install PHP 8.1
sudo yum install php php-fpm php-mysql php-xml php-curl php-zip php-gd php-mbstring php-json php-fileinfo

# Install MySQL
sudo yum install mysql-server

# Install Node.js
curl -fsSL https://rpm.nodesource.com/setup_18.x | sudo bash -
sudo yum install nodejs

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 2. Database Setup

```bash
# Secure MySQL installation
sudo mysql_secure_installation

# Create database and user
sudo mysql -u root -p
```

```sql
CREATE DATABASE unas_fest_2025;
CREATE USER 'unasfest'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON unas_fest_2025.* TO 'unasfest'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. Application Deployment

```bash
# Clone repository
cd /var/www
sudo git clone https://github.com/your-username/unas-fest-2025.git
cd unas-fest-2025

# Set permissions
sudo chown -R www-data:www-data /var/www/unas-fest-2025
sudo chmod -R 755 /var/www/unas-fest-2025
sudo chmod -R 775 storage bootstrap/cache

# Install dependencies
composer install --no-dev --optimize-autoloader
npm ci --production

# Environment setup
cp .env.example .env
nano .env  # Edit configuration
```

#### Production .env Configuration
```env
APP_NAME="UNAS Fest 2025"
APP_ENV=production
APP_KEY=base64:your_generated_key_here
APP_DEBUG=false
APP_URL=https://unasfest.ac.id

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=unas_fest_2025
DB_USERNAME=unasfest
DB_PASSWORD=secure_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=noreply@unasfest.ac.id
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@unasfest.ac.id
MAIL_FROM_NAME="${APP_NAME}"

# Midtrans Production Configuration
MIDTRANS_SERVER_KEY=SB-Mid-server-your_production_server_key
MIDTRANS_CLIENT_KEY=SB-Mid-client-your_production_client_key
MIDTRANS_IS_PRODUCTION=true
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true

# Session & Cache
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=database

# Security
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict
```

```bash
# Generate application key
php artisan key:generate

# Run migrations and seeders
php artisan migrate --force
php artisan db:seed --force

# Create storage link
php artisan storage:link

# Build frontend assets
npm run build

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## 🐳 Docker Deployment

### Dockerfile
```dockerfile
FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u 1000 -d /home/unasfest unasfest
RUN mkdir -p /home/unasfest/.composer && \
    chown -R unasfest:unasfest /home/unasfest

# Set working directory
WORKDIR /var/www

# Copy application
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader
RUN npm ci --production && npm run build

# Set permissions
RUN chown -R unasfest:www-data /var/www
RUN chmod -R 775 storage bootstrap/cache

USER unasfest

EXPOSE 9000
CMD ["php-fpm"]
```

### Docker Compose
```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    image: unasfest/app
    container_name: unasfest-app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./:/var/www
    networks:
      - unasfest-network
    depends_on:
      - db
      - redis

  nginx:
    image: nginx:alpine
    container_name: unasfest-nginx
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./:/var/www
      - ./docker/nginx:/etc/nginx/conf.d
      - ./docker/ssl:/etc/ssl/certs
    networks:
      - unasfest-network
    depends_on:
      - app

  db:
    image: mysql:8.0
    container_name: unasfest-db
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: unas_fest_2025
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_USER: unasfest
      MYSQL_PASSWORD: secure_password
    volumes:
      - db_data:/var/lib/mysql
    ports:
      - "3306:3306"
    networks:
      - unasfest-network

  redis:
    image: redis:alpine
    container_name: unasfest-redis
    restart: unless-stopped
    ports:
      - "6379:6379"
    networks:
      - unasfest-network

  worker:
    build:
      context: .
      dockerfile: Dockerfile
    image: unasfest/app
    container_name: unasfest-worker
    restart: unless-stopped
    command: php artisan queue:work --sleep=3 --tries=3 --max-time=3600
    volumes:
      - ./:/var/www
    networks:
      - unasfest-network
    depends_on:
      - db
      - redis

  scheduler:
    build:
      context: .
      dockerfile: Dockerfile
    image: unasfest/app
    container_name: unasfest-scheduler
    restart: unless-stopped
    command: crond -f
    volumes:
      - ./:/var/www
      - ./docker/cron:/etc/cron.d
    networks:
      - unasfest-network
    depends_on:
      - db

networks:
  unasfest-network:
    driver: bridge

volumes:
  db_data:
    driver: local
```

### Docker Commands
```bash
# Build and start containers
docker-compose up -d --build

# Run migrations
docker-compose exec app php artisan migrate --force

# Run seeders
docker-compose exec app php artisan db:seed --force

# Generate application key
docker-compose exec app php artisan key:generate

# Create storage link
docker-compose exec app php artisan storage:link

# View logs
docker-compose logs -f app

# Stop containers
docker-compose down

# Remove everything including volumes
docker-compose down -v --remove-orphans
```

## ☁️ Cloud Deployment

### AWS Deployment

#### EC2 Setup
```bash
# Launch EC2 instance (Ubuntu 22.04 LTS)
# Instance type: t3.medium or larger
# Security groups: HTTP (80), HTTPS (443), SSH (22)

# Connect to instance
ssh -i your-key.pem ubuntu@your-ec2-ip

# Follow Ubuntu server setup from above
# Additional AWS-specific setup:

# Install AWS CLI
sudo apt install awscli

# Setup S3 for file storage (optional)
aws configure
```

#### RDS Database Setup
```bash
# Create RDS MySQL instance
# Engine: MySQL 8.0
# Instance class: db.t3.micro (for testing) or db.t3.small (production)
# Multi-AZ: Yes (for production)
# Backup retention: 7 days

# Update .env with RDS endpoint
DB_HOST=your-rds-endpoint.region.rds.amazonaws.com
DB_PORT=3306
DB_DATABASE=unas_fest_2025
DB_USERNAME=admin
DB_PASSWORD=your_secure_password
```

#### Application Load Balancer
```bash
# Create Application Load Balancer
# Target group: EC2 instances
# Health check path: /health-check
# Listener: HTTP (80) -> HTTPS (443)
# SSL certificate: ACM or upload custom
```

### DigitalOcean Deployment

#### Droplet Setup
```bash
# Create Droplet (Ubuntu 22.04)
# Size: 2GB RAM minimum
# Add SSH key

# Connect and setup
ssh root@your-droplet-ip

# Follow Ubuntu setup steps
# Additional DigitalOcean setup:

# Setup firewall
ufw allow ssh
ufw allow http
ufw allow https
ufw enable

# Setup automatic backups (recommended)
# Enable in DigitalOcean control panel
```

#### Managed Database
```bash
# Create Managed MySQL Database
# Version: 8
# Size: 1GB RAM minimum
# Enable automatic backups

# Get connection details from control panel
# Update .env accordingly
```

### Google Cloud Platform

#### Compute Engine Setup
```bash
# Create VM instance
# Machine type: e2-medium
# OS: Ubuntu 22.04 LTS
# Firewall: Allow HTTP/HTTPS traffic

# SSH into instance
gcloud compute ssh your-instance-name

# Follow Ubuntu setup steps
```

#### Cloud SQL Setup
```bash
# Create Cloud SQL instance
# Database version: MySQL 8.0
# Machine type: db-f1-micro (testing) or db-n1-standard-1 (production)

# Create database and user
gcloud sql databases create unas_fest_2025 --instance=your-instance-name
gcloud sql users create unasfest --instance=your-instance-name --password=secure_password

# Get connection details and update .env
```

## 🔄 CI/CD Pipeline

### GitHub Actions
```yaml
# .github/workflows/deploy.yml
name: Deploy to Production

on:
  push:
    branches: [ main ]

jobs:
  test:
    runs-on: ubuntu-latest
    
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ROOT_PASSWORD: password
          MYSQL_DATABASE: test_db
        ports:
          - 3306:3306
        options: --health-cmd="mysqladmin ping" --health-interval=10s --health-timeout=5s --health-retries=3

    steps:
    - uses: actions/checkout@v3

    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.1'
        extensions: mysql, zip, gd

    - name: Install Composer dependencies
      run: composer install --prefer-dist --no-progress

    - name: Copy environment file
      run: cp .env.example .env

    - name: Generate application key
      run: php artisan key:generate

    - name: Run tests
      run: php artisan test

  deploy:
    needs: test
    runs-on: ubuntu-latest
    if: github.ref == 'refs/heads/main'

    steps:
    - name: Deploy to server
      uses: appleboy/ssh-action@v0.1.5
      with:
        host: ${{ secrets.HOST }}
        username: ${{ secrets.USERNAME }}
        key: ${{ secrets.SSH_KEY }}
        script: |
          cd /var/www/unas-fest-2025
          git pull origin main
          composer install --no-dev --optimize-autoloader
          npm ci --production
          npm run build
          php artisan migrate --force
          php artisan config:cache
          php artisan route:cache
          php artisan view:cache
          sudo supervisorctl restart unasfest-worker:*
          sudo systemctl reload nginx
```

### GitLab CI/CD
```yaml
# .gitlab-ci.yml
stages:
  - test
  - build
  - deploy

variables:
  MYSQL_ROOT_PASSWORD: password
  MYSQL_DATABASE: test_db

test:
  stage: test
  image: php:8.1
  services:
    - mysql:8.0
  before_script:
    - apt-get update -yqq
    - apt-get install -yqq git libzip-dev
    - docker-php-ext-install zip pdo_mysql
    - curl -sS https://getcomposer.org/installer | php
    - php composer.phar install
    - cp .env.example .env
    - php artisan key:generate
  script:
    - php artisan test

build:
  stage: build
  image: node:18
  script:
    - npm ci
    - npm run build
  artifacts:
    paths:
      - public/build/

deploy:
  stage: deploy
  image: ubuntu:latest
  before_script:
    - apt-get update -yqq
    - apt-get install -yqq openssh-client
    - eval $(ssh-agent -s)
    - echo "$SSH_PRIVATE_KEY" | tr -d '\r' | ssh-add -
    - mkdir -p ~/.ssh
    - chmod 700 ~/.ssh
  script:
    - ssh -o StrictHostKeyChecking=no $SERVER_USER@$SERVER_HOST "
        cd /var/www/unas-fest-2025 &&
        git pull origin main &&
        composer install --no-dev --optimize-autoloader &&
        php artisan migrate --force &&
        php artisan optimize &&
        sudo supervisorctl restart unasfest-worker:* &&
        sudo systemctl reload nginx"
  only:
    - main
```

## 🔒 Security Checklist

### Application Security
- [ ] `APP_DEBUG=false` in production
- [ ] Strong `APP_KEY` generated
- [ ] Database credentials secured
- [ ] HTTPS enabled with valid SSL certificate
- [ ] Security headers configured
- [ ] File upload restrictions implemented
- [ ] Rate limiting enabled
- [ ] CSRF protection enabled
- [ ] SQL injection protection (using Eloquent)
- [ ] XSS protection enabled

### Server Security
- [ ] SSH key-based authentication
- [ ] Firewall configured (UFW/iptables)
- [ ] Regular security updates
- [ ] Non-root user for application
- [ ] File permissions set correctly
- [ ] Database access restricted
- [ ] Backup strategy implemented
- [ ] Monitoring and logging setup
- [ ] Fail2ban installed (optional)
- [ ] Regular security audits

### Midtrans Security
- [ ] Production server key secured
- [ ] Notification URL using HTTPS
- [ ] IP whitelist configured (if applicable)
- [ ] Transaction signature verification
- [ ] Sensitive data not logged

## 📊 Performance Optimization

### Application Optimization
```bash
# Enable OPcache
sudo nano /etc/php/8.1/fpm/conf.d/10-opcache.ini
```

```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

### Database Optimization
```sql
-- Add indexes for better performance
CREATE INDEX idx_registrations_competition_id ON registrations(competition_id);
CREATE INDEX idx_registrations_status ON registrations(status);
CREATE INDEX idx_payments_transaction_status ON payments(transaction_status);
CREATE INDEX idx_scores_competition_id ON scores(competition_id);
CREATE INDEX idx_scores_jury_id ON scores(jury_id);

-- Optimize MySQL configuration
-- Add to /etc/mysql/mysql.conf.d/mysqld.cnf
[mysqld]
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
query_cache_size = 32M
max_connections = 200
```

### Web Server Optimization
```nginx
# Add to nginx.conf
worker_processes auto;
worker_connections 1024;

# Enable gzip compression
gzip on;
gzip_types text/css application/javascript image/svg+xml;

# Enable browser caching
location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg)$ {
    expires 1y;
    add_header Cache-Control "public, immutable";
}
```

## 📝 Maintenance

### Regular Maintenance Tasks
```bash
# Weekly maintenance script
#!/bin/bash

# Update application
cd /var/www/unas-fest-2025
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize

# Clear old logs (keep last 30 days)
find storage/logs -name "*.log" -mtime +30 -delete

# Backup database
mysqldump -u unasfest -p unas_fest_2025 > /backups/db_$(date +%Y%m%d).sql
gzip /backups/db_$(date +%Y%m%d).sql

# Remove old backups (keep last 7 days)
find /backups -name "db_*.sql.gz" -mtime +7 -delete

# Restart services
sudo supervisorctl restart unasfest-worker:*
sudo systemctl reload nginx
```

### Monitoring Commands
```bash
# Check application status
curl -f http://localhost/health-check

# Check database connection
php artisan tinker --execute="DB::connection()->getPdo();"

# Check queue status
php artisan queue:monitor

# Check disk space
df -h

# Check memory usage
free -h

# Check running processes
ps aux | grep php

# Check nginx status
sudo systemctl status nginx

# Check logs
tail -f storage/logs/laravel.log
tail -f /var/log/nginx/error.log
```

## 🆘 Troubleshooting

### Common Issues

#### Permission Issues
```bash
# Fix storage permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

#### 500 Internal Server Error
```bash
# Check error logs
tail -f storage/logs/laravel.log
tail -f /var/log/nginx/error.log

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### Database Connection Issues
```bash
# Test connection
php artisan tinker
DB::connection()->getPdo();

# Check MySQL status
sudo systemctl status mysql
```

#### Payment Gateway Issues
```bash
# Check Midtrans configuration
php artisan tinker
Config::get('midtrans');

# Test API connection
curl -u "YOUR_SERVER_KEY:" \
  -X GET https://api.sandbox.midtrans.com/v2/ping
```

### Emergency Procedures

#### Application Rollback
```bash
# Rollback to previous version
cd /var/www/unas-fest-2025
git log --oneline -10  # Find previous commit
git checkout PREVIOUS_COMMIT_HASH
composer install --no-dev --optimize-autoloader
php artisan migrate:rollback
php artisan optimize
sudo supervisorctl restart unasfest-worker:*
```

#### Database Recovery
```bash
# Restore from backup
mysql -u unasfest -p unas_fest_2025 < /backups/db_YYYYMMDD.sql
```

#### Emergency Maintenance Mode
```bash
# Enable maintenance mode
php artisan down --message="Maintenance in progress" --retry=60

# Disable maintenance mode
php artisan up
```

## 📞 Support Contacts

- **Technical Support**: tech@unasfest.ac.id
- **Emergency Contact**: +62-xxx-xxx-xxxx
- **Midtrans Support**: https://support.midtrans.com
- **Server Provider Support**: [Provider specific contact]

---

**Note**: Sesuaikan konfigurasi dan credential sesuai dengan environment dan kebutuhan spesifik Anda. Pastikan untuk tidak menggunakan credential default dalam production.

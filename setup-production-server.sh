#!/bin/bash

# UNAS Fest 2025 - Production Server Setup Script
# Usage: sudo ./setup-production-server.sh

set -e

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Configuration
PROJECT_NAME="unas-fest-2025"
PROJECT_DIR="/var/www/$PROJECT_NAME"
DOMAIN="unasfest.ac.id"
DB_NAME="unas_fest_2025"
DB_USER="unas_fest_user"

echo -e "${GREEN}🚀 UNAS Fest 2025 - Production Server Setup${NC}"
echo "=============================================="

# Check if running as root
if [[ $EUID -ne 0 ]]; then
    echo -e "${RED}❌ This script must be run as root (use sudo)${NC}"
    exit 1
fi

# Update system
echo -e "${BLUE}📦 Updating system packages...${NC}"
apt update && apt upgrade -y

# Install required packages
echo -e "${BLUE}📦 Installing required packages...${NC}"
apt install -y software-properties-common apt-transport-https ca-certificates gnupg lsb-release

# Add PHP repository
echo -e "${BLUE}📦 Adding PHP repository...${NC}"
add-apt-repository -y ppa:ondrej/php
apt update

# Install PHP and extensions
echo -e "${BLUE}📦 Installing PHP and extensions...${NC}"
apt install -y php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip php8.1-gd php8.1-intl php8.1-bcmath php8.1-redis php8.1-imagick

# Install Nginx
echo -e "${BLUE}📦 Installing Nginx...${NC}"
apt install -y nginx

# Install MySQL
echo -e "${BLUE}📦 Installing MySQL...${NC}"
apt install -y mysql-server

# Install Redis
echo -e "${BLUE}📦 Installing Redis...${NC}"
apt install -y redis-server

# Install Git and other tools
echo -e "${BLUE}📦 Installing Git and other tools...${NC}"
apt install -y git curl unzip zip htop supervisor

# Install Node.js
echo -e "${BLUE}📦 Installing Node.js...${NC}"
curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
apt install -y nodejs

# Install Composer
echo -e "${BLUE}📦 Installing Composer...${NC}"
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Configure PHP
echo -e "${BLUE}🔧 Configuring PHP...${NC}"
sed -i 's/memory_limit = .*/memory_limit = 256M/' /etc/php/8.1/fpm/php.ini
sed -i 's/upload_max_filesize = .*/upload_max_filesize = 20M/' /etc/php/8.1/fpm/php.ini
sed -i 's/post_max_size = .*/post_max_size = 25M/' /etc/php/8.1/fpm/php.ini
sed -i 's/max_execution_time = .*/max_execution_time = 300/' /etc/php/8.1/fpm/php.ini

# Configure PHP-FPM
cat > /etc/php/8.1/fpm/pool.d/www.conf << 'EOF'
[www]
user = www-data
group = www-data
listen = /var/run/php/php8.1-fpm.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0660
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 500
EOF

# Create project directory structure
echo -e "${BLUE}📁 Creating project directory structure...${NC}"
mkdir -p "$PROJECT_DIR"
mkdir -p "$PROJECT_DIR/releases"
mkdir -p "$PROJECT_DIR/shared"
mkdir -p "$PROJECT_DIR/shared/storage"
mkdir -p "$PROJECT_DIR/shared/storage/app"
mkdir -p "$PROJECT_DIR/shared/storage/framework"
mkdir -p "$PROJECT_DIR/shared/storage/logs"
mkdir -p "/var/backups/$PROJECT_NAME"

# Set ownership
chown -R www-data:www-data "$PROJECT_DIR"
chown -R www-data:www-data "/var/backups/$PROJECT_NAME"

# Configure Nginx
echo -e "${BLUE}🔧 Configuring Nginx...${NC}"
cat > /etc/nginx/sites-available/$PROJECT_NAME << EOF
server {
    listen 80;
    server_name $DOMAIN www.$DOMAIN;
    root $PROJECT_DIR/current/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.php index.html index.htm;

    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied expired no-cache no-store private auth;
    gzip_types
        text/plain
        text/css
        text/xml
        text/javascript
        application/javascript
        application/xml+rss
        application/json;

    # Cache static files
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff|woff2|ttf|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
EOF

# Enable site
ln -sf /etc/nginx/sites-available/$PROJECT_NAME /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Configure MySQL
echo -e "${BLUE}🔧 Configuring MySQL...${NC}"
systemctl start mysql
systemctl enable mysql

# Generate random password for database user
DB_PASSWORD=$(openssl rand -base64 32)

# Create database and user
mysql -u root << EOF
CREATE DATABASE IF NOT EXISTS $DB_NAME;
CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASSWORD';
GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';
FLUSH PRIVILEGES;
EOF

# Create .env file
echo -e "${BLUE}📋 Creating .env file...${NC}"
cat > $PROJECT_DIR/shared/.env << EOF
APP_NAME="UNAS Fest 2025"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://$DOMAIN

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=$DB_NAME
DB_USERNAME=$DB_USER
DB_PASSWORD=$DB_PASSWORD

BROADCAST_DRIVER=log
CACHE_DRIVER=redis
FILESYSTEM_DISK=local
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@$DOMAIN"
MAIL_FROM_NAME="UNAS Fest 2025"

# Midtrans Configuration - UPDATE THESE!
MIDTRANS_SERVER_KEY=your_production_server_key
MIDTRANS_CLIENT_KEY=your_production_client_key
MIDTRANS_IS_PRODUCTION=true
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true

# Competition Settings
COMPETITION_REGISTRATION_OPEN=true
COMPETITION_MAX_PARTICIPANTS=1000
COMPETITION_EARLY_BIRD_DISCOUNT=20

# File Upload Settings
MAX_FILE_SIZE=10240
ALLOWED_FILE_TYPES=pdf,doc,docx,jpg,jpeg,png
EOF

chown www-data:www-data $PROJECT_DIR/shared/.env
chmod 600 $PROJECT_DIR/shared/.env

# Configure Redis
echo -e "${BLUE}🔧 Configuring Redis...${NC}"
systemctl start redis-server
systemctl enable redis-server

# Configure firewall
echo -e "${BLUE}🔧 Configuring firewall...${NC}"
ufw allow 'Nginx Full'
ufw allow 'OpenSSH'
ufw --force enable

# Start services
echo -e "${BLUE}🔄 Starting services...${NC}"
systemctl start nginx
systemctl enable nginx
systemctl start php8.1-fpm
systemctl enable php8.1-fpm

# Create log rotation
echo -e "${BLUE}📝 Setting up log rotation...${NC}"
cat > /etc/logrotate.d/unas-fest << 'EOF'
/var/www/unas-fest-2025/shared/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
}
EOF

# Create cron job for Laravel scheduler
echo -e "${BLUE}⏰ Setting up cron job...${NC}"
(crontab -l 2>/dev/null; echo "* * * * * cd $PROJECT_DIR/current && php artisan schedule:run >> /dev/null 2>&1") | crontab -

# Create systemd service for queue worker
echo -e "${BLUE}🔧 Creating queue worker service...${NC}"
cat > /etc/systemd/system/laravel-worker.service << EOF
[Unit]
Description=Laravel queue worker
After=network.target

[Service]
Type=simple
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php $PROJECT_DIR/current/artisan queue:work --sleep=3 --tries=3 --daemon
StandardOutput=journal
StandardError=journal

[Install]
WantedBy=multi-user.target
EOF

systemctl daemon-reload
systemctl enable laravel-worker

# Install SSL certificate (Let's Encrypt)
echo -e "${BLUE}🔒 Installing SSL certificate...${NC}"
apt install -y certbot python3-certbot-nginx

echo -e "${YELLOW}⚠️  SSL certificate setup:${NC}"
echo "Run this command after DNS is configured:"
echo "sudo certbot --nginx -d $DOMAIN -d www.$DOMAIN"

# Create monitoring script
echo -e "${BLUE}📊 Creating monitoring script...${NC}"
cat > /usr/local/bin/monitor-unas-fest << 'EOF'
#!/bin/bash

# Monitor UNAS Fest 2025 Application
PROJECT_DIR="/var/www/unas-fest-2025"

# Check if application is responding
if curl -f -s "http://localhost" > /dev/null; then
    echo "$(date): Application is responding" >> /var/log/unas-fest-monitor.log
else
    echo "$(date): Application is NOT responding" >> /var/log/unas-fest-monitor.log
    systemctl restart nginx
    systemctl restart php8.1-fpm
fi

# Check disk space
DISK_USAGE=$(df / | awk 'NR==2 {print $5}' | sed 's/%//')
if [ "$DISK_USAGE" -gt 80 ]; then
    echo "$(date): Disk usage is high: $DISK_USAGE%" >> /var/log/unas-fest-monitor.log
fi

# Check memory usage
MEM_USAGE=$(free | grep Mem | awk '{printf "%.0f", $3/$2 * 100.0}')
if [ "$MEM_USAGE" -gt 80 ]; then
    echo "$(date): Memory usage is high: $MEM_USAGE%" >> /var/log/unas-fest-monitor.log
fi
EOF

chmod +x /usr/local/bin/monitor-unas-fest

# Add monitoring to cron
(crontab -l 2>/dev/null; echo "*/5 * * * * /usr/local/bin/monitor-unas-fest") | crontab -

# Create deployment info
echo -e "${BLUE}📋 Creating deployment info...${NC}"
cat > $PROJECT_DIR/deployment-info.txt << EOF
UNAS Fest 2025 - Production Server Setup Complete
================================================

Server Information:
- OS: $(lsb_release -d | cut -f2)
- PHP: $(php -v | head -n1)
- Nginx: $(nginx -v 2>&1)
- MySQL: $(mysql --version)
- Node.js: $(node -v)
- Composer: $(composer -V)

Project Configuration:
- Domain: $DOMAIN
- Project Directory: $PROJECT_DIR
- Database: $DB_NAME
- Database User: $DB_USER
- Database Password: $DB_PASSWORD

Next Steps:
1. Configure DNS to point to this server
2. Update .env file with correct settings:
   - MIDTRANS_SERVER_KEY
   - MIDTRANS_CLIENT_KEY
   - MAIL_* settings
3. Run first deployment:
   sudo ./deploy-zero-downtime.sh
4. Install SSL certificate:
   sudo certbot --nginx -d $DOMAIN -d www.$DOMAIN

Important Files:
- Environment: $PROJECT_DIR/shared/.env
- Nginx Config: /etc/nginx/sites-available/$PROJECT_NAME
- PHP Config: /etc/php/8.1/fpm/php.ini
- Deployment Scripts: $PROJECT_DIR/scripts/

Setup completed: $(date)
EOF

echo ""
echo -e "${GREEN}🎉 Production server setup completed successfully!${NC}"
echo "=============================================="
echo -e "${GREEN}✅ All services installed and configured${NC}"
echo -e "${GREEN}✅ Database created: $DB_NAME${NC}"
echo -e "${GREEN}✅ Database user: $DB_USER${NC}"
echo -e "${GREEN}✅ Nginx configured for: $DOMAIN${NC}"
echo -e "${GREEN}✅ SSL ready (run certbot after DNS setup)${NC}"
echo -e "${GREEN}✅ Monitoring configured${NC}"
echo ""
echo -e "${YELLOW}📋 Next steps:${NC}"
echo "1. Configure DNS to point to this server"
echo "2. Update .env file with correct settings"
echo "3. Run first deployment"
echo "4. Install SSL certificate"
echo ""
echo -e "${BLUE}💾 Database password saved to: $PROJECT_DIR/deployment-info.txt${NC}"
echo -e "${BLUE}🔐 Database password: $DB_PASSWORD${NC}"
echo ""
echo -e "${GREEN}🚀 Server is ready for UNAS Fest 2025 deployment!${NC}"

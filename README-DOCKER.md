# 🐳 UNAS Fest 2025 - Hybrid Docker Setup Guide
**by Tamas**

Panduan lengkap untuk menjalankan aplikasi UNAS Fest 2025 dengan tiga mode deployment yang berbeda: Infrastructure Only, Full Docker Development, dan Full Docker Production. Setup ini memberikan fleksibilitas maksimal untuk berbagai kebutuhan development dan deployment.

## 📋 Prerequisites

### Sistem Requirements
- **OS**: Linux, macOS, atau Windows 10/11 dengan WSL2
- **RAM**: Minimum 4GB, Recommended 8GB+
- **Storage**: Minimum 5GB free space
- **Network**: Internet connection untuk download images

### Software Requirements

#### 1. Docker Installation

**Linux (Ubuntu/Debian):**
```bash
# Update package index
sudo apt update

# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Add user to docker group
sudo usermod -aG docker $USER

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Logout and login again, then verify
docker --version
docker-compose --version
```

**macOS:**
```bash
# Install Docker Desktop from https://www.docker.com/products/docker-desktop
# Or using Homebrew:
brew install --cask docker

# Verify installation
docker --version
docker-compose --version
```

**Windows:**
```bash
# Install Docker Desktop from https://www.docker.com/products/docker-desktop
# Make sure WSL2 is enabled
# Verify in PowerShell or WSL:
docker --version
docker-compose --version
```

#### 2. Git Installation
```bash
# Linux
sudo apt install git

# macOS
brew install git

# Windows
# Download from https://git-scm.com/download/win
```

## 🚀 Quick Start

### 1. Clone Repository
```bash
git clone https://github.com/el-pablos/caturnawa-uf-2025.git
cd caturnawa-uf-2025
```

### 2. Choose Your Setup Mode

#### 🏗️ Mode 1: Infrastructure Only (Recommended for Development)
Perfect for developers who prefer native Laravel development with containerized services.

```bash
# Automated setup
./setup.sh
# Select option 1 when prompted

# Or manual setup
make setup-infra
```

**What you get:**
- MySQL 8.0, Redis, MailHog running in Docker containers
- Laravel runs natively via `php artisan serve`
- Full development tools (phpMyAdmin, Redis Commander)
- Fast development cycle with native PHP debugging

#### 🛠️ Mode 2: Full Docker Development
Complete containerized development environment with all development tools.

```bash
# Automated setup
./setup.sh
# Select option 2 when prompted

# Or manual setup
make setup-dev
```

**What you get:**
- Complete Laravel application in Docker containers
- Xdebug integration for debugging
- All development tools included
- Consistent environment across all machines

#### 🚀 Mode 3: Full Docker Production
Optimized production deployment with performance optimizations.

```bash
# Automated setup
./setup.sh
# Select option 3 when prompted

# Or manual setup
make setup-prod
```

**What you get:**
- Production-optimized Docker images
- OPcache enabled, debug disabled
- Resource limits and health checks
- Ready for production deployment

**Required Environment Variables:**
```env
# Database passwords (CHANGE THESE!)
DB_PASSWORD=your_secure_password_here
DB_ROOT_PASSWORD=your_root_password_here

# Midtrans configuration (REQUIRED for payments)
MIDTRANS_SERVER_KEY=your_midtrans_server_key
MIDTRANS_CLIENT_KEY=your_midtrans_client_key

# Email configuration (REQUIRED for notifications)
MAIL_HOST=smtp.gmail.com
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
```

### 3. Automated Setup (Recommended)
```bash
# Run automated setup script
./docker-setup.sh
```

### 4. Manual Setup (Alternative)
```bash
# Build Docker images
docker-compose build

# Start services
docker-compose up -d

# Wait for services to be ready (check logs)
docker-compose logs -f

# Run Laravel setup
docker-compose exec app composer install --optimize-autoloader
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan storage:link
docker-compose exec app php artisan config:cache
```

### 5. Access Application
- **Website**: http://localhost:8000
- **Health Check**: http://localhost:8000/health

## 🛠️ Development Setup

### Development Environment
```bash
# Use development compose file
docker-compose -f docker-compose.yml -f docker-compose.dev.yml up -d --build

# Access development tools:
# - Application: http://localhost:8000
# - phpMyAdmin: http://localhost:8080
# - MailHog: http://localhost:8025
# - Redis Commander: http://localhost:8081
```

### Xdebug Configuration
1. Set `DOCKER_TARGET=development` in `.env`
2. Configure your IDE:
   - **Host**: `localhost`
   - **Port**: `9003`
   - **Path Mapping**: `/var/www/html` → `./`

### Live Code Editing
Development setup mounts your local code into containers, so changes are reflected immediately without rebuilding.

## 📊 Services Overview

| Service | Container Name | Port | Description |
|---------|---------------|------|-------------|
| Laravel App | unas_fest_app | 8000 | Main application with Nginx + PHP-FPM |
| MySQL | unas_fest_mysql | 3306 | Database server |
| Redis | unas_fest_redis | 6379 | Cache & session store |
| Queue Worker | unas_fest_queue | - | Background job processing |
| Scheduler | unas_fest_scheduler | - | Cron job handling |
| phpMyAdmin | unas_fest_phpmyadmin | 8080 | Database management (dev only) |
| MailHog | unas_fest_mailhog | 8025 | Email testing (dev only) |
| Redis Commander | unas_fest_redis_commander | 8081 | Redis management (dev only) |

## 🔧 Common Commands

### Container Management
```bash
# Start all services
docker-compose up -d

# Stop all services
docker-compose down

# Restart specific service
docker-compose restart app

# View logs
docker-compose logs -f app
docker-compose logs -f mysql

# Access container shell
docker-compose exec app bash
docker-compose exec mysql mysql -u root -p
```

### Laravel Commands
```bash
# Artisan commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan queue:work
docker-compose exec app php artisan cache:clear

# Composer commands
docker-compose exec app composer install
docker-compose exec app composer update

# File permissions
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Database Operations
```bash
# Database backup
docker-compose exec mysql mysqldump -u root -p unas_fest_2025 > backup.sql

# Database restore
docker-compose exec -T mysql mysql -u root -p unas_fest_2025 < backup.sql

# Access MySQL CLI
docker-compose exec mysql mysql -u root -p
```

## 🔍 Troubleshooting

### Common Issues

#### 1. Port Already in Use
```bash
# Check what's using the port
sudo lsof -i :8000
sudo lsof -i :3306

# Change ports in .env file
APP_PORT=8001
DB_PORT=3307
```

#### 2. Permission Issues
```bash
# Fix storage permissions
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 755 storage bootstrap/cache
```

#### 3. Database Connection Failed
```bash
# Check MySQL container status
docker-compose ps mysql
docker-compose logs mysql

# Verify database credentials in .env
# Wait for MySQL to fully initialize (can take 1-2 minutes)
```

#### 4. Composer Install Fails
```bash
# Clear Composer cache
docker-compose exec app composer clear-cache

# Install with verbose output
docker-compose exec app composer install -vvv
```

#### 5. Application Key Missing
```bash
# Generate application key
docker-compose exec app php artisan key:generate
```

### Performance Issues

#### 1. Slow File Operations (macOS/Windows)
```bash
# Use cached/delegated volume mounts (already configured in dev setup)
# Consider using Docker Desktop with VirtioFS (macOS) or WSL2 (Windows)
```

#### 2. High Memory Usage
```bash
# Limit container memory in docker-compose.yml
services:
  app:
    deploy:
      resources:
        limits:
          memory: 512M
```

### Debugging

#### 1. Container Won't Start
```bash
# Check container logs
docker-compose logs app

# Check container status
docker-compose ps

# Rebuild without cache
docker-compose build --no-cache app
```

#### 2. Application Errors
```bash
# Check Laravel logs
docker-compose exec app tail -f storage/logs/laravel.log

# Check Nginx logs
docker-compose logs app | grep nginx

# Enable debug mode temporarily
# Set APP_DEBUG=true in .env, then restart
docker-compose restart app
```

## 🔒 Security Considerations

### Production Deployment
1. **Change default passwords** in `.env`
2. **Set `APP_DEBUG=false`** and `APP_ENV=production`
3. **Use HTTPS** with proper SSL certificates
4. **Configure firewall** to restrict access
5. **Regular security updates** for base images
6. **Monitor logs** for suspicious activities

### Environment Variables
- Never commit `.env` file to version control
- Use Docker secrets for sensitive data in production
- Rotate passwords and API keys regularly

## 📈 Monitoring & Maintenance

### Health Checks
```bash
# Application health
curl http://localhost:8000/health

# Service health
docker-compose ps
```

### Log Management
```bash
# Rotate logs to prevent disk space issues
docker-compose exec app php artisan log:clear

# Monitor disk usage
docker system df
docker system prune  # Clean unused resources
```

### Backup Strategy
```bash
# Database backup script
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
docker-compose exec mysql mysqldump -u root -p${DB_ROOT_PASSWORD} unas_fest_2025 > "backup_${DATE}.sql"

# File backup
tar -czf "files_${DATE}.tar.gz" storage/app/public
```

## 🤝 Contributing

1. Fork the repository
2. Create feature branch: `git checkout -b feature/new-feature`
3. Make changes and test with Docker
4. Commit changes: `git commit -am 'Add new feature'`
5. Push to branch: `git push origin feature/new-feature`
6. Submit pull request

## 📞 Support

- **Issues**: Create GitHub issue with Docker logs
- **Documentation**: Check Laravel and Docker documentation
- **Community**: Join project discussions

---

## 🏗️ Architecture Overview

### Container Architecture
```
┌─────────────────────────────────────────────────────────────┐
│                    Docker Network                           │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐  │
│  │    Nginx    │  │  PHP-FPM    │  │    Laravel App      │  │
│  │   (Port 80) │◄─┤   (9000)    │◄─┤   (Application)    │  │
│  └─────────────┘  └─────────────┘  └─────────────────────┘  │
│         │                                    │               │
│         ▼                                    ▼               │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐  │
│  │   MySQL     │  │    Redis    │  │   Queue Worker      │  │
│  │  (Port 3306)│  │ (Port 6379) │  │   (Background)      │  │
│  └─────────────┘  └─────────────┘  └─────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

### Data Flow
1. **HTTP Request** → Nginx → PHP-FPM → Laravel
2. **Database** → MySQL with persistent volume
3. **Cache/Sessions** → Redis with persistent volume
4. **File Uploads** → Local storage with volume mount
5. **Background Jobs** → Queue Worker → Redis Queue
6. **Scheduled Tasks** → Scheduler Container → Cron Jobs

### Volume Mounts
- `mysql_data:/var/lib/mysql` - Database persistence
- `redis_data:/data` - Redis persistence
- `./storage:/var/www/html/storage` - File uploads & logs
- `./public/storage:/var/www/html/public/storage` - Public files

## 🔧 Advanced Configuration

### Custom PHP Configuration
Edit `docker/php/php.ini` for production or `docker/php/php-dev.ini` for development:

```ini
; Memory limits
memory_limit = 512M
max_execution_time = 300

; File uploads
upload_max_filesize = 100M
post_max_size = 100M

; OPcache (production)
opcache.enable = 1
opcache.memory_consumption = 256
```

### Custom Nginx Configuration
Edit `docker/nginx/default.conf`:

```nginx
# Custom location for API
location /api/ {
    try_files $uri $uri/ /index.php?$query_string;

    # Rate limiting
    limit_req zone=api burst=10 nodelay;
}

# Custom headers
add_header X-Custom-Header "UNAS Fest 2025";
```

### Environment-Specific Overrides
Create `docker-compose.override.yml` for local customizations:

```yaml
version: '3.8'
services:
  app:
    ports:
      - "8001:80"  # Custom port
    environment:
      - CUSTOM_VAR=value
```

## 🧪 Testing

### Running Tests
```bash
# Unit tests
docker-compose exec app php artisan test

# Feature tests
docker-compose exec app php artisan test --testsuite=Feature

# With coverage
docker-compose exec app php artisan test --coverage

# Specific test
docker-compose exec app php artisan test --filter=UserTest
```

### Database Testing
```bash
# Create test database
docker-compose exec mysql mysql -u root -p -e "CREATE DATABASE unas_fest_2025_test;"

# Run migrations for testing
docker-compose exec app php artisan migrate --database=mysql_test

# Seed test data
docker-compose exec app php artisan db:seed --database=mysql_test
```

## 🚀 Production Deployment

### Production Docker Compose
Create `docker-compose.prod.yml`:

```yaml
version: '3.8'
services:
  app:
    build:
      target: production
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
    restart: always
    deploy:
      resources:
        limits:
          memory: 1G
          cpus: '1.0'
        reservations:
          memory: 512M
          cpus: '0.5'

  mysql:
    restart: always
    deploy:
      resources:
        limits:
          memory: 2G
        reservations:
          memory: 1G
```

### SSL/HTTPS Setup
```bash
# Using Let's Encrypt with Certbot
docker run -it --rm \
  -v /etc/letsencrypt:/etc/letsencrypt \
  -v /var/www/certbot:/var/www/certbot \
  certbot/certbot certonly --webroot \
  -w /var/www/certbot \
  -d yourdomain.com
```

### Load Balancing
```yaml
# docker-compose.lb.yml
version: '3.8'
services:
  nginx-lb:
    image: nginx:alpine
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./nginx-lb.conf:/etc/nginx/nginx.conf
    depends_on:
      - app1
      - app2

  app1:
    extends:
      file: docker-compose.yml
      service: app

  app2:
    extends:
      file: docker-compose.yml
      service: app
```

## 📊 Monitoring & Observability

### Application Metrics
```bash
# Install Laravel Telescope for debugging
docker-compose exec app composer require laravel/telescope --dev
docker-compose exec app php artisan telescope:install
docker-compose exec app php artisan migrate
```

### Container Monitoring
```bash
# Resource usage
docker stats

# Container health
docker-compose ps
docker inspect unas_fest_app --format='{{.State.Health.Status}}'
```

### Log Aggregation
```yaml
# Add to docker-compose.yml
services:
  app:
    logging:
      driver: "json-file"
      options:
        max-size: "10m"
        max-file: "3"
```

## 🔐 Security Best Practices

### Container Security
1. **Non-root user**: Containers run as `www-data`
2. **Read-only filesystem**: Where possible
3. **Minimal base images**: Alpine Linux
4. **Security scanning**: Regular image updates
5. **Secrets management**: Use Docker secrets

### Network Security
```yaml
# Restrict network access
services:
  app:
    networks:
      - frontend
      - backend

  mysql:
    networks:
      - backend  # No frontend access

networks:
  frontend:
    driver: bridge
  backend:
    driver: bridge
    internal: true  # No external access
```

### Environment Security
```bash
# Use Docker secrets for sensitive data
echo "your_secret_password" | docker secret create db_password -

# Reference in compose file
services:
  mysql:
    secrets:
      - db_password
    environment:
      MYSQL_ROOT_PASSWORD_FILE: /run/secrets/db_password
```

**Happy Dockerizing! 🐳**

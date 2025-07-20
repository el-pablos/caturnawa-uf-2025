# 🎉 UNAS Fest 2025 - Competition Platform
**by Tamas**

![UNAS Fest 2025](https://img.shields.io/badge/UNAS%20Fest-2025-blue)
![Laravel](https://img.shields.io/badge/Laravel-10.x-red)
![PHP](https://img.shields.io/badge/PHP-8.3+-purple)
![Docker](https://img.shields.io/badge/Docker-Ready-blue)
![Production Ready](https://img.shields.io/badge/Production-Ready-green)

A comprehensive Laravel-based competition platform featuring payment integration, file uploads, user management with role-based access control, and hybrid Docker deployment options.

## 🎯 **Project Overview**

UNAS Fest 2025 is a comprehensive competition platform built with Laravel 10 and Bootstrap 5. The system supports Midtrans payment integration, role-based access control, analytics dashboard, and offers three flexible deployment modes: Infrastructure Only, Full Docker Development, and Full Docker Production.

## ✨ **Key Features**

### 🔐 Authentication & Authorization
- Multi-role user system (Admin, Juri, Peserta, Superadmin)
- Role-based dashboard redirection (FIXED: authentication redirect bug)
- Secure authentication with proper role separation
- Account activation system

### 💳 Payment Integration
- Midtrans payment gateway integration
- Real-time payment status updates
- Invoice generation and management
- Payment notification handling

### 📁 File Management
- Secure file upload system
- Competition submission handling
- Document management for participants
- File validation and sanitization

### 🏆 Competition Management
- Competition creation and management
- Participant registration system
- Submission tracking and evaluation
- QR Code ticketing system

### 🐳 Hybrid Docker Deployment
- **Infrastructure Only**: MySQL, Redis, MailHog in Docker + Laravel native
- **Full Docker Development**: Complete containerized development environment
- **Full Docker Production**: Optimized production deployment
- Cross-platform support (Windows, Linux, macOS)

## 🚀 **Quick Start**

### Prerequisites
- Docker and Docker Compose
- Git

### Installation

1. **Clone the repository**
```bash
git clone https://github.com/el-pablos/caturnawa-uf-2025.git
cd caturnawa-uf-2025
```

2. **Choose your setup mode**

#### 🏗️ Infrastructure Only (Recommended for Development)
Run supporting services in Docker, Laravel natively:
```bash
./setup.sh
# Select option 1
```

#### 🛠️ Full Docker Development
Complete containerized development environment:
```bash
./setup.sh
# Select option 2
```

#### 🚀 Full Docker Production
Production-ready deployment:
```bash
./setup.sh
# Select option 3
```

3. **Access the application**
- **Application**: http://localhost:8000
- **Health Check**: http://localhost:8000/health
- **phpMyAdmin**: http://localhost:8080
- **MailHog**: http://localhost:8025
- **Redis Commander**: http://localhost:8081

## 🐳 Docker Deployment Modes

### 🏗️ Infrastructure Only Mode
Perfect for developers who prefer native Laravel development with containerized services.

**What you get:**
- MySQL 8.0, Redis, MailHog running in Docker containers
- Laravel runs natively via `php artisan serve`
- Full development tools (phpMyAdmin, Redis Commander)
- Fast development cycle with native PHP debugging

**Setup:**
```bash
# Interactive setup
./setup.sh  # Select option 1

# Or direct command
make infra

# Start Laravel development server
make serve  # or php artisan serve
```

### 🛠️ Full Docker Development Mode
Complete containerized development environment with all development tools.

**What you get:**
- Complete Laravel application in Docker containers
- Xdebug integration for debugging
- All development tools included
- Consistent environment across all machines

**Setup:**
```bash
# Interactive setup
./setup.sh  # Select option 2

# Or direct command
make dev
```

**Xdebug Configuration:**
1. Set `DOCKER_TARGET=development` in `.env`
2. Configure your IDE:
   - **Host**: `localhost`
   - **Port**: `9003`
   - **Path Mapping**: `/var/www/html` → `./`

### 🚀 Full Docker Production Mode
Optimized production deployment with performance optimizations.

**What you get:**
- Production-optimized Docker images
- OPcache enabled, debug disabled
- Resource limits and health checks
- Ready for production deployment

**Setup:**
```bash
# Interactive setup
./setup.sh  # Select option 3

# Or direct command
make prod
```

## 📋 System Requirements

### Minimum Requirements
- **OS**: Linux, macOS, atau Windows 10/11 dengan WSL2
- **RAM**: Minimum 4GB, Recommended 8GB+
- **Storage**: Minimum 5GB free space
- **Network**: Internet connection untuk download images

### For Infrastructure Mode
- PHP 8.1+ with extensions (mysql, redis, gd, zip, mbstring, xml, curl)
- Composer
- Node.js 16+
- Docker and Docker Compose

### For Full Docker Modes
- Docker and Docker Compose only
- 4GB+ RAM recommended
- 5GB+ free disk space

## 🔧 Installation Instructions

### Docker Installation

#### Linux (Ubuntu/Debian):
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

#### macOS:
```bash
# Install Docker Desktop from https://www.docker.com/products/docker-desktop
# Or using Homebrew:
brew install --cask docker

# Verify installation
docker --version
docker-compose --version
```

#### Windows:
```bash
# Install Docker Desktop from https://www.docker.com/products/docker-desktop
# Make sure WSL2 is enabled
# Verify in PowerShell or WSL:
docker --version
docker-compose --version
```

### Native Dependencies (Infrastructure Mode Only)

#### PHP Installation:
**Linux:**
```bash
sudo apt update
sudo apt install php8.3 php8.3-cli php8.3-fpm php8.3-mysql php8.3-redis php8.3-gd php8.3-zip php8.3-mbstring php8.3-xml php8.3-curl
```

**macOS:**
```bash
brew install php@8.3
brew install php@8.3-redis
```

**Windows:**
```bash
# Download PHP from: https://windows.php.net/download/
# Or use XAMPP: https://www.apachefriends.org/
```

#### Composer Installation:
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

#### Node.js Installation:
**Linux:**
```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs
```

**macOS:**
```bash
brew install node
```

**Windows:**
```bash
# Download from: https://nodejs.org/
```

## 🛠️ Management Commands

### Quick Commands
```bash
make help           # Show all available commands
make setup          # Interactive hybrid setup
make infra          # Start infrastructure only
make dev            # Start development environment
make prod           # Start production environment
make serve          # Start Laravel dev server (infrastructure mode)
```

### Docker Operations
```bash
make build          # Build Docker images
make up             # Start all services
make down           # Stop all services
make restart        # Restart all services
make status         # Show service status
```

### Laravel Commands
```bash
make artisan cmd="migrate"     # Run artisan commands
make migrate                   # Run database migrations
make migrate-fresh            # Fresh migration with seeding
make seed                     # Run database seeders
make cache-clear              # Clear all caches
make cache-optimize           # Optimize caches for production
make composer-install         # Install Composer dependencies
make composer-update          # Update Composer dependencies
```

### Database Operations
```bash
make db-backup                # Backup database
make db-restore file="backup.sql"  # Restore database
make mysql                    # Access MySQL CLI
```

### Testing
```bash
make test              # Run all tests
make test-feature      # Run feature tests
make test-unit         # Run unit tests
make test-coverage     # Run tests with coverage
```

### Container Access
```bash
make shell             # Access application container shell
make logs              # Show all logs
make logs-app          # Show application logs
make logs-mysql        # Show MySQL logs
make health            # Check application health
```

### Maintenance
```bash
make clean             # Clean up Docker resources
make clean-all         # Clean up everything (including images)
make permissions       # Fix file permissions
```

## ⚙️ Configuration

### Environment Variables
Copy the appropriate environment template:
- `.env.example.infrastructure` - For infrastructure mode
- `.env.example.docker` - For full Docker modes

**Required configurations:**
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

### Services Overview

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

## 🔍 Troubleshooting

### Common Issues

#### Port Already in Use
```bash
# Check what's using the port
sudo lsof -i :8000
sudo lsof -i :3306

# Change ports in .env file
APP_PORT=8001
DB_PORT=3307
```

#### Permission Issues
```bash
# Fix storage permissions
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 755 storage bootstrap/cache

# Or using make command
make permissions
```

#### Database Connection Failed
```bash
# Check MySQL container status
docker-compose ps mysql
docker-compose logs mysql

# Verify database credentials in .env
# Wait for MySQL to fully initialize (can take 1-2 minutes)
```

#### Application Key Missing
```bash
# Generate application key
docker-compose exec app php artisan key:generate

# Or for infrastructure mode
php artisan key:generate
```

#### Composer Install Fails
```bash
# Clear Composer cache
docker-compose exec app composer clear-cache

# Install with verbose output
docker-compose exec app composer install -vvv
```

### Performance Issues

#### Slow File Operations (macOS/Windows)
```bash
# Use cached/delegated volume mounts (already configured in dev setup)
# Consider using Docker Desktop with VirtioFS (macOS) or WSL2 (Windows)
```

#### High Memory Usage
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

#### Container Won't Start
```bash
# Check container logs
docker-compose logs app

# Check container status
docker-compose ps

# Rebuild without cache
docker-compose build --no-cache app
```

#### Application Errors
```bash
# Check Laravel logs
docker-compose exec app tail -f storage/logs/laravel.log

# Check Nginx logs
docker-compose logs app | grep nginx

# Enable debug mode temporarily
# Set APP_DEBUG=true in .env, then restart
docker-compose restart app
```

## 🔒 Security Features

### Authentication & Authorization
- **FIXED**: Authentication redirect bug - all user roles now redirect to correct dashboards
- Multi-role user system with proper role separation
- Secure password hashing with bcrypt
- Session management with Redis
- CSRF protection on all forms

### Application Security
- SQL injection prevention with parameter binding
- Mass assignment protection
- File upload security with sanitization
- XSS prevention with output escaping
- Security headers implementation
- Input validation and sanitization

### Container Security
- Non-root user execution (www-data)
- Minimal base images (Alpine Linux)
- Security scanning with regular image updates
- Read-only filesystem where possible
- Network isolation with custom Docker networks

### Production Security
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

## 📊 Performance Optimizations

### Production Optimizations
- Redis caching for sessions and application cache
- Database query optimization (N+1 prevention)
- OPcache for production environments
- Optimized Docker images with multi-stage builds
- CDN-ready asset compilation

### Container Optimizations
- Multi-stage Docker builds for smaller images
- Alpine Linux base images for security and size
- Resource limits and health checks
- Proper volume mounting for persistent data
- Network optimization with custom bridges

## 🧪 Testing

### Running Tests
```bash
# All tests
make test

# Feature tests
make test-feature

# Unit tests
make test-unit

# With coverage
make test-coverage

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

### Docker Production Setup
```bash
# Production deployment
./setup.sh  # Select option 3

# Or direct command
make prod

# Deploy with updates
git pull origin master
make prod
```

### Production Environment Variables
```env
APP_ENV=production
APP_DEBUG=false
DOCKER_TARGET=production

# Security
DB_PASSWORD=strong_production_password
DB_ROOT_PASSWORD=strong_root_password

# Performance
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
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

### Health Checks
```bash
# Application health
curl http://localhost:8000/health

# Service health
make health
## 🤝 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/new-feature`
3. Make your changes and test thoroughly
4. Commit with conventional format: `git commit -m "feat: add new feature"`
5. Push to your branch: `git push origin feature/new-feature`
6. Submit a pull request

## 📁 Project Structure

```
unas-fest-2025/
├── 🐳 Docker Configuration
│   ├── Dockerfile                        # Multi-stage Docker build
│   ├── docker-compose.yml                # Production services
│   ├── docker-compose.dev.yml            # Development overrides
│   ├── docker-compose.infrastructure.yml # Infrastructure-only services
│   └── docker/                           # Service configurations
│       ├── php/                          # PHP configurations
│       ├── nginx/                        # Nginx configurations
│       ├── mysql/                        # MySQL configurations
│       └── redis/                        # Redis configurations
├── 🚀 Setup Scripts
│   ├── setup.sh                          # Hybrid setup script
│   ├── docker-setup.sh                   # Legacy Docker setup
│   └── Makefile                          # Management commands
├── ⚙️ Environment Templates
│   ├── .env.example.docker               # Full Docker mode
│   └── .env.example.infrastructure       # Infrastructure mode
├── 📚 Documentation
│   └── README.md                         # This comprehensive guide
└── 🎯 Laravel Application
    ├── app/                              # Application code
    ├── resources/                        # Views, assets, lang
    ├── routes/                           # Route definitions
    ├── database/                         # Migrations, seeders
    └── storage/                          # Logs, cache, uploads
```

## 🔧 Architecture Overview

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

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Laravel Framework
- Midtrans Payment Gateway
- Docker Community
- UNAS Fest 2025 Team

## 📞 Support

- **Issues**: [GitHub Issues](https://github.com/el-pablos/caturnawa-uf-2025/issues)
- **Repository**: https://github.com/el-pablos/caturnawa-uf-2025.git
- **Email**: support@unasfest2025.com

## 🎯 Key Achievements

### ✅ Authentication Bug Fix
- **FIXED**: Authentication redirect issue where all user roles redirected to `/peserta/dashboard`
- **RESOLVED**: 403 errors regardless of user role
- **IMPLEMENTED**: Proper role-based dashboard redirection:
  - Admin → `/admin/dashboard`
  - Juri → `/juri/dashboard`
  - Superadmin → `/admin/dashboard`
  - Peserta → `/peserta/dashboard`

### ✅ Hybrid Docker Implementation
- **Infrastructure Only Mode**: MySQL, Redis, MailHog in Docker + Laravel native
- **Full Docker Development**: Complete containerized development environment
- **Full Docker Production**: Optimized production deployment
- **Cross-Platform Support**: Windows (WSL2), Linux, macOS compatibility
- **One-Command Setup**: Interactive setup script with OS detection

### ✅ Developer Experience
- **Flexible Deployment**: Choose between native Laravel or full Docker
- **Comprehensive Documentation**: All setup modes documented
- **Management Commands**: 40+ make commands for easy management
- **Troubleshooting Guides**: Platform-specific solutions
- **Professional Implementation**: Clean code with "by Tamas" signature

### ✅ Production Ready
- **Security Features**: CSRF protection, input validation, SQL injection prevention
- **Performance Optimizations**: Redis caching, OPcache, optimized Docker images
- **Monitoring**: Health checks, logging, container monitoring
- **Scalability**: Load balancing ready, resource limits configured

---

**🎉 UNAS Fest 2025 - Competition Platform**
**Made with ❤️ by Tamas**

*Ready for development and production deployment with maximum flexibility and cross-platform compatibility!* 🚀

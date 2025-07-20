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
- **phpMyAdmin**: http://localhost:8080
- **MailHog**: http://localhost:8025
- **Redis Commander**: http://localhost:8081

## 🚀 **Production Deployment Scripts**

### 1. **setup-production-server.sh**
Script untuk setup awal server production dari nol.

**Usage:**
```bash
sudo ./setup-production-server.sh
```

**What it does:**
- ✅ Install semua dependencies (PHP, Nginx, MySQL, Redis, Node.js)
- ✅ Configure server settings
- ✅ Create database dan user
- ✅ Setup directory structure
- ✅ Configure SSL-ready Nginx
- ✅ Setup monitoring dan logging
- ✅ Create systemd services

### 2. **update-production.sh**
Script untuk update aplikasi dengan downtime minimal.

**Usage:**
```bash
sudo ./update-production.sh
```

**What it does:**
- ✅ Enable maintenance mode
- ✅ Pull latest code
- ✅ Install dependencies
- ✅ Run migrations
- ✅ Build assets
- ✅ Optimize caches
- ✅ Restart services
- ✅ Health check

### 3. **deploy-zero-downtime.sh**
Script untuk deployment zero-downtime menggunakan symlink strategy.

**Usage:**
```bash
sudo ./deploy-zero-downtime.sh
```

**What it does:**
- ✅ Create new release directory
- ✅ Clone fresh code
- ✅ Install dependencies
- ✅ Build assets
- ✅ Test new release
- ✅ Atomic switch to new release
- ✅ Automatic rollback on failure
- ✅ Keep last 3 releases

### 4. **rollback-production.sh**
Script untuk rollback ke release sebelumnya.

**Usage:**
```bash
sudo ./rollback-production.sh [steps_back]
```

**Examples:**
```bash
# Rollback ke release sebelumnya
sudo ./rollback-production.sh 1

# Rollback 2 release ke belakang
sudo ./rollback-production.sh 2
```

### 5. **restart-production.sh**
Script untuk restart lengkap dengan backup.

**Usage:**
```bash
sudo ./restart-production.sh [branch]
```

**What it does:**
- ✅ Create full backup (database + files)
- ✅ Comprehensive deployment
- ✅ Environment validation
- ✅ Detailed logging
- ✅ Advanced health checks

### 6. **monitor-system.sh**
Script untuk monitoring sistem secara real-time.

**Usage:**
```bash
./monitor-system.sh              # Quick check
./monitor-system.sh --detailed   # Detailed monitoring
```

**What it monitors:**
- ✅ Service status (Nginx, PHP-FPM, MySQL, Redis)
- ✅ System resources (CPU, Memory, Disk)
- ✅ Application response
- ✅ Database connectivity
- ✅ SSL certificate status
- ✅ Log file sizes

## 📁 **File Structure**

```
project-uf/unas-fest-2025/
├── 📄 README.md                           # This file
├── 📄 PRODUCTION-DEPLOYMENT-GUIDE.md     # Detailed deployment guide
├── 📄 PRE-LAUNCH-CHECKLIST.md           # Pre-launch checklist
├── 📄 health-check.sh                    # System health check
├── 📄 quick-fix.sh                       # Quick fixes (Linux)
├── 📄 quick-fix.bat                      # Quick fixes (Windows)
├── 🚀 setup-production-server.sh         # Initial server setup
├── 🔄 update-production.sh               # Simple update script
├── 🔄 deploy-zero-downtime.sh           # Zero-downtime deployment
├── 🔄 rollback-production.sh            # Rollback script
├── 🔄 restart-production.sh             # Full restart with backup
├── 📊 monitor-system.sh                  # System monitoring
├── 🎨 resources/css/app.css              # Updated with Poppins font
├── 🎨 resources/css/app-optimized.css    # Optimized CSS
├── ⚙️ .env.production                    # Production environment template
└── 📋 deployment-info.txt                # Deployment information
```

## 🔧 **Quick Start Guide**

### For New Production Server:
```bash
# 1. Setup server from scratch
sudo ./setup-production-server.sh

# 2. Configure DNS to point to your server
# 3. Update .env file with correct settings
# 4. Deploy application
sudo ./deploy-zero-downtime.sh

# 5. Install SSL certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

### For Existing Server Updates:
```bash
# Regular updates
sudo ./update-production.sh

# Zero-downtime deployment
sudo ./deploy-zero-downtime.sh

# Emergency rollback
sudo ./rollback-production.sh 1
```

### For Monitoring:
```bash
# Quick status check
./monitor-system.sh

# Detailed monitoring
./monitor-system.sh --detailed

# Continuous monitoring
watch -n 30 ./monitor-system.sh
```

## 📊 **System Requirements**

### Server Specifications:
- **OS**: Ubuntu 20.04 LTS or later
- **RAM**: Minimum 2GB, Recommended 4GB+
- **CPU**: 2 cores minimum, 4 cores recommended
- **Storage**: 20GB+ SSD
- **Network**: Stable internet connection

### Software Dependencies:
- **PHP**: 8.1+ with extensions (mysql, xml, mbstring, curl, zip, gd, intl, bcmath)
- **Database**: MySQL 8.0+ or MariaDB 10.5+
- **Web Server**: Nginx
- **Cache**: Redis (recommended)
- **Node.js**: 16.x or later
- **Composer**: 2.x

## 🔐 **Security Features**

- ✅ **CSRF Protection** - All forms protected
- ✅ **Input Validation** - Server-side validation
- ✅ **SQL Injection Prevention** - Using Eloquent ORM
- ✅ **File Upload Security** - Type and size validation
- ✅ **Environment Validation** - Production safety checks
- ✅ **SSL Ready** - HTTPS configuration
- ✅ **Rate Limiting** - API and form submission limits

## 🚀 **Performance Optimizations**

- ✅ **Caching Strategy** - Config, route, view caching
- ✅ **Asset Optimization** - Minified CSS/JS
- ✅ **Database Optimization** - Proper indexing
- ✅ **Redis Caching** - Session and cache storage
- ✅ **Nginx Optimization** - Gzip compression, static file caching
- ✅ **PHP-FPM Tuning** - Optimized pool configuration

## 📋 **Deployment Checklist**

### Pre-Deployment:
- [ ] Server setup completed
- [ ] DNS configured
- [ ] SSL certificate installed
- [ ] Environment variables configured
- [ ] Database created and migrated
- [ ] File permissions set correctly

### Post-Deployment:
- [ ] Health check passed
- [ ] Application responding
- [ ] Database connectivity verified
- [ ] Email functionality tested
- [ ] Payment gateway tested
- [ ] Monitoring configured

## 🐛 **Troubleshooting**

### Common Issues:

#### 1. **Permission Errors**
```bash
sudo chown -R www-data:www-data /var/www/unas-fest-2025/
sudo chmod -R 755 /var/www/unas-fest-2025/
sudo chmod -R 775 /var/www/unas-fest-2025/shared/storage/
```

#### 2. **Database Connection Issues**
```bash
# Check database service
sudo systemctl status mysql

# Test connection
mysql -u root -p
```

#### 3. **Application Not Loading**
```bash
# Check logs
tail -f /var/www/unas-fest-2025/shared/storage/logs/laravel.log

# Restart services
sudo systemctl restart nginx
sudo systemctl restart php8.1-fpm
```

#### 4. **SSL Certificate Issues**
```bash
# Renew SSL certificate
sudo certbot renew

# Check certificate status
sudo certbot certificates
```

## 📞 **Support & Maintenance**

### Regular Maintenance:
- **Daily**: Monitor logs dan system resources
- **Weekly**: Update dependencies, review performance
- **Monthly**: Update system packages, review security

### Emergency Procedures:
1. **Application Down**: Run `./monitor-system.sh --detailed`
2. **Database Issues**: Check logs dan restart services
3. **High Traffic**: Monitor resources dan scale if needed
4. **Security Issues**: Review logs dan apply patches

## 🎉 **Success Indicators**

### Technical Metrics:
- ✅ **Uptime**: 99.9%+
- ✅ **Response Time**: < 2 seconds
- ✅ **Error Rate**: < 0.1%
- ✅ **Security**: No vulnerabilities

### Business Metrics:
- ✅ **User Registration**: Smooth process
- ✅ **Payment Success**: High conversion rate
- ✅ **System Reliability**: Minimal downtime
- ✅ **User Experience**: Positive feedback

## 🔄 **Continuous Integration**

### Automated Testing:
- Unit tests dengan PHPUnit
- Integration tests untuk API
- Frontend tests untuk user interactions
- Security tests untuk vulnerabilities

### Deployment Pipeline:
1. **Development** → Code review
2. **Staging** → Automated testing
3. **Production** → Zero-downtime deployment
4. **Monitoring** → Real-time alerts

## 📈 **Scaling Recommendations**

### For High Traffic:
- Load balancer dengan multiple app servers
- Database read replicas
- CDN untuk static assets
- Redis cluster untuk caching
- Queue workers untuk background jobs

### For High Availability:
- Multi-region deployment
- Database failover
- Monitoring alerts
- Backup automation
- Disaster recovery plan

---

## 🎯 **Final Notes**

**UNAS Fest 2025** adalah sistem yang **production-ready** dengan fitur lengkap dan optimasi yang baik. Dengan menggunakan scripts yang telah disediakan, deployment dan maintenance menjadi lebih mudah dan reliable.

**Key Achievements:**
- ✅ Font updated to Poppins for better visual consistency
- ✅ Complete production deployment automation
- ✅ Zero-downtime deployment capability
- ✅ Comprehensive monitoring and alerting
- ✅ Security best practices implemented
- ✅ Performance optimizations applied

**Ready for Launch:** 🚀

**Contact:** [Your Name] - [Your Email]
**Version:** 1.0
**Last Updated:** $(date)

---

*🎉 Selamat! Sistem UNAS Fest 2025 siap untuk menjadi platform utama kompetisi nasional dengan performa dan keamanan terbaik!*

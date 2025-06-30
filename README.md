/caturnawa-uf-2025.git
cd caturnawa-uf-2025
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database Configuration**
```bash
# Edit .env file with your database configuration
php artisan migrate --seed
```

5. **Build Assets**
```bash
npm run build
# or for development
npm run dev
```

6. **Start Development Server**
```bash
php artisan serve
```

## ⚙️ Konfigurasi | Configuration

### Environment Variables
```env
# Application
APP_NAME="UNAS Fest 2025"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://caturnawa.unasfest.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=unas_fest_2025
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls

# Payment Gateway (Midtrans)
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false

# SEO & Analytics
GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX
GOOGLE_SITE_VERIFICATION=your_verification_code
```

## 🏗️ Struktur Proyek | Project Structure

```
caturnawa-uf-2025/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/           # Admin controllers
│   │   ├── Juri/            # Judge controllers
│   │   ├── Peserta/         # Participant controllers
│   │   └── Public/          # Public pages
│   ├── Models/              # Eloquent models
│   ├── Services/            # Business logic services
│   └── Providers/           # Service providers
├── resources/
│   ├── views/
│   │   ├── layouts/         # Layout templates
│   │   ├── public/          # Public pages
│   │   ├── admin/           # Admin dashboard
│   │   ├── juri/            # Judge dashboard
│   │   └── peserta/         # Participant dashboard
│   ├── js/                  # JavaScript files
│   └── scss/                # Styling files
├── public/
│   ├── assets/              # Static assets
│   ├── sitemap.xml          # SEO sitemap
│   ├── robots.txt           # Search engine directives
│   ├── manifest.json        # PWA manifest
│   └── sw.js                # Service worker
├── database/
│   ├── migrations/          # Database migrations
│   ├── seeders/             # Data seeders
│   └── factories/           # Model factories
└── routes/
    ├── web.php              # Web routes
    ├── api.php              # API routes
    └── auth.php             # Authentication routes
```

## 👥 Roles & Permissions

### 🔐 User Roles

**Super Admin**
- Kelola semua aspek sistem
- Manajemen user dan role
- Konfigurasi sistem
- Akses semua fitur

**Admin**
- Kelola kompetisi
- Kelola pendaftaran
- Kelola pembayaran
- Review submission

**Juri**
- Akses kompetisi yang ditugaskan
- Sistem penilaian
- Review dan scoring
- Export hasil

**Peserta**
- Daftar kompetisi
- Upload submission
- Tracking progress
- Download sertifikat

## 📱 Responsive Design

### Breakpoints
```scss
// Mobile First Approach
$mobile: 320px;
$tablet: 768px;
$desktop: 1024px;
$large-desktop: 1200px;
$xl-desktop: 1400px;
```

### Browser Support
- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+
- ✅ Opera 76+

## 🔧 Development

### Menjalankan Development | Running Development

**Bahasa Indonesia:**
```bash
# Start development server
php artisan serve

# Watch assets untuk perubahan
npm run dev

# Jalankan queue worker
php artisan queue:work

# Clear cache
php artisan optimize:clear
```

**English:**
```bash
# Start development server
php artisan serve

# Watch assets for changes
npm run dev

# Run queue worker
php artisan queue:work

# Clear cache
php artisan optimize:clear
```

### Testing
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Generate coverage report
php artisan test --coverage
```

### Code Quality
```bash
# PHP CS Fixer
vendor/bin/php-cs-fixer fix

# PHPStan analysis
vendor/bin/phpstan analyse

# Psalm static analysis
vendor/bin/psalm
```

## 🚀 Deployment

### Production Deployment

**Bahasa Indonesia:**

1. **Server Requirements**
   - PHP 8.1+ dengan ekstensi yang diperlukan
   - MySQL 8.0+ atau PostgreSQL 13+
   - Nginx atau Apache
   - SSL Certificate
   - Redis (recommended)

2. **Deployment Steps**
```bash
# 1. Clone & install
git clone https://github.com/el-pablos/caturnawa-uf-2025.git
cd caturnawa-uf-2025
composer install --optimize-autoloader --no-dev

# 2. Environment setup
cp .env.example .env
# Edit .env dengan konfigurasi production

# 3. Generate key & optimize
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Database migration
php artisan migrate --force

# 5. Build assets
npm ci --only=production
npm run build

# 6. Set permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

**English:**

1. **Server Requirements**
   - PHP 8.1+ with required extensions
   - MySQL 8.0+ or PostgreSQL 13+
   - Nginx or Apache
   - SSL Certificate
   - Redis (recommended)

2. **Deployment Process**
```bash
# 1. Clone & install dependencies
git clone https://github.com/el-pablos/caturnawa-uf-2025.git
cd caturnawa-uf-2025
composer install --optimize-autoloader --no-dev

# 2. Environment configuration
cp .env.example .env
# Edit .env with production settings

# 3. Generate keys & optimize
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Run database migrations
php artisan migrate --force

# 5. Build production assets
npm ci --only=production
npm run build

# 6. Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### Nginx Configuration
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name caturnawa.unasfest.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name caturnawa.unasfest.com;
    root /var/www/caturnawa-uf-2025/public;

    ssl_certificate /path/to/ssl/cert.pem;
    ssl_certificate_key /path/to/ssl/private.key;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains";

    index index.php;

    charset utf-8;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|pdf|webp|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 📊 Performance & SEO

### Core Web Vitals
- **LCP (Largest Contentful Paint)**: < 2.5s
- **FID (First Input Delay)**: < 100ms
- **CLS (Cumulative Layout Shift)**: < 0.1

### SEO Features
- ✅ **Meta Tags Optimization** - Dinamis untuk setiap halaman
- ✅ **Open Graph & Twitter Cards** - Social media optimization
- ✅ **Structured Data (JSON-LD)** - Rich snippets
- ✅ **XML Sitemap** - Auto-generated sitemap
- ✅ **Robots.txt** - Search engine directives
- ✅ **Canonical URLs** - Prevent duplicate content
- ✅ **Image Alt Tags** - Accessibility & SEO
- ✅ **Page Speed Optimization** - Optimized loading times

### Performance Optimizations
- Image lazy loading
- CSS/JS minification
- GZIP compression
- Browser caching
- Database query optimization
- Redis caching
- CDN integration ready

## 🤝 Contributing

### Contribution Guidelines

**Bahasa Indonesia:**

1. Fork repository ini
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

**English:**

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Code Standards
- Follow PSR-12 coding standards
- Write meaningful commit messages
- Include tests for new features
- Update documentation as needed
- Ensure all tests pass

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Author

**Developer**: el-pablos  
**Email**: yeteprem.end23juni@gmail.com  
**GitHub**: [@el-pablos](https://github.com/el-pablos)

## 🙏 Acknowledgments

- **Universitas Nasional** - For the project opportunity
- **Laravel Community** - For the amazing framework
- **Bootstrap Team** - For the excellent UI framework
- **Contributors** - For their valuable contributions

## 📞 Support

**Bahasa Indonesia:**

Jika Anda mengalami masalah atau memiliki pertanyaan:

1. Cek [Issues](https://github.com/el-pablos/caturnawa-uf-2025/issues) yang sudah ada
2. Buat issue baru jika belum ada
3. Hubungi developer: yeteprem.end23juni@gmail.com

**English:**

If you encounter any issues or have questions:

1. Check existing [Issues](https://github.com/el-pablos/caturnawa-uf-2025/issues)
2. Create a new issue if none exists
3. Contact developer: yeteprem.end23juni@gmail.com

---

<div align="center">

**🏆 UNAS Fest 2025 - Wujudkan Inovasi untuk Masa Depan Indonesia**

[![GitHub stars](https://img.shields.io/github/stars/el-pablos/caturnawa-uf-2025?style=social)](https://github.com/el-pablos/caturnawa-uf-2025/stargazers)
[![GitHub forks](https://img.shields.io/github/forks/el-pablos/caturnawa-uf-2025?style=social)](https://github.com/el-pablos/caturnawa-uf-2025/network)

[🌐 Live Demo](https://caturnawa.unasfest.com) • [📖 Documentation](https://github.com/el-pablos/caturnawa-uf-2025/wiki) • [🐛 Report Bug](https://github.com/el-pablos/caturnawa-uf-2025/issues) • [✨ Request Feature](https://github.com/el-pablos/caturnawa-uf-2025/issues)

</div>

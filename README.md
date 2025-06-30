# Caturnawa UNAS Fest 2025 🏆

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Build Status](https://img.shields.io/badge/Build-Passing-brightgreen.svg)](#)

> **Festival Kompetisi Nasional Terbesar di Indonesia**  
> Platform digital modern untuk kompetisi debat, film pendek, dan karya ilmiah dengan total hadiah 200 juta rupiah.

[🇮🇩 Bahasa Indonesia](#bahasa-indonesia) | [🇺🇸 English](#english)

---

## 🇮🇩 Bahasa Indonesia

### 🌟 Tentang Caturnawa 2025

Caturnawa UNAS Fest 2025 adalah festival kompetisi nasional yang menggabungkan empat kategori utama:

- **🎯 KDBI** - Kompetisi Debat Bahasa Indonesia
- **🌍 EDC** - English Debate Competition  
- **🎬 Short Movie** - Kompetisi Film Pendek
- **📚 SPC** - Scientific Paper Competition

### ✨ Fitur Utama

- **Modern UI/UX** - Desain responsif dengan teknologi terdepan
- **SEO Optimized** - Struktur SEO yang sempurna untuk ranking tinggi
- **Real-time Dashboard** - Monitoring peserta dan kompetisi secara real-time
- **Multi-Payment Gateway** - Integrasi dengan Midtrans untuk pembayaran aman
- **Document Management** - Sistem upload dan validasi dokumen otomatis
- **Notification System** - Email dan SMS notification untuk update terkini

### 🛠️ Tech Stack

#### Backend
- **Framework**: Laravel 10.x
- **Database**: MySQL 8.0
- **Cache**: Redis
- **Queue**: Redis
- **Storage**: AWS S3 / Local

#### Frontend
- **CSS Framework**: Custom CSS dengan Design System
- **JavaScript**: Vanilla JS (ES6+)
- **Icons**: Bootstrap Icons 1.11
- **Animation**: AOS (Animate On Scroll)
- **Build Tool**: Vite

### 📦 Instalasi

#### Persyaratan Sistem
- PHP >= 8.1
- Composer >= 2.0
- Node.js >= 16.x
- MySQL >= 8.0
- Redis >= 6.x

#### Langkah Instalasi
```bash
# 1. Clone Repository
git clone https://github.com/el-pablos/caturnawa-uf-2025.git
cd caturnawa-uf-2025

# 2. Install Dependencies
composer install
npm install

# 3. Environment Setup
cp .env.example .env
php artisan key:generate

# 4. Database Setup
php artisan migrate --seed

# 5. Build Assets
npm run build

# 6. Start Development Server
php artisan serve
```

### ⚙️ Konfigurasi Environment

```env
# Application
APP_NAME="Caturnawa UNAS Fest 2025"
APP_ENV=production
APP_URL=https://caturnawa2025.unasfest.com

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=caturnawa_2025

# Payment (Midtrans)
MIDTRANS_SERVER_KEY=your-server-key
MIDTRANS_CLIENT_KEY=your-client-key

# Email
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_FROM_ADDRESS=noreply@caturnawa2025.com
```

### 🚀 Deployment

#### Server Requirements
- **Server**: VPS/Dedicated (Min 2GB RAM)
- **Web Server**: Nginx/Apache
- **PHP**: 8.1+ dengan extensions yang diperlukan
- **Database**: MySQL 8.0+
- **SSL**: Let's Encrypt

#### Production Deployment
```bash
# 1. Clone & Install
git clone https://github.com/el-pablos/caturnawa-uf-2025.git
cd caturnawa-uf-2025
composer install --optimize-autoloader --no-dev

# 2. Environment Setup
cp .env.example .env
# Edit .env dengan konfigurasi production

# 3. Optimize & Build
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build

# 4. Database
php artisan migrate --force

# 5. Permissions
chmod -R 755 storage bootstrap/cache
```

---

## 🇺🇸 English

### 🌟 About Caturnawa 2025

Caturnawa UNAS Fest 2025 is a national competition festival featuring four main categories:

- **🎯 KDBI** - Indonesian Language Debate Competition
- **🌍 EDC** - English Debate Competition  
- **🎬 Short Movie** - Short Film Competition
- **📚 SPC** - Scientific Paper Competition

### ✨ Key Features

- **Modern UI/UX** - Responsive design with cutting-edge technology
- **SEO Optimized** - Perfect SEO structure for high rankings
- **Real-time Dashboard** - Real-time monitoring of participants and competitions
- **Multi-Payment Gateway** - Secure payment integration with Midtrans
- **Document Management** - Automated document upload and validation system
- **Notification System** - Email and SMS notifications for updates

### 🛠️ Tech Stack

#### Backend
- **Framework**: Laravel 10.x
- **Database**: MySQL 8.0
- **Cache**: Redis
- **Queue**: Redis
- **Storage**: AWS S3 / Local

#### Frontend
- **CSS Framework**: Custom CSS with Design System
- **JavaScript**: Vanilla JS (ES6+)
- **Icons**: Bootstrap Icons 1.11
- **Animation**: AOS (Animate On Scroll)
- **Build Tool**: Vite

### 📦 Installation

#### System Requirements
- PHP >= 8.1
- Composer >= 2.0
- Node.js >= 16.x
- MySQL >= 8.0
- Redis >= 6.x

#### Installation Steps
```bash
# 1. Clone Repository
git clone https://github.com/el-pablos/caturnawa-uf-2025.git
cd caturnawa-uf-2025

# 2. Install Dependencies
composer install
npm install

# 3. Environment Setup
cp .env.example .env
php artisan key:generate

# 4. Database Setup
php artisan migrate --seed

# 5. Build Assets
npm run build

# 6. Start Development Server
php artisan serve
```

### ⚙️ Environment Configuration

```env
# Application
APP_NAME="Caturnawa UNAS Fest 2025"
APP_ENV=production
APP_URL=https://caturnawa2025.unasfest.com

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=caturnawa_2025

# Payment (Midtrans)
MIDTRANS_SERVER_KEY=your-server-key
MIDTRANS_CLIENT_KEY=your-client-key

# Email
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_FROM_ADDRESS=noreply@caturnawa2025.com
```

### 🚀 Deployment

#### Server Requirements
- **Server**: VPS/Dedicated (Min 2GB RAM)
- **Web Server**: Nginx/Apache
- **PHP**: 8.1+ with required extensions
- **Database**: MySQL 8.0+
- **SSL**: Let's Encrypt

#### Production Deployment
```bash
# 1. Clone & Install
git clone https://github.com/el-pablos/caturnawa-uf-2025.git
cd caturnawa-uf-2025
composer install --optimize-autoloader --no-dev

# 2. Environment Setup
cp .env.example .env
# Edit .env with production configuration

# 3. Optimize & Build
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build

# 4. Database
php artisan migrate --force

# 5. Permissions
chmod -R 755 storage bootstrap/cache
```

---

## 🏗️ Project Structure

```
caturnawa-uf-2025/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Admin panel controllers
│   │   ├── Auth/           # Authentication controllers
│   │   ├── Public/         # Public website controllers
│   │   └── API/            # API controllers
│   ├── Models/             # Eloquent models
│   ├── Services/           # Business logic services
│   └── Traits/             # Reusable traits
├── resources/
│   ├── views/
│   │   ├── layouts/        # Blade layouts
│   │   ├── public/         # Public pages
│   │   ├── admin/          # Admin pages
│   │   └── components/     # Reusable components
│   ├── css/                # Custom CSS files
│   └── js/                 # JavaScript files
├── public/
│   ├── assets/             # Static assets
│   └── storage/            # Public storage link
└── database/
    ├── migrations/         # Database migrations
    ├── seeders/            # Database seeders
    └── factories/          # Model factories
```

## 📊 Performance & SEO

### Core Web Vitals
- **LCP**: < 2.5s ✅
- **FID**: < 100ms ✅
- **CLS**: < 0.1 ✅

### SEO Features
- ✅ Structured data (JSON-LD)
- ✅ Optimized meta tags
- ✅ Clean URL structure
- ✅ XML sitemap
- ✅ Social media integration
- ✅ Mobile-first responsive design

## 🔐 Security Features

- CSRF protection
- XSS prevention
- SQL injection protection
- Rate limiting
- Secure headers
- File upload validation
- User input sanitization

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

## 🤝 Contributing

### How to Contribute

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

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Author & Contributors

### Project Lead
- **Pablo** - Full Stack Developer
  - GitHub: [@el-pablos](https://github.com/el-pablos)
  - Email: yeteprem.end23juni@gmail.com

### Contributors
- **UNAS Fest Team** - Product Requirements & Testing
- **UI/UX Team** - Design System & User Experience
- **QA Team** - Quality Assurance & Testing

## 📞 Support & Contact

### Official Channels
- **Website**: [https://caturnawa2025.unasfest.com](https://caturnawa2025.unasfest.com)
- **Email**: info@caturnawa2025.com
- **Phone**: +62 21 7806700

### Social Media
- **Instagram**: [@caturnawa2025](https://instagram.com/caturnawa2025)
- **TikTok**: [@caturnawa2025](https://tiktok.com/@caturnawa2025)
- **YouTube**: [Caturnawa 2025](https://youtube.com/@caturnawa2025)
- **LinkedIn**: [Caturnawa](https://linkedin.com/company/caturnawa)

### Technical Support
- **GitHub Issues**: [Report bugs](https://github.com/el-pablos/caturnawa-uf-2025/issues)
- **GitHub Discussions**: [Community support](https://github.com/el-pablos/caturnawa-uf-2025/discussions)

## 🙏 Acknowledgments

- **Universitas Nasional** - For institutional support
- **Laravel Community** - For the amazing framework
- **Open Source Community** - For tools and libraries
- **All Contributors** - For code, documentation, and ideas

---

<div align="center">

**Dibuat dengan ❤️ untuk masa depan kompetisi yang lebih baik**

[⭐ Star this repo](https://github.com/el-pablos/caturnawa-uf-2025) • [🐛 Report bug](https://github.com/el-pablos/caturnawa-uf-2025/issues) • [💡 Feature request](https://github.com/el-pablos/caturnawa-uf-2025/discussions)

[![GitHub stars](https://img.shields.io/github/stars/el-pablos/caturnawa-uf-2025?style=social)](https://github.com/el-pablos/caturnawa-uf-2025/stargazers)
[![GitHub forks](https://img.shields.io/github/forks/el-pablos/caturnawa-uf-2025?style=social)](https://github.com/el-pablos/caturnawa-uf-2025/network)

</div>
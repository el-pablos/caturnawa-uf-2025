# UNAS Fest 2025 - Festival Kompetisi Nasional

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg)](CONTRIBUTING.md)

## 🇮🇩 Bahasa Indonesia

### Tentang UNAS Fest 2025

UNAS Fest 2025 adalah festival kompetisi nasional terbesar di Indonesia yang menggabungkan inovasi teknologi, kesehatan, dan biodiversitas. Platform website ini dibangun menggunakan Laravel untuk mengelola pendaftaran peserta, manajemen kompetisi, dan sistem penilaian.

### Fitur Utama

#### 🏆 Sistem Kompetisi
- **Tiga Kategori Utama**: Teknologi, Kesehatan, dan Biodiversitas
- **Manajemen Tim**: Mendukung kompetisi individu dan tim
- **Pendaftaran Online**: Sistem pendaftaran yang mudah dan aman
- **Timeline Kompetisi**: Manajemen jadwal pendaftaran hingga pengumuman

#### 👥 Manajemen Pengguna
- **Multi-Role System**: Super Admin, Admin, Juri, dan Peserta
- **Dashboard Khusus**: Interface yang disesuaikan untuk setiap role
- **Profil Pengguna**: Manajemen data peserta dan tim

#### 💳 Sistem Pembayaran
- **Integrasi Midtrans**: Gateway pembayaran yang aman
- **Early Bird Pricing**: Diskon khusus untuk pendaftar awal
- **Invoice Otomatis**: Generasi invoice dan receipt

#### 📊 Sistem Penilaian
- **Dashboard Juri**: Interface khusus untuk penilaian
- **Scoring System**: Sistem penilaian terstruktur
- **Laporan Hasil**: Export hasil dalam berbagai format

#### 🎫 Manajemen Tiket
- **QR Code Generator**: Tiket digital dengan QR code
- **Verifikasi Real-time**: Scanner QR untuk check-in
- **Tracking Kehadiran**: Monitoring peserta event

### Teknologi yang Digunakan

- **Backend**: Laravel 10.x
- **Frontend**: Bootstrap 5, Tailwind CSS
- **Database**: MySQL
- **Payment**: Midtrans Gateway
- **Maps**: Google Maps API
- **Analytics**: Visitor Statistics
- **Authentication**: Laravel Sanctum
- **File Storage**: Laravel Storage

### Persyaratan Sistem

- PHP 8.1 atau lebih tinggi
- Composer
- Node.js & NPM
- MySQL 5.7 atau lebih tinggi
- Extension PHP: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON

### Instalasi & Setup

1. **Clone Repository**
   ```bash
   git clone https://github.com/el-pablos/caturnawa-uf-2025.git
   cd caturnawa-uf-2025
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Build Assets**
   ```bash
   npm run build
   ```

6. **Storage Link**
   ```bash
   php artisan storage:link
   ```

7. **Start Development Server**
   ```bash
   php artisan serve
   ```

### Konfigurasi Environment

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=unas_fest_2025
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Midtrans Payment
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false

# Google Maps
GOOGLE_MAPS_API_KEY=your_google_maps_key

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
```

### SEO & Performance

- **Meta Tags Otomatis**: SEO-friendly meta tags untuk semua halaman
- **Structured Data**: JSON-LD untuk rich snippets
- **Optimized Images**: Lazy loading dan compression
- **CDN Ready**: Asset optimization untuk production
- **Sitemap**: Auto-generated XML sitemap

### API Endpoints

#### Authentication
- `POST /api/login` - User login
- `POST /api/register` - User registration
- `POST /api/logout` - User logout

#### Competitions
- `GET /api/competitions` - List all competitions
- `GET /api/competitions/{id}` - Get competition details
- `POST /api/competitions/{id}/register` - Register for competition

#### Submissions
- `GET /api/submissions` - List user submissions
- `POST /api/submissions` - Create new submission
- `PUT /api/submissions/{id}` - Update submission

### Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

### Deployment

1. **Production Environment**
   ```bash
   # Optimize for production
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Database Migration**
   ```bash
   php artisan migrate --force
   ```

3. **Asset Compilation**
   ```bash
   npm run production
   ```

### Kontribusi

Kami menyambut kontribusi dari komunitas! Silakan baca [CONTRIBUTING.md](CONTRIBUTING.md) untuk panduan kontribusi.

### Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

### Kontak

- **Email**: info@unasfest.com
- **Telepon**: 0858-1737-8442
- **Website**: [https://unasfest.com](https://unasfest.com)
- **Repository**: [https://github.com/el-pablos/caturnawa-uf-2025](https://github.com/el-pablos/caturnawa-uf-2025)

---

## 🇺🇸 English

### About UNAS Fest 2025

UNAS Fest 2025 is Indonesia's largest national competition festival combining innovation in technology, health, and biodiversity. This website platform is built using Laravel to manage participant registration, competition management, and scoring systems.

### Key Features

#### 🏆 Competition System
- **Three Main Categories**: Technology, Health, and Biodiversity
- **Team Management**: Support for individual and team competitions
- **Online Registration**: Easy and secure registration system
- **Competition Timeline**: Management from registration to announcement

#### 👥 User Management
- **Multi-Role System**: Super Admin, Admin, Jury, and Participants
- **Custom Dashboards**: Tailored interface for each role
- **User Profiles**: Participant and team data management

#### 💳 Payment System
- **Midtrans Integration**: Secure payment gateway
- **Early Bird Pricing**: Special discounts for early registrants
- **Auto Invoicing**: Automatic invoice and receipt generation

#### 📊 Scoring System
- **Jury Dashboard**: Dedicated interface for scoring
- **Structured Scoring**: Comprehensive evaluation system
- **Result Reports**: Export results in various formats

#### 🎫 Ticket Management
- **QR Code Generator**: Digital tickets with QR codes
- **Real-time Verification**: QR scanner for check-in
- **Attendance Tracking**: Event participant monitoring

### Technology Stack

- **Backend**: Laravel 10.x
- **Frontend**: Bootstrap 5, Tailwind CSS
- **Database**: MySQL
- **Payment**: Midtrans Gateway
- **Maps**: Google Maps API
- **Analytics**: Visitor Statistics
- **Authentication**: Laravel Sanctum
- **File Storage**: Laravel Storage

### System Requirements

- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MySQL 5.7 or higher
- PHP Extensions: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON

### Installation & Setup

1. **Clone Repository**
   ```bash
   git clone https://github.com/el-pablos/caturnawa-uf-2025.git
   cd caturnawa-uf-2025
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Build Assets**
   ```bash
   npm run build
   ```

6. **Storage Link**
   ```bash
   php artisan storage:link
   ```

7. **Start Development Server**
   ```bash
   php artisan serve
   ```

### Environment Configuration

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=unas_fest_2025
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Midtrans Payment
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false

# Google Maps
GOOGLE_MAPS_API_KEY=your_google_maps_key

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
```

### SEO & Performance

- **Auto Meta Tags**: SEO-friendly meta tags for all pages
- **Structured Data**: JSON-LD for rich snippets
- **Optimized Images**: Lazy loading and compression
- **CDN Ready**: Asset optimization for production
- **Sitemap**: Auto-generated XML sitemap

### API Endpoints

#### Authentication
- `POST /api/login` - User login
- `POST /api/register` - User registration
- `POST /api/logout` - User logout

#### Competitions
- `GET /api/competitions` - List all competitions
- `GET /api/competitions/{id}` - Get competition details
- `POST /api/competitions/{id}/register` - Register for competition

#### Submissions
- `GET /api/submissions` - List user submissions
- `POST /api/submissions` - Create new submission
- `PUT /api/submissions/{id}` - Update submission

### Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

### Deployment

1. **Production Environment**
   ```bash
   # Optimize for production
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Database Migration**
   ```bash
   php artisan migrate --force
   ```

3. **Asset Compilation**
   ```bash
   npm run production
   ```

### Contributing

We welcome contributions from the community! Please read [CONTRIBUTING.md](CONTRIBUTING.md) for contribution guidelines.

### License

This project is licensed under the [MIT License](LICENSE).

### Contact

- **Email**: info@unasfest.com
- **Phone**: 0858-1737-8442
- **Website**: [https://unasfest.com](https://unasfest.com)
- **Repository**: [https://github.com/el-pablos/caturnawa-uf-2025](https://github.com/el-pablos/caturnawa-uf-2025)

---

## 📸 Screenshots

### Homepage
![Homepage](docs/screenshots/homepage.png)

### Competition Details
![Competition Details](docs/screenshots/competition-detail.png)

### Dashboard
![Dashboard](docs/screenshots/dashboard.png)

---

<div align="center">
  <p>Made with ❤️ for UNAS Fest 2025</p>
  <p>© 2025 Universitas Nasional Jakarta. All rights reserved.</p>
</div>
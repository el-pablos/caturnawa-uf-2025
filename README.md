# UNAS Fest 2025 - Competition Registration System

## Deskripsi Proyek
Aplikasi web pendaftaran lomba UNAS Fest 2025 dengan tema Bio-diversity, Health, dan Technology. Sistem ini mengintegrasikan payment gateway Midtrans untuk pembayaran fee lomba dan mencakup 4 role pengguna: Super Admin, Admin, Juri, dan Peserta.

## Fitur Utama
- **Multi-role Management**: Super Admin, Admin, Juri, Peserta
- **Payment Gateway**: Integrasi Midtrans untuk pembayaran
- **E-ticket System**: Generate barcode/QR code untuk peserta
- **Competition Management**: CRUD kompetisi dan manajemen peserta
- **Scoring System**: Sistem penilaian dari juri
- **Dashboard Analytics**: Statistik dan laporan

## Tech Stack
- **Backend**: Laravel 10
- **Frontend**: Bootstrap 5, Blade Templates
- **Database**: MySQL
- **Payment**: Midtrans Laravel
- **Others**: Chart.js, QR Code Generator

## Requirements
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL 8.0+
- Git

## Installation

### 1. Clone Repository
```bash
git clone <repository-url>
cd unas-fest-2025
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup
```bash
# Edit .env file dengan konfigurasi database
php artisan migrate --seed
```

### 5. Midtrans Configuration
Tambahkan konfigurasi Midtrans di `.env`:
```
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

### 6. Storage Link
```bash
php artisan storage:link
```

### 7. Run Application
```bash
php artisan serve
npm run dev
```

## Default Accounts

### Super Admin
- Email: superadmin@unasfest.ac.id
- Password: superadmin123

### Admin
- Email: admin@unasfest.ac.id
- Password: admin123

### Juri
- Email: juri@unasfest.ac.id
- Password: juri123

## Project Structure
```
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/
│   │   ├── Auth/
│   │   ├── Juri/
│   │   └── Peserta/
│   ├── Models/
│   ├── Services/
│   └── Traits/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── admin/
│   │   ├── auth/
│   │   ├── juri/
│   │   └── peserta/
│   └── js/
└── routes/
```

## API Documentation
- `/api/competitions` - Daftar kompetisi
- `/api/payments` - Handle pembayaran
- `/api/tickets` - Generate e-ticket

## Contributing
1. Fork repository
2. Create feature branch
3. Commit changes
4. Push to branch
5. Create Pull Request

## 📁 File Structure Update

Semua file yang diperlukan telah dibuat lengkap termasuk:

### ✅ Controllers Lengkap
- `app/Http/Controllers/Auth/AuthController.php`
- `app/Http/Controllers/Admin/DashboardController.php`
- `app/Http/Controllers/Admin/CompetitionController.php`
- `app/Http/Controllers/Juri/JuriDashboardController.php`
- `app/Http/Controllers/Juri/ScoringController.php`
- `app/Http/Controllers/Peserta/PesertaDashboardController.php`
- `app/Http/Controllers/Peserta/CompetitionController.php`
- `app/Http/Controllers/PaymentController.php`

### ✅ Views Dashboard
- `resources/views/layouts/app.blade.php` (Main layout)
- `resources/views/auth/login.blade.php`
- `resources/views/admin/dashboard.blade.php`
- `resources/views/juri/dashboard.blade.php`
- `resources/views/peserta/dashboard.blade.php`

### ✅ Frontend Assets
- `resources/css/app.css` (Custom styling)
- `resources/js/app.js` (Interactive JavaScript)
- `vite.config.js` (Build configuration)

### ✅ Core Laravel Files
- `bootstrap/app.php` (Application bootstrap)
- `public/index.php` (Entry point)
- `routes/web.php` (Web routes)
- `routes/api.php` (API routes)
- `routes/console.php` (Console commands)

### ✅ Database Structure
- 6 Migration files (Users, Competitions, Registrations, Payments, Submissions, Scores)
- Seeders dengan data awal dan role permissions

### ✅ Storage Directories
- `storage/app/public/` (File uploads)
- `storage/framework/` (Cache, sessions, views)
- `storage/logs/` (Application logs)

## 🚀 Ready to Run!

Proyek sekarang sudah 100% lengkap dan siap dijalankan:

```bash
# Windows
setup.bat

# Linux/Mac
chmod +x setup.sh
./setup.sh

# Manual
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

## License
This project is licensed under the MIT License.

## Contact
- Email: support@unasfest.ac.id
- Website: https://unasfest.ac.id

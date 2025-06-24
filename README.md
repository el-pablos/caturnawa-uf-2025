# 🏆 UNAS Fest 2025 - Competition Management System

<div align="center">

![UNAS Fest 2025](https://img.shields.io/badge/UNAS%20Fest-2025-blue?style=for-the-badge)
![Laravel](https://img.shields.io/badge/Laravel-10.x-red?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql)

**Sistem Manajemen Kompetisi Nasional yang Komprehensif**

*Menggabungkan inovasi teknologi, kesehatan, dan biodiversitas untuk masa depan berkelanjutan*

</div>

---

## 📋 Daftar Isi

- [🎯 Tentang Proyek](#-tentang-proyek)
- [✨ Fitur Utama](#-fitur-utama)
- [🏗️ Arsitektur Sistem](#️-arsitektur-sistem)
- [🚀 Instalasi](#-instalasi)
- [⚙️ Konfigurasi](#️-konfigurasi)
- [👥 Manajemen Pengguna](#-manajemen-pengguna)
- [🔧 Penggunaan](#-penggunaan)
- [📱 Responsive Design](#-responsive-design)
- [🔒 Keamanan](#-keamanan)
- [📊 Monitoring & Analytics](#-monitoring--analytics)

---

## 🎯 Tentang Proyek

**UNAS Fest 2025** adalah platform kompetisi nasional yang dirancang khusus untuk mengelola berbagai jenis kompetisi dalam bidang:

- 🌱 **Bio-diversity** - Kompetisi inovasi lingkungan dan keanekaragaman hayati
- 🏥 **Health** - Kompetisi solusi kesehatan dan teknologi medis
- 💻 **Technology** - Kompetisi pengembangan teknologi dan inovasi digital

### 🎪 Visi & Misi

**Visi:** Menjadi platform terdepan dalam menyelenggarakan kompetisi nasional yang mendorong inovasi berkelanjutan.

**Misi:**
- Memfasilitasi kompetisi berkualitas tinggi
- Mendorong kolaborasi antar institusi pendidikan
- Mengembangkan talenta muda Indonesia
- Mempromosikan solusi inovatif untuk tantangan nasional

---

## ✨ Fitur Utama

### 🔐 **Sistem Autentikasi & Otorisasi**
- Multi-role authentication (Super Admin, Admin, Juri, Peserta)
- Role-based access control (RBAC)
- Secure session management
- Password reset & email verification

### 👨‍💼 **Panel Super Admin**
- **Dashboard Analytics** - Real-time statistics & insights
- **User Management** - CRUD operations untuk semua pengguna
- **Competition Management** - Kelola kompetisi dari A-Z
- **Payment Oversight** - Monitor & konfirmasi pembayaran
- **System Reports** - Laporan komprehensif dengan export
- **QR Scanner** - Sistem check-in peserta dengan QR code

### 👩‍💻 **Panel Admin**
- **Competition Control** - Toggle status kompetisi dengan pop-up
- **Registration Management** - Re-enable/delete registrations
- **Payment Processing** - Konfirmasi & tolak pembayaran
- **Participant Monitoring** - Track progress peserta
- **Report Generation** - Generate laporan dengan data real

### ⚖️ **Panel Juri**
- **Submission Review** - Evaluasi karya peserta
- **Scoring System** - Penilaian multi-kriteria
- **Comment System** - Feedback untuk peserta
- **Progress Tracking** - Monitor status penilaian
- **Competition Assignment** - Akses kompetisi yang ditugaskan

### 🎓 **Panel Peserta**
- **Registration System** - Daftar kompetisi dengan mudah
- **Submission Management** - Upload & edit karya
- **Payment Integration** - Pembayaran dengan Midtrans QRIS
- **E-Ticket System** - QR code untuk check-in event
- **Progress Tracking** - Monitor status registrasi & submission

### 💳 **Sistem Pembayaran**
- **Midtrans Integration** - QRIS payment gateway
- **Multiple Payment Methods** - Transfer bank, e-wallet, dll
- **Payment Confirmation** - Admin dapat konfirmasi manual
- **Refund System** - Proses refund otomatis
- **Transaction History** - Riwayat pembayaran lengkap

### 📱 **QR Code System**
- **E-Ticket Generation** - QR code otomatis setelah payment
- **Real-time Scanning** - Scanner dengan camera integration
- **Check-in Management** - Track kehadiran peserta
- **Verification System** - Multi-layer validation
- **History Tracking** - Riwayat check-in komprehensif

---

## 🏗️ Arsitektur Sistem

### 🗄️ **Database Schema**
```
├── users (Super Admin, Admin, Juri, Peserta)
├── competitions (Bio-diversity, Health, Technology)
├── registrations (Pendaftaran peserta)
├── submissions (Karya peserta)
├── payments (Transaksi pembayaran)
├── scores (Penilaian juri)
├── qr_codes (E-ticket system)
└── reports (Analytics & reporting)
```

### 🔄 **Workflow Sistem**
1. **Registration** → Peserta daftar kompetisi
2. **Payment** → Pembayaran via Midtrans
3. **Confirmation** → Admin konfirmasi pembayaran
4. **QR Generation** → Sistem generate e-ticket
5. **Submission** → Peserta upload karya
6. **Review** → Juri evaluasi submission
7. **Scoring** → Penilaian multi-kriteria
8. **Check-in** → QR scanner untuk event

---

## 🚀 Instalasi

### 📋 **Persyaratan Sistem**
- **PHP** 8.1 atau lebih tinggi
- **Laravel** 10.x
- **MySQL** 8.0+ atau **PostgreSQL** 13+
- **Composer** 2.x
- **Node.js** 16+ & **NPM**
- **Git** untuk version control

### 🛠️ **Langkah Instalasi**

1. **Clone Repository**
   ```bash
   git clone https://github.com/username/unas-fest-2025.git
   cd unas-fest-2025
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

4. **Database Configuration**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=unas_fest_2025
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Database Migration & Seeding**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Storage Setup**
   ```bash
   php artisan storage:link
   ```

7. **Build Assets**
   ```bash
   npm run build
   ```

8. **Start Development Server**
   ```bash
   php artisan serve
   ```

---

## ⚙️ Konfigurasi

### 💳 **Midtrans Payment Gateway**
```env
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

### 📧 **Email Configuration**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@unasfest.com
MAIL_FROM_NAME="UNAS Fest 2025"
```

### 📁 **File Storage**
```env
FILESYSTEM_DISK=public
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=unas-fest-2025
```

---

## 👥 Manajemen Pengguna

### 🔑 **Default Accounts**
Setelah seeding, akun default tersedia:

| Role | Email | Password | Akses |
|------|-------|----------|-------|
| Super Admin | superadmin@unasfest.com | password | Full system access |
| Admin | admin@unasfest.com | password | Competition management |
| Juri | juri@unasfest.com | password | Submission evaluation |
| Peserta | peserta@unasfest.com | password | Competition participation |

### 🎭 **Role & Permissions**

#### 🔴 **Super Admin**
- ✅ Semua akses sistem
- ✅ User management (CRUD)
- ✅ Competition management
- ✅ Payment oversight
- ✅ System configuration
- ✅ QR Scanner access
- ✅ Advanced reporting

#### 🟠 **Admin**
- ✅ Competition management
- ✅ Registration oversight
- ✅ Payment confirmation
- ✅ Basic reporting
- ✅ QR Scanner access
- ❌ User management
- ❌ System configuration

#### 🟡 **Juri**
- ✅ Assigned competitions
- ✅ Submission review
- ✅ Scoring system
- ✅ Comment system
- ❌ Competition management
- ❌ Payment access
- ❌ User management

#### 🟢 **Peserta**
- ✅ Competition registration
- ✅ Submission management
- ✅ Payment processing
- ✅ E-ticket access
- ❌ Admin functions
- ❌ Scoring access
- ❌ Other participants' data

---

## 🔧 Penggunaan

### 🏁 **Getting Started**

1. **Akses Sistem**
   - Buka browser dan kunjungi `http://localhost:8000`
   - Login menggunakan akun default sesuai role

2. **Super Admin Workflow**
   ```
   Dashboard → User Management → Create Users
   → Competition Setup → Assign Juries
   → Monitor Registrations → Confirm Payments
   ```

3. **Admin Workflow**
   ```
   Dashboard → Competition Management
   → Registration Oversight → Payment Processing
   → Generate Reports
   ```

4. **Juri Workflow**
   ```
   Dashboard → Assigned Competitions
   → Review Submissions → Score Participants
   → Add Comments
   ```

5. **Peserta Workflow**
   ```
   Register → Choose Competition → Make Payment
   → Upload Submission → Get E-ticket
   → Attend Event
   ```

### 📊 **Dashboard Features**

#### **Super Admin Dashboard**
- 📈 Real-time analytics
- 👥 User statistics
- 💰 Revenue tracking
- 🏆 Competition overview
- 📱 QR scanner access

#### **Admin Dashboard**
- 🎯 Competition metrics
- 📝 Registration status
- 💳 Payment overview
- 📊 Basic reports

#### **Juri Dashboard**
- 📋 Assigned competitions
- ⭐ Scoring progress
- 💬 Comment history
- 📈 Evaluation stats

#### **Peserta Dashboard**
- 🎫 My registrations
- 📤 Submission status
- 💰 Payment history
- 🎟️ E-tickets

---

## 📱 Responsive Design

### 📱 **Mobile-First Approach**
- Responsive navbar dengan scrollable menu
- Touch-friendly interface
- Optimized untuk semua screen sizes
- Progressive Web App (PWA) ready

### 💻 **Cross-Platform Compatibility**
- **Desktop** - Full feature access
- **Tablet** - Adaptive layout
- **Mobile** - Optimized experience
- **All Browsers** - Chrome, Firefox, Safari, Edge

### 🎨 **UI/UX Features**
- Modern Bootstrap 5 design
- Professional white admin theme
- Smooth animations & transitions
- Intuitive navigation
- Accessibility compliant

---

## 🔒 Keamanan

### 🛡️ **Security Features**
- **CSRF Protection** - Laravel built-in CSRF tokens
- **SQL Injection Prevention** - Eloquent ORM protection
- **XSS Protection** - Input sanitization
- **Authentication** - Secure session management
- **Authorization** - Role-based access control
- **Password Hashing** - Bcrypt encryption
- **File Upload Security** - Type & size validation

### 🔐 **Data Protection**
- Encrypted sensitive data
- Secure payment processing
- GDPR compliance ready
- Regular security updates
- Audit trail logging

---

## 📊 Monitoring & Analytics

### 📈 **Real-time Analytics**
- Competition participation rates
- Payment success rates
- User engagement metrics
- System performance monitoring

### 📋 **Reporting System**
- **Competition Reports** - Participant statistics
- **Financial Reports** - Revenue & payment tracking
- **User Reports** - Registration & activity data
- **Export Options** - PDF, Excel, CSV formats

### 🔍 **System Monitoring**
- Error logging & tracking
- Performance metrics
- User activity logs
- Security event monitoring

---

## 🤝 Kontribusi

### 🔄 **Development Workflow**
1. Fork repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

### 📝 **Coding Standards**
- Follow PSR-12 coding standards
- Write comprehensive tests
- Document new features
- Follow Laravel best practices

### 🐛 **Bug Reports**
- Use GitHub Issues
- Provide detailed description
- Include steps to reproduce
- Add relevant screenshots

---

## 📞 Support & Contact

### 🆘 **Technical Support**
- **Email**: support@unasfest.com
- **Documentation**: [Wiki](https://github.com/username/unas-fest-2025/wiki)
- **Issues**: [GitHub Issues](https://github.com/username/unas-fest-2025/issues)

### 🏢 **Organization**
- **Universitas Nasional**
- **Jakarta Selatan, Indonesia**
- **Website**: [unasfest.com](https://unasfest.com)

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

```
Copyright (c) 2025 UNAS Fest

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.
```

---

<div align="center">

**🏆 UNAS Fest 2025 - Membangun Masa Depan Melalui Kompetisi Inovatif 🏆**

*Made with ❤️ by UNAS Development Team*

</div>
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

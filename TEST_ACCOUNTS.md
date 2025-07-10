# UNAS Fest 2025 - Test Accounts

Database telah berhasil direset dan seeder telah dijalankan ulang dengan format yang benar.

## 🔐 Akun Test yang Tersedia

### Super Admin
- **Email**: `superadmin@unasfest.com`
- **Password**: `password123`
- **Status**: Mahasiswa Unas
- **Institution**: UNAS Fest 2025

### Admin (1-5)
- **Email**: `admin1@unasfest.com` / `password123`
- **Email**: `admin2@unasfest.com` / `password123`
- **Email**: `admin3@unasfest.com` / `password123`
- **Email**: `admin4@unasfest.com` / `password123`
- **Email**: `admin5@unasfest.com` / `password123`
- **Status**: Mahasiswa Unas
- **Institution**: UNAS Fest 2025

### Juri (1-5)
- **Email**: `juri1@unasfest.com` / `password123`
- **Email**: `juri2@unasfest.com` / `password123`
- **Email**: `juri3@unasfest.com` / `password123`
- **Email**: `juri4@unasfest.com` / `password123`
- **Email**: `juri5@unasfest.com` / `password123`
- **Status**: Mahasiswa Eksternal
- **Institution**: Berbagai universitas eksternal

### Peserta (1-5)
- **Email**: `peserta1@unasfest.com` / `password123` (Status: Siswa SMA/SMK)
- **Email**: `peserta2@unasfest.com` / `password123` (Status: Mahasiswa Eksternal)
- **Email**: `peserta3@unasfest.com` / `password123` (Status: Mahasiswa Unas)
- **Email**: `peserta4@unasfest.com` / `password123` (Status: Siswa SMA/SMK)
- **Email**: `peserta5@unasfest.com` / `password123` (Status: Mahasiswa Eksternal)

## 🎯 Perubahan yang Telah Dilakukan

### 1. ✅ Participant Count Displays Dihapus
- Homepage: Statistik peserta diganti dengan info kompetisi
- Competition details: Jumlah peserta dihapus dari statistik
- About page: Section "Institusi Peserta" dihapus
- Admin dashboard: Terminologi peserta diubah
- API endpoints: Perhitungan participant count dihapus

### 2. ✅ Enhanced Account Registration
- Form registrasi sekarang memiliki field wajib:
  - **Participant Status**: Dropdown dengan pilihan "Mahasiswa Unas", "Mahasiswa Eksternal", "Siswa SMA/SMK"
  - **Asal Instansi**: Field wajib untuk nama institusi
  - **Student ID/NIM**: Field wajib untuk ID mahasiswa/siswa

### 3. ✅ Competition Registration Refactored
- Dropdown participant category dihapus dari form kompetisi
- Status peserta diambil dari akun user (read-only display)
- Pricing otomatis berdasarkan status akun user
- JavaScript untuk handling participant category dihapus

### 4. ✅ Database Schema Updated
- Migration baru: `add_participant_status_to_users_table`
- Field `participant_status` ditambahkan ke tabel users
- Seeder diperbarui dengan participant status yang beragam

### 5. ✅ Controllers Updated
- Auth Controller: Validasi field baru
- Competition Controller: Logic menggunakan status dari akun user
- Statistics Controllers: Participant count logic dihapus

## 🧪 Testing Flow

1. **Account Registration**: Test dengan berbagai participant status
2. **Login**: Gunakan akun test yang tersedia
3. **Competition Registration**: Verifikasi status otomatis dari akun
4. **Pricing**: Pastikan harga sesuai dengan status peserta
5. **Payment Flow**: Test dengan Midtrans (sandbox)

## 🌐 Server Information

- **Development Server**: http://127.0.0.1:8000
- **Database**: Fresh migration dengan seeder terbaru
- **Midtrans**: Menggunakan official package v2.6.2 (sandbox mode)

## 📝 Format Email yang Benar

Sesuai permintaan, format email sekarang mengikuti pola:
- `admin1@unasfest.com`, `admin2@unasfest.com`, dst.
- `juri1@unasfest.com`, `juri2@unasfest.com`, dst.
- `peserta1@unasfest.com`, `peserta2@unasfest.com`, dst.

Semua password: `password123`

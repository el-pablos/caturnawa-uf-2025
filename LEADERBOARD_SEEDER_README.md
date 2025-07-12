# 🏆 Leaderboard Seeder Documentation

## Overview
File seeder untuk mengisi data dummy leaderboard kompetisi UNAS Fest 2025 dengan data realistis yang sesuai dengan proposal.

## File yang Dibuat

### 1. LeaderboardSeeder.php
**Location**: `database/seeders/LeaderboardSeeder.php`
**Purpose**: Membuat data dummy leaderboard untuk 6 kompetisi UNAS Fest 2025

**Kompetisi yang Di-seed:**
- 🔍 **DCC (Data Challenge Competition)** - 8 teams
- 📝 **Scientific Paper Competition** - 8 teams  
- 🗣️ **English Debate Competition** - 8 teams
- 🎭 **KDBI (Kompetisi Debat Bahasa Indonesia)** - 8 teams
- 🎬 **Short Movie Competition** - 8 teams
- 📸 **Photography Competition** - 8 teams

**Total**: 48 dummy teams dengan data realistis

### 2. Script Eksekusi
- **Windows**: `seed-leaderboard.bat`
- **Linux/Mac**: `seed-leaderboard.sh`

## Data yang Di-generate

### Struktur Data per Team:
- **Team Name**: Nama tim yang sesuai tema kompetisi
- **Participant Name**: Nama peserta yang realistis
- **Institution**: Universitas top Indonesia (UI, ITB, UGM, dll)
- **Score**: Skor kompetisi (80-96 point range)
- **Rank**: Ranking 1-8 per kompetisi
- **Status**: Final/Semifinal/Penyisihan
- **Victory Points**: Calculated based on score

### Sample Data:
```
DCC Competition:
🥇 Data Scientists United - UI (94.8)
🥈 Analytics Pro - ITB (91.5)  
🥉 Big Data Heroes - UGM (89.2)
...

Photography Competition:
🥇 Lens Masters - ISBI (96.3)
🥈 Photo Experts - Trisakti (93.8)
🥉 Visual Storytellers - UPH (91.2)
...
```

## Cara Menjalankan

### Option 1: Script Otomatis
```bash
# Windows
./seed-leaderboard.bat

# Linux/Mac  
chmod +x seed-leaderboard.sh
./seed-leaderboard.sh
```

### Option 2: Laravel Artisan
```bash
# Hanya leaderboard seeder
php artisan db:seed --class=LeaderboardSeeder

# Semua seeder (termasuk leaderboard)
php artisan db:seed
```

### Option 3: Fresh Migration + Seed
```bash
# Reset database dan isi ulang semua data
php artisan migrate:fresh --seed
```

## Prerequisites

1. **Database sudah di-setup** 
2. **Competition data sudah ada** (run `UnasFestCompetitionSeeder` dulu)
3. **Laravel environment ready**

## Database Tables Affected

### Primary:
- `leaderboard_entries` - Data ranking kompetisi

### Supporting:  
- `users` - Dummy participant accounts
- `registrations` - Dummy registrations
- `competitions` - Reference ke kompetisi existing

## Features

### Realistic Data:
- ✅ Score distribution yang natural (80-96)
- ✅ Universitas Indonesia yang terkenal
- ✅ Nama peserta yang sesuai konteks
- ✅ Status tournament yang progresif

### Auto-calculation:
- ✅ Victory points based on score
- ✅ Ranking otomatis (1-8)
- ✅ Registration numbers unik
- ✅ Timestamps realistis

### Data Integrity:
- ✅ Foreign key constraints
- ✅ Unique competition-registration pairs
- ✅ Proper user email generation
- ✅ Status consistency

## Troubleshooting

### Error: "No competitions found"
**Solution**: Run competition seeder first
```bash
php artisan db:seed --class=UnasFestCompetitionSeeder
```

### Error: "Duplicate entry"
**Solution**: Clear existing data
```bash
php artisan tinker
>>> App\Models\LeaderboardEntry::truncate();
>>> exit
```

### Error: "Class not found"
**Solution**: Composer autoload
```bash
composer dump-autoload
```

## Integration dengan Proposal

Data seeder ini menggunakan **exact same data** yang ada di proposal:
- Team names match proposal leaderboard
- Universities sesuai dengan contoh di proposal  
- Score ranges realistis untuk kompetisi nasional
- Status progression yang logical

## Next Steps

Setelah seeder dijalankan:
1. ✅ Cek leaderboard page di website
2. ✅ Test sorting dan filtering
3. ✅ Verify data di admin dashboard
4. ✅ Screenshot untuk proposal

---

**Dibuat oleh**: Tim Development UNAS Fest 2025  
**Tanggal**: Juli 2025  
**Version**: 1.0  
**Status**: Ready for Production

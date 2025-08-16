# CI/CD Pipeline Setup Guide

## 🚀 Automated Deployment with GitHub Actions

Pipeline ini akan otomatis deploy setiap perubahan di branch `master` ke server production.

## 📋 Setup GitHub Secrets

Buka repository GitHub → Settings → Secrets and variables → Actions → New repository secret

Tambahkan secrets berikut:

### 1. HOST
```
178.128.58.34
```

### 2. USERNAME
```
root
```

### 3. PORT
```
22
```

### 4. SSH_KEY
```
-----BEGIN OPENSSH PRIVATE KEY-----
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
-----END OPENSSH PRIVATE KEY-----
```

## 🔧 Cara Kerja Pipeline

1. **Trigger**: Setiap push ke branch `master`
2. **Build**: Install dependencies PHP & Node.js, build assets
3. **Deploy**: SSH ke server, pull changes, update dependencies, clear cache
4. **Test**: Verify deployment dengan HTTP check
5. **Backup**: Otomatis backup sebelum deploy, rollback jika gagal

## 📁 File yang Dibuat

- `.github/workflows/deploy.yml` - GitHub Actions workflow
- `CI_CD_SETUP.md` - Dokumentasi setup (file ini)

## ✅ Langkah Selanjutnya

1. **Setup GitHub Secrets** (wajib):
   - Buka repository GitHub
   - Settings → Secrets and variables → Actions
   - Tambahkan 4 secrets di atas

2. **Test Pipeline**:
   - Commit dan push file ini ke master
   - Lihat di tab "Actions" di GitHub
   - Pipeline akan otomatis berjalan

## 🔍 Monitoring

- **GitHub Actions**: Tab "Actions" di repository
- **Server Logs**: `/var/log/nginx/error.log` dan `storage/logs/laravel.log`
- **Backups**: `/root/backups/` (otomatis keep 5 backup terakhir)

## 🚨 Troubleshooting

Jika deployment gagal:
1. Cek logs di GitHub Actions
2. SSH manual ke server untuk debug
3. Backup otomatis akan restore jika HTTP check gagal
4. Manual rollback: `mv /root/backups/caturnawa-YYYYMMDD-HHMMSS /root/work/projek-unasfest/caturnawa-uf-2025`

## 🎯 Fitur Pipeline

- ✅ Automated testing sebelum deploy
- ✅ Automatic backup & rollback
- ✅ Cache optimization
- ✅ Permission fixing
- ✅ Asset building
- ✅ Zero-downtime deployment
- ✅ Cleanup old backups

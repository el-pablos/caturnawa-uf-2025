# 🏆 UNAS Fest 2025 - Competition Status Management Guide

## 📋 Overview

This guide explains how competition status works across different parts of the system and ensures consistency between public pages, participant dashboard, and admin panel.

## 🔍 Competition Status Logic

### Database Fields
- `is_active` (boolean): Whether the competition is active/enabled
- `status` (string): Current status ('active', 'inactive', 'draft', 'completed')
- `registration_start` (datetime): When registration opens
- `registration_end` (datetime): When registration closes
- `competition_start` (datetime): When the competition begins
- `competition_end` (datetime): When the competition ends

### Status Determination Logic

#### 1. **Public Page** (`/competitions`)
- **Shows**: All competitions where `is_active = true`
- **Logic**: `Competition::where('is_active', true)`
- **Purpose**: Display all available competitions for general information

#### 2. **Participant Dashboard** (`/peserta/dashboard`)
- **Shows**: Only competitions with open registration
- **Logic**: `Competition::active()->openRegistration()`
- **Purpose**: Show only competitions participants can actually register for

#### 3. **Admin Dashboard** (`/admin/competitions`)
- **Shows**: All competitions regardless of status
- **Logic**: `Competition::all()` or filtered by admin preferences
- **Purpose**: Full management access for administrators

## 🎯 Scope Methods

### `scopeActive($query)`
```php
return $query->where('is_active', true)->where('status', 'active');
```

### `scopeOpenRegistration($query)`
```php
return $query->where('registration_start', '<=', now())
             ->where('registration_end', '>=', now());
```

### `isRegistrationOpen()`
```php
public function isRegistrationOpen(): bool
{
    return $this->is_active && 
           now()->between($this->registration_start, $this->registration_end);
}
```

## 📅 Current Competition Schedule (2025)

| Competition | Registration Period | Competition Period | Status |
|-------------|--------------------|--------------------|---------|
| **DCC** | Jul 1 - Aug 31 | Sep 15 - Sep 17 | 🟢 Open |
| **EDC** | Jul 15 - Aug 31 | Sep 20 - Sep 22 | 🟢 Open |
| **KDBI** | Aug 1 - Sep 15 | Oct 5 - Oct 7 | 🟡 Not Started |
| **SPC** | Jul 1 - Sep 30 | Oct 15 - Oct 17 | 🟢 Open |
| **Infografis** | Jul 15 - Sep 15 | Oct 1 - Oct 3 | 🟢 Open |

## 🔧 Troubleshooting Common Issues

### Issue 1: Competition visible in public but not in participant dashboard
**Cause**: Registration period has ended or not started yet
**Solution**: Check registration dates with `php artisan competition:status`

### Issue 2: Competition shows as active in admin but inactive in public
**Cause**: `is_active = false` or `status != 'active'`
**Solution**: Update competition status in admin panel

### Issue 3: Inconsistent status across different pages
**Cause**: Database inconsistency or caching issues
**Solution**: 
1. Run `php artisan competition:status --detailed`
2. Clear cache: `php artisan cache:clear`
3. Fresh seed if needed: `php artisan migrate:fresh --seed`

## 🛠️ Maintenance Commands

### Check Competition Status
```bash
# Basic status check
php artisan competition:status

# Detailed information
php artisan competition:status --detailed
```

### Fresh Database Seeding
```bash
# Reset database with current dates
php artisan migrate:fresh --seed
```

### Clear Application Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 🔒 Status Validation

The system includes automatic validation middleware (`CompetitionStatusValidator`) that checks for:

- ✅ Consistent `is_active` and `status` fields
- ✅ Valid date ranges (start < end)
- ✅ Logical date sequences
- ✅ Proper team configuration
- ✅ Correct pricing structure

## 📊 Visibility Matrix

| View | Active | Registration Open | Registration Closed | Registration Not Started |
|------|--------|------------------|-------------------|------------------------|
| **Public** | ✅ Visible | ✅ Visible | ✅ Visible | ✅ Visible |
| **Participant** | ✅ Visible | ❌ Hidden | ❌ Hidden | ❌ Hidden |
| **Admin** | ✅ Visible | ✅ Visible | ✅ Visible | ✅ Visible |

## 🚨 Important Notes

1. **Date Format**: All dates are stored in UTC and converted to local timezone for display
2. **Caching**: Competition status is cached for performance - clear cache after updates
3. **Validation**: Always run status validation after making changes
4. **Testing**: Use test accounts (peserta@test.com / admin@test.com) with password: password123

## 🔄 Update Process

When updating competition dates:

1. **Update Seeder**: Modify `database/seeders/CompetitionDetailSeeder.php`
2. **Fresh Seed**: Run `php artisan migrate:fresh --seed`
3. **Validate**: Run `php artisan competition:status --detailed`
4. **Test**: Check all three views (public, participant, admin)
5. **Clear Cache**: Run `php artisan cache:clear`

## 📞 Support

If you encounter issues:
1. Check this guide first
2. Run diagnostic commands
3. Check application logs: `storage/logs/laravel.log`
4. Validate database consistency

---

**Last Updated**: July 24, 2025
**System Status**: ✅ All competitions synchronized and working correctly

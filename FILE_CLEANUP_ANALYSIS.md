# File Cleanup Analysis - PC Shop Project

## 📊 Current Files in `/public/` folder:

### ✅ **ESSENTIAL FILES (Keep for both Local & AWS):**
- `index.php` - Laravel entry point
- `.htaccess` - Apache configuration
- `robots.txt` - SEO configuration
- `favicon.ico` - Website icon
- `build/` - Compiled assets (CSS/JS)
- `css/` - Stylesheets
- `js/` - JavaScript files
- `images/` - Static images
- `img/` - Dynamic images (product photos)
- `storage/` - Laravel storage link

### 🗑️ **SAFE TO DELETE (Debug/Import scripts - no longer needed):**
- `aws-import.php` - Original import script (buggy)
- `aws-import-fixed.php` - Fixed version (used once)
- `aws-import-safe.php` - Safe import attempt (used once)
- `aws-import-secure.php` - Secure version (used once)
- `aws-import-json-fixed.php` - JSON fix version (used once)
- `fixed-restore.php` - Restore attempt (used once)
- `simple-restore.php` - Final restore script (already used)
- `database-restore.php` - Another restore script (used once)

### 🔄 **BACKUP FILES (Keep temporarily, can archive later):**
- `database_export_2025_10_26_16_47_43.sql` - Original backup (IMPORTANT)
- `pre_restore_backup_2025_10_27_10_09_11.sql` - Pre-restore backup

### 🛠️ **UTILITY FILES (Keep for local development):**
- `database-debug.php` - Useful for debugging database issues locally
- `complete-export.php` - Export tool for future backups
- `smart-export.php` - Advanced export tool
- `export-database.php` - Basic export tool

### ☁️ **AWS-SPECIFIC (Keep for AWS deployment only):**
- `aws-debug.php` - AWS environment debugging
- `aws-debug-simple.php` - Simplified AWS debug
- `aws-complete-import.php` - Future AWS imports

## 📋 **Recommended Actions:**

### 1. **Delete immediately (completed their purpose):**
```
aws-import.php
aws-import-fixed.php
aws-import-safe.php
aws-import-secure.php
aws-import-json-fixed.php
fixed-restore.php
simple-restore.php
database-restore.php
```

### 2. **Archive backup files (move to `/storage/backups/`):**
```
database_export_2025_10_26_16_47_43.sql
pre_restore_backup_2025_10_27_10_09_11.sql
```

### 3. **Keep for local development:**
```
database-debug.php
complete-export.php
smart-export.php
export-database.php
```

### 4. **AWS deployment structure:**
```
Essential files + aws-debug.php + aws-complete-import.php
```

## 🎯 **Benefits of cleanup:**
- ✅ Cleaner codebase
- ✅ Faster uploads to AWS
- ✅ Reduced security surface area
- ✅ Better organization
- ✅ Easier maintenance

## ⚠️ **Important Notes:**
- Keep `database_export_2025_10_26_16_47_43.sql` as master backup
- Export tools remain available for future database operations
- AWS debug tools kept for production troubleshooting
- Can always recreate import scripts if needed
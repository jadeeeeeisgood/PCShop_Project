# 🔧 PC Shop Production Troubleshooting Guide

## Vấn đề: Domain chỉ hiển thị phần header, không load nội dung chính

### Nguyên nhân có thể:

1. **Mixed Content (HTTP/HTTPS)**
   - Assets được load qua HTTP trên trang HTTPS
   - Browser block các resource không secure

2. **Database Connection**
   - Không kết nối được database
   - Credentials không đúng

3. **Environment Configuration**
   - APP_URL không match với domain thực tế
   - DEBUG mode không phù hợp

4. **Asset Building**
   - CSS/JS chưa được build cho production
   - Vite manifest missing

## Các bước khắc phục:

### Bước 1: Kiểm tra cơ bản
```bash
# Truy cập debug page
https://www.pcshopvn.id.vn/debug.php

# Kiểm tra file log
tail -f storage/logs/laravel.log
```

### Bước 2: Cập nhật environment
```bash
# Copy production config
cp .env.production .env

# Update APP_URL
APP_URL=https://www.pcshopvn.id.vn

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

### Bước 3: Fix HTTPS/Assets
```bash
# Force HTTPS in Laravel
php artisan config:cache

# Rebuild assets with correct base URL
npm run build
```

### Bước 4: Database Connection
```bash
# Test database connection
php artisan tinker
> DB::connection()->getPdo();

# Run migrations if needed
php artisan migrate --force
```

### Bước 5: Permissions
```bash
# Fix permissions (Linux/Mac)
chmod -R 755 storage bootstrap/cache

# Windows (Run as Administrator)
icacls storage /grant Everyone:F /T
icacls bootstrap\cache /grant Everyone:F /T
```

## Debug Commands:

### Laravel Artisan
```bash
# Check application status
php artisan --version
php artisan about

# Clear all cache
php artisan optimize:clear

# Recreate optimized files
php artisan optimize
```

### Check Services
```bash
# Test route
php artisan route:list | grep home

# Test database
php artisan migrate:status

# Test storage
php artisan storage:link
```

## Common Issues & Solutions:

### 1. "Mixed Content" Error
**Problem:** Browser blocks HTTP assets on HTTPS page
**Solution:**
```php
// In AppServiceProvider.php
if (app()->environment('production')) {
    URL::forceScheme('https');
}
```

### 2. CSS/JS Not Loading
**Problem:** Vite assets not built or wrong paths
**Solution:**
```bash
npm ci
npm run build
php artisan config:cache
```

### 3. Database Connection Failed
**Problem:** Wrong credentials or host
**Solution:**
```bash
# Check .env file
DB_HOST=your-db-host
DB_DATABASE=pcshop_production
DB_USERNAME=your-username
DB_PASSWORD=your-password

# Test connection
php artisan tinker
> DB::select('SELECT 1');
```

### 4. Session/Cookie Issues
**Problem:** Session not working across HTTP/HTTPS
**Solution:**
```bash
# In .env
SESSION_SECURE_COOKIE=true
SESSION_DOMAIN=.pcshopvn.id.vn
```

### 5. Storage Symlink Missing
**Problem:** Images not showing
**Solution:**
```bash
php artisan storage:link
```

## Production Checklist:

### Before Deployment:
- [ ] Update .env with production values
- [ ] Set APP_DEBUG=false
- [ ] Set APP_ENV=production
- [ ] Configure database credentials
- [ ] Set correct APP_URL
- [ ] Build frontend assets (npm run build)

### After Deployment:
- [ ] Run php artisan config:cache
- [ ] Run php artisan route:cache
- [ ] Run php artisan view:cache
- [ ] Test database connection
- [ ] Verify file permissions
- [ ] Check SSL certificate
- [ ] Test all major pages

### Security:
- [ ] Remove debug.php file
- [ ] Disable directory listing
- [ ] Set proper file permissions
- [ ] Enable security headers
- [ ] Configure firewall

## Emergency Fixes:

### Quick Production Setup:
```bash
# Run deployment script
chmod +x deploy-production.sh
./deploy-production.sh

# OR Windows
PowerShell -ExecutionPolicy Bypass -File deploy-production.ps1
```

### Manual Recovery:
```bash
# 1. Copy environment
cp .env.production .env

# 2. Install dependencies
composer install --no-dev --optimize-autoloader

# 3. Clear everything
php artisan optimize:clear

# 4. Build assets
npm ci --production
npm run build

# 5. Optimize for production
php artisan optimize
```

## Monitoring:

### Health Check URLs:
- `https://www.pcshopvn.id.vn/` - Main site
- `https://www.pcshopvn.id.vn/debug.php` - Debug info (remove in production)
- `https://pcshop-final.eba-gm3xqw32.us-east-1.elasticbeanstalk.com/` - Backup/staging

### Log Files:
- `storage/logs/laravel.log` - Application logs
- Web server error logs (Apache/Nginx)
- PHP error logs

## Contact:

If issues persist:
1. Check error logs first
2. Use debug.php for diagnostics
3. Compare working staging vs broken production
4. Document exact error messages
5. Check browser developer console for JavaScript errors

---

**Remember:** Always backup before making changes and test on staging first!
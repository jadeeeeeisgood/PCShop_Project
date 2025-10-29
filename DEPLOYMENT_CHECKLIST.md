# 📋 PC Shop Production Deployment Checklist

## ⚠️ TRƯỚC KHI NÉN PROJECT:

### 1. Environment Configuration
- [ ] Copy `.env.production` thành `.env`
- [ ] Verify `APP_URL=https://www.pcshopvn.id.vn`
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Update database credentials cho production

### 2. Dependencies & Build
```bash
# Install production dependencies
composer install --no-dev --optimize-autoloader

# Build frontend assets
npm ci --production
npm run build

# Clear development cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 3. Files to Include in ZIP
✅ **INCLUDE:**
- app/
- bootstrap/
- config/
- database/
- public/ (including new .htaccess)
- resources/
- routes/
- storage/
- vendor/
- .env (production version)
- artisan
- composer.json
- composer.lock
- package.json
- vite.config.js

❌ **EXCLUDE:**
- .env.production (backup file)
- node_modules/
- .git/
- tests/
- *.md files (except if needed)
- storage/logs/* (old logs)
- bootstrap/cache/* (will be regenerated)

## 🚀 DEPLOYMENT STEPS:

### AWS Elastic Beanstalk Deploy:
1. Nén project (không bao gồm node_modules, .git)
2. Upload lên Elastic Beanstalk
3. Deploy version mới
4. Wait for deployment completion

### 🔄 SAU KHI DEPLOY:

### 1. Run Artisan Commands
```bash
# SSH vào server hoặc qua EB console:
cd /var/app/current

# Cache configuration for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage symlink
php artisan storage:link

# Run migrations if needed
php artisan migrate --force
```

### 2. Verify Deployment
- [ ] Check https://www.pcshopvn.id.vn/debug.php
- [ ] Verify HTTPS redirect working
- [ ] Test main pages load correctly
- [ ] Check browser console for errors
- [ ] Verify assets (CSS/JS) load over HTTPS

### 3. Security (After Confirmation)
- [ ] Remove debug.php file
- [ ] Verify no sensitive files exposed
- [ ] Check .htaccess security headers working

## 🐛 TROUBLESHOOTING:

### If site still shows blank/partial content:
1. Check browser console (F12) for errors
2. Look at EB logs in AWS console
3. SSH to server and check Laravel logs:
   ```bash
   tail -f /var/app/current/storage/logs/laravel.log
   ```

### Common Issues:
- **Mixed Content:** Browser blocks HTTP assets on HTTPS
- **Database:** Connection credentials wrong
- **Permissions:** storage/ and bootstrap/cache/ need write access
- **Assets:** CSS/JS not built or wrong paths

## 📞 VERIFICATION COMMANDS:

```bash
# After deployment, test these:
curl -I https://www.pcshopvn.id.vn/
php artisan --version
php artisan config:show app.url
ls -la storage/
```

## 🎯 SUCCESS INDICATORS:

✅ **You'll know it's working when:**
- https://www.pcshopvn.id.vn shows full homepage
- No mixed content warnings in browser
- All images and assets load
- HTTPS redirect from HTTP works
- No JavaScript errors in console

---

**💡 Pro Tip:** Keep the working Elastic Beanstalk URL as backup while testing the domain!
#!/bin/bash

# AWS Production Deployment Script - Fixed Nginx Configuration
# Fixes the document root issue that was causing 100% 5xx errors

echo "==================================="
echo "AWS DEPLOYMENT - NGINX FIXED"
echo "==================================="
echo "Timestamp: $(date)"

# Step 1: Backup current .env
echo "[1/6] Backing up current .env..."
if [ -f .env ]; then
    cp .env .env.local.backup
    echo "✓ Local .env backed up to .env.local.backup"
else
    echo "⚠ No .env file found to backup"
fi

# Step 2: Switch to production environment
echo "[2/6] Switching to production environment..."
if [ -f .env.production ]; then
    cp .env.production .env
    echo "✓ Switched to production environment"
    echo "  - Database: AWS RDS (ebdb)"
    echo "  - APP_URL: https://www.pcshopvn.id.vn"
    echo "  - APP_ENV: production"
else
    echo "❌ .env.production not found!"
    exit 1
fi

# Step 3: Clear all caches
echo "[3/6] Clearing Laravel caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
echo "✓ All caches cleared"

# Step 4: Optimize for production
echo "[4/6] Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "✓ Production optimizations applied"

# Step 5: Verify critical files
echo "[5/6] Verifying deployment package..."

# Check nginx configuration
if [ -f .platform/nginx/conf.d/laravel.conf ]; then
    echo "✓ Laravel nginx config found"
    grep -q "/var/app/current/public" .platform/nginx/conf.d/laravel.conf && echo "✓ Document root correctly set to /var/app/current/public"
else
    echo "❌ Laravel nginx config missing!"
    exit 1
fi

if [ -f .platform/nginx/conf.d/https.conf ]; then
    echo "✓ HTTPS nginx config found"
    grep -q "/var/app/current/public" .platform/nginx/conf.d/https.conf && echo "✓ HTTPS document root correctly set"
else
    echo "❌ HTTPS nginx config missing!"
    exit 1
fi

# Check other critical files
[ -f composer.json ] && echo "✓ composer.json found"
[ -f .env.production ] && echo "✓ .env.production found"
[ -d vendor/ ] && echo "✓ vendor/ directory found"
[ -d .platform/ ] && echo "✓ .platform/ directory found"

# Step 6: Create deployment package
echo "[6/6] Creating deployment package..."
TIMESTAMP=$(date +%Y%m%d-%H%M)
ZIP_NAME="aws-deployment-nginx-fixed-${TIMESTAMP}.zip"

# Include all necessary files for deployment
zip -r "${ZIP_NAME}" \
    app/ \
    bootstrap/ \
    config/ \
    database/ \
    public/ \
    resources/ \
    routes/ \
    storage/ \
    vendor/ \
    .platform/ \
    artisan \
    composer.json \
    composer.lock \
    .env.production \
    -x "*.log" "storage/logs/*" "node_modules/*" ".git/*" "tests/*"

if [ -f "${ZIP_NAME}" ]; then
    SIZE=$(du -h "${ZIP_NAME}" | cut -f1)
    echo "✓ Deployment package created: ${ZIP_NAME} (${SIZE})"
    echo ""
    echo "==================================="
    echo "NGINX CONFIGURATION FIXES APPLIED:"
    echo "==================================="
    echo "• Document root: /var/app/current/public (was /var/www/html/)"
    echo "• Laravel routing: try_files → index.php"
    echo "• FastCGI params: SCRIPT_FILENAME fixed"
    echo "• Security: deny .env, vendor access"
    echo "• Health check: /health endpoint"
    echo "• HTTPS: proper SSL handling"
    echo ""
    echo "NEXT STEPS:"
    echo "1. Upload ${ZIP_NAME} to AWS Elastic Beanstalk"
    echo "2. Deploy to pcshop-final environment"
    echo "3. Monitor deployment logs for success"
    echo "4. Test https://www.pcshopvn.id.vn"
    echo ""
    echo "This should fix the 403 Forbidden errors!"
    echo "==================================="
else
    echo "❌ Failed to create deployment package"
    exit 1
fi
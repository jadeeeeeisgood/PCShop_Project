# 🚨 EMERGENCY ROLLBACK DEPLOYMENT
Write-Host "🚨 Emergency Rollback Deployment - Fixing Severe Health Status..." -ForegroundColor Red

# Use AWS-compatible environment
if (Test-Path ".env.aws") {
    Write-Host "📋 Using AWS-compatible environment..." -ForegroundColor Yellow
    Copy-Item ".env.aws" ".env" -Force
} else {
    Write-Host "❌ .env.aws not found, using basic production config" -ForegroundColor Red
}

# Quick build without HTTPS forcing
Write-Host "🔧 Building safe version..." -ForegroundColor Blue
composer install --optimize-autoloader --no-dev --no-interaction
npm ci --production
npm run build

# Clear everything
Write-Host "🧹 Clearing caches..." -ForegroundColor Blue
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Test basic functionality
Write-Host "🧪 Testing..." -ForegroundColor Blue
php artisan --version

Write-Host "✅ Emergency build complete!" -ForegroundColor Green
Write-Host "📦 Ready to zip and deploy to AWS" -ForegroundColor Yellow
Write-Host "⚠️  Remember to exclude:" -ForegroundColor Red
Write-Host "   - node_modules/" 
Write-Host "   - .git/"
Write-Host "   - storage/logs/*"
Write-Host "   - bootstrap/cache/*"
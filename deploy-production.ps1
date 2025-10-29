# PC Shop Production Deployment Script for Windows
Write-Host "🚀 Starting PC Shop Production Deployment..." -ForegroundColor Cyan

# Check if we're in the right directory
if (-Not (Test-Path "artisan")) {
    Write-Host "❌ Error: Please run this script from the Laravel project root directory" -ForegroundColor Red
    exit 1
}

Write-Host "📁 Current directory: $(Get-Location)" -ForegroundColor Blue

# Backup current .env if exists
if (Test-Path ".env") {
    Write-Host "📋 Backing up current .env file..." -ForegroundColor Yellow
    $timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
    Copy-Item ".env" ".env.backup.$timestamp"
}

# Copy production environment
if (Test-Path ".env.production") {
    Write-Host "🔧 Copying production environment configuration..." -ForegroundColor Blue
    Copy-Item ".env.production" ".env" -Force
} else {
    Write-Host "❌ Error: .env.production file not found" -ForegroundColor Red
    exit 1
}

# Install dependencies
Write-Host "📦 Installing PHP dependencies..." -ForegroundColor Blue
composer install --optimize-autoloader --no-dev --no-interaction

# Install Node dependencies and build assets
Write-Host "🎨 Building frontend assets..." -ForegroundColor Blue
npm ci --production
npm run build

# Clear and optimize Laravel
Write-Host "🧹 Clearing and optimizing Laravel..." -ForegroundColor Blue
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Generate optimized files for production
Write-Host "⚡ Generating optimized configuration..." -ForegroundColor Blue
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
Write-Host "🔗 Creating storage symlink..." -ForegroundColor Blue
php artisan storage:link

# Test basic functionality
Write-Host "🧪 Testing basic functionality..." -ForegroundColor Blue
php artisan --version

Write-Host "✅ Production deployment completed successfully!" -ForegroundColor Green
Write-Host "📝 Next steps:" -ForegroundColor Yellow
Write-Host "   1. Update your domain DNS to point to this server"
Write-Host "   2. Configure SSL certificate (Let's Encrypt recommended)"
Write-Host "   3. Update database configuration in .env"
Write-Host "   4. Test the website: https://www.pcshopvn.id.vn"
Write-Host "   5. Set up task scheduler for Laravel scheduling"

Write-Host "🌟 Happy deploying!" -ForegroundColor Blue
# Fix HTTPS Mixed Content Issues
Write-Host "🔧 Fixing HTTPS Mixed Content Issues..." -ForegroundColor Green

Write-Host "`n📋 Changes being made:" -ForegroundColor Cyan
Write-Host "  ✅ Created ForceHttpsAssets middleware" -ForegroundColor Green
Write-Host "  ✅ Force HTTPS for CSS/JS/Images only" -ForegroundColor Green  
Write-Host "  ✅ No HTTP redirects (prevents infinite loops)" -ForegroundColor Green
Write-Host "  ✅ Health checks bypass" -ForegroundColor Green

Write-Host "`n🧪 Testing locally first..." -ForegroundColor Yellow
try {
    php artisan --version
    Write-Host "✅ Laravel working" -ForegroundColor Green
} catch {
    Write-Host "❌ Laravel error: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "`n📦 Committing changes..." -ForegroundColor Cyan
git add .
git commit -m "Fix HTTPS mixed content - assets middleware only"

Write-Host "`n🚀 Deploying to AWS..." -ForegroundColor Green
git push

Write-Host "`n✅ Deploy completed!" -ForegroundColor Green
Write-Host "`n🔍 Test results expected:" -ForegroundColor Cyan
Write-Host "  📱 HTTP URL: Full working website" -ForegroundColor White
Write-Host "     http://pcshopvn-env.eba-wupxhaqp.ap-southeast-1.elasticbeanstalk.com" -ForegroundColor Gray
Write-Host "  🔒 HTTPS URL: Fixed layout with proper CSS/JS" -ForegroundColor White  
Write-Host "     https://www.pcshopvn.id.vn" -ForegroundColor Gray

Write-Host "`n⏰ Wait 2-3 minutes then test both URLs" -ForegroundColor Yellow
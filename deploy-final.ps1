# Deploy to pcshop-final environment (US East)
Write-Host "🚀 Deploy to pcshop-final.eba-gm3xqw32.us-east-1" -ForegroundColor Green

Write-Host "`n📋 Environment Info:" -ForegroundColor Cyan
Write-Host "  🌐 Primary URL: http://pcshop-final.eba-gm3xqw32.us-east-1.elasticbeanstalk.com/" -ForegroundColor White
Write-Host "  📍 Region: us-east-1 (Virginia)" -ForegroundColor White
Write-Host "  🏷️  Environment: pcshop-final" -ForegroundColor White

Write-Host "`n🔧 Checking EB CLI configuration..." -ForegroundColor Yellow
if (Test-Path ".elasticbeanstalk/config.yml") {
    Write-Host "  ✅ EB config found" -ForegroundColor Green
    Get-Content ".elasticbeanstalk/config.yml" | Select-String "environment_name|region"
} else {
    Write-Host "  ⚠️  No EB config found - need to initialize" -ForegroundColor Yellow
    Write-Host "  💡 Run: eb init" -ForegroundColor Cyan
}

Write-Host "`n📦 Preparing deployment..." -ForegroundColor Cyan
git add .
git commit -m "Update to pcshop-final environment (us-east-1)"

Write-Host "`n🚀 Deploying via Git..." -ForegroundColor Green
git push

Write-Host "`n✅ Git deployment completed!" -ForegroundColor Green

Write-Host "`n📝 Next Steps:" -ForegroundColor Cyan
Write-Host "  1. 📤 Upload/Deploy to AWS Elastic Beanstalk manually" -ForegroundColor White
Write-Host "  2. 🕐 Wait 2-3 minutes for deployment" -ForegroundColor White
Write-Host "  3. 🧪 Test: http://pcshop-final.eba-gm3xqw32.us-east-1.elasticbeanstalk.com/" -ForegroundColor White
Write-Host "  4. ✅ Verify website loads completely" -ForegroundColor White

Write-Host "`n🔗 AWS Console Links:" -ForegroundColor Cyan
Write-Host "  📊 Environment: https://us-east-1.console.aws.amazon.com/elasticbeanstalk/" -ForegroundColor Gray
Write-Host "  📈 Logs: Check Environment Health and Recent Events" -ForegroundColor Gray

Write-Host "`n💡 Deployment Methods:" -ForegroundColor Yellow
Write-Host "  Method 1: ZIP upload via AWS Console" -ForegroundColor White
Write-Host "  Method 2: EB CLI - eb deploy" -ForegroundColor White
Write-Host "  Method 3: GitHub integration (if configured)" -ForegroundColor White
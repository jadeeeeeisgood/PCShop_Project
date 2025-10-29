# Deploy PC Shop to AWS Elastic Beanstalk
Write-Host "🚀 Bắt đầu deploy PC Shop lên AWS Elastic Beanstalk..." -ForegroundColor Green

# Kiểm tra EB CLI
Write-Host "🔍 Kiểm tra EB CLI..." -ForegroundColor Yellow
try {
    eb --version
    Write-Host "✅ EB CLI đã sẵn sàng" -ForegroundColor Green
} catch {
    Write-Host "❌ EB CLI chưa được cài đặt. Vui lòng cài đặt AWS EB CLI trước." -ForegroundColor Red
    exit 1
}

# Kiểm tra git status
Write-Host "📋 Kiểm tra git status..." -ForegroundColor Yellow
git status --porcelain
if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Git repository sạch sẽ" -ForegroundColor Green
} else {
    Write-Host "⚠️ Có uncommitted changes" -ForegroundColor Yellow
}

# Deploy với EB CLI
Write-Host "🌐 Deploying với EB CLI..." -ForegroundColor Cyan
eb deploy

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Deploy thành công!" -ForegroundColor Green
    Write-Host "🔗 Các link để kiểm tra:" -ForegroundColor Cyan
    Write-Host "   📱 AWS Console: https://ap-southeast-1.console.aws.amazon.com/elasticbeanstalk/" -ForegroundColor White
    Write-Host "   🌐 Elastic Beanstalk URL: http://pcshopvn-env.eba-wupxhaqp.ap-southeast-1.elasticbeanstalk.com" -ForegroundColor White
    Write-Host "   🔒 Custom Domain: https://www.pcshopvn.id.vn" -ForegroundColor White
} else {
    Write-Host "❌ Deploy thất bại!" -ForegroundColor Red
    Write-Host "🔧 Chạy emergency-deploy.ps1 nếu cần rollback" -ForegroundColor Yellow
}

Write-Host "`n📝 Ghi chú:" -ForegroundColor Yellow
Write-Host "   - Đợi 2-3 phút để AWS deploy hoàn tất" -ForegroundColor White
Write-Host "   - Kiểm tra Environment Health trong AWS Console" -ForegroundColor White
Write-Host "   - Test cả 2 domains để đảm bảo HTTPS hoạt động" -ForegroundColor White
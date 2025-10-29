# Deploy PC Shop với HTTPS enabled (Safe version)
Write-Host "🚀 Bắt đầu deploy PC Shop với HTTPS được bật..." -ForegroundColor Green

# Kiểm tra git status
Write-Host "📋 Kiểm tra git status..." -ForegroundColor Yellow
git status

# Add tất cả changes
Write-Host "📦 Adding changes to git..." -ForegroundColor Yellow
git add .

# Commit với message
$commitMessage = "Enable safe HTTPS middleware for production - $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')"
Write-Host "💾 Committing: $commitMessage" -ForegroundColor Yellow
git commit -m $commitMessage

# Deploy lên AWS
Write-Host "🌐 Deploying to AWS Elastic Beanstalk..." -ForegroundColor Green
git push

Write-Host "✅ Deploy hoàn thành!" -ForegroundColor Green
Write-Host "🔍 Hãy kiểm tra:" -ForegroundColor Cyan
Write-Host "   - AWS Environment Health status" -ForegroundColor White
Write-Host "   - Website hoạt động trên: http://pcshopvn-env.eba-wupxhaqp.ap-southeast-1.elasticbeanstalk.com" -ForegroundColor White
Write-Host "   - Custom domain: https://www.pcshopvn.id.vn" -ForegroundColor White

Write-Host "⚠️  Nếu có vấn đề, chạy emergency-deploy.ps1 để rollback" -ForegroundColor Yellow
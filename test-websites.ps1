# Test PC Shop trên 2 domains
Write-Host "🧪 Bắt đầu test PC Shop trên 2 domains..." -ForegroundColor Green

$elasticBeanstalkUrl = "http://pcshopvn-env.eba-wupxhaqp.ap-southeast-1.elasticbeanstalk.com"
$customDomain = "https://www.pcshopvn.id.vn"

Write-Host "`n🔍 Test 1: Elastic Beanstalk URL" -ForegroundColor Cyan
Write-Host "URL: $elasticBeanstalkUrl" -ForegroundColor White

try {
    $response1 = Invoke-WebRequest -Uri $elasticBeanstalkUrl -Method GET -TimeoutSec 30
    Write-Host "✅ Status Code: $($response1.StatusCode)" -ForegroundColor Green
    Write-Host "✅ Response Length: $($response1.Content.Length) bytes" -ForegroundColor Green
    
    # Kiểm tra có chứa nội dung chính không
    if ($response1.Content -match "PC Shop|Máy tính|Laptop") {
        Write-Host "✅ Website content loaded successfully" -ForegroundColor Green
    } else {
        Write-Host "⚠️ Website content might have issues" -ForegroundColor Yellow
    }
} catch {
    Write-Host "❌ Error accessing Elastic Beanstalk URL: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "`n🔍 Test 2: Custom Domain" -ForegroundColor Cyan
Write-Host "URL: $customDomain" -ForegroundColor White

try {
    $response2 = Invoke-WebRequest -Uri $customDomain -Method GET -TimeoutSec 30
    Write-Host "✅ Status Code: $($response2.StatusCode)" -ForegroundColor Green
    Write-Host "✅ Response Length: $($response2.Content.Length) bytes" -ForegroundColor Green
    Write-Host "✅ HTTPS working properly" -ForegroundColor Green
    
    # Kiểm tra có chứa nội dung chính không
    if ($response2.Content -match "PC Shop|Máy tính|Laptop") {
        Write-Host "✅ Website content loaded successfully" -ForegroundColor Green
    } else {
        Write-Host "⚠️ Website content might have issues" -ForegroundColor Yellow
    }
} catch {
    Write-Host "❌ Error accessing Custom Domain: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "🔧 Possible issues:" -ForegroundColor Yellow
    Write-Host "   - SSL certificate not properly configured" -ForegroundColor White
    Write-Host "   - Domain DNS not pointing to correct Load Balancer" -ForegroundColor White
    Write-Host "   - HTTPS middleware causing redirect loops" -ForegroundColor White
}

Write-Host "`n🔧 Manual Testing Checklist:" -ForegroundColor Cyan
Write-Host "□ Open browser and visit: $elasticBeanstalkUrl" -ForegroundColor White
Write-Host "□ Check if homepage loads completely with products" -ForegroundColor White
Write-Host "□ Open browser and visit: $customDomain" -ForegroundColor White
Write-Host "□ Check if HTTPS lock icon appears in browser" -ForegroundColor White
Write-Host "□ Test navigation: Categories, Products, Login/Register" -ForegroundColor White
Write-Host "□ Check AWS Console for Environment Health status" -ForegroundColor White

Write-Host "`n📊 AWS Environment Health Check:" -ForegroundColor Cyan
Write-Host "🌐 AWS Console: https://ap-southeast-1.console.aws.amazon.com/elasticbeanstalk/home?region=ap-southeast-1#/environment/dashboard?applicationName=pcshopvn&environmentId=e-wupxhaqp" -ForegroundColor White

Write-Host "`n🎯 Success Criteria:" -ForegroundColor Green
Write-Host "✅ Both URLs should return 200 status" -ForegroundColor White
Write-Host "✅ Both should show complete website content" -ForegroundColor White
Write-Host "✅ Custom domain should have HTTPS working" -ForegroundColor White
Write-Host "✅ AWS Environment Health should be OK (Green)" -ForegroundColor White
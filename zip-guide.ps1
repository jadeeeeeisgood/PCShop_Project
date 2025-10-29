# Hướng dẫn nén PC Shop Project
Write-Host "📦 Hướng dẫn nén PC Shop Project" -ForegroundColor Green

Write-Host "`n📋 Chuẩn bị nén project:" -ForegroundColor Cyan

# Kiểm tra kích thước hiện tại
$currentSize = (Get-ChildItem . -Recurse | Measure-Object -Property Length -Sum).Sum
$sizeMB = [math]::Round($currentSize / 1MB, 2)
$sizeGB = [math]::Round($currentSize / 1GB, 2)

Write-Host "📊 Kích thước hiện tại: $sizeMB MB ($sizeGB GB)" -ForegroundColor White

Write-Host "`n🎯 Folders chính cần nén:" -ForegroundColor Yellow
$mainFolders = @("app", "bootstrap", "config", "database", "public", "resources", "routes", "storage")
foreach ($folder in $mainFolders) {
    if (Test-Path $folder) {
        $folderSize = (Get-ChildItem $folder -Recurse | Measure-Object -Property Length -Sum).Sum / 1MB
        Write-Host "  ✅ $folder/ ($([math]::Round($folderSize, 1)) MB)" -ForegroundColor Green
    }
}

Write-Host "`n📁 Folders có thể loại trừ khi nén:" -ForegroundColor Cyan
$excludeFolders = @("vendor", "node_modules", "storage/logs", "storage/framework/cache")
foreach ($folder in $excludeFolders) {
    if (Test-Path $folder) {
        $folderSize = (Get-ChildItem $folder -Recurse | Measure-Object -Property Length -Sum).Sum / 1MB
        Write-Host "  ⚪ $folder/ ($([math]::Round($folderSize, 1)) MB) - có thể loại trừ" -ForegroundColor Gray
    }
}

Write-Host "`n🗜️ Tùy chọn nén:" -ForegroundColor Cyan

Write-Host "`n1️⃣ NÉN TOÀN BỘ (bao gồm vendor/):" -ForegroundColor Yellow
Write-Host "   - Chọn toàn bộ folder pc-shop" -ForegroundColor White
Write-Host "   - Click chuột phải → Send to → Compressed folder" -ForegroundColor White
Write-Host "   - Hoặc dùng 7-Zip/WinRAR" -ForegroundColor White
Write-Host "   - Kích thước: ~$sizeMB MB" -ForegroundColor White

Write-Host "`n2️⃣ NÉN CHỈ SOURCE CODE (loại trừ vendor/):" -ForegroundColor Yellow
Write-Host "   - Chọn các folders: app, bootstrap, config, database, public, resources, routes" -ForegroundColor White
Write-Host "   - Chọn các files: .env.example, composer.json, package.json, README.md" -ForegroundColor White
Write-Host "   - Kích thước nhỏ hơn, cần chạy composer install sau khi giải nén" -ForegroundColor White

Write-Host "`n3️⃣ SỬ DỤNG POWERSHELL NÉN:" -ForegroundColor Yellow
Write-Host "   Compress-Archive -Path . -DestinationPath 'PCShop-Project.zip' -Force" -ForegroundColor Gray

Write-Host "`n📝 Checklist trước khi nén:" -ForegroundColor Cyan
Write-Host "  ✅ Đã cleanup files debug" -ForegroundColor Green
Write-Host "  ✅ Đã commit code mới nhất" -ForegroundColor Green  
Write-Host "  ✅ File .env.aws đã có (cho production)" -ForegroundColor Green
Write-Host "  ✅ emergency-deploy.ps1 đã có (cho rollback)" -ForegroundColor Green

Write-Host "`n🚀 Sau khi nén:" -ForegroundColor Cyan
Write-Host "  1. Upload ZIP lên AWS Elastic Beanstalk" -ForegroundColor White
Write-Host "  2. Hoặc deploy qua EB CLI: eb deploy" -ForegroundColor White
Write-Host "  3. Test 2 domains sau khi deploy" -ForegroundColor White

Write-Host "`n❓ Bạn muốn nén bằng PowerShell ngay bây giờ? (Y/N): " -ForegroundColor Yellow -NoNewline
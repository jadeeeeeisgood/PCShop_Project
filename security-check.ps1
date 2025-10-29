# Files KHÔNG nên nén khi deploy - Best Security Practices
Write-Host "🔒 Danh sách files KHÔNG nên nén (Best Practices)" -ForegroundColor Green

$excludeFiles = @(
    # Environment files - chứa thông tin nhạy cảm
    ".env",
    ".env.local", 
    ".env.production",
    
    # Development files
    ".env.example",  # Optional: có thể giữ hoặc loại trừ
    
    # IDE files
    ".vscode/",
    ".idea/", 
    
    # OS files
    "Thumbs.db",
    ".DS_Store",
    
    # Logs
    "storage/logs/*.log",
    
    # Cache files
    "storage/framework/cache/*",
    "storage/framework/sessions/*", 
    "storage/framework/views/*",
    
    # Node modules (sẽ được rebuild)
    "node_modules/",
    
    # Vendor (sẽ được composer install)
    "vendor/",
    
    # Git files (không cần thiết cho production)
    ".git/",
    ".gitignore"
)

Write-Host "`n📋 Files/Folders nên LOẠI TRỪ khi nén:" -ForegroundColor Yellow
foreach ($file in $excludeFiles) {
    if (Test-Path $file) {
        Write-Host "  ❌ $file - LOẠI TRỪ" -ForegroundColor Red
    } else {
        Write-Host "  ⚪ $file - không tồn tại" -ForegroundColor Gray
    }
}

Write-Host "`n✅ Files QUAN TRỌNG cần nén:" -ForegroundColor Green
$includeFiles = @(
    "app/",
    "bootstrap/", 
    "config/",
    "database/",
    "public/",
    "resources/",
    "routes/",
    "storage/app/",
    "composer.json",
    "composer.lock",
    "package.json",
    "artisan",
    ".ebextensions/",  # AWS config
    ".platform/",      # AWS platform config
    "emergency-deploy.ps1"  # Rollback script
)

foreach ($file in $includeFiles) {
    if (Test-Path $file) {
        Write-Host "  ✅ $file - CẦN NÉN" -ForegroundColor Green
    } else {
        Write-Host "  ⚠️  $file - không tìm thấy" -ForegroundColor Yellow
    }
}

Write-Host "`n🛡️  BẢO MẬT:" -ForegroundColor Cyan
Write-Host "  ✅ .env sẽ KHÔNG được nén - ĐÚNG!" -ForegroundColor Green
Write-Host "  ✅ AWS sử dụng Environment Variables riêng" -ForegroundColor Green  
Write-Host "  ✅ Thông tin nhạy cảm được bảo vệ" -ForegroundColor Green

Write-Host "`n⚙️  AWS SẼ TỰ ĐỘNG:" -ForegroundColor Cyan
Write-Host "  🔄 Chạy composer install (cài vendor/)" -ForegroundColor White
Write-Host "  🔄 Chạy npm install (cài node_modules/)" -ForegroundColor White
Write-Host "  🔄 Load Environment Variables từ AWS config" -ForegroundColor White
Write-Host "  🔄 Chạy artisan commands (migrate, cache, etc.)" -ForegroundColor White

Write-Host "`n📦 KẾT LUẬN:" -ForegroundColor Green
Write-Host "  👍 Không nén .env là HOÀN TOÀN ĐÚNG!" -ForegroundColor Green
Write-Host "  👍 Tăng bảo mật và tuân thủ best practices" -ForegroundColor Green
Write-Host "  👍 AWS có config riêng, không cần .env local" -ForegroundColor Green
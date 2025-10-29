# Cleanup nhẹ - chỉ xóa files debug và temp
Write-Host "🧹 Cleanup nhẹ - chỉ xóa files debug và temporary..." -ForegroundColor Green

# Chỉ xóa những file thực sự không cần thiết
$safeToDelete = @(
    # Debug files - an toàn xóa
    "public\debug.php",
    "public\database-debug.php", 
    "public\vnpay-localhost-debug.php",
    "public\aws-debug-simple.php",
    "check_product_status.php",
    
    # Temporary files
    "ac.zip",
    
    # Backup files
    "bootstrap\app_rollback.php",
    
    # Script files đã sử dụng xong
    "cleanup-project.ps1"
)

Write-Host "📋 Files sẽ xóa (an toàn):" -ForegroundColor Yellow
foreach ($file in $safeToDelete) {
    if (Test-Path $file) {
        Write-Host "  ❌ $file" -ForegroundColor Red
    }
}

Write-Host "`n🗑️ Bắt đầu xóa..." -ForegroundColor Cyan

foreach ($file in $safeToDelete) {
    if (Test-Path $file) {
        try {
            Remove-Item $file -Force
            Write-Host "✅ Deleted: $file" -ForegroundColor Green
        } catch {
            Write-Host "❌ Error deleting $file`: $($_.Exception.Message)" -ForegroundColor Red
        }
    }
}

Write-Host "`n✅ Cleanup nhẹ hoàn thành!" -ForegroundColor Green

# Hiển thị files còn lại có thể xóa (tùy chọn)
Write-Host "`n📝 Files khác có thể xóa (tùy chọn):" -ForegroundColor Cyan
$optionalDelete = @(
    "public\aws-debug.php",
    "public\vnpay-final-debug.php", 
    "deploy-production.ps1",
    "deploy-with-https.ps1",
    "reports\"
)

foreach ($file in $optionalDelete) {
    if (Test-Path $file) {
        Write-Host "  📁 $file" -ForegroundColor Yellow
    }
}

Write-Host "`n💡 Gợi ý: Có thể giữ lại reports/ nếu cần cho báo cáo" -ForegroundColor Cyan
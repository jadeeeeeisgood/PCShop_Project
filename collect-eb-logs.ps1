# Script thu thập logs từ Elastic Beanstalk
# Tạo bởi: GitHub Copilot
# Ngày: 30/10/2025

Write-Host "🔍 Thu thập logs từ Elastic Beanstalk..." -ForegroundColor Green

# Tạo thư mục logs nếu chưa có
$logsDir = "eb-diagnostics-$(Get-Date -Format 'yyyyMMdd-HHmm')"
New-Item -ItemType Directory -Path $logsDir -Force | Out-Null

Write-Host "📁 Tạo thư mục: $logsDir" -ForegroundColor Yellow

try {
    # Lấy danh sách environments
    Write-Host "1️⃣ Lấy danh sách environments..." -ForegroundColor Cyan
    eb list | Out-File -FilePath "$logsDir\eb-environments.txt" -Encoding UTF8
    
    # Hiển thị environments
    Write-Host "📋 Environments hiện có:" -ForegroundColor White
    Get-Content "$logsDir\eb-environments.txt" | Write-Host
    
    # Lấy full logs
    Write-Host "`n2️⃣ Lấy full logs (có thể mất vài phút)..." -ForegroundColor Cyan
    eb logs --all | Out-File -FilePath "$logsDir\eb-full-logs.txt" -Encoding UTF8
    
    # Lấy recent logs
    Write-Host "3️⃣ Lấy recent logs..." -ForegroundColor Cyan
    eb logs | Out-File -FilePath "$logsDir\eb-recent-logs.txt" -Encoding UTF8
    
    # Lấy health status
    Write-Host "4️⃣ Lấy health status..." -ForegroundColor Cyan
    eb health --refresh | Out-File -FilePath "$logsDir\eb-health.txt" -Encoding UTF8
    
    # Lấy environment info
    Write-Host "5️⃣ Lấy environment info..." -ForegroundColor Cyan
    eb status | Out-File -FilePath "$logsDir\eb-status.txt" -Encoding UTF8
    
    Write-Host "`n✅ Hoàn thành! Logs đã được lưu trong thư mục: $logsDir" -ForegroundColor Green
    
    # Hiển thị danh sách files
    Write-Host "`n📄 Files đã tạo:" -ForegroundColor White
    Get-ChildItem $logsDir | ForEach-Object {
        $size = [math]::Round($_.Length / 1KB, 2)
        Write-Host "  $($_.Name) ($size KB)" -ForegroundColor Gray
    }
    
    # Tìm và hiển thị lỗi quan trọng
    Write-Host "`n🔍 Tìm kiếm lỗi quan trọng..." -ForegroundColor Cyan
    
    $fullLogs = Get-Content "$logsDir\eb-full-logs.txt" -ErrorAction SilentlyContinue
    if ($fullLogs) {
        # Tìm các dòng có ERROR, FATAL, Exception
        $errors = $fullLogs | Select-String -Pattern "(ERROR|FATAL|Exception|Fatal error|Parse error|5xx)" -SimpleMatch
        
        if ($errors) {
            Write-Host "`n❌ Lỗi tìm thấy:" -ForegroundColor Red
            $errors | Select-Object -Last 10 | ForEach-Object {
                Write-Host "  $($_.Line)" -ForegroundColor Red
            }
        } else {
            Write-Host "✅ Không tìm thấy lỗi rõ ràng trong logs." -ForegroundColor Green
        }
    }
    
    # Hướng dẫn bước tiếp theo
    Write-Host "`n📋 Bước tiếp theo:" -ForegroundColor Yellow
    Write-Host "1. Mở file: $logsDir\eb-full-logs.txt" -ForegroundColor White
    Write-Host "2. Tìm kiếm: 'ERROR', 'FATAL', 'Exception', 'laravel.log'" -ForegroundColor White
    Write-Host "3. Copy đoạn lỗi và gửi cho assistant để phân tích" -ForegroundColor White
    
} catch {
    Write-Host "❌ Lỗi khi thu thập logs: $_" -ForegroundColor Red
    Write-Host "💡 Đảm bảo bạn đã:" -ForegroundColor Yellow
    Write-Host "   - Cài đặt EB CLI: pip install awsebcli" -ForegroundColor White
    Write-Host "   - Cấu hình AWS credentials: aws configure" -ForegroundColor White
    Write-Host "   - Khởi tạo EB trong project: eb init" -ForegroundColor White
}

Write-Host "`n🏁 Kết thúc script." -ForegroundColor Green
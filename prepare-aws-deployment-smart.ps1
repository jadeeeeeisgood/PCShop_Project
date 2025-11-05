# SMART AWS Deployment Script
# Tự động switch environment và deploy

Write-Host "======================================================"
Write-Host "🤖 SMART AWS Deployment (Auto Environment Switch)" -ForegroundColor Green
Write-Host "======================================================"
Write-Host ""

$deploymentFile = "aws-deployment-smart-$(Get-Date -Format 'yyyyMMdd-HHmm').zip"

Write-Host "📁 Creating SMART deployment package: $deploymentFile" -ForegroundColor Blue

# Backup current .env
Write-Host "💾 Backing up current .env..." -ForegroundColor Yellow
Copy-Item ".env" ".env.local.backup" -Force

# Switch to production environment
Write-Host "🔄 Switching to PRODUCTION environment..." -ForegroundColor Yellow
Copy-Item ".env.production" ".env" -Force

# Clear all caches
Write-Host "🧹 Clearing caches..." -ForegroundColor Yellow
php artisan config:clear | Out-Null
php artisan cache:clear | Out-Null
php artisan optimize:clear | Out-Null

# Clean storage
if (Test-Path "storage/logs") { Remove-Item "storage/logs/*" -Force -Recurse -ErrorAction SilentlyContinue }
if (Test-Path "storage/framework/cache/data") { Remove-Item "storage/framework/cache/data/*" -Force -Recurse -ErrorAction SilentlyContinue }
if (Test-Path "storage/framework/sessions") { Remove-Item "storage/framework/sessions/*" -Force -Recurse -ErrorAction SilentlyContinue }
if (Test-Path "storage/framework/views") { Remove-Item "storage/framework/views/*" -Force -Recurse -ErrorAction SilentlyContinue }

# Create simple .ebextensions for AWS
Write-Host "📝 Creating simplified .ebextensions..." -ForegroundColor Yellow

if (Test-Path ".ebextensions") { Remove-Item ".ebextensions" -Force -Recurse }
New-Item -ItemType Directory -Path ".ebextensions" -Force | Out-Null

# Simple container commands
$ebextensionsContent = @"
container_commands:
  01_migrate:
    command: "php artisan migrate --force"
    leader_only: true
  02_seed_check:
    command: 'if [ `php artisan tinker --execute="echo App\Models\Product::count();" 2>/dev/null | tail -1` = "0" ]; then php artisan db:seed --class=ProductionSeeder --force; fi'
    leader_only: true
  03_config_cache:
    command: "php artisan config:cache"
  04_route_cache:
    command: "php artisan route:cache"
  05_view_cache:  
    command: "php artisan view:cache"
"@

$ebextensionsContent | Out-File -FilePath ".ebextensions\deploy.config" -Encoding UTF8

# Items to exclude (minimal exclusions)
$excludeItems = @(
    ".git*",
    "vendor*",  # Let AWS handle composer
    "node_modules*",
    "tests*",
    "storage/logs*",
    "storage/framework/cache*",
    "storage/framework/sessions*", 
    "storage/framework/views*",
    ".env.local.backup",
    ".env.production",
    "*.log",
    "aws-deployment-*.zip",
    "prepare-*.ps1"
)

Write-Host "📦 Creating ZIP package..." -ForegroundColor Yellow

# Get files to zip
$filesToZip = Get-ChildItem -Path "." -Recurse | Where-Object {
    $item = $_
    $shouldExclude = $false
    
    # Skip directories
    if ($item.PSIsContainer) { return $false }
    
    # Skip large files
    if ($item.Length -gt 10MB) { return $false }
    
    foreach ($exclude in $excludeItems) {
        if ($item.FullName -like "*$exclude*") {
            $shouldExclude = $true
            break
        }
    }
    
    return -not $shouldExclude
}

# Create ZIP
Add-Type -AssemblyName System.IO.Compression.FileSystem
$zip = [System.IO.Compression.ZipFile]::Open($deploymentFile, 'Create')

$fileCount = 0
foreach ($file in $filesToZip) {
    $relativePath = $file.FullName.Substring((Get-Location).Path.Length + 1)
    $relativePath = $relativePath.Replace('\', '/')
    
    try {
        [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile($zip, $file.FullName, $relativePath) | Out-Null
        $fileCount++
        if ($fileCount % 50 -eq 0) {
            Write-Host "  ✓ Processed $fileCount files..." -ForegroundColor DarkGray
        }
    }
    catch {
        Write-Host "  ✗ Failed: $relativePath" -ForegroundColor Red
    }
}

$zip.Dispose()

# Restore local environment
Write-Host "🔄 Restoring LOCAL environment..." -ForegroundColor Yellow
Copy-Item ".env.local.backup" ".env" -Force
Remove-Item ".env.local.backup" -Force

# Clean up temporary .ebextensions
Remove-Item ".ebextensions" -Force -Recurse

$fileSize = [math]::Round((Get-Item $deploymentFile).Length / 1MB, 2)

Write-Host ""
Write-Host "======================================================"
Write-Host "✅ SMART Deployment Package Ready!" -ForegroundColor Green
Write-Host "======================================================"
Write-Host ""
Write-Host "📦 File: $deploymentFile" -ForegroundColor Cyan
Write-Host "📊 Size: $fileSize MB" -ForegroundColor Cyan
Write-Host "📁 Files: $fileCount total" -ForegroundColor Cyan
Write-Host ""
Write-Host "🤖 What this package does:" -ForegroundColor Yellow
Write-Host "  ✅ Production .env (AWS RDS)" -ForegroundColor Green
Write-Host "  ✅ Simplified .ebextensions" -ForegroundColor Green
Write-Host "  ✅ AWS handles composer install" -ForegroundColor Green
Write-Host "  ✅ Auto migration & seeding" -ForegroundColor Green
Write-Host "  ✅ Laravel optimizations" -ForegroundColor Green
Write-Host ""
Write-Host "🏠 Local environment: RESTORED" -ForegroundColor Green
Write-Host "🚀 Ready for AWS deployment!" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Upload: $deploymentFile to AWS Elastic Beanstalk" -ForegroundColor Cyan
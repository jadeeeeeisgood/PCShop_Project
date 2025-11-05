# SAFE AWS Deployment - WITH VENDOR FOLDER
# Include vendor folder để tránh composer install issues

Write-Host "======================================================"
Write-Host "🛡️ SAFE AWS Deployment (With Vendor Folder)" -ForegroundColor Green
Write-Host "======================================================"
Write-Host ""

$deploymentFile = "aws-deployment-safe-$(Get-Date -Format 'yyyyMMdd-HHmm').zip"

Write-Host "📁 Creating SAFE deployment package: $deploymentFile" -ForegroundColor Blue

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

# Install PRODUCTION dependencies
Write-Host "📦 Installing PRODUCTION dependencies..." -ForegroundColor Yellow
composer install --no-dev --optimize-autoloader --no-interaction | Out-Null

# Clean storage but keep vendor
if (Test-Path "storage/logs") { Remove-Item "storage/logs/*" -Force -Recurse -ErrorAction SilentlyContinue }
if (Test-Path "storage/framework/cache/data") { Remove-Item "storage/framework/cache/data/*" -Force -Recurse -ErrorAction SilentlyContinue }
if (Test-Path "storage/framework/sessions") { Remove-Item "storage/framework/sessions/*" -Force -Recurse -ErrorAction SilentlyContinue }
if (Test-Path "storage/framework/views") { Remove-Item "storage/framework/views/*" -Force -Recurse -ErrorAction SilentlyContinue }

# NO .ebextensions - too risky
Write-Host "🚫 Skipping .ebextensions (too risky)..." -ForegroundColor Yellow

# Minimal exclude list - keep vendor folder
$excludeItems = @(
    ".git*",
    "node_modules*",
    "tests*", 
    "storage/logs*",
    "storage/framework/cache*",
    "storage/framework/sessions*", 
    "storage/framework/views*",
    ".env.local.backup",
    ".env.production",
    ".ebextensions*",  # Exclude any .ebextensions
    ".platform*",       # Exclude any .platform
    "*.log",
    "aws-deployment-*.zip",
    "prepare-*.ps1"
)

Write-Host "📦 Creating ZIP with vendor folder..." -ForegroundColor Yellow

# Get files including vendor
$filesToZip = Get-ChildItem -Path "." -Recurse | Where-Object {
    $item = $_
    $shouldExclude = $false
    
    # Skip directories
    if ($item.PSIsContainer) { return $false }
    
    # Skip very large files
    if ($item.Length -gt 20MB) { 
        Write-Host "  ⚠️ Skipping large file: $($item.Name) ($([math]::Round($item.Length / 1MB, 2)) MB)" -ForegroundColor Yellow
        return $false 
    }
    
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
        if ($fileCount % 100 -eq 0) {
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

# Reinstall dev dependencies for local
Write-Host "📦 Reinstalling dev dependencies..." -ForegroundColor Yellow
composer install | Out-Null

$fileSize = [math]::Round((Get-Item $deploymentFile).Length / 1MB, 2)

Write-Host ""
Write-Host "======================================================"
Write-Host "✅ SAFE Deployment Package Ready!" -ForegroundColor Green
Write-Host "======================================================"
Write-Host ""
Write-Host "📦 File: $deploymentFile" -ForegroundColor Cyan
Write-Host "📊 Size: $fileSize MB" -ForegroundColor Cyan
Write-Host "📁 Files: $fileCount total" -ForegroundColor Cyan

if ($fileSize -gt 500) {
    Write-Host "⚠️  WARNING: File size ($fileSize MB) exceeds AWS limit" -ForegroundColor Red
} else {
    Write-Host "✅ Size OK: Within AWS limit" -ForegroundColor Green
}

Write-Host ""
Write-Host "🛡️ SAFE Features:" -ForegroundColor Yellow
Write-Host "  ✅ vendor/ folder included (no composer install needed)" -ForegroundColor Green
Write-Host "  ✅ Production dependencies only" -ForegroundColor Green
Write-Host "  ✅ Production .env configured" -ForegroundColor Green
Write-Host "  ❌ No .ebextensions (avoid command failures)" -ForegroundColor Red
Write-Host "  ❌ No .platform hooks (avoid PHP config errors)" -ForegroundColor Red
Write-Host ""
Write-Host "🏠 Local environment: RESTORED" -ForegroundColor Green
Write-Host "🚀 AWS will use included dependencies!" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Upload: $deploymentFile to AWS Elastic Beanstalk" -ForegroundColor Cyan
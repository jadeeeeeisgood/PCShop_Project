<?php
// PC Shop Debug Information Page
// This file helps diagnose production issues

header('Content-Type: text/html; charset=UTF-8');

echo "<!DOCTYPE html>";
echo "<html><head><title>PC Shop Debug Info</title>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;} pre{background:#f5f5f5;padding:10px;border-radius:5px;}</style>";
echo "</head><body>";

echo "<h1>🔍 PC Shop Debug Information</h1>";
echo "<p><strong>Generated at:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "<hr>";

// Environment Check
echo "<h2>🌍 Environment Information</h2>";
echo "<ul>";
echo "<li><strong>PHP Version:</strong> " . PHP_VERSION . "</li>";
echo "<li><strong>Server Software:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</li>";
echo "<li><strong>Document Root:</strong> " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "</li>";
echo "<li><strong>Script Path:</strong> " . __FILE__ . "</li>";
echo "<li><strong>Current URL:</strong> " . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "</li>";
echo "</ul>";

// Laravel Bootstrap Check
echo "<h2>🚀 Laravel Framework Status</h2>";

$laravelPath = __DIR__ . '/../bootstrap/app.php';
if (file_exists($laravelPath)) {
    echo "<p class='success'>✅ Laravel bootstrap file found</p>";

    try {
        require_once __DIR__ . '/../vendor/autoload.php';
        $app = require_once $laravelPath;
        echo "<p class='success'>✅ Laravel application loaded successfully</p>";

        // Check if we can create request
        $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
        echo "<p class='success'>✅ HTTP Kernel loaded</p>";

    } catch (Exception $e) {
        echo "<p class='error'>❌ Laravel failed to load: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
} else {
    echo "<p class='error'>❌ Laravel bootstrap file not found at: $laravelPath</p>";
}

// Environment File Check
echo "<h2>⚙️ Configuration Files</h2>";
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    echo "<p class='success'>✅ .env file found</p>";

    // Read key environment variables (safely)
    $envContent = file_get_contents($envPath);
    preg_match('/APP_ENV=(.*)/', $envContent, $appEnv);
    preg_match('/APP_DEBUG=(.*)/', $envContent, $appDebug);
    preg_match('/APP_URL=(.*)/', $envContent, $appUrl);
    preg_match('/DB_CONNECTION=(.*)/', $envContent, $dbConnection);

    echo "<ul>";
    echo "<li><strong>APP_ENV:</strong> " . ($appEnv[1] ?? 'Not set') . "</li>";
    echo "<li><strong>APP_DEBUG:</strong> " . ($appDebug[1] ?? 'Not set') . "</li>";
    echo "<li><strong>APP_URL:</strong> " . ($appUrl[1] ?? 'Not set') . "</li>";
    echo "<li><strong>DB_CONNECTION:</strong> " . ($dbConnection[1] ?? 'Not set') . "</li>";
    echo "</ul>";
} else {
    echo "<p class='error'>❌ .env file not found</p>";
}

// Directory Permissions
echo "<h2>📁 Directory Permissions</h2>";
$directories = [
    'storage' => __DIR__ . '/../storage',
    'bootstrap/cache' => __DIR__ . '/../bootstrap/cache',
    'public' => __DIR__,
];

foreach ($directories as $name => $path) {
    if (is_dir($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        $writable = is_writable($path) ? '✅ Writable' : '❌ Not writable';
        echo "<p><strong>$name:</strong> $writable (Permissions: $perms)</p>";
    } else {
        echo "<p class='error'><strong>$name:</strong> ❌ Directory not found</p>";
    }
}

// Composer Check
echo "<h2>📦 Dependencies</h2>";
$vendorPath = __DIR__ . '/../vendor';
if (is_dir($vendorPath)) {
    echo "<p class='success'>✅ Vendor directory exists</p>";

    $composerLock = __DIR__ . '/../composer.lock';
    if (file_exists($composerLock)) {
        echo "<p class='success'>✅ composer.lock file exists</p>";
    } else {
        echo "<p class='warning'>⚠️ composer.lock file missing - run 'composer install'</p>";
    }
} else {
    echo "<p class='error'>❌ Vendor directory missing - run 'composer install'</p>";
}

// Assets Check
echo "<h2>🎨 Frontend Assets</h2>";
$buildPath = __DIR__ . '/build';
if (is_dir($buildPath)) {
    echo "<p class='success'>✅ Build directory exists</p>";

    $manifest = $buildPath . '/manifest.json';
    if (file_exists($manifest)) {
        echo "<p class='success'>✅ Vite manifest found</p>";
    } else {
        echo "<p class='warning'>⚠️ Vite manifest missing - run 'npm run build'</p>";
    }
} else {
    echo "<p class='warning'>⚠️ Build directory missing - run 'npm run build'</p>";
}

// PHP Extensions
echo "<h2>🔧 PHP Extensions</h2>";
$requiredExtensions = ['pdo', 'pdo_mysql', 'mbstring', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath'];
foreach ($requiredExtensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<p class='success'>✅ $ext</p>";
    } else {
        echo "<p class='error'>❌ $ext (missing)</p>";
    }
}

// Memory and Limits
echo "<h2>💾 System Resources</h2>";
echo "<ul>";
echo "<li><strong>Memory Limit:</strong> " . ini_get('memory_limit') . "</li>";
echo "<li><strong>Max Execution Time:</strong> " . ini_get('max_execution_time') . "s</li>";
echo "<li><strong>Upload Max Size:</strong> " . ini_get('upload_max_filesize') . "</li>";
echo "<li><strong>Post Max Size:</strong> " . ini_get('post_max_size') . "</li>";
echo "</ul>";

// Quick Laravel Test
echo "<h2>🧪 Quick Laravel Test</h2>";
try {
    if (isset($app)) {
        $config = $app->make('config');
        echo "<p class='success'>✅ Config service working</p>";

        $appName = $config->get('app.name', 'Unknown');
        echo "<p><strong>App Name:</strong> $appName</p>";

        $appEnv = $config->get('app.env', 'Unknown');
        echo "<p><strong>Environment:</strong> $appEnv</p>";

        $appUrl = $config->get('app.url', 'Unknown');
        echo "<p><strong>App URL:</strong> $appUrl</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Laravel config test failed: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<hr>";
echo "<p><small>This debug file should be removed in production for security reasons.</small></p>";
echo "</body></html>";
?>
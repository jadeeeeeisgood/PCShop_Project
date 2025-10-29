<?php

/*
|--------------------------------------------------------------------------
| Set Production Environment Configuration
|--------------------------------------------------------------------------
| Run this script once before AWS deployment to set production values
|--------------------------------------------------------------------------
*/

echo "<h2>🚀 Setting Production Environment...</h2>\n";

// Read current .env file
$envPath = __DIR__ . '/../.env';
$envContent = file_get_contents($envPath);

// Set production values
$productionSettings = [
    'APP_ENV' => 'production',
    'APP_DEBUG' => 'false',
    'APP_NAME' => '"PC Shop"',
    // Keep existing database and VNPay settings
];

foreach ($productionSettings as $key => $value) {
    $pattern = '/^' . preg_quote($key, '/') . '=.*$/m';
    if (preg_match($pattern, $envContent)) {
        $envContent = preg_replace($pattern, $key . '=' . $value, $envContent);
        echo "✅ Updated {$key}={$value}<br>\n";
    } else {
        $envContent .= "\n{$key}={$value}";
        echo "✅ Added {$key}={$value}<br>\n";
    }
}

// Backup current .env
$backupPath = __DIR__ . '/../.env.backup.' . date('Y-m-d-H-i-s');
copy($envPath, $backupPath);
echo "📋 Backed up current .env to: .env.backup." . date('Y-m-d-H-i-s') . "<br>\n";

// Write new .env
file_put_contents($envPath, $envContent);
echo "✅ Production environment configured!<br>\n";

echo "<br><h3>🔧 Next Steps:</h3>\n";
echo "1. Test your application thoroughly<br>\n";
echo "2. Deploy to AWS Elastic Beanstalk<br>\n";
echo "3. Set AWS environment variables (APP_URL, database)<br>\n";
echo "4. Run: php artisan config:clear on AWS<br>\n";
echo "5. Import data using aws-complete-import.php<br>\n";

echo "<br><p style='color: green;'><strong>Ready for AWS deployment! 🚀</strong></p>\n";

?>
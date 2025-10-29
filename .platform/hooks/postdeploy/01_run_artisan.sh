#!/bin/bash

# Set strict error handling
set -e

cd /var/app/current

echo "==================== DEPLOYMENT STARTED ===================="
echo "Timestamp: $(date)"
echo "Working directory: $(pwd)"
echo "PHP version: $(php --version | head -n1)"

# Function to log errors
log_error() {
    echo "ERROR: $1" >&2
    echo "Timestamp: $(date)" >&2
    exit 1
}

# Function to log success
log_success() {
    echo "SUCCESS: $1"
}

# Clear caches first
echo "==================== CLEARING CACHES ===================="
php artisan config:clear || log_error "Failed to clear config cache"
php artisan route:clear || log_error "Failed to clear route cache"
php artisan view:clear || log_error "Failed to clear view cache"
php artisan cache:clear || log_error "Failed to clear application cache"
log_success "All caches cleared"

# Check database connection
echo "==================== CHECKING DATABASE ===================="
php artisan tinker --execute="
try {
    \DB::connection()->getPdo();
    echo 'Database connection: OK';
} catch (Exception \$e) {
    echo 'Database connection failed: ' . \$e->getMessage();
    exit(1);
}
" || log_error "Database connection failed"

# Run migrations
echo "==================== RUNNING MIGRATIONS ===================="
php artisan migrate --force || log_error "Migration failed"
log_success "Migrations completed"

# Check if seeding should run (check both categories and products)
echo "==================== CHECKING DATA STATE ===================="
CATEGORY_COUNT=$(php artisan tinker --execute="echo \App\Models\Category::count();" 2>/dev/null || echo "0")
PRODUCT_COUNT=$(php artisan tinker --execute="echo \App\Models\Product::count();" 2>/dev/null || echo "0")
echo "Current category count: $CATEGORY_COUNT"
echo "Current product count: $PRODUCT_COUNT"

# Force seeding if products are missing, even if categories exist
if [ "$PRODUCT_COUNT" -eq "0" ]; then
    echo "==================== RUNNING SEEDING ===================="
    echo "Products missing, running seeding..."
    
    # Try seeding with error handling
    if php artisan db:seed --force --no-interaction; then
        log_success "Database seeding completed"
        
        # Verify seeding results
        NEW_CATEGORY_COUNT=$(php artisan tinker --execute="echo \App\Models\Category::count();" 2>/dev/null || echo "0")
        NEW_PRODUCT_COUNT=$(php artisan tinker --execute="echo \App\Models\Product::count();" 2>/dev/null || echo "0")
        echo "After seeding - Categories: $NEW_CATEGORY_COUNT, Products: $NEW_PRODUCT_COUNT"
    else
        echo "WARNING: Seeding failed, trying ProductionSeeder as fallback..."
        if php artisan db:seed --class=ProductionSeeder --force --no-interaction; then
            log_success "ProductionSeeder completed successfully"
        else
            echo "ERROR: Both regular seeding and ProductionSeeder failed"
            echo "Manual seeding required: php artisan db:seed --class=ProductionSeeder --force"
        fi
    fi
elif [ "$CATEGORY_COUNT" -gt "0" ] && [ "$PRODUCT_COUNT" -gt "0" ]; then
    echo "Both categories and products exist, skipping seeding"
else
    echo "Partial data detected, running ProductionSeeder to ensure completeness..."
    if php artisan db:seed --class=ProductionSeeder --force --no-interaction; then
        echo "ProductionSeeder completed successfully"
    else
        echo "ProductionSeeder failed, trying custom command..."
        php artisan seed:production --force || echo "All seeding methods failed, manual intervention required"
    fi
fi

# Generate application key if needed
echo "==================== CHECKING APP KEY ===================="
if ! php artisan key:generate --show --no-ansi | grep -q "base64:"; then
    echo "Generating application key..."
    php artisan key:generate --force || log_error "Failed to generate app key"
fi

# Optimize for production
echo "==================== OPTIMIZING FOR PRODUCTION ===================="
php artisan config:cache || log_error "Failed to cache config"
php artisan route:cache || log_error "Failed to cache routes"  
php artisan view:cache || log_error "Failed to cache views"
log_success "Production optimization completed"

# Set proper permissions
echo "==================== SETTING PERMISSIONS ===================="
chmod -R 755 storage/ || echo "Warning: Could not set storage permissions"
chmod -R 755 bootstrap/cache/ || echo "Warning: Could not set bootstrap cache permissions"

echo "==================== DEPLOYMENT COMPLETED ===================="
echo "Timestamp: $(date)"

# CloudWatch Agent restart (optional, non-blocking)
echo "==================== RESTARTING CLOUDWATCH AGENT ===================="
sudo /opt/aws/amazon-cloudwatch-agent/bin/amazon-cloudwatch-agent-ctl -m ec2 -a stop 2>/dev/null || echo "CloudWatch agent not running"
sudo /opt/aws/amazon-cloudwatch-agent/bin/amazon-cloudwatch-agent-ctl -m ec2 -a start 2>/dev/null || echo "CloudWatch agent start failed or not installed"

echo "Deployment script completed successfully!"
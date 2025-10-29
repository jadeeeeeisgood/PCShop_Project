#!/bin/bash
# AWS Production Database Setup Script - FINAL VERSION
# Run this script on your AWS EC2 instance via SSH

echo "======================================================"
echo "🚀 AWS PC-Shop Database Setup - FINAL VERSION"
echo "======================================================"
echo "Website: http://pcshop-final.eba-gm3xqw32.us-east-1.elasticbeanstalk.com/"
echo "This script will fix the 'no products' issue on AWS"
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to check if command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

# Check environment
print_status "Step 1: Checking environment..."

if ! command_exists php; then
    print_error "PHP is not installed or not in PATH"
    exit 1
fi

if [ ! -f "artisan" ]; then
    print_error "artisan file not found. Are you in the Laravel project root?"
    print_warning "Try: cd /var/app/current"
    exit 1
fi

print_success "PHP and Laravel found"

# Check database connection
print_status "Step 2: Testing database connection..."
DB_TEST=$(php artisan tinker --execute="try { \App\Models\User::count(); echo 'OK'; } catch(Exception \$e) { echo 'FAIL: ' . \$e->getMessage(); }" 2>/dev/null | tail -1)

if [[ "$DB_TEST" == *"FAIL"* ]]; then
    print_error "Database connection failed: $DB_TEST"
    print_warning "Please check your .env file database settings"
    exit 1
elif [[ "$DB_TEST" == "OK" ]]; then
    print_success "Database connection successful"
else
    print_warning "Database connection test returned: $DB_TEST"
fi

# Check current migration status
print_status "Step 3: Checking migration status..."
php artisan migrate:status

print_status "Step 4: Running pending migrations..."
if php artisan migrate --force; then
    print_success "Migrations completed successfully"
else
    print_error "Migration failed"
    exit 1
fi

# Check current database state
print_status "Step 5: Checking current database state..."
php artisan tinker --execute="
echo 'Current state:' . chr(10);
echo 'Users: ' . \App\Models\User::count() . chr(10);
echo 'Categories: ' . \App\Models\Category::count() . chr(10);
echo 'Products: ' . \App\Models\Product::count() . chr(10);
echo 'Active Products: ' . \App\Models\Product::where('is_active', true)->count() . chr(10);
"

# Run safe seeding (only products and categories, no user conflicts)
print_status "Step 6: Seeding products and categories..."
if php artisan db:seed --class=SafeProductSeeder --force; then
    print_success "Products and categories seeded successfully"
else
    print_error "Product seeding failed"
    print_warning "Trying to create admin user first..."
    if php artisan db:seed --class=AdminUserSeeder --force; then
        print_success "Admin user created"
        print_status "Retrying product seeding..."
        if php artisan db:seed --class=SafeProductSeeder --force; then
            print_success "Products and categories seeded successfully"
        else
            print_error "Product seeding failed again"
            exit 1
        fi
    else
        print_error "Admin user creation failed"
        exit 1
    fi
fi

# Clear cache to ensure changes take effect
print_status "Step 7: Clearing application cache..."
php artisan cache:clear >/dev/null 2>&1 || true
php artisan config:clear >/dev/null 2>&1 || true
php artisan route:clear >/dev/null 2>&1 || true
php artisan view:clear >/dev/null 2>&1 || true
print_success "Cache cleared"

# Final verification
print_status "Step 8: Final verification..."
php artisan tinker --execute="
echo 'Final database state:' . chr(10);
echo '=====================' . chr(10);
echo 'Users: ' . \App\Models\User::count() . chr(10);
echo 'Categories: ' . \App\Models\Category::count() . chr(10);
echo 'Products: ' . \App\Models\Product::count() . chr(10);
echo 'Active Products: ' . \App\Models\Product::where('is_active', true)->count() . chr(10);
echo 'Featured Products: ' . \App\Models\Product::where('is_featured', true)->count() . chr(10);
echo '=====================' . chr(10);
if (\App\Models\Product::count() > 0) {
    echo 'Sample products:' . chr(10);
    \$products = \App\Models\Product::with('category')->take(3)->get();
    foreach (\$products as \$product) {
        echo '- ' . \$product->name . ' (' . \$product->category->name . ')' . chr(10);
    }
}
"

echo ""
echo "======================================================"
print_success "🎉 AWS Database Setup Completed!"
echo "======================================================"
echo ""
print_status "📝 Summary:"
print_success "✅ Database connection verified"
print_success "✅ All migrations completed"
print_success "✅ Products and categories created"
print_success "✅ Cache cleared"
echo ""
print_status "🌐 Your website should now display products:"
echo "   http://pcshop-final.eba-gm3xqw32.us-east-1.elasticbeanstalk.com/products"
echo ""
print_warning "⚠️ If products still don't show, wait 2-3 minutes for AWS to refresh"
echo "   and check application logs: /var/log/eb-hooks.log"
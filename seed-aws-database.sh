#!/bin/bash

# AWS EB Seed Database Script
# Run this script after deploying to AWS Elastic Beanstalk

echo "=== AWS EB Database Seed Script ==="
echo "Starting database seeding process..."

# Check current environment
echo "Environment: $(php artisan env)"

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Clear caches
echo "Clearing caches..."
php artisan optimize:clear

# Seed database with production data
echo "Seeding database..."
php artisan db:seed --force --class=ProductionSeeder

# Verify data
echo "Verifying data..."
echo "Categories count: $(php artisan tinker --execute='echo App\Models\Category::count();')"
echo "Products count: $(php artisan tinker --execute='echo App\Models\Product::count();')"

# Cache configuration
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache

echo "=== Database seeding completed! ==="
echo "Please check your website now."
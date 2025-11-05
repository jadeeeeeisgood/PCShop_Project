#!/bin/bash

# Simple deployment hooks for AWS Elastic Beanstalk
# This file runs after the application deployment

# Clear Laravel caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage/logs storage/framework

# Run migrations (if database is accessible)
php artisan migrate --force || echo "Migration failed - continuing..."

echo "Deployment hooks completed successfully"
#!/bin/bash

# Đi đến thư mục ứng dụng
cd /var/app/current

# Chạy migrate và seed
php artisan migrate --force
php artisan db:seed --force

# Xóa cache cũ và tạo cache mới cho production (Rất quan trọng)
php artisan config:cache
php artisan route:cache
php artisan view:cache
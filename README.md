# PC Shop - E-commerce Platform

🖥️ **PC Shop** là một nền tảng thương mại điện tử hoàn chỉnh được xây dựng bằng Laravel, chuyên bán các sản phẩm máy tính và phụ kiện công nghệ.

## ✨ Tính năng nổi bật

### 🛍️ **Cho khách hàng**
- **Catalog sản phẩm**: Duyệt và tìm kiếm sản phẩm theo danh mục
- **Giỏ hàng thông minh**: Quản lý sản phẩm với session support
- **Thanh toán đa dạng**: VNPay, COD với xử lý bảo mật
- **Theo dõi đơn hàng**: Real-time order tracking
- **Responsive design**: Tối ưu cho mọi thiết bị

### ⚡ **Real-time Features**
- **Cập nhật tồn kho**: WebSocket với Pusher
- **Thông báo instant**: Stock alerts và order updates
- **Conflict prevention**: Tránh overselling

### 🔧 **Cho admin**
- **Dashboard analytics**: Thống kê doanh thu và đơn hàng
- **Quản lý sản phẩm**: CRUD với upload hình ảnh
- **Quản lý đơn hàng**: Theo dõi và cập nhật trạng thái
- **Quản lý người dùng**: Role-based access control

## 🚀 Tech Stack

- **Backend**: Laravel 11, PHP 8.2+
- **Frontend**: Tailwind CSS, Alpine.js
- **Database**: MySQL với migrations
- **Real-time**: Pusher WebSocket
- **Payment**: VNPay Gateway
- **Auth**: Laravel Breeze
- **Email**: Laravel Mail

## 📦 Cài đặt

### Yêu cầu hệ thống
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL
- Git

### Bước 1: Clone repository
```bash
git clone https://github.com/jadeeeeeisgood/PCShop_Project.git
cd PCShop_Project
```

### Bước 2: Cài đặt dependencies
```bash
# PHP dependencies
composer install

# Node.js dependencies
npm install
```

### Bước 3: Cấu hình môi trường
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Bước 4: Cấu hình database
Cập nhật file `.env` với thông tin database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pcshop
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Bước 5: Chạy migrations và seeders
```bash
# Create database tables
php artisan migrate

# Seed sample data
php artisan db:seed
```

### Bước 6: Build assets
```bash
# Development
npm run dev

# Production
npm run build
```

### Bước 7: Cấu hình real-time features (optional)
Cập nhật Pusher credentials trong `.env`:
```env
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=ap1
```

### Bước 8: Cấu hình VNPay (optional)
```env
VNPAY_TMN_CODE=your_tmn_code
VNPAY_HASH_SECRET=your_hash_secret
```

### Bước 9: Khởi chạy ứng dụng
```bash
php artisan serve
```

Truy cập: `http://localhost:8000`

## 🎯 Demo

- **Frontend**: Catalog sản phẩm và shopping cart
- **Admin Panel**: `/admin` (admin@example.com / password)
- **Real-time Demo**: `/demo/real-time-features`

## 📂 Cấu trúc project

```
app/
├── Events/              # Broadcasting events
├── Http/Controllers/    # Controllers
├── Models/             # Eloquent models
├── Services/           # Business logic
│   ├── StockService.php    # Real-time stock management
│   ├── VNPayService.php    # Payment processing
│   └── CartService.php     # Cart operations
└── Mail/               # Email templates

resources/views/
├── admin/              # Admin interface
├── auth/               # Authentication
├── cart/               # Shopping cart
├── checkout/           # Checkout process
├── products/           # Product catalog
└── demo/               # Feature demonstrations

public/
├── css/                # Stylesheets
├── js/                 # JavaScript
│   └── stock-updates.js    # Real-time features
└── img/                # Images

database/
├── migrations/         # Database schema
└── seeders/           # Sample data
```

## 🛡️ Bảo mật

- **CSRF Protection**: Laravel's built-in CSRF
- **SQL Injection**: Eloquent ORM protection
- **XSS Prevention**: Blade template escaping
- **Payment Security**: VNPay HMAC verification
- **Input Validation**: Form request validation

## 📈 Tính năng nâng cao

### Real-time Stock Management
- Tự động cập nhật tồn kho khi có thay đổi
- Reservation system tránh overselling
- Low stock notifications

### VNPay Integration
- Secure payment processing
- Transaction tracking
- Automatic order confirmation

### Performance Optimization
- Database query optimization
- Image compression
- Asset minification
- Caching strategies

## 🤝 Đóng góp

1. Fork repository
2. Tạo feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Tạo Pull Request

## 📝 License

Dự án này được phân phối dưới giấy phép [MIT License](LICENSE).

## 📞 Liên hệ

- **Developer**: PCShop Team
- **Email**: support@pcshop.com
- **GitHub**: [@jadeeeeeisgood](https://github.com/jadeeeeeisgood)

## 🙏 Acknowledgments

- Laravel Framework
- Tailwind CSS
- Pusher
- VNPay
- Tất cả contributors

---

**⭐ Nếu dự án này hữu ích, hãy cho chúng tôi một star!**

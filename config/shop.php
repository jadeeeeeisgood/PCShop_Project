<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Shop Configuration
    |--------------------------------------------------------------------------
    */

    'name' => env('SHOP_NAME', 'PC Shop'),
    'description' => env('SHOP_DESCRIPTION', 'Cửa hàng máy tính hàng đầu Việt Nam'),
    'keywords' => env('SHOP_KEYWORDS', 'máy tính, laptop, pc, gaming, phụ kiện, chuột, bàn phím'),

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */
    'pagination' => [
        'products_per_page' => 12,
        'admin_per_page' => 15,
        'search_per_page' => 20,
    ],

    /*
    |--------------------------------------------------------------------------
    | Product Configuration
    |--------------------------------------------------------------------------
    */
    'products' => [
        'max_images' => 10,
        'image_size_limit' => 2048, // KB
        'allowed_image_types' => ['jpeg', 'png', 'jpg', 'gif', 'webp'],
        'low_stock_threshold' => 10,
        'featured_limit' => 10,
        'related_limit' => 4,
        'popular_limit' => 8,
        'new_limit' => 8,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cart Configuration
    |--------------------------------------------------------------------------
    */
    'cart' => [
        'session_key' => 'cart',
        'max_quantity' => 99,
        'expire_days' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Order Configuration
    |--------------------------------------------------------------------------
    */
    'orders' => [
        'statuses' => [
            'pending' => 'Chờ xử lý',
            'confirmed' => 'Đã xác nhận',
            'processing' => 'Đang xử lý',
            'shipped' => 'Đã gửi hàng',
            'delivered' => 'Đã giao hàng',
            'cancelled' => 'Đã hủy',
            'refunded' => 'Đã hoàn tiền',
        ],
        'payment_methods' => [
            'cod' => 'Thanh toán khi nhận hàng',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'credit_card' => 'Thẻ tín dụng',
            'e_wallet' => 'Ví điện tử',
        ],
        'shipping_methods' => [
            'standard' => [
                'name' => 'Giao hàng tiêu chuẩn',
                'cost' => 30000,
                'estimated_days' => '2-3 ngày',
            ],
            'express' => [
                'name' => 'Giao hàng nhanh',
                'cost' => 50000,
                'estimated_days' => '1-2 ngày',
            ],
            'same_day' => [
                'name' => 'Giao hàng trong ngày',
                'cost' => 100000,
                'estimated_days' => 'Trong ngày',
            ],
        ],
        'free_shipping_threshold' => 2000000, // 2 triệu VND
    ],

    /*
    |--------------------------------------------------------------------------
    | Contact Information
    |--------------------------------------------------------------------------
    */
    'contact' => [
        'phone' => '+84 123 456 789',
        'email' => 'info@pcshop.com',
        'address' => '123 Đường ABC, Quận 1, TP. Hồ Chí Minh',
        'working_hours' => 'Thứ 2 - Chủ nhật: 8:00 - 22:00',
        'support_hours' => '24/7',
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Media Links
    |--------------------------------------------------------------------------
    */
    'social' => [
        'facebook' => 'https://facebook.com/pcshop',
        'twitter' => 'https://twitter.com/pcshop',
        'instagram' => 'https://instagram.com/pcshop',
        'youtube' => 'https://youtube.com/pcshop',
    ],

    /*
    |--------------------------------------------------------------------------
    | SEO Configuration
    |--------------------------------------------------------------------------
    */
    'seo' => [
        'meta_title_suffix' => ' - PC Shop',
        'meta_description_length' => 160,
        'meta_keywords_limit' => 10,
        'og_image_default' => '/images/og-default.jpg',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'categories_ttl' => 3600, // 1 hour
        'products_ttl' => 1800, // 30 minutes
        'featured_products_ttl' => 3600, // 1 hour
        'popular_products_ttl' => 1800, // 30 minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Configuration
    |--------------------------------------------------------------------------
    */
    'images' => [
        'thumbnails' => [
            'small' => ['width' => 150, 'height' => 150],
            'medium' => ['width' => 300, 'height' => 300],
            'large' => ['width' => 600, 'height' => 600],
        ],
        'watermark' => [
            'enabled' => false,
            'image' => 'watermark.png',
            'position' => 'bottom-right',
            'opacity' => 0.5,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Features Toggle
    |--------------------------------------------------------------------------
    */
    'features' => [
        'reviews' => true,
        'wishlist' => true,
        'compare' => true,
        'live_chat' => false,
        'advanced_search' => true,
        'bulk_discount' => false,
        'loyalty_program' => false,
    ],
];
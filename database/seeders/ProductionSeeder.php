<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds for production environment.
     */
    public function run(): void
    {
        $this->command->info('Starting production seeding...');

        try {
            // Always try to create admin user if it doesn't exist
            $this->seedAdminUser();

            // Check current state
            $categoryCount = Category::count();
            $productCount = Product::count();

            $this->command->info("Current state - Categories: {$categoryCount}, Products: {$productCount}");

            // Seed categories if none exist
            if ($categoryCount == 0) {
                $this->seedCategories();
            } else {
                $this->command->info('Categories already exist, skipping category seeding');
            }

            // Always try to seed products if none exist, even if categories exist
            if ($productCount == 0) {
                $this->command->info('No products found, seeding products...');
                $this->seedProducts();
            } else {
                $this->command->info('Products already exist, skipping product seeding');
            }

            // Final verification
            $finalCategoryCount = Category::count();
            $finalProductCount = Product::count();
            $this->command->info("Final state - Categories: {$finalCategoryCount}, Products: {$finalProductCount}");

            $this->command->info('Production seeding completed successfully!');

        } catch (\Exception $e) {
            $this->command->error('Production seeding failed: ' . $e->getMessage());
            $this->command->error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    private function seedAdminUser()
    {
        $adminUser = User::where('email', 'admin@pcshop.com')->first();

        if (!$adminUser) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@pcshop.com',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
            $this->command->info('Admin user created successfully!');
        } else {
            $this->command->info('Admin user already exists.');
        }
    }

    private function seedCategories()
    {
        $this->command->info('Seeding categories...');

        $categories = [
            ['name' => 'Gaming PC', 'slug' => 'gaming-pc'],
            ['name' => 'Office PC', 'slug' => 'office-pc'],
            ['name' => 'Laptop', 'slug' => 'laptop'],
            ['name' => 'PS5', 'slug' => 'ps5'],
            ['name' => 'Macbook', 'slug' => 'macbook'],
            ['name' => 'Linh kiện', 'slug' => 'linh-kien'],
            ['name' => 'Gaming Gear', 'slug' => 'gaming-gear'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('Categories seeded successfully!');
    }

    private function seedProducts()
    {
        $this->command->info('Seeding products...');

        // Get categories with error handling
        $categories = Category::all()->keyBy('slug');

        if ($categories->isEmpty()) {
            $this->command->error('No categories found! Cannot seed products without categories.');
            return;
        }

        $this->command->info('Found categories: ' . $categories->keys()->implode(', '));

        // Safe category retrieval
        $gamingPcCategory = $categories->get('gaming-pc');
        $laptopCategory = $categories->get('laptop');
        $ps5Category = $categories->get('ps5');

        // If any required category is missing, create essential ones
        if (!$gamingPcCategory) {
            $gamingPcCategory = Category::create(['name' => 'Gaming PC', 'slug' => 'gaming-pc']);
            $this->command->info('Created missing Gaming PC category');
        }
        if (!$laptopCategory) {
            $laptopCategory = Category::create(['name' => 'Laptop', 'slug' => 'laptop']);
            $this->command->info('Created missing Laptop category');
        }
        if (!$ps5Category) {
            $ps5Category = Category::create(['name' => 'PS5', 'slug' => 'ps5']);
            $this->command->info('Created missing PS5 category');
        }

        $products = [
            // Gaming PC
            [
                'category_id' => $gamingPcCategory->id,
                'name' => 'PC Gaming Performance RTX 4060',
                'slug' => 'pc-gaming-performance-rtx-4060',
                'description' => 'PC Gaming hiệu năng cao với RTX 4060, Intel i5-12400F, RAM 16GB, SSD 512GB',
                'price' => 21990000.00,
                'stock' => 50,
                'is_featured' => true,
                'is_active' => true,
                'image' => 'gaming-pc-1.jpg',
            ],
            [
                'category_id' => $gamingPcCategory->id,
                'name' => 'PC Gaming Beast RTX 4070',
                'slug' => 'pc-gaming-beast-rtx-4070',
                'description' => 'PC Gaming đỉnh cao với RTX 4070, Intel i7-13700F, RAM 32GB, SSD 1TB',
                'price' => 28990000.00,
                'stock' => 30,
                'is_featured' => true,
                'is_active' => true,
                'image' => 'gaming-pc-2.jpg',
            ],
            [
                'category_id' => $gamingPcCategory->id,
                'name' => 'PC Gaming Pro RTX 4080',
                'slug' => 'pc-gaming-pro-rtx-4080',
                'description' => 'PC Gaming chuyên nghiệp với RTX 4080, Intel i9-13900F, RAM 32GB, SSD 2TB',
                'price' => 39990000.00,
                'stock' => 15,
                'is_featured' => true,
                'is_active' => true,
                'image' => 'gaming-pc-3.jpg',
            ],

            // Office PC
            [
                'category_id' => $categories->get('office-pc')->id ?? $gamingPcCategory->id,
                'name' => 'PC Văn Phòng Essential',
                'slug' => 'pc-van-phong-essential',
                'description' => 'PC Văn phòng cơ bản với Intel i3-12100, RAM 8GB, SSD 256GB',
                'price' => 8990000.00,
                'stock' => 100,
                'is_featured' => false,
                'is_active' => true,
                'image' => 'office-pc-1.jpg',
            ],
            [
                'category_id' => $categories->get('office-pc')->id ?? $gamingPcCategory->id,
                'name' => 'PC Văn Phòng Business',
                'slug' => 'pc-van-phong-business',
                'description' => 'PC Văn phòng cao cấp với Intel i5-13400, RAM 16GB, SSD 512GB',
                'price' => 12990000.00,
                'stock' => 80,
                'is_featured' => false,
                'is_active' => true,
                'image' => 'office-pc-2.jpg',
            ],

            // Laptops
            [
                'category_id' => $laptopCategory->id,
                'name' => 'HP Pavilion 16',
                'slug' => 'hp-pavilion-16',
                'description' => 'Laptop HP Pavilion 16 inch, Intel Core i5, RAM 8GB, SSD 512GB',
                'price' => 16000000.00,
                'stock' => 30,
                'is_featured' => true,
                'is_active' => true,
                'image' => 'laptop-hp-1.jpg',
            ],
            [
                'category_id' => $laptopCategory->id,
                'name' => 'ASUS VivoBook 15',
                'slug' => 'asus-vivobook-15',
                'description' => 'Laptop ASUS VivoBook 15, AMD Ryzen 5, RAM 8GB, SSD 512GB',
                'price' => 14500000.00,
                'stock' => 25,
                'is_featured' => false,
                'is_active' => true,
                'image' => 'laptop-asus-1.jpg',
            ],
            [
                'category_id' => $laptopCategory->id,
                'name' => 'Dell Inspiron 15 3000',
                'slug' => 'dell-inspiron-15-3000',
                'description' => 'Laptop Dell Inspiron 15, Intel Core i3, RAM 4GB, SSD 256GB',
                'price' => 11500000.00,
                'stock' => 40,
                'is_featured' => false,
                'is_active' => true,
                'image' => 'laptop-dell-1.jpg',
            ],
            [
                'category_id' => $laptopCategory->id,
                'name' => 'Acer Aspire 5',
                'slug' => 'acer-aspire-5',
                'description' => 'Laptop Acer Aspire 5, Intel Core i5, RAM 8GB, SSD 512GB',
                'price' => 15500000.00,
                'stock' => 35,
                'is_featured' => false,
                'is_active' => true,
                'image' => 'laptop-acer-1.jpg',
            ],

            // Gaming Consoles
            [
                'category_id' => $ps5Category->id,
                'name' => 'PlayStation 5',
                'slug' => 'playstation-5',
                'description' => 'Sony PlayStation 5 Console - Thế hệ game mới',
                'price' => 13300000.00,
                'stock' => 20,
                'is_featured' => true,
                'is_active' => true,
                'image' => 'ps5-console.jpg',
            ],
            [
                'category_id' => $ps5Category->id,
                'name' => 'PlayStation 5 Digital Edition',
                'slug' => 'playstation-5-digital',
                'description' => 'Sony PlayStation 5 Digital Edition - Không có ổ đĩa',
                'price' => 11800000.00,
                'stock' => 15,
                'is_featured' => false,
                'is_active' => true,
                'image' => 'ps5-digital.jpg',
            ],

            // Macbooks
            [
                'category_id' => $categories->get('macbook')->id ?? $laptopCategory->id,
                'name' => 'MacBook Air M2',
                'slug' => 'macbook-air-m2',
                'description' => 'MacBook Air với chip M2, RAM 8GB, SSD 256GB',
                'price' => 27990000.00,
                'stock' => 10,
                'is_featured' => true,
                'is_active' => true,
                'image' => 'macbook-air-m2.jpg',
            ],
            [
                'category_id' => $categories->get('macbook')->id ?? $laptopCategory->id,
                'name' => 'MacBook Pro 14" M3',
                'slug' => 'macbook-pro-14-m3',
                'description' => 'MacBook Pro 14 inch với chip M3, RAM 16GB, SSD 512GB',
                'price' => 49990000.00,
                'stock' => 5,
                'is_featured' => true,
                'is_active' => true,
                'image' => 'macbook-pro-14.jpg',
            ],

            // Linh kiện
            [
                'category_id' => $categories->get('linh-kien')->id ?? $gamingPcCategory->id,
                'name' => 'RAM Corsair Vengeance 16GB',
                'slug' => 'ram-corsair-vengeance-16gb',
                'description' => 'RAM Corsair Vengeance LPX 16GB DDR4 3200MHz',
                'price' => 1890000.00,
                'stock' => 100,
                'is_featured' => false,
                'is_active' => true,
                'image' => 'ram-corsair.jpg',
            ],
            [
                'category_id' => $categories->get('linh-kien')->id ?? $gamingPcCategory->id,
                'name' => 'SSD Samsung 980 PRO 1TB',
                'slug' => 'ssd-samsung-980-pro-1tb',
                'description' => 'SSD Samsung 980 PRO NVMe M.2 1TB',
                'price' => 2990000.00,
                'stock' => 80,
                'is_featured' => false,
                'is_active' => true,
                'image' => 'ssd-samsung.jpg',
            ],
            [
                'category_id' => $categories->get('linh-kien')->id ?? $gamingPcCategory->id,
                'name' => 'CPU Intel Core i5-13400F',
                'slug' => 'cpu-intel-i5-13400f',
                'description' => 'Bộ vi xử lý Intel Core i5-13400F',
                'price' => 4990000.00,
                'stock' => 50,
                'is_featured' => false,
                'is_active' => true,
                'image' => 'cpu-intel-i5.jpg',
            ],

            // Gaming Gear
            [
                'category_id' => $categories->get('gaming-gear')->id ?? $gamingPcCategory->id,
                'name' => 'Logitech G502 Hero',
                'slug' => 'logitech-g502-hero',
                'description' => 'Chuột gaming Logitech G502 Hero với 11 nút bấm',
                'price' => 1290000.00,
                'stock' => 200,
                'is_featured' => false,
                'is_active' => true,
                'image' => 'mouse-logitech.jpg',
            ],
            [
                'category_id' => $categories->get('gaming-gear')->id ?? $gamingPcCategory->id,
                'name' => 'Razer BlackWidow V3',
                'slug' => 'razer-blackwidow-v3',
                'description' => 'Bàn phím cơ gaming Razer BlackWidow V3',
                'price' => 2890000.00,
                'stock' => 150,
                'is_featured' => false,
                'is_active' => true,
                'image' => 'keyboard-razer.jpg',
            ],
            [
                'category_id' => $categories->get('gaming-gear')->id ?? $gamingPcCategory->id,
                'name' => 'HyperX Cloud II',
                'slug' => 'hyperx-cloud-ii',
                'description' => 'Tai nghe gaming HyperX Cloud II 7.1',
                'price' => 1990000.00,
                'stock' => 120,
                'is_featured' => false,
                'is_active' => true,
                'image' => 'headset-hyperx.jpg',
            ],
        ];

        foreach ($products as $product) {
            try {
                Product::create($product);
                $this->command->info('Created product: ' . $product['name']);
            } catch (\Exception $e) {
                $this->command->error('Failed to create product ' . $product['name'] . ': ' . $e->getMessage());
            }
        }

        $this->command->info('Products seeded successfully!');
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories
        $categories = [
            ['name' => 'Gaming PCs', 'slug' => 'gaming-pcs'],
            ['name' => 'Office PCs', 'slug' => 'office-pcs'],
            ['name' => 'Laptops', 'slug' => 'laptops'],
            ['name' => 'Components', 'slug' => 'components'],
            ['name' => 'Accessories', 'slug' => 'accessories'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }

        $gaming = \App\Models\Category::where('slug', 'gaming-pcs')->first();
        $office = \App\Models\Category::where('slug', 'office-pcs')->first();
        $laptops = \App\Models\Category::where('slug', 'laptops')->first();
        $components = \App\Models\Category::where('slug', 'components')->first();
        $accessories = \App\Models\Category::where('slug', 'accessories')->first();

        // Create gaming products
        $gamingProducts = [
            [
                'category_id' => $gaming->id,
                'name' => 'Gaming Beast RTX 4090',
                'slug' => 'gaming-beast-rtx-4090',
                'description' => 'PC Gaming cao cấp với RTX 4090, CPU Intel i9-13900K, RAM 32GB DDR5, SSD 1TB NVMe',
                'price' => 85000000,
                'stock' => 5,
                'is_featured' => true,
                'specifications' => [
                    'CPU' => 'Intel Core i9-13900K',
                    'GPU' => 'NVIDIA RTX 4090 24GB',
                    'RAM' => '32GB DDR5-5600',
                    'Storage' => '1TB NVMe SSD',
                    'Motherboard' => 'ASUS ROG STRIX Z790-E',
                    'PSU' => '1000W 80+ Gold',
                    'Cooling' => 'AIO Liquid Cooling'
                ]
            ],
            [
                'category_id' => $gaming->id,
                'name' => 'Gaming Pro RTX 4070',
                'slug' => 'gaming-pro-rtx-4070',
                'description' => 'PC Gaming tầm trung với RTX 4070, CPU Intel i7-13700F, RAM 16GB DDR4, SSD 500GB',
                'price' => 45000000,
                'stock' => 8,
                'is_featured' => true,
                'specifications' => [
                    'CPU' => 'Intel Core i7-13700F',
                    'GPU' => 'NVIDIA RTX 4070 12GB',
                    'RAM' => '16GB DDR4-3200',
                    'Storage' => '500GB NVMe SSD',
                    'Motherboard' => 'MSI MAG B760M',
                    'PSU' => '750W 80+ Bronze',
                    'Cooling' => 'Tower Air Cooler'
                ]
            ],
            [
                'category_id' => $gaming->id,
                'name' => 'Gaming Entry RTX 4060',
                'slug' => 'gaming-entry-rtx-4060',
                'description' => 'PC Gaming giá rẻ với RTX 4060, CPU AMD Ryzen 5 7600X, RAM 16GB DDR5',
                'price' => 25000000,
                'stock' => 12,
                'specifications' => [
                    'CPU' => 'AMD Ryzen 5 7600X',
                    'GPU' => 'NVIDIA RTX 4060 8GB',
                    'RAM' => '16GB DDR5-5200',
                    'Storage' => '500GB NVMe SSD',
                    'Motherboard' => 'ASRock B650M Pro',
                    'PSU' => '650W 80+ Bronze',
                    'Cooling' => 'Stock Cooler'
                ]
            ]
        ];

        // Create office products
        $officeProducts = [
            [
                'category_id' => $office->id,
                'name' => 'Office Workstation Pro',
                'slug' => 'office-workstation-pro',
                'description' => 'Máy tính văn phòng chuyên nghiệp với CPU Intel i5, RAM 16GB, SSD 256GB',
                'price' => 18000000,
                'stock' => 15,
                'specifications' => [
                    'CPU' => 'Intel Core i5-12400',
                    'GPU' => 'Intel UHD Graphics 730',
                    'RAM' => '16GB DDR4-3200',
                    'Storage' => '256GB NVMe SSD',
                    'Motherboard' => 'ASUS PRIME B660M-A',
                    'PSU' => '500W 80+ Bronze'
                ]
            ],
            [
                'category_id' => $office->id,
                'name' => 'Office Standard',
                'slug' => 'office-standard',
                'description' => 'Máy tính văn phòng cơ bản với CPU AMD Ryzen 3, RAM 8GB, SSD 256GB',
                'price' => 12000000,
                'stock' => 20,
                'specifications' => [
                    'CPU' => 'AMD Ryzen 3 4300G',
                    'GPU' => 'AMD Radeon Graphics',
                    'RAM' => '8GB DDR4-3200',
                    'Storage' => '256GB NVMe SSD',
                    'Motherboard' => 'ASRock A520M-HDV',
                    'PSU' => '450W 80+ Bronze'
                ]
            ]
        ];

        // Create laptop products
        $laptopProducts = [
            [
                'category_id' => $laptops->id,
                'name' => 'Gaming Laptop RTX 4060',
                'slug' => 'gaming-laptop-rtx-4060',
                'description' => 'Laptop Gaming với RTX 4060, Intel i7-13700H, RAM 16GB, màn hình 144Hz',
                'price' => 35000000,
                'stock' => 6,
                'is_featured' => true,
                'specifications' => [
                    'CPU' => 'Intel Core i7-13700H',
                    'GPU' => 'NVIDIA RTX 4060 8GB',
                    'RAM' => '16GB DDR5',
                    'Storage' => '512GB NVMe SSD',
                    'Display' => '15.6" FHD 144Hz',
                    'Battery' => '80Wh'
                ]
            ],
            [
                'category_id' => $laptops->id,
                'name' => 'Business Laptop Elite',
                'slug' => 'business-laptop-elite',
                'description' => 'Laptop công việc cao cấp với Intel i7, RAM 32GB, SSD 1TB',
                'price' => 28000000,
                'stock' => 8,
                'specifications' => [
                    'CPU' => 'Intel Core i7-1365U',
                    'GPU' => 'Intel Iris Xe Graphics',
                    'RAM' => '32GB DDR5',
                    'Storage' => '1TB NVMe SSD',
                    'Display' => '14" QHD IPS',
                    'Battery' => '57Wh'
                ]
            ]
        ];

        // Create component products
        $componentProducts = [
            [
                'category_id' => $components->id,
                'name' => 'NVIDIA RTX 4090 Graphics Card',
                'slug' => 'nvidia-rtx-4090-graphics-card',
                'description' => 'Card đồ họa cao cấp nhất RTX 4090 24GB GDDR6X',
                'price' => 45000000,
                'stock' => 3,
                'is_featured' => true,
                'specifications' => [
                    'Memory' => '24GB GDDR6X',
                    'Base Clock' => '2230 MHz',
                    'Boost Clock' => '2520 MHz',
                    'Memory Speed' => '21 Gbps',
                    'Interface' => 'PCIe 4.0 x16'
                ]
            ],
            [
                'category_id' => $components->id,
                'name' => 'Intel Core i9-13900K',
                'slug' => 'intel-core-i9-13900k',
                'description' => 'CPU Intel thế hệ 13 cao cấp nhất với 24 cores',
                'price' => 15000000,
                'stock' => 10,
                'specifications' => [
                    'Cores' => '24 (8P + 16E)',
                    'Threads' => '32',
                    'Base Clock' => '3.0 GHz',
                    'Boost Clock' => '5.8 GHz',
                    'Socket' => 'LGA 1700'
                ]
            ]
        ];

        // Create accessory products
        $accessoryProducts = [
            [
                'category_id' => $accessories->id,
                'name' => 'Gaming Mechanical Keyboard',
                'slug' => 'gaming-mechanical-keyboard',
                'description' => 'Bàn phím cơ gaming với switch Cherry MX, LED RGB',
                'price' => 2500000,
                'stock' => 25,
                'specifications' => [
                    'Switch' => 'Cherry MX Red',
                    'Backlighting' => 'RGB',
                    'Layout' => 'Full Size',
                    'Connection' => 'USB-C'
                ]
            ],
            [
                'category_id' => $accessories->id,
                'name' => 'Gaming Mouse Pro',
                'slug' => 'gaming-mouse-pro',
                'description' => 'Chuột gaming chuyên nghiệp với sensor cao cấp',
                'price' => 1200000,
                'stock' => 30,
                'specifications' => [
                    'Sensor' => 'PixArt PMW3360',
                    'DPI' => 'Up to 12000',
                    'Buttons' => '7',
                    'Weight' => '85g'
                ]
            ]
        ];

        // Insert all products
        $allProducts = array_merge($gamingProducts, $officeProducts, $laptopProducts, $componentProducts, $accessoryProducts);

        foreach ($allProducts as $product) {
            \App\Models\Product::create($product);
        }

        // Create some sample customers
        $customers = [
            [
                'name' => 'Nguyễn Văn A',
                'email' => 'customer1@example.com',
                'password' => bcrypt('password'),
                'role' => 'customer'
            ],
            [
                'name' => 'Trần Thị B',
                'email' => 'customer2@example.com',
                'password' => bcrypt('password'),
                'role' => 'customer'
            ],
            [
                'name' => 'Lê Văn C',
                'email' => 'customer3@example.com',
                'password' => bcrypt('password'),
                'role' => 'customer'
            ]
        ];

        foreach ($customers as $customer) {
            \App\Models\User::create($customer);
        }

        $this->command->info('Sample data created successfully!');
    }
}

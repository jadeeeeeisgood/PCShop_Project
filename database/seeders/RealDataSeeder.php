<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RealDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data first
        \App\Models\Product::truncate();
        \App\Models\Category::truncate();

        // Real Categories Data
        $categories = [
            [
                'name' => 'Gaming PC',
                'slug' => 'gaming-pc',
            ],
            [
                'name' => 'Office PC',
                'slug' => 'office-pc',
            ],
            [
                'name' => 'Laptop',
                'slug' => 'laptop',
            ],
            [
                'name' => 'PS5',
                'slug' => 'ps5',
                'description' => 'PlayStation 5 (PS5) là máy chơi game thế hệ tiếp theo do Sony phát triển, có phần cứng tiên tiến , trải nghiệm chơi game đắm chìm và thư viện độc quyền mạnh mẽ',
            ],
            [
                'name' => 'Macbook',
                'slug' => 'macbook',
            ],
            [
                'name' => 'Linh kiện',
                'slug' => 'linh-kien',
            ],
            [
                'name' => 'Gaming Gear',
                'slug' => 'gaming-gear',
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }

        // Get category IDs for products
        $gaming_pc = \App\Models\Category::where('slug', 'gaming-pc')->first();
        $office_pc = \App\Models\Category::where('slug', 'office-pc')->first();
        $laptop = \App\Models\Category::where('slug', 'laptop')->first();
        $ps5 = \App\Models\Category::where('slug', 'ps5')->first();
        $macbook = \App\Models\Category::where('slug', 'macbook')->first();
        $linh_kien = \App\Models\Category::where('slug', 'linh-kien')->first();
        $gaming_gear = \App\Models\Category::where('slug', 'gaming-gear')->first();

        // Real Products Data
        $products = [
            [
                'category_id' => $office_pc->id,
                'name' => 'PC văn phòng giá rẻ',
                'slug' => 'pc-van-phong-gia-re',
                'description' => 'Siêu PC',
                'price' => 36000000.00,
                'stock' => 36,
                'is_featured' => false,
                'image_url' => 'http://localhost/img/1760533638_9RIsndDYoh.jpg',
            ],
            [
                'category_id' => $ps5->id,
                'name' => 'PS5 Pro',
                'slug' => 'ps5-pro',
                'description' => 'Siêu PS5',
                'price' => 13300000.00,
                'stock' => 19,
                'is_featured' => true,
                'image_url' => 'http://localhost/img/1760548013_kBH54dTtKw.jpg',
            ],
            [
                'category_id' => $ps5->id,
                'name' => 'PS5 ProMax',
                'slug' => 'ps5-promax',
                'description' => 'Siêu PS5',
                'price' => 13300000.00,
                'stock' => 25,
                'is_featured' => false,
                'image_url' => 'http://localhost/img/1760548047_HBvulcQYbE.jpg',
            ],
            [
                'category_id' => $macbook->id,
                'name' => 'Apple MacBook Pro 13',
                'slug' => 'apple-macbook-pro-13',
                'description' => 'Discover the perfect balance of speed, storage, and portability with the MacBook Pro',
                'price' => 31590000.00,
                'stock' => 63,
                'is_featured' => true,
                'image_url' => 'http://localhost/img/1760631507_Jm7SYlwA3T.jpg',
            ],
            [
                'category_id' => $gaming_gear->id,
                'name' => 'Bàn phím cơ AULA F99 3 Mode Glacier Blue Reaper switch',
                'slug' => 'ban-phim-co-aula-f99-3-mode-glacier-blue-reaper-switch',
                'description' => 'Bàn phím cơ AULA F99 3 Mode Glacier Blue Reaper switch
- Bàn phím cơ gaming 3 mode AULA F99
- Độ bền phím: 60 triệu lần bấm
- Thời gian sử dụng sau khi sạc đầy pin: Khoảng 53 giờ (hiệu ứng ánh sáng mặc định)/ 400 giờ (tất cả đèn tắt)
- Kết nối: 3 mode có dây Type-C & không dây 2.4G & BT
- Màu sắc: Màu xanh dương + trắng + tím đậm
- Keycap: PBT Double-Shot
- Đèn nền: LED RGB
- 16 loại hiệu ứng ánh sáng
- Loại switch: Reaper switch
- Hiệu ứng âm thanh khi gõ phím: Linear
- Hot-Swap 5 pin
- Gasket mount
- Mạch ngược
- Số lượng phím: 99 phím
- Trọng lượng: Khoảng 1132g (không có cáp/bộ thu)/ 1183g (với cáp/bộ thu)
- Điện áp định mức:DC 3.7V (Đầy đủ 4.2V)
- Dung lượng pin:Pin Li-ion có thể sạc lại 8000mAh (2*4000mAh)
- Điện áp/Dòng điện sạc: DC 5V/≦830mA
- Dòng điện định mức: 150mA @3.7V (hiệu ứng ánh sáng mặc định)/20 mA (tắt tất cả đèn)
- Kích thước bàn phím (LxWxH): 390,63 x 146,78 x 42,57 mm
- Hệ điều hành tương thích: WIN XP7/8/10/Android/IOS/MAC
- Phụ kiện kèm theo: Sách hướng dẫn sử dụng + 2 Switch tặng kèm + Dây USB type-C + Dụng cụ thay keycap

Bảo hành : 12 Tháng',
                'price' => 1550000.00,
                'stock' => 27,
                'is_featured' => false,
                'image_url' => 'http://localhost/img/1760634456_koDgxOFctn.png',
            ],
            [
                'category_id' => $gaming_pc->id,
                'name' => 'PC CHƠI GAME HIỆU SUẤT CAO',
                'slug' => 'pc-choi-game-hieu-suat-cao',
                'description' => 'CPU Intel Core i5-12400F (Up To 4.40GHz, 6 Nhân 12 Luồng,18MB Cache, Socket 1700, Alder Lake)- Tray 
Mainboard ASUS B760M -K DDR4
Ram SSTC DDR4 16GB 3200Mhz XMP/EXPO Tản Nhiệt
Ổ cứng SSD SSTC Oceanic Whitetip E130 512GB M.2 2280 PCIe NVMe (Gen 3x4)
Card Màn Hình ZOTAC GAMING GeForce RTX 3060 Twin Edge 12G
NGUỒN MÁY TÍNH AIGO VK650 - 650W (80 PLUS/ ACTIVE PFC/ SINGLE RAIL)
Vỏ Case AIGO C218M BLACK - KÈM 4 FAN ARGB
Tản nhiệt khí Jonsbo CR-1000 RGB',
                'price' => 18990000.00,
                'stock' => 50,
                'is_featured' => false,
                'image_url' => 'http://localhost/img/1760694888_5X5xV0i2Hs.jpg',
            ],
            [
                'category_id' => $gaming_pc->id,
                'name' => 'PC GAMING PERFORMANCE RTX 4060 8GB - i5 12400F',
                'slug' => 'pc-gaming-performance-rtx-4060-8gb-i5-12400f',
                'description' => 'CPU Intel Core i5-12400F (Upto 4.4Ghz, 6 nhân 12 luồng, 18MB Cache, 65W) TRAY 1
Mainboard ASUS B760M-K PRIME DDR4
Ram SSTC DDR4 16GB 3200Mhz XMP/EXPO Tản Nhiệt
Ổ cứng SSD SSTC Oceanic Whitetip E130 512GB M.2 2280 PCIe NVMe (Gen 3x4)
NGUỒN MÁY TÍNH AIGO VK650 - 650W (80 PLUS/ ACTIVE PFC/ SINGLE RAIL)
VGA MSI RTX 4060 VENTUS 2X BLACK 8GB OC
Vỏ Case AIGO C218M BLACK - KÈM 4 FAN ARGB
Tản nhiệt khí JONSBO CR-1000',
                'price' => 21990000.00,
                'stock' => 23,
                'is_featured' => false,
                'image_url' => 'http://localhost/img/1760695106_GumJvxaqUo.jpg',
            ],
            [
                'category_id' => $gaming_pc->id,
                'name' => 'PC HIỆU SUẤT GAMING CAO RTX 4060 - I5 12400F',
                'slug' => 'pc-hieu-suat-gaming-cao-rtx-4060-i5-12400f',
                'description' => 'CPU Intel Core i5-12400F (Upto 4.4Ghz, 6 nhân 12 luồng, 18MB Cache, 65W) TRAY
Mainboard ASUS B760M-K PRIME DDR4
Ram SSTC DDR4 16GB 3200Mhz XMP/EXPO Tản Nhiệt
Ổ cứng SSD SSTC Oceanic Whitetip E130 512GB M.2 2280 PCIe NVMe (Gen 3x4)
NGUỒN MÁY TÍNH AIGO VK650 - 650W (80 PLUS/ ACTIVE PFC/ SINGLE RAIL)
VGA ASUS RTX 4060 DUAL OC 8GB WHITE
Vỏ Case AIGO C218M WHITE - KÈM 4 FAN ARGB
Tản nhiệt khí JONSBO CR-1000',
                'price' => 17280000.00,
                'stock' => 100,
                'is_featured' => false,
                'image_url' => 'http://localhost/img/1760697072_QY5ZAZ3VEo.jpg',
            ],
            [
                'category_id' => $gaming_pc->id,
                'name' => 'PC PV Gaming Cupid M017',
                'slug' => 'pc-pv-gaming-cupid-m017',
                'description' => 'CPU Intel Core i7-14700K
Mainboard Msi PRO Z790-P WIFI DDR5
RAM Corsair 2 x 16GB DDR5 6000MHz
VGA Msi GeForce RTX 5070 12GB GDDR7
SSD Samsung 990 EVO Plus 1TB M.2 2280 PCIe 4.0 x4 NVMe 2.0
PSU MSI MAG A850GL PCIE5 80 PLUS Gold, Full Modular
Tản nhiệt nước Master Liquid 360 CORE SI Black
Case MSI MAG FORGE 320R AIRFLOW',
                'price' => 42990000.00,
                'stock' => 36,
                'is_featured' => true,
                'image_url' => 'http://localhost/img/1760712363_eHKnYWSaAe.jpg',
            ],
            [
                'category_id' => $gaming_pc->id,
                'name' => 'PC PV Gaming Apollo M013',
                'slug' => 'pc-pv-gaming-apollo-m013',
                'description' => 'CPU Intel Core i5-14600K
Mainboard Asus TUF GAMING B760M-PLUS WIFI II DDR5
RAM Adata 1 x 16GB DDR5 6000MHz
VGA Asus GeForce RTX 5060Ti 16GB GDDR7
SSD ADATA LEGEND 860 PCIe Gen4 x4 M.2 2280 1TB
PSU Cooler Master MWE Gold 850 V3 ATX 3.1 - Non Modular
Tản nhiệt nước Master Liquid 360 CORE SI Black
CASE MIK FOCALORS M BLACK (Đen)',
                'price' => 33490000.00,
                'stock' => 359,
                'is_featured' => true,
                'image_url' => 'http://localhost/img/1760712452_ly88Njr5lS.jpg',
            ],
            [
                'category_id' => $ps5->id,
                'name' => 'Đĩa game PS5 Jurassic World Evolution 2',
                'slug' => 'dia-game-ps5-jurassic-world-evolution-2',
                'description' => 'Trò chơi xây dựng và quản lý công viên khủng long với đồ họa 3D sinh động và cốt truyện hấp dẫn.',
                'price' => 900000.00,
                'stock' => 1000,
                'is_featured' => false,
                'image_url' => 'http://localhost/img/1760713427_gPpB0Dds4c.jpg',
            ],
            [
                'category_id' => $ps5->id,
                'name' => 'Tay cầm PS5 Dualsense Wireless Controller The Last Of Us Special Limited Edition',
                'slug' => 'tay-cam-ps5-dualsense-wireless-controller-the-last-of-us-special-limited-edition',
                'description' => 'Tay cầm không dây thiết kế đặc biệt phiên bản The Last Of Us, mang lại trải nghiệm chơi game sống động với nhiều tính năng cảm ứng hiện đại.',
                'price' => 3700000.00,
                'stock' => 1234,
                'is_featured' => true,
                'image_url' => 'http://localhost/img/1760713500_J1moWZtkHu.jpg',
            ],
            [
                'category_id' => $ps5->id,
                'name' => 'Đĩa game PS5 Call of Duty: Black Ops Cold War',
                'slug' => 'dia-game-ps5-call-of-duty-black-ops-cold-war',
                'description' => 'Trò chơi bắn súng góc nhìn thứ nhất mang lại trải nghiệm chiến tranh lạnh chân thực và hấp dẫn.',
                'price' => 900000.00,
                'stock' => 693,
                'is_featured' => true,
                'image_url' => 'http://localhost/img/1760713575_3kF42SjmKI.jpg',
            ],
            [
                'category_id' => $linh_kien->id,
                'name' => 'Intel® Core™ Ultra 5 245K Desktop Processor',
                'slug' => 'intel-core-ultra-5-245k-desktop-processor',
                'description' => 'Bộ vi xử lý Intel thế hệ mới với 14 nhân, hiệu năng mạnh mẽ cho đa tác vụ và chơi game.',
                'price' => 8200000.00,
                'stock' => 300,
                'is_featured' => false,
                'image_url' => 'http://localhost/img/1760714536_lvgLb3AfpG.jpg',
            ],
            [
                'category_id' => $linh_kien->id,
                'name' => 'MD Ryzen™ 9 7950X Desktop Processor',
                'slug' => 'md-ryzen-9-7950x-desktop-processor',
                'description' => 'CPU hiệu năng cao dành cho các ứng dụng chuyên nghiệp và game nặng, với tần số xử lý nhanh và đa nhân.',
                'price' => 15200000.00,
                'stock' => 123,
                'is_featured' => false,
                'image_url' => 'http://localhost/img/1760714684_IC8Ln6I9o8.jpg',
            ],
            [
                'category_id' => $linh_kien->id,
                'name' => 'ASUS ROG STRIX B650-A GAMING WIFI 6E Motherboard',
                'slug' => 'asus-rog-strix-b650-a-gaming-wifi-6e-motherboard',
                'description' => 'Bo mạch chủ hỗ trợ Ryzen 7000 series, DDR5, PCIe 4.0, WiFi 6E, nhiều cổng mở rộng, tối ưu hiệu năng chơi game.',
                'price' => 7200000.00,
                'stock' => 9,
                'is_featured' => false,
                'image_url' => 'http://localhost/img/1760714754_4yF0wm134n.png',
            ],
            [
                'category_id' => $linh_kien->id,
                'name' => 'CORSAIR Vengeance RGB 32GB (2 x 16GB) DDR5 6400 Desktop Memory',
                'slug' => 'corsair-vengeance-rgb-32gb-2-x-16gb-ddr5-6400-desktop-memory',
                'description' => 'Bộ nhớ RAM DDR5 tốc độ cao 6400MHz, hỗ trợ đa nhiệm và chơi game mượt mà.',
                'price' => 4000000.00,
                'stock' => 3000,
                'is_featured' => false,
                'image_url' => 'http://localhost/img/1760714807_E8OAt3keTd.jpg',
            ],
            [
                'category_id' => $office_pc->id,
                'name' => 'PC E-Power Office 30',
                'slug' => 'pc-e-power-office-30',
                'description' => 'CPU AMD Ryzen 3 3200G, RAM 16GB, SSD 256GB, nguồn 450W. Phù hợp cho công việc văn phòng cơ bản, đa nhiệm nhẹ nhàng.',
                'price' => 6240000.00,
                'stock' => 99,
                'is_featured' => true,
                'image_url' => 'http://localhost/img/1760714948_Gm7Jhy7aUf.png',
            ],
            [
                'category_id' => $laptop->id,
                'name' => 'HP Pavilion 16-af0052TU',
                'slug' => 'hp-pavilion-16-af0052tu',
                'description' => 'Laptop màn hình 16 inch lớn, thiết kế gọn nhẹ, cấu hình Intel Core i5 thế hệ mới, RAM 8GB, SSD 512GB, phù hợp đa nhiệm và làm việc văn phòng hiệu quả.',
                'price' => 16000000.00,
                'stock' => 51,
                'is_featured' => true,
                'image_url' => 'http://localhost/img/1760715062_LU7XREKpyO.jpg',
            ],
            [
                'category_id' => $laptop->id,
                'name' => 'Dell Inspiron 14 7440 2in1',
                'slug' => 'dell-inspiron-14-7440-2in1',
                'description' => 'Laptop kiêm tablet với thiết kế xoay 360 độ, cấu hình Intel Core i5 cao cấp, RAM 8GB, SSD 256GB, tiện lợi cho công việc linh hoạt và di chuyển.',
                'price' => 17000000.00,
                'stock' => 78,
                'is_featured' => true,
                'image_url' => 'http://localhost/img/1760715114_9SbK631Nxg.jpg',
            ],
            [
                'category_id' => $laptop->id,
                'name' => 'Acer Aspire 3 A315',
                'slug' => 'acer-aspire-3-a315',
                'description' => 'Laptop phổ thông giá tốt, Intel Core i5, RAM 8GB, SSD 256GB, màn hình 15.6 inch Full HD, đáp ứng ổn cho nhu cầu văn phòng và học tập.',
                'price' => 11000000.00,
                'stock' => 555,
                'is_featured' => false,
                'image_url' => 'http://localhost/img/1760715164_TphAJgMm88.jpg',
            ],
            [
                'category_id' => $gaming_gear->id,
                'name' => 'Màn hình gaming Asus TUF Gaming VG27AQM5A (27 inch / QHD / IPS / 300Hz / 0.3ms)',
                'slug' => 'man-hinh-gaming-asus-tuf-gaming-vg27aqm5a-27-inch-qhd-ips-300hz-03ms',
                'description' => 'Kích thước màn hình: 27 inch, tỉ lệ 16:9.
 Độ phân giải: 2560x1440, tần số quét tối đa 300Hz.
 Thời gian phản hồi: 0.3ms (GTG).
 Độ sáng: 300cd/㎡, độ tương phản 1300:1.
 Gam màu sRGB: 130%, 16.7 triệu màu.
 Cổng kết nối: DP 1.4, HDMI 2.1, USB-C.',
                'price' => 7280000.00,
                'stock' => 97,
                'is_featured' => true,
                'image_url' => 'http://localhost/img/1760715260_kBLo98OLgl.jpg',
            ],
        ];

        foreach ($products as $product) {
            // Ensure all products are active
            $product['is_active'] = true;
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

        $this->command->info('Real data seeded successfully!');
    }
}

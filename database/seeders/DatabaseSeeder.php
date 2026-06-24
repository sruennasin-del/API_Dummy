<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin User
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'is_admin' => true,
            ]
        );

        // 2. Seed Default Categories
        $categories = [
            [
                'name' => 'Smartphones',
                'slug' => 'smartphones',
                'description' => 'Latest mobile phones and devices',
                'status' => 'active',
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'description' => 'Fittings, gadgets, and additional components',
                'status' => 'active',
            ],
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Consumer electronics, appliances, and gadgets',
                'status' => 'active',
            ],
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'description' => 'Trendy clothing, shoes, and apparel',
                'status' => 'active',
            ],
            [
                'name' => 'Kitchen Accessories',
                'slug' => 'kitchen-accessories',
                'description' => 'Modern tools and utensils for the kitchen',
                'status' => 'active',
            ],
            [
                'name' => 'Beauty',
                'slug' => 'beauty',
                'description' => 'Cosmetics, makeup, and skin care products',
                'status' => 'active',
            ],
            [
                'name' => 'Sports Accessories',
                'slug' => 'sports-accessories',
                'description' => 'Gear and accessories for athletics and gym',
                'status' => 'active',
            ],
            [
                'name' => 'Furniture',
                'slug' => 'furniture',
                'description' => 'Elegant and modern home and office furniture',
                'status' => 'active',
            ],
            [
                'name' => 'Groceries',
                'slug' => 'groceries',
                'description' => 'Daily essentials and food items',
                'status' => 'active',
            ]
        ];

        foreach ($categories as $category) {
            \App\Models\Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        // 3. Seed Default Colors
        $colors = [
            ['name' => 'Crimson Red', 'code' => '#EF4444', 'status' => 'active'],
            ['name' => 'Royal Blue', 'code' => '#3B82F6', 'status' => 'active'],
            ['name' => 'Emerald Green', 'code' => '#10B981', 'status' => 'active'],
            ['name' => 'Amber Gold', 'code' => '#F59E0B', 'status' => 'active'],
            ['name' => 'Charcoal Black', 'code' => '#1F2937', 'status' => 'active'],
            ['name' => 'Pure White', 'code' => '#FFFFFF', 'status' => 'active'],
        ];

        foreach ($colors as $color) {
            \App\Models\Color::firstOrCreate(
                ['code' => $color['code']],
                $color
            );
        }

        // 4. Seed Default Sizes
        $sizes = [
            ['name' => 'S', 'status' => 'active'],
            ['name' => 'M', 'status' => 'active'],
            ['name' => 'L', 'status' => 'active'],
            ['name' => 'XL', 'status' => 'active'],
            ['name' => 'XXL', 'status' => 'active'],
        ];

        foreach ($sizes as $size) {
            \App\Models\Size::firstOrCreate(
                ['name' => $size['name']],
                $size
            );
        }

        // 5. Seed Default Products
        $smartphoneCat = \App\Models\Category::where('slug', 'smartphones')->first();
        $accessoriesCat = \App\Models\Category::where('slug', 'accessories')->first();
        $fashionCat = \App\Models\Category::where('slug', 'fashion')->first();
        $electronicsCat = \App\Models\Category::where('slug', 'electronics')->first();

        // Get colors
        $colors = \App\Models\Color::where('status', 'active')->get();
        // Get sizes
        $sizes = \App\Models\Size::where('status', 'active')->get();

        // Define 50 products data
        $productTemplates = [
            // Smartphones (13)
            [
                'title' => 'iPhone 15 Pro Max',
                'category_id' => $smartphoneCat?->id,
                'price' => 1299.00,
                'image' => '/images/demo_phone.png',
                'details' => ['/images/demo_phone_detail_1.png', '/images/demo_phone_detail_2.png', '/images/demo_phone_detail_3.png'],
                'description' => 'The iPhone 15 Pro Max features a titanium design, a powerful A17 Pro chip, and a custom Action button.',
            ],
            [
                'title' => 'Samsung Galaxy S24 Ultra',
                'category_id' => $smartphoneCat?->id,
                'price' => 1199.99,
                'image' => '/images/demo_phone.png',
                'details' => ['/images/demo_phone_detail_1.png', '/images/demo_phone_detail_2.png', '/images/demo_phone_detail_3.png'],
                'description' => 'Galaxy S24 Ultra introduces Galaxy AI, a titanium exterior, and an enhanced quad telephoto system.',
            ],
            [
                'title' => 'Google Pixel 8 Pro',
                'category_id' => $smartphoneCat?->id,
                'price' => 999.00,
                'image' => '/images/demo_phone.png',
                'details' => ['/images/demo_phone_detail_1.png', '/images/demo_phone_detail_2.png', '/images/demo_phone_detail_3.png'],
                'description' => 'The all-pro phone engineered by Google. It has the best of Google AI, the most advanced Pixel Camera yet.',
            ],
            [
                'title' => 'OnePlus 12',
                'category_id' => $smartphoneCat?->id,
                'price' => 799.99,
                'image' => '/images/demo_phone.png',
                'details' => ['/images/demo_phone_detail_1.png', '/images/demo_phone_detail_2.png', '/images/demo_phone_detail_3.png'],
                'description' => 'Redefined flagship specifications. Empowered by Snapdragon 8 Gen 3 with up to 16GB of RAM.',
            ],
            [
                'title' => 'Xiaomi 14 Ultra',
                'category_id' => $smartphoneCat?->id,
                'price' => 1099.00,
                'image' => '/images/demo_phone.png',
                'details' => ['/images/demo_phone_detail_1.png', '/images/demo_phone_detail_2.png', '/images/demo_phone_detail_3.png'],
                'description' => 'Leica Summilux optical lens, 1-inch sensor, and legendary imaging capabilities.',
            ],
            [
                'title' => 'Sony Xperia 1 VI',
                'category_id' => $smartphoneCat?->id,
                'price' => 1299.99,
                'image' => '/images/demo_phone.png',
                'details' => ['/images/demo_phone_detail_1.png', '/images/demo_phone_detail_2.png', '/images/demo_phone_detail_3.png'],
                'description' => 'Uncompromising camera capabilities, high-fidelity audio, and a premium 19.5:9 display.',
            ],
            [
                'title' => 'Asus ROG Phone 8',
                'category_id' => $smartphoneCat?->id,
                'price' => 999.99,
                'image' => '/images/demo_phone.png',
                'details' => ['/images/demo_phone_detail_1.png', '/images/demo_phone_detail_2.png', '/images/demo_phone_detail_3.png'],
                'description' => 'The ultimate gaming smartphone with advanced thermal cooling and air triggers.',
            ],
            [
                'title' => 'Motorola Edge 50 Ultra',
                'category_id' => $smartphoneCat?->id,
                'price' => 849.00,
                'image' => '/images/demo_phone.png',
                'details' => ['/images/demo_phone_detail_1.png', '/images/demo_phone_detail_2.png', '/images/demo_phone_detail_3.png'],
                'description' => 'Designed with real wood and vegan leather backing, featuring Pantone-validated display and cameras.',
            ],
            [
                'title' => 'Nothing Phone (2)',
                'category_id' => $smartphoneCat?->id,
                'price' => 599.00,
                'image' => '/images/demo_phone.png',
                'details' => ['/images/demo_phone_detail_1.png', '/images/demo_phone_detail_2.png', '/images/demo_phone_detail_3.png'],
                'description' => 'A new way to interact. Unique transparent design featuring customizable Glyph Interface.',
            ],
            [
                'title' => 'Huawei Pura 70 Ultra',
                'category_id' => $smartphoneCat?->id,
                'price' => 1399.00,
                'image' => '/images/demo_phone.png',
                'details' => ['/images/demo_phone_detail_1.png', '/images/demo_phone_detail_2.png', '/images/demo_phone_detail_3.png'],
                'description' => 'Ultra speed snapshot, retractable camera lens mechanism, and Kunlun glass durability.',
            ],
            [
                'title' => 'RealMe GT 6',
                'category_id' => $smartphoneCat?->id,
                'price' => 649.00,
                'image' => '/images/demo_phone.png',
                'details' => ['/images/demo_phone_detail_1.png', '/images/demo_phone_detail_2.png', '/images/demo_phone_detail_3.png'],
                'description' => 'AI flagship killer. Features 6000 nits ultra bright display and dual-cell charging.',
            ],
            [
                'title' => 'Oppo Find X7 Ultra',
                'category_id' => $smartphoneCat?->id,
                'price' => 949.00,
                'image' => '/images/demo_phone.png',
                'details' => ['/images/demo_phone_detail_1.png', '/images/demo_phone_detail_2.png', '/images/demo_phone_detail_3.png'],
                'description' => 'World first quad-main camera system with dual periscope cameras, tuned by Hasselblad.',
            ],
            [
                'title' => 'Vivo X100 Pro',
                'category_id' => $smartphoneCat?->id,
                'price' => 899.00,
                'image' => '/images/demo_phone.png',
                'details' => ['/images/demo_phone_detail_1.png', '/images/demo_phone_detail_2.png', '/images/demo_phone_detail_3.png'],
                'description' => 'Zeiss APO telephoto lens, professional imaging chip V3, and premium finish.',
            ],

            // Accessories/Watches (12)
            [
                'title' => 'Smart Watch Series 9',
                'category_id' => $accessoriesCat?->id,
                'price' => 399.00,
                'image' => '/images/demo_watch.png',
                'details' => ['/images/demo_watch_detail_1.png', '/images/demo_watch_detail_2.png', '/images/demo_watch_detail_3.png'],
                'description' => 'Powerful sensors, advanced fitness tracking, and a bright always-on display.',
            ],
            [
                'title' => 'Galaxy Watch 6 Classic',
                'category_id' => $accessoriesCat?->id,
                'price' => 349.99,
                'image' => '/images/demo_watch.png',
                'details' => ['/images/demo_watch_detail_1.png', '/images/demo_watch_detail_2.png', '/images/demo_watch_detail_3.png'],
                'description' => 'Classic rotating bezel design, comprehensive sleep coaching, and BIA body analysis.',
            ],
            [
                'title' => 'Pixel Watch 2',
                'category_id' => $accessoriesCat?->id,
                'price' => 299.00,
                'image' => '/images/demo_watch.png',
                'details' => ['/images/demo_watch_detail_1.png', '/images/demo_watch_detail_2.png', '/images/demo_watch_detail_3.png'],
                'description' => 'Help by Google. Health by Fitbit. Engineered with stress tracking and safety check.',
            ],
            [
                'title' => 'Garmin Fenix 7 Pro',
                'category_id' => $accessoriesCat?->id,
                'price' => 799.99,
                'image' => '/images/demo_watch.png',
                'details' => ['/images/demo_watch_detail_1.png', '/images/demo_watch_detail_2.png', '/images/demo_watch_detail_3.png'],
                'description' => 'Ultimate multisport GPS smartwatch with solar charging, built-in flashlight, and mapping.',
            ],
            [
                'title' => 'Apple Watch Ultra 2',
                'category_id' => $accessoriesCat?->id,
                'price' => 799.00,
                'image' => '/images/demo_watch.png',
                'details' => ['/images/demo_watch_detail_1.png', '/images/demo_watch_detail_2.png', '/images/demo_watch_detail_3.png'],
                'description' => 'The ultimate sports and adventure watch. Features a rugged titanium case and custom actions.',
            ],
            [
                'title' => 'Fitbit Sense 2',
                'category_id' => $accessoriesCat?->id,
                'price' => 229.95,
                'image' => '/images/demo_watch.png',
                'details' => ['/images/demo_watch_detail_1.png', '/images/demo_watch_detail_2.png', '/images/demo_watch_detail_3.png'],
                'description' => 'Advanced health and fitness smartwatch with all-day body response tracking.',
            ],
            [
                'title' => 'Amazfit GTR 4',
                'category_id' => $accessoriesCat?->id,
                'price' => 199.99,
                'image' => '/images/demo_watch.png',
                'details' => ['/images/demo_watch_detail_1.png', '/images/demo_watch_detail_2.png', '/images/demo_watch_detail_3.png'],
                'description' => 'Strong and precise GPS tracking, circular classic design, and 14-day battery life.',
            ],
            [
                'title' => 'Fossil Gen 6',
                'category_id' => $accessoriesCat?->id,
                'price' => 229.00,
                'image' => '/images/demo_watch.png',
                'details' => ['/images/demo_watch_detail_1.png', '/images/demo_watch_detail_2.png', '/images/demo_watch_detail_3.png'],
                'description' => 'Powered with Wear OS, fast charging, and retro round dial aesthetics.',
            ],
            [
                'title' => 'Withings ScanWatch 2',
                'category_id' => $accessoriesCat?->id,
                'price' => 349.95,
                'image' => '/images/demo_watch.png',
                'details' => ['/images/demo_watch_detail_1.png', '/images/demo_watch_detail_2.png', '/images/demo_watch_detail_3.png'],
                'description' => 'Premium hybrid smartwatch with ECG, SPO2, body temperature tracking, and analog hands.',
            ],
            [
                'title' => 'Huawei Watch GT 4',
                'category_id' => $accessoriesCat?->id,
                'price' => 249.00,
                'image' => '/images/demo_watch.png',
                'details' => ['/images/demo_watch_detail_1.png', '/images/demo_watch_detail_2.png', '/images/demo_watch_detail_3.png'],
                'description' => 'Fashion forward design with scientific calorie tracker and up to 14 days battery.',
            ],
            [
                'title' => 'Tag Heuer Connected',
                'category_id' => $accessoriesCat?->id,
                'price' => 1800.00,
                'image' => '/images/demo_watch.png',
                'details' => ['/images/demo_watch_detail_1.png', '/images/demo_watch_detail_2.png', '/images/demo_watch_detail_3.png'],
                'description' => 'Swiss watchmaking excellence blended with high-tech digital features and premium materials.',
            ],
            [
                'title' => 'Suunto Race',
                'category_id' => $accessoriesCat?->id,
                'price' => 449.00,
                'image' => '/images/demo_watch.png',
                'details' => ['/images/demo_watch_detail_1.png', '/images/demo_watch_detail_2.png', '/images/demo_watch_detail_3.png'],
                'description' => 'Performance sports watch with AMOLED display, detailed offline maps, and training metrics.',
            ],

            // Fashion/Shoes (13)
            [
                'title' => 'Nike Air Max 90',
                'category_id' => $fashionCat?->id,
                'price' => 130.00,
                'image' => '/images/demo_shoes.png',
                'details' => ['/images/demo_shoes_detail_1.png', '/images/demo_shoes_detail_2.png', '/images/demo_shoes_detail_3.png'],
                'description' => 'Nothing as fly, nothing as comfortable, nothing as proven. The Nike Air Max 90 stays true to its OG roots.',
            ],
            [
                'title' => 'Adidas Ultraboost Light',
                'category_id' => $fashionCat?->id,
                'price' => 190.00,
                'image' => '/images/demo_shoes.png',
                'details' => ['/images/demo_shoes_detail_1.png', '/images/demo_shoes_detail_2.png', '/images/demo_shoes_detail_3.png'],
                'description' => 'Experience epic energy with the new Ultraboost Light, the lightest Ultraboost ever made.',
            ],
            [
                'title' => 'Puma Velocity Nitro 3',
                'category_id' => $fashionCat?->id,
                'price' => 120.00,
                'image' => '/images/demo_shoes.png',
                'details' => ['/images/demo_shoes_detail_1.png', '/images/demo_shoes_detail_2.png', '/images/demo_shoes_detail_3.png'],
                'description' => 'Featuring NITRO FOAM for premium cushion and responsiveness, perfect for any runner.',
            ],
            [
                'title' => 'New Balance 1906R',
                'category_id' => $fashionCat?->id,
                'price' => 150.00,
                'image' => '/images/demo_shoes.png',
                'details' => ['/images/demo_shoes_detail_1.png', '/images/demo_shoes_detail_2.png', '/images/demo_shoes_detail_3.png'],
                'description' => 'Tech runner design inspired by 2000s running styles, offering modern comfort and durability.',
            ],
            [
                'title' => 'Reebok Club C 85',
                'category_id' => $fashionCat?->id,
                'price' => 85.00,
                'image' => '/images/demo_shoes.png',
                'details' => ['/images/demo_shoes_detail_1.png', '/images/demo_shoes_detail_2.png', '/images/demo_shoes_detail_3.png'],
                'description' => 'Clean minimalist court shoes with soft leather uppers, bringing timeless retro vibes.',
            ],
            [
                'title' => 'ASICS Gel-Kayano 30',
                'category_id' => $fashionCat?->id,
                'price' => 160.00,
                'image' => '/images/demo_shoes.png',
                'details' => ['/images/demo_shoes_detail_1.png', '/images/demo_shoes_detail_2.png', '/images/demo_shoes_detail_3.png'],
                'description' => 'Maximum support and ultimate comfort from 5km to full marathons. 4D Guidance System technology.',
            ],
            [
                'title' => 'Brooks Ghost 16',
                'category_id' => $fashionCat?->id,
                'price' => 140.00,
                'image' => '/images/demo_shoes.png',
                'details' => ['/images/demo_shoes_detail_1.png', '/images/demo_shoes_detail_2.png', '/images/demo_shoes_detail_3.png'],
                'description' => 'Smooth rides and soft landings. Highly breathable upper with eco-friendly DNA Loft v3 foam.',
            ],
            [
                'title' => 'Saucony Ride 17',
                'category_id' => $fashionCat?->id,
                'price' => 140.00,
                'image' => '/images/demo_shoes.png',
                'details' => ['/images/demo_shoes_detail_1.png', '/images/demo_shoes_detail_2.png', '/images/demo_shoes_detail_3.png'],
                'description' => 'A completely upgraded daily trainer with PWRRUN+ foam cushioning for bounce and comfort.',
            ],
            [
                'title' => 'Hoka Clifton 9',
                'category_id' => $fashionCat?->id,
                'price' => 145.00,
                'image' => '/images/demo_shoes.png',
                'details' => ['/images/demo_shoes_detail_1.png', '/images/demo_shoes_detail_2.png', '/images/demo_shoes_detail_3.png'],
                'description' => 'The ultimate everyday cushion runner. Lighter and more cushioned than ever before.',
            ],
            [
                'title' => 'Under Armour Phantom 3',
                'category_id' => $fashionCat?->id,
                'price' => 130.00,
                'image' => '/images/demo_shoes.png',
                'details' => ['/images/demo_shoes_detail_1.png', '/images/demo_shoes_detail_2.png', '/images/demo_shoes_detail_3.png'],
                'description' => 'UA IntelliKnit upper for form-fitting comfort, combined with ultra responsive UA HOVR technology.',
            ],
            [
                'title' => 'Salomon Speedcross 6',
                'category_id' => $fashionCat?->id,
                'price' => 150.00,
                'image' => '/images/demo_shoes.png',
                'details' => ['/images/demo_shoes_detail_1.png', '/images/demo_shoes_detail_2.png', '/images/demo_shoes_detail_3.png'],
                'description' => 'Legendary trail runner. Grip is even better and mud evacuation is faster than predecessors.',
            ],
            [
                'title' => 'On Cloudmonster 2',
                'category_id' => $fashionCat?->id,
                'price' => 180.00,
                'image' => '/images/demo_shoes.png',
                'details' => ['/images/demo_shoes_detail_1.png', '/images/demo_shoes_detail_2.png', '/images/demo_shoes_detail_3.png'],
                'description' => 'Monster CloudTec cushioning for extreme energy return and maximum cushioning.',
            ],
            [
                'title' => 'Fjallraven Kanken Backpack',
                'category_id' => $fashionCat?->id,
                'price' => 89.95,
                'image' => '/images/demo_shoes.png', // Fallback to shoes main for fashion
                'details' => ['/images/demo_shoes_detail_1.png', '/images/demo_shoes_detail_2.png', '/images/demo_shoes_detail_3.png'],
                'description' => 'Classic Scandinavian style backpack, highly durable water-resistant fabric.',
            ],

            // Electronics/Headphones (12)
            [
                'title' => 'Sony WH-1000XM5 Headphones',
                'category_id' => $electronicsCat?->id,
                'price' => 398.00,
                'image' => '/images/demo_headphones.png',
                'details' => ['/images/demo_headphones_detail_1.png', '/images/demo_headphones_detail_2.png', '/images/demo_headphones_detail_3.png'],
                'description' => 'Industry-leading noise cancellation, exceptional call quality, and sleek modern design.',
            ],
            [
                'title' => 'Bose QuietComfort Ultra',
                'category_id' => $electronicsCat?->id,
                'price' => 429.00,
                'image' => '/images/demo_headphones.png',
                'details' => ['/images/demo_headphones_detail_1.png', '/images/demo_headphones_detail_2.png', '/images/demo_headphones_detail_3.png'],
                'description' => 'World-class noise cancellation, breakthrough spatialized audio, and ultimate comfort.',
            ],
            [
                'title' => 'Sennheiser Momentum 4',
                'category_id' => $electronicsCat?->id,
                'price' => 349.95,
                'image' => '/images/demo_headphones.png',
                'details' => ['/images/demo_headphones_detail_1.png', '/images/demo_headphones_detail_2.png', '/images/demo_headphones_detail_3.png'],
                'description' => 'Outstanding sound signature, customized sound profiles, and massive 60-hour battery life.',
            ],
            [
                'title' => 'Apple AirPods Max',
                'category_id' => $electronicsCat?->id,
                'price' => 549.00,
                'image' => '/images/demo_headphones.png',
                'details' => ['/images/demo_headphones_detail_1.png', '/images/demo_headphones_detail_2.png', '/images/demo_headphones_detail_3.png'],
                'description' => 'A perfect balance of exhilarating high-fidelity audio and the effortless magic of AirPods.',
            ],
            [
                'title' => 'Bowers & Wilkins Px7 S2',
                'category_id' => $electronicsCat?->id,
                'price' => 399.00,
                'image' => '/images/demo_headphones.png',
                'details' => ['/images/demo_headphones_detail_1.png', '/images/demo_headphones_detail_2.png', '/images/demo_headphones_detail_3.png'],
                'description' => 'High-resolution audio with custom-designed drive units, premium fit and finish.',
            ],
            [
                'title' => 'JBL Tour One M2',
                'category_id' => $electronicsCat?->id,
                'price' => 299.95,
                'image' => '/images/demo_headphones.png',
                'details' => ['/images/demo_headphones_detail_1.png', '/images/demo_headphones_detail_2.png', '/images/demo_headphones_detail_3.png'],
                'description' => 'True Adaptive Noise Cancelling, smart ambient technology, and crystal clear call quality.',
            ],
            [
                'title' => 'Audio-Technica ATH-M50xBT2',
                'category_id' => $electronicsCat?->id,
                'price' => 199.00,
                'image' => '/images/demo_headphones.png',
                'details' => ['/images/demo_headphones_detail_1.png', '/images/demo_headphones_detail_2.png', '/images/demo_headphones_detail_3.png'],
                'description' => 'Studio-quality legendary sonic signature in a convenient, portable wireless design.',
            ],
            [
                'title' => 'Shure AONIC 50',
                'category_id' => $electronicsCat?->id,
                'price' => 299.00,
                'image' => '/images/demo_headphones.png',
                'details' => ['/images/demo_headphones_detail_1.png', '/images/demo_headphones_detail_2.png', '/images/demo_headphones_detail_3.png'],
                'description' => 'Premium studio-quality sound with adjustable active noise cancellation.',
            ],
            [
                'title' => 'Jabra Elite 85h',
                'category_id' => $electronicsCat?->id,
                'price' => 249.99,
                'image' => '/images/demo_headphones.png',
                'details' => ['/images/demo_headphones_detail_1.png', '/images/demo_headphones_detail_2.png', '/images/demo_headphones_detail_3.png'],
                'description' => 'SmartSound technology adjusts audio to your surroundings, water resistant design.',
            ],
            [
                'title' => 'Beyerdynamic Amiron Wireless',
                'category_id' => $electronicsCat?->id,
                'price' => 599.00,
                'image' => '/images/demo_headphones.png',
                'details' => ['/images/demo_headphones_detail_1.png', '/images/demo_headphones_detail_2.png', '/images/demo_headphones_detail_3.png'],
                'description' => 'High-end Tesla drivers, customized sound via MIY app, handmade in Germany.',
            ],
            [
                'title' => 'Marshall Monitor II A.N.C.',
                'category_id' => $electronicsCat?->id,
                'price' => 319.99,
                'image' => '/images/demo_headphones.png',
                'details' => ['/images/demo_headphones_detail_1.png', '/images/demo_headphones_detail_2.png', '/images/demo_headphones_detail_3.png'],
                'description' => 'Iconic black vinyl finish, custom-tuned dynamic drivers, and high-performance ANC.',
            ],
            [
                'title' => 'Silicon Power 256GB SSD',
                'category_id' => $electronicsCat?->id,
                'price' => 109.00,
                'image' => '/images/demo_headphones.png', // Fallback to headphones main for electronics
                'details' => ['/images/demo_headphones_detail_1.png', '/images/demo_headphones_detail_2.png', '/images/demo_headphones_detail_3.png'],
                'description' => 'High-speed solid-state drive for computing reliability and fast loading times.',
            ],
        ];

        foreach ($productTemplates as $template) {
            $slug = \Illuminate\Support\Str::slug($template['title']);
            
            // Check if product exists or create it
            $product = \App\Models\Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $template['title'],
                    'category_id' => $template['category_id'],
                    'price' => $template['price'],
                    'stock' => rand(0, 100),
                    'sales' => rand(10, 500),
                    'rating' => round(4.0 + (rand(0, 10) / 10), 2),
                    'description' => $template['description'],
                    'image' => $template['image'],
                    'status' => 'active',
                ]
            );

            // Sync random colors (1 to 3)
            $randomColors = $colors->random(rand(1, min(3, $colors->count())))->pluck('id')->toArray();
            $product->colors()->sync($randomColors);

            // Sync random sizes (0 to 2) - sizes remain nullable/optional
            $numSizes = rand(0, min(2, $sizes->count()));
            if ($numSizes > 0) {
                $randomSizes = $sizes->random($numSizes)->pluck('id')->toArray();
                $product->sizes()->sync($randomSizes);
            } else {
                $product->sizes()->sync([]);
            }

            // Sync detail gallery images (delete old details, save new details)
            $product->images()->delete();
            foreach ($template['details'] as $detailPath) {
                \App\Models\ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $detailPath,
                ]);
            }
        }
    }
}

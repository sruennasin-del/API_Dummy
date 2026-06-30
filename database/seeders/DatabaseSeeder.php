<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\MainCategory;
use App\Models\Color;
use App\Models\Size;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin User
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'is_admin' => true,
            ]
        );

        // 2. Seed Default Colors (Tailored for Clothing Shop)
        $colors = [
            ['name' => 'Charcoal Black', 'code' => '#1F2937', 'status' => 'active'],
            ['name' => 'Pure White', 'code' => '#FFFFFF', 'status' => 'active'],
            ['name' => 'Heather Grey', 'code' => '#9CA3AF', 'status' => 'active'],
            ['name' => 'Navy Blue', 'code' => '#1E3A8A', 'status' => 'active'],
            ['name' => 'Dusty Pink', 'code' => '#F472B6', 'status' => 'active'],
            ['name' => 'Khaki Beige', 'code' => '#D8BC97', 'status' => 'active'],
            ['name' => 'Olive Green', 'code' => '#3F4E3F', 'status' => 'active'],
            ['name' => 'Crimson Red', 'code' => '#B91C1C', 'status' => 'active'],
        ];

        foreach ($colors as $color) {
            Color::firstOrCreate(
                ['code' => $color['code']],
                $color
            );
        }

        // 3. Seed Default Sizes
        $sizes = [
            ['name' => 'XS', 'status' => 'active'],
            ['name' => 'S', 'status' => 'active'],
            ['name' => 'M', 'status' => 'active'],
            ['name' => 'L', 'status' => 'active'],
            ['name' => 'XL', 'status' => 'active'],
            ['name' => 'XXL', 'status' => 'active'],
        ];

        foreach ($sizes as $size) {
            Size::firstOrCreate(
                ['name' => $size['name']],
                $size
            );
        }

        // 4. Seed Main Categories
        $mainCategoriesData = [
            [
                'name' => "Men's Wear",
                'slug' => 'mens-wear',
                'description' => 'Classic and modern clothing for men.',
                'image' => 'https://images.unsplash.com/photo-1490367532201-b9bc1dc483f6?w=800&auto=format&fit=crop',
                'status' => 'active',
            ],
            [
                'name' => "Women's Wear",
                'slug' => 'womens-wear',
                'description' => 'Elegant, trendy, and comfortable clothing for women.',
                'image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=800&auto=format&fit=crop',
                'status' => 'active',
            ],
            [
                'name' => 'Kids & Baby',
                'slug' => 'kids-baby',
                'description' => 'Soft, durable, and playful apparel for children and infants.',
                'image' => 'https://images.unsplash.com/photo-1519457431-44ccd64a579b?w=800&auto=format&fit=crop',
                'status' => 'active',
            ],
            [
                'name' => 'Shoes & Accessories',
                'slug' => 'shoes-accessories',
                'description' => 'Complete the look with premium footwear, bags, and items.',
                'image' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=800&auto=format&fit=crop',
                'status' => 'active',
            ],
        ];

        $mainCategories = [];
        foreach ($mainCategoriesData as $mcat) {
            $mainCategories[$mcat['slug']] = MainCategory::updateOrCreate(
                ['slug' => $mcat['slug']],
                $mcat
            );
        }

        // 5. Seed Sub-Categories (linked to Main Categories)
        $subCategoriesData = [
            // Men's Wear Subcategories
            [
                'name' => 'Shirts & Tees',
                'slug' => 'mens-shirts',
                'description' => 'Casual t-shirts, polo shirts, and formal dress shirts.',
                'main_category_id' => $mainCategories['mens-wear']->id,
                'image' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=600&auto=format&fit=crop',
                'status' => 'active',
            ],
            [
                'name' => 'Pants & Denim',
                'slug' => 'mens-pants',
                'description' => 'Jeans, chinos, trousers, and shorts.',
                'main_category_id' => $mainCategories['mens-wear']->id,
                'image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=600&auto=format&fit=crop',
                'status' => 'active',
            ],
            [
                'name' => 'Jackets & Coats',
                'slug' => 'mens-jackets',
                'description' => 'Hoodies, bombers, parkas, and coats.',
                'main_category_id' => $mainCategories['mens-wear']->id,
                'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=600&auto=format&fit=crop',
                'status' => 'active',
            ],

            // Women's Wear Subcategories
            [
                'name' => 'Dresses & Jumpsuits',
                'slug' => 'womens-dresses',
                'description' => 'Summer dresses, evening gowns, and stylish jumpsuits.',
                'main_category_id' => $mainCategories['womens-wear']->id,
                'image' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=600&auto=format&fit=crop',
                'status' => 'active',
            ],
            [
                'name' => 'Tops & Knitwear',
                'slug' => 'womens-tops',
                'description' => 'Blouses, t-shirts, sweaters, and cardigans.',
                'main_category_id' => $mainCategories['womens-wear']->id,
                'image' => 'https://images.unsplash.com/photo-1548624149-f7b3166885b8?w=600&auto=format&fit=crop',
                'status' => 'active',
            ],
            [
                'name' => 'Jeans & Skirts',
                'slug' => 'womens-pants',
                'description' => 'Denim jeans, high-waisted pants, and skirts.',
                'main_category_id' => $mainCategories['womens-wear']->id,
                'image' => 'https://images.unsplash.com/photo-1582562124811-c09040d0a901?w=600&auto=format&fit=crop',
                'status' => 'active',
            ],

            // Kids & Baby Subcategories
            [
                'name' => 'Boys Clothing',
                'slug' => 'boys-clothing',
                'description' => 'Apparel designed for active boys.',
                'main_category_id' => $mainCategories['kids-baby']->id,
                'image' => 'https://images.unsplash.com/photo-1519457431-44ccd64a579b?w=600&auto=format&fit=crop',
                'status' => 'active',
            ],
            [
                'name' => 'Girls Clothing',
                'slug' => 'girls-clothing',
                'description' => 'Pretty dresses, leggings, and cardigans for girls.',
                'main_category_id' => $mainCategories['kids-baby']->id,
                'image' => 'https://images.unsplash.com/photo-1503919545889-aef636e10ad4?w=600&auto=format&fit=crop',
                'status' => 'active',
            ],
            [
                'name' => 'Baby Rompers',
                'slug' => 'baby-rompers',
                'description' => 'Super soft, 100% organic cotton rompers and onesies.',
                'main_category_id' => $mainCategories['kids-baby']->id,
                'image' => 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=600&auto=format&fit=crop',
                'status' => 'active',
            ],

            // Shoes & Accessories Subcategories
            [
                'name' => 'Footwear',
                'slug' => 'footwear',
                'description' => 'Sneakers, boots, sandals, and formal shoes.',
                'main_category_id' => $mainCategories['shoes-accessories']->id,
                'image' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=600&auto=format&fit=crop',
                'status' => 'active',
            ],
            [
                'name' => 'Bags & Purses',
                'slug' => 'bags',
                'description' => 'Leather handbags, backpacks, totes, and wallets.',
                'main_category_id' => $mainCategories['shoes-accessories']->id,
                'image' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=600&auto=format&fit=crop',
                'status' => 'active',
            ],
            [
                'name' => 'Sunglasses & Belts',
                'slug' => 'accessories-belts',
                'description' => 'UV protective sunglasses, leather belts, and hats.',
                'main_category_id' => $mainCategories['shoes-accessories']->id,
                'image' => 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=600&auto=format&fit=crop',
                'status' => 'active',
            ],
        ];

        $subCategories = [];
        foreach ($subCategoriesData as $subcat) {
            $subCategories[$subcat['slug']] = Category::updateOrCreate(
                ['slug' => $subcat['slug']],
                $subcat
            );
        }

        // Fetch colors and sizes
        $dbColors = Color::all();
        $dbSizes = Size::all();

        // 6. Seed Clothing Products
        $productsData = [
            // --- Men's Wear (Shirts & Tees) ---
            [
                'title' => 'Classic White Oxford Shirt',
                'category_slug' => 'mens-shirts',
                'price' => 45.00,
                'image' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=600&auto=format&fit=crop',
                'details' => [
                    'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1603252109303-2751441dd157?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1621072156002-e2fcc104e768?w=600&auto=format&fit=crop'
                ],
                'description' => 'Crafted from premium long-staple cotton, this classic Oxford shirt is breathable, durable, and versatile. Perfect for both office settings and weekend outings.',
            ],
            [
                'title' => 'Premium Heavyweight Crewneck Tee',
                'category_slug' => 'mens-shirts',
                'price' => 24.50,
                'image' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=600&auto=format&fit=crop',
                'details' => [
                    'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1562157873-818bc0726f68?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=600&auto=format&fit=crop'
                ],
                'description' => 'A structured knit t-shirt made of 100% combed ringspun cotton. It features a relaxed fit and durable double-needle stitching on the sleeves and bottom hem.',
            ],

            // --- Men's Wear (Pants & Denim) ---
            [
                'title' => 'Slim Fit Stretch Indigo Jeans',
                'category_slug' => 'mens-pants',
                'price' => 59.90,
                'image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=600&auto=format&fit=crop',
                'details' => [
                    'https://images.unsplash.com/photo-1584273187211-463fa1558298?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1604176354204-9268737828e4?w=600&auto=format&fit=crop'
                ],
                'description' => 'Classic five-pocket denim pants with a modern tapered leg and built-in elastane stretch for all-day flexibility. Hand-washed indigo finish.',
            ],
            [
                'title' => 'Tapered Fit Chino Pants',
                'category_slug' => 'mens-pants',
                'price' => 49.00,
                'image' => 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=600&auto=format&fit=crop',
                'details' => [
                    'https://images.unsplash.com/photo-1479064555552-3ef4979f8908?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1517445312882-bc9910d016b7?w=600&auto=format&fit=crop'
                ],
                'description' => 'Made from comfortable cotton twill, these chinos offer a clean front design with slanted side pockets. Ideal for smart-casual wear.',
            ],

            // --- Men's Wear (Jackets & Coats) ---
            [
                'title' => 'Vintage Suede Bomber Jacket',
                'category_slug' => 'mens-jackets',
                'price' => 120.00,
                'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=600&auto=format&fit=crop',
                'details' => [
                    'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1551488831-00ddcb6c6bd3?w=600&auto=format&fit=crop'
                ],
                'description' => 'Add retro charm with this luxurious suede leather bomber jacket. Equipped with ribbed cuffs, zip front closure, and a soft satin lining.',
            ],

            // --- Women's Wear (Dresses & Jumpsuits) ---
            [
                'title' => 'Floral Summer Midi Dress',
                'category_slug' => 'womens-dresses',
                'price' => 54.00,
                'image' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=600&auto=format&fit=crop',
                'details' => [
                    'https://images.unsplash.com/photo-1612336307429-8a898d10e223?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1496747611176-843222e1e57c?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=600&auto=format&fit=crop'
                ],
                'description' => 'Lightweight, flowy, and styled with an allover vintage floral pattern. Features a wrap-around waist tie and a flattering V-neck cut.',
            ],
            [
                'title' => 'Elegant Little Black Dress',
                'category_slug' => 'womens-dresses',
                'price' => 69.99,
                'image' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=600&auto=format&fit=crop',
                'details' => [
                    'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=600&auto=format&fit=crop'
                ],
                'description' => 'A timeless wardrobe essential. Designed with a fitted silhouette, a crew neckline, and a concealed back zipper. A perfect dress for cocktail parties.',
            ],

            // --- Women's Wear (Tops & Knitwear) ---
            [
                'title' => 'Silk V-Neck Blouse',
                'category_slug' => 'womens-tops',
                'price' => 75.00,
                'image' => 'https://images.unsplash.com/photo-1548624149-f7b3166885b8?w=600&auto=format&fit=crop',
                'details' => [
                    'https://images.unsplash.com/photo-1534126416832-a88fdf2911c2?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1548624149-f7b3166885b8?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1609357605129-26f69add5d6e?w=600&auto=format&fit=crop'
                ],
                'description' => 'Pure mulberry silk blouse with an elegant drape. Designed with a delicate V-neckline and buttoned cuffs, ideal for layering or solo wear.',
            ],
            [
                'title' => 'Cable-Knit Wool Sweater',
                'category_slug' => 'womens-tops',
                'price' => 88.00,
                'image' => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=600&auto=format&fit=crop',
                'details' => [
                    'https://images.unsplash.com/photo-1574169208507-84376144848b?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1574169208507-84376144848b?w=600&auto=format&fit=crop'
                ],
                'description' => 'Cozy up in style. This sweater is spun from soft, warm merino wool and detailed with traditional cable-knit detailing and ribbed trims.',
            ],

            // --- Women's Wear (Jeans & Skirts) ---
            [
                'title' => 'High-Waisted Distressed Skinny Jeans',
                'category_slug' => 'womens-pants',
                'price' => 64.00,
                'image' => 'https://images.unsplash.com/photo-1582562124811-c09040d0a901?w=600&auto=format&fit=crop',
                'details' => [
                    'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1582562124811-c09040d0a901?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1604176354204-9268737828e4?w=600&auto=format&fit=crop'
                ],
                'description' => 'Trendy distressed skinny jeans with a vintage high-rise fit. Constructed from premium power-stretch denim that keeps its shape all day.',
            ],

            // --- Kids & Baby (Boys, Girls, Rompers) ---
            [
                'title' => 'Toddler Denim Dungarees',
                'category_slug' => 'boys-clothing',
                'price' => 32.00,
                'image' => 'https://images.unsplash.com/photo-1519457431-44ccd64a579b?w=600&auto=format&fit=crop',
                'details' => [
                    'https://images.unsplash.com/photo-1519457431-44ccd64a579b?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1519457431-44ccd64a579b?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1519457431-44ccd64a579b?w=600&auto=format&fit=crop'
                ],
                'description' => 'Classic children\'s dungarees made from soft, washed denim. Complete with adjustable button shoulder straps and side pocket details.',
            ],
            [
                'title' => 'Kids Organic Cotton Romper Set',
                'category_slug' => 'baby-rompers',
                'price' => 28.00,
                'image' => 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=600&auto=format&fit=crop',
                'details' => [
                    'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=600&auto=format&fit=crop'
                ],
                'description' => 'Includes 2 rompers made from 100% certified organic cotton. Features nickel-free snaps on the legs for easy, stress-free diaper changing.',
            ],

            // --- Shoes & Accessories ---
            [
                'title' => 'Retro Leather Canvas Sneakers',
                'category_slug' => 'footwear',
                'price' => 65.00,
                'image' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=600&auto=format&fit=crop',
                'details' => [
                    'https://images.unsplash.com/photo-1525966222434-6ad5334a415e?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=600&auto=format&fit=crop'
                ],
                'description' => 'A perfect blend of heritage style and everyday comfort. Features a breathable canvas body with premium suede overlays and a vulcanized rubber outsole.',
            ],
            [
                'title' => 'Minimalist Leather Crossbody Bag',
                'category_slug' => 'bags',
                'price' => 110.00,
                'image' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=600&auto=format&fit=crop',
                'details' => [
                    'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1547949003-9792a18a2601?w=600&auto=format&fit=crop'
                ],
                'description' => 'Handcrafted from full-grain vegetable-tanned leather. It features an adjustable shoulder strap, brass hardware, and a fully lined interior with a pocket.',
            ],
            [
                'title' => 'Round Frame Acetate Sunglasses',
                'category_slug' => 'accessories-belts',
                'price' => 38.00,
                'image' => 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=600&auto=format&fit=crop',
                'details' => [
                    'https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1508296695146-257a814070b4?w=600&auto=format&fit=crop'
                ],
                'description' => 'Designed with sturdy tortoiseshell acetate frames and scratch-resistant polarized lenses that provide complete UV400 protection.',
            ],
        ];

        foreach ($productsData as $pData) {
            $slug = Str::slug($pData['title']);
            $subcat = $subCategories[$pData['category_slug']] ?? null;

            if (!$subcat) {
                continue;
            }

            // Create product
            $product = Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $pData['title'],
                    'category_id' => $subcat->id,
                    'price' => $pData['price'],
                    'stock' => rand(20, 80),
                    'sales' => rand(5, 120),
                    'rating' => round(4.2 + (rand(0, 8) / 10), 2),
                    'description' => $pData['description'],
                    'image' => $pData['image'],
                    'status' => 'active',
                ]
            );

            // Create color variants for this product (2 to 4 colors)
            $randomColors = $dbColors->random(rand(2, min(4, $dbColors->count())));
            
            foreach ($randomColors as $color) {
                // The variant's price can be slightly adjusted based on the base price
                $variantPrice = $pData['price'] + rand(-5, 5);
                
                $productColor = \App\Models\ProductColor::create([
                    'product_id' => $product->id,
                    'color_id' => $color->id,
                    'price' => $variantPrice,
                ]);

                // Sync random sizes to this specific color (3 to 5 sizes)
                $randomSizes = $dbSizes->random(rand(3, min(5, $dbSizes->count())))->pluck('id')->toArray();
                $productColor->sizes()->sync($randomSizes);

                // Add the 3 gallery detail images to this specific color
                foreach ($pData['details'] as $detailPath) {
                    \App\Models\ProductImage::create([
                        'product_color_id' => $productColor->id,
                        'image_path' => $detailPath,
                    ]);
                }
            }
        }
    }
}

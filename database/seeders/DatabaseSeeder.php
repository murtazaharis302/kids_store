<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\AgeGroup;
use App\Models\Category;
use App\Models\Color;
use App\Models\Collection;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Users
        $admin = User::create([
            'name' => 'AH Kids Admin',
            'email' => 'admin@ahkids.pk',
            'phone' => '03001234567',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => true,
            'email_verified_at' => now(),
        ]);

        $customer = User::create([
            'name' => 'Ali Khan',
            'email' => 'customer@ahkids.pk',
            'phone' => '03007654321',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'status' => true,
            'email_verified_at' => now(),
        ]);

        // 2. Customer Address
        Address::create([
            'user_id' => $customer->id,
            'first_name' => 'Ali',
            'last_name' => 'Khan',
            'phone' => '03007654321',
            'address_line_1' => 'House # 123, Street 4, Block H3',
            'address_line_2' => 'Johar Town',
            'city' => 'Lahore',
            'state' => 'Punjab',
            'postal_code' => '54000',
            'country' => 'Pakistan',
            'is_default' => true,
        ]);

        // 3. Categories & Subcategories
        $categoriesTree = [
            'Girls' => [
                'Dresses',
                'Tops',
                'Trousers',
                'Winter Wear',
            ],
            'Boys' => [
                'Shirts',
                'T-Shirts',
                'Pants',
                'Winter Wear',
            ],
            'Newborn' => [
                'Rompers',
                'Sets',
                'Starter Sets',
            ],
            'Accessories' => [
                'Caps',
                'Socks',
                'Bags',
                'Shoes',
            ],
        ];

        $createdCategories = [];
        $sortOrder = 1;

        foreach ($categoriesTree as $parentName => $subCategories) {
            $parent = Category::create([
                'parent_id' => null,
                'name' => $parentName,
                'slug' => Str::slug($parentName),
                'description' => "Premium {$parentName} collection for kids",
                'image' => "categories/" . Str::slug($parentName) . ".jpg",
                'status' => true,
                'sort_order' => $sortOrder++,
            ]);

            $createdCategories[$parentName] = $parent;

            foreach ($subCategories as $subName) {
                $subCategory = Category::create([
                    'parent_id' => $parent->id,
                    'name' => $subName,
                    'slug' => Str::slug("{$parentName}-{$subName}"),
                    'description' => "High quality {$subName} for {$parentName}",
                    'image' => "categories/" . Str::slug("{$parentName}-{$subName}") . ".jpg",
                    'status' => true,
                    'sort_order' => $sortOrder++,
                ]);

                $createdCategories["{$parentName} > {$subName}"] = $subCategory;
            }
        }

        // 4. Age Groups
        $ageGroupsData = [
            'Newborn',
            '0-3 Months',
            '3-6 Months',
            '6-9 Months',
            '9-12 Months',
            '12-18 Months',
            '18-24 Months',
            '2-3 Years',
            '3-4 Years',
            '4-5 Years',
            '5-6 Years',
            '6-7 Years',
        ];

        $createdAgeGroups = [];
        foreach ($ageGroupsData as $index => $ageName) {
            $createdAgeGroups[$ageName] = AgeGroup::create([
                'name' => $ageName,
                'slug' => Str::slug($ageName),
                'sort_order' => $index + 1,
                'status' => true,
            ]);
        }

        // 5. Sizes
        $sizesData = [
            '0-3M',
            '3-6M',
            '6-9M',
            '9-12M',
            '1-2Y',
            '2-3Y',
            '3-4Y',
            '4-5Y',
            '5-6Y',
            '6-7Y',
        ];

        $createdSizes = [];
        foreach ($sizesData as $index => $sizeName) {
            $createdSizes[$sizeName] = Size::create([
                'name' => $sizeName,
                'sort_order' => $index + 1,
                'status' => true,
            ]);
        }

        // 6. Colors
        $colorsData = [
            ['name' => 'Black', 'hex' => '#000000'],
            ['name' => 'White', 'hex' => '#FFFFFF'],
            ['name' => 'Brown', 'hex' => '#8B4513'],
            ['name' => 'Beige', 'hex' => '#F5F5DC'],
            ['name' => 'Pink', 'hex' => '#FFC0CB'],
            ['name' => 'Blue', 'hex' => '#1E90FF'],
            ['name' => 'Green', 'hex' => '#2E8B57'],
            ['name' => 'Red', 'hex' => '#FF0000'],
            ['name' => 'Yellow', 'hex' => '#FFD700'],
        ];

        $createdColors = [];
        foreach ($colorsData as $color) {
            $createdColors[$color['name']] = Color::create([
                'name' => $color['name'],
                'hex_code' => $color['hex'],
                'status' => true,
            ]);
        }

        // 7. Collections
        $collectionsData = [
            'New Arrivals',
            'Winter Collection',
            'Summer Collection',
            'Featured',
            'Best Sellers',
            'Sale',
        ];

        $createdCollections = [];
        foreach ($collectionsData as $index => $collName) {
            $createdCollections[$collName] = Collection::create([
                'name' => $collName,
                'slug' => Str::slug($collName),
                'description' => "Curated {$collName} items for children",
                'image' => "collections/" . Str::slug($collName) . ".jpg",
                'status' => true,
                'sort_order' => $index + 1,
            ]);
        }

        // 8. Products & Variants & Images
        $productsSeed = [
            [
                'category' => 'Boys > Winter Wear',
                'name' => 'Teddy Sweater',
                'sku' => 'TEDDY-SWEATER-01',
                'short_description' => 'Cozy fleece knitted teddy bear sweater for boys.',
                'description' => 'Ultra-soft fleece fabric designed to keep your little boy warm and cozy during cold winter days. Stylish knitted teddy design.',
                'price' => 2499.00,
                'sale_price' => 1999.00,
                'cost_price' => 1100.00,
                'featured' => true,
                'new_arrival' => true,
                'is_sale' => true,
                'age_groups' => ['3-4 Years', '4-5 Years', '5-6 Years'],
                'collections' => ['New Arrivals', 'Winter Collection', 'Featured', 'Sale'],
                'colors' => ['Brown', 'Beige'],
                'sizes' => ['3-4Y', '4-5Y', '5-6Y'],
            ],
            [
                'category' => 'Girls > Dresses',
                'name' => 'Floral Embroidered Dress',
                'sku' => 'FLORAL-DRESS-02',
                'short_description' => 'Elegant floral print cotton frock for girls.',
                'description' => 'Beautiful premium cotton dress featuring handcrafted floral embroidery on neck line and soft breathable inner lining.',
                'price' => 3299.00,
                'sale_price' => null,
                'cost_price' => 1400.00,
                'featured' => true,
                'new_arrival' => true,
                'is_sale' => false,
                'age_groups' => ['2-3 Years', '3-4 Years', '4-5 Years'],
                'collections' => ['New Arrivals', 'Featured', 'Best Sellers'],
                'colors' => ['Pink', 'White', 'Yellow'],
                'sizes' => ['2-3Y', '3-4Y', '4-5Y'],
            ],
            [
                'category' => 'Newborn > Rompers',
                'name' => 'Organic Cotton Newborn Romper',
                'sku' => 'NEWBORN-ROMPER-03',
                'short_description' => 'Hypoallergenic soft organic cotton onesie for infants.',
                'description' => 'Designed with gentle snap buttons for easy diaper change. Made with 100% certified organic cotton for sensitive newborn skin.',
                'price' => 1499.00,
                'sale_price' => 1299.00,
                'cost_price' => 600.00,
                'featured' => true,
                'new_arrival' => false,
                'is_sale' => true,
                'age_groups' => ['Newborn', '0-3 Months', '3-6 Months'],
                'collections' => ['Best Sellers', 'Sale'],
                'colors' => ['Blue', 'Pink', 'White'],
                'sizes' => ['0-3M', '3-6M'],
            ],
            [
                'category' => 'Boys > Pants',
                'name' => 'Classic Denim Jeans',
                'sku' => 'BOYS-DENIM-04',
                'short_description' => 'Stretchable durable blue denim pants with elastic waistband.',
                'description' => 'High quality denim crafted for energetic boys. Features soft elastic waist band ensuring max comfort during playtime.',
                'price' => 2199.00,
                'sale_price' => null,
                'cost_price' => 950.00,
                'featured' => false,
                'new_arrival' => true,
                'is_sale' => false,
                'age_groups' => ['4-5 Years', '5-6 Years', '6-7 Years'],
                'collections' => ['New Arrivals', 'Best Sellers'],
                'colors' => ['Blue', 'Black'],
                'sizes' => ['4-5Y', '5-6Y', '6-7Y'],
            ],
            [
                'category' => 'Accessories > Caps',
                'name' => 'Soft Knit Beanie Cap',
                'sku' => 'BEANIE-CAP-05',
                'short_description' => 'Warm winter beanie cap for toddlers.',
                'description' => 'Cute knitted winter cap with pom-pom top to keep little heads warm.',
                'price' => 799.00,
                'sale_price' => 599.00,
                'cost_price' => 250.00,
                'featured' => false,
                'new_arrival' => true,
                'is_sale' => true,
                'age_groups' => ['12-18 Months', '18-24 Months', '2-3 Years'],
                'collections' => ['Winter Collection', 'Sale'],
                'colors' => ['Yellow', 'Red', 'Beige'],
                'sizes' => ['1-2Y', '2-3Y'],
            ]
        ];

        foreach ($productsSeed as $pData) {
            $cat = $createdCategories[$pData['category']];

            $product = Product::create([
                'category_id' => $cat->id,
                'name' => $pData['name'],
                'slug' => Str::slug($pData['name']),
                'sku' => $pData['sku'],
                'short_description' => $pData['short_description'],
                'description' => $pData['description'],
                'price' => $pData['price'],
                'sale_price' => $pData['sale_price'],
                'cost_price' => $pData['cost_price'],
                'status' => true,
                'featured' => $pData['featured'],
                'new_arrival' => $pData['new_arrival'],
                'is_sale' => $pData['is_sale'],
            ]);

            // Images
            ProductImage::create([
                'product_id' => $product->id,
                'image' => "products/" . Str::slug($product->name) . "-front.jpg",
                'alt_text' => "{$product->name} Front View",
                'is_primary' => true,
                'sort_order' => 1,
            ]);

            ProductImage::create([
                'product_id' => $product->id,
                'image' => "products/" . Str::slug($product->name) . "-back.jpg",
                'alt_text' => "{$product->name} Back View",
                'is_primary' => false,
                'sort_order' => 2,
            ]);

            // Age Groups pivot
            $ageGroupIds = [];
            foreach ($pData['age_groups'] as $agName) {
                if (isset($createdAgeGroups[$agName])) {
                    $ageGroupIds[] = $createdAgeGroups[$agName]->id;
                }
            }
            $product->ageGroups()->sync($ageGroupIds);

            // Collections pivot
            $collectionIds = [];
            foreach ($pData['collections'] as $cName) {
                if (isset($createdCollections[$cName])) {
                    $collectionIds[] = $createdCollections[$cName]->id;
                }
            }
            $product->collections()->sync($collectionIds);

            // Variants (Color x Size)
            foreach ($pData['colors'] as $colName) {
                foreach ($pData['sizes'] as $szName) {
                    $color = $createdColors[$colName];
                    $size = $createdSizes[$szName];

                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size_id' => $size->id,
                        'color_id' => $color->id,
                        'sku' => "{$product->sku}-{$color->name}-{$size->name}",
                        'price' => $product->price,
                        'sale_price' => $product->sale_price,
                        'stock_quantity' => rand(10, 50),
                        'status' => true,
                    ]);
                }
            }
        }

        // 9. Coupons
        Coupon::create([
            'code' => 'WELCOME10',
            'type' => 'percentage',
            'value' => 10.00,
            'minimum_order_amount' => 1000.00,
            'maximum_discount' => 500.00,
            'usage_limit' => 500,
            'used_count' => 0,
            'starts_at' => now(),
            'expires_at' => now()->addDays(90),
            'status' => true,
        ]);

        Coupon::create([
            'code' => 'WINTER20',
            'type' => 'percentage',
            'value' => 20.00,
            'minimum_order_amount' => 2000.00,
            'maximum_discount' => 1000.00,
            'usage_limit' => 200,
            'used_count' => 0,
            'starts_at' => now(),
            'expires_at' => now()->addDays(60),
            'status' => true,
        ]);

        Coupon::create([
            'code' => 'AH500',
            'type' => 'fixed',
            'value' => 500.00,
            'minimum_order_amount' => 2500.00,
            'maximum_discount' => 500.00,
            'usage_limit' => 100,
            'used_count' => 0,
            'starts_at' => now(),
            'expires_at' => now()->addDays(30),
            'status' => true,
        ]);

        // 10. Wishlist for Customer
        $wishlist = Wishlist::create([
            'user_id' => $customer->id,
        ]);

        $firstProduct = Product::first();
        if ($firstProduct) {
            $wishlist->items()->create([
                'product_id' => $firstProduct->id,
            ]);
        }
    }
}

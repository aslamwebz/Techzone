<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        Product::truncate();
        Category::truncate();

        // Create images directory if it doesn't exist
        $imagePath = public_path('images/products');
        if (!File::exists($imagePath)) {
            File::makeDirectory($imagePath, 0755, true);
        }

        // Create categories
        $categories = [
            [
                'name' => 'Laptops',
                'description' => 'High-performance laptops for work and play',
            ],
            [
                'name' => 'Smartphones',
                'description' => 'Latest smartphones with cutting-edge technology',
            ],
            [
                'name' => 'Tablets',
                'description' => 'Portable tablets for entertainment and productivity',
            ],
            [
                'name' => 'Accessories',
                'description' => 'Essential tech accessories',
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'description' => $categoryData['description'],
            ]);

            // Create sample products for each category
            $this->createProductsForCategory($category, $this->getProductsForCategory($category));
        }
    }

    private function getProductsForCategory(Category $category)
    {
        $products = [];

        if ($category->name === 'Laptops') {
            $products = [
                [
                    'name' => 'UltraBook Pro',
                    'description' => 'Sleek and powerful laptop for professionals with 16GB RAM, 1TB SSD, and 15.6" 4K display.',
                    'price' => 1299.99,
                    'stock' => 15,
                    'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&auto=format&fit=crop&q=80' // Laptop image
                ],
                [
                    'name' => 'Gaming Beast',
                    'description' => 'High-performance gaming laptop with RTX 3080, 32GB RAM, 2TB NVMe SSD, and 17.3" 240Hz display.',
                    'price' => 1999.99,
                    'stock' => 8,
                    'image' => 'https://images.unsplash.com/photo-1551033406-611cf9a28f67?w=800&auto=format&fit=crop&q=80' // Gaming laptop image
                ],
                [
                    'name' => 'MacBook Air M2',
                    'description' => 'Ultra-thin and light laptop with Apple M2 chip, 8GB RAM, and 256GB SSD.',
                    'price' => 999.99,
                    'stock' => 12,
                    'image' => 'https://images.unsplash.com/photo-1611186871348-b1ce6960c830?w=800&auto=format&fit=crop&q=80' // MacBook Air image
                ],
                [
                    'name' => 'Dell XPS 15',
                    'description' => '15.6" 4K InfinityEdge touch display, 11th Gen Intel i9, 32GB RAM, 1TB SSD.',
                    'price' => 2399.99,
                    'stock' => 7,
                    'image' => 'https://images.unsplash.com/photo-1593642702749-b3d4c2a9a9e6?w=800&auto=format&fit=crop&q=80' // Dell XPS image
                ],
                [
                    'name' => 'Lenovo ThinkPad X1 Carbon',
                    'description' => '14" business laptop with Intel i7, 16GB RAM, 512GB SSD, and military-grade durability.',
                    'price' => 1499.99,
                    'stock' => 10,
                    'image' => 'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b6b?w=800&auto=format&fit=crop&q=80' // ThinkPad image
                ],
                [
                    'name' => 'ASUS ROG Zephyrus G14',
                    'description' => '14" gaming laptop with Ryzen 9, RTX 3060, 16GB RAM, and 1TB SSD.',
                    'price' => 1599.99,
                    'stock' => 6,
                    'image' => 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=800&auto=format&fit=crop&q=80' // ASUS ROG image
                ],
                [
                    'name' => 'HP Spectre x360',
                    'description' => '13.5" 2-in-1 convertible laptop with OLED display, 16GB RAM, and 1TB SSD.',
                    'price' => 1699.99,
                    'stock' => 9,
                    'image' => 'https://images.unsplash.com/photo-1593642634402-b0eb5e2eebc9?w=800&auto=format&fit=crop&q=80' // HP Spectre image
                ],
                [
                    'name' => 'Acer Swift 3',
                    'description' => '14" budget laptop with AMD Ryzen 7, 8GB RAM, and 512GB SSD.',
                    'price' => 699.99,
                    'stock' => 14,
                    'image' => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=800&auto=format&fit=crop&q=80' // Acer Swift image
                ],
                [
                    'name' => 'Microsoft Surface Laptop 4',
                    'description' => '13.5" touchscreen laptop with Intel i7, 16GB RAM, and 512GB SSD.',
                    'price' => 1499.99,
                    'stock' => 8,
                    'image' => 'https://images.unsplash.com/photo-1629131726695-4b7b7292e43b?w=800&auto=format&fit=crop&q=80' // Surface Laptop image
                ],
                [
                    'name' => 'Razer Blade 15',
                    'description' => '15.6" gaming laptop with RTX 3070, 32GB RAM, and 1TB SSD.',
                    'price' => 2499.99,
                    'stock' => 5,
                    'image' => 'https://images.unsplash.com/photo-1593642702749-b3d4c2a9a9e6?w=800&auto=format&fit=crop&q=80' // Razer Blade image
                ],
            ];
        } elseif ($category->name === 'Smartphones') {
            $products = [
                [
                    'name' => 'Samsung Galaxy S23 Ultra',
                    'description' => '6.8" Dynamic AMOLED 2X, 200MP camera, S Pen support, and 5000mAh battery.',
                    'price' => 1199.99,
                    'stock' => 15,
                    'image' => 'https://images.unsplash.com/photo-1677432657095-6f6e5e8b1b0d?w=800&auto=format&fit=crop&q=80' // S23 Ultra image
                ],
                [
                    'name' => 'iPhone 15 Pro',
                    'description' => 'Latest iPhone with A17 Pro chip, 48MP camera, and 6.1" Super Retina XDR display.',
                    'price' => 999.99,
                    'stock' => 20,
                    'image' => 'https://images.unsplash.com/photo-1695048136859-7f1d702b7f3d?w=800&auto=format&fit=crop&q=80' // iPhone 15 Pro image
                ],
                [
                    'name' => 'Google Pixel 8 Pro',
                    'description' => '6.7" QHD+ LTPO OLED, Google Tensor G3, and advanced AI camera features.',
                    'price' => 899.99,
                    'stock' => 18,
                    'image' => 'https://images.unsplash.com/photo-1697609770229-c23a84942fb2?w=800&auto=format&fit=crop&q=80' // Pixel 8 Pro image
                ],
                [
                    'name' => 'OnePlus 11',
                    'description' => 'Flagship killer with Snapdragon 8 Gen 2, 16GB RAM, and 256GB storage.',
                    'price' => 799.99,
                    'stock' => 12,
                    'image' => 'https://images.unsplash.com/photo-1677432657095-6f6e5e8b1b0d?w=800&auto=format&fit=crop&q=80' // OnePlus 11 image
                ],
                [
                    'name' => 'Samsung Galaxy Z Fold 5',
                    'description' => '7.6" Dynamic AMOLED 2X, 12GB RAM, and 512GB storage.',
                    'price' => 1799.99,
                    'stock' => 6,
                    'image' => 'https://images.unsplash.com/photo-1661749719861-2a5f5a8d1f8c?w=800&auto=format&fit=crop&q=80' // Z Fold 5 image
                ],
                [
                    'name' => 'Xiaomi 13 Pro',
                    'description' => '6.73" 120Hz AMOLED, Leica camera system, and 4820mAh battery.',
                    'price' => 999.99,
                    'stock' => 10,
                    'image' => 'https://images.unsplash.com/photo-1677432657095-6f6e5e8b1b0d?w=800&auto=format&fit=crop&q=80' // Xiaomi 13 Pro image
                ],
                [
                    'name' => 'ASUS ROG Phone 7',
                    'description' => '6.78" 165Hz AMOLED, Snapdragon 8 Gen 2, and 6000mAh battery.',
                    'price' => 999.99,
                    'stock' => 8,
                    'image' => 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=800&auto=format&fit=crop&q=80' // ROG Phone 7 image
                ],
                [
                    'name' => 'Sony Xperia 1 V',
                    'description' => '6.1" 4K HDR OLED, Snapdragon 8 Gen 2, and 12GB RAM.',
                    'price' => 1399.99,
                    'stock' => 7,
                    'image' => 'https://images.unsplash.com/photo-1677432657095-6f6e5e8b1b0d?w=800&auto=format&fit=crop&q=80' // Xperia 1 V image
                ],
                [
                    'name' => 'Nothing Phone 2',
                    'description' => '6.55" 120Hz OLED, Snapdragon 8+ Gen 1, and 50MP dual camera.',
                    'price' => 599.99,
                    'stock' => 12,
                    'image' => 'https://images.unsplash.com/photo-1677432657095-6f6e5e8b1b0d?w=800&auto=format&fit=crop&q=80' // Nothing Phone 2 image
                ],
                [
                    'name' => 'iPhone SE (3rd Gen)',
                    'description' => '4.7" Retina HD display, A15 Bionic chip, and 12MP camera.',
                    'price' => 429.99,
                    'stock' => 22,
                    'image' => 'https://images.unsplash.com/photo-1643413575613-6eba9f0a3a8f?w=800&auto=format&fit=crop&q=80' // iPhone SE image
                ],
            ];
        } elseif ($category->name === 'Tablets') {
            $products = [
                [
                    'name' => 'iPad Pro 12.9" (M2)',
                    'description' => '12.9" Liquid Retina XDR display, M2 chip, and 128GB storage.',
                    'price' => 1099.99,
                    'stock' => 10,
                    'image' => 'https://images.unsplash.com/photo-1611186871348-b1ce6960c830?w=800&auto=format&fit=crop&q=80' // iPad Pro image
                ],
                [
                    'name' => 'Samsung Galaxy Tab S9 Ultra',
                    'description' => '14.6" Dynamic AMOLED 2X, Snapdragon 8 Gen 2, and 12GB RAM.',
                    'price' => 1199.99,
                    'stock' => 10,
                    'image' => 'https://images.unsplash.com/photo-1631715003916-0ae35d377a1e?w=800&auto=format&fit=crop&q=80' // Tab S9 Ultra image
                ],
                [
                    'name' => 'Microsoft Surface Pro 9',
                    'description' => '13" PixelSense Flow touch display, 12th Gen Intel Core i7, and 16GB RAM.',
                    'price' => 1599.99,
                    'stock' => 7,
                    'image' => 'https://images.unsplash.com/photo-1670272502974-19e6c8e9e8b3?w=800&auto=format&fit=crop&q=80' // Surface Pro 9 image
                ],
                [
                    'name' => 'iPad 10.2" (9th Gen)',
                    'description' => '10.2" Retina display, A13 Bionic chip, and 64GB storage.',
                    'price' => 329.99,
                    'stock' => 18,
                    'image' => 'https://images.unsplash.com/photo-1631729371255-6054019e4e3f?w=800&auto=format&fit=crop&q=80' // iPad 9th Gen image
                ],
                [
                    'name' => 'Lenovo Tab P12 Pro',
                    'description' => '12.7" 3K OLED, MediaTek Dimensity 9000, and 8GB RAM.',
                    'price' => 749.99,
                    'stock' => 9,
                    'image' => 'https://images.unsplash.com/photo-1631715003916-0ae35d377a1e?w=800&auto=format&fit=crop&q=80' // Lenovo Tab P12 Pro image
                ],
                [
                    'name' => 'Amazon Fire HD 10',
                    'description' => '10.1" Full HD display, octa-core processor, and 32GB storage.',
                    'price' => 149.99,
                    'stock' => 20,
                    'image' => 'https://images.unsplash.com/photo-1670272502974-19e6c8e9e8b3?w=800&auto=format&fit=crop&q=80' // Fire HD 10 image
                ],
                [
                    'name' => 'Xiaomi Pad 6',
                    'description' => '11" 2.8K display, Snapdragon 870, and 8GB RAM.',
                    'price' => 449.99,
                    'stock' => 10,
                    'image' => 'https://images.unsplash.com/photo-1631729371255-6054019e4e3f?w=800&auto=format&fit=crop&q=80' // Xiaomi Pad 6 image
                ],
                [
                    'name' => 'Samsung Galaxy Tab A8',
                    'description' => '10.5" display, quad speakers, and expandable storage.',
                    'price' => 229.99,
                    'stock' => 15,
                    'image' => 'https://images.unsplash.com/photo-1631715003916-0ae35d377a1e?w=800&auto=format&fit=crop&q=80' // Tab A8 image
                ],
                [
                    'name' => 'Huawei MatePad Pro 12.6',
                    'description' => '12.6" OLED, Kirin 9000E, and 8GB RAM with HarmonyOS.',
                    'price' => 899.99,
                    'stock' => 7,
                    'image' => 'https://images.unsplash.com/photo-1670272502974-19e6c8e9e8b3?w=800&auto=format&fit=crop&q=80' // MatePad Pro image
                ],
                [
                    'name' => 'Realme Pad X',
                    'description' => '10.4" 2K display with stylus support and 7,100mAh battery.',
                    'price' => 299.99,
                    'stock' => 14,
                    'image' => 'https://images.unsplash.com/photo-1631729371255-6054019e4e3f?w=800&auto=format&fit=crop&q=80' // Realme Pad X image
                ],
            ];
        } elseif ($category->name === 'Accessories') {
            $products = [
                [
                    'name' => 'AirPods Pro (2nd Gen)',
                    'description' => 'Wireless earbuds with Active Noise Cancellation and Spatial Audio.',
                    'price' => 249.99,
                    'stock' => 30,
                    'image' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&auto=format&fit=crop&q=80' // AirPods Pro image
                ],
                [
                    'name' => 'JBL Flip 6',
                    'description' => 'Portable Bluetooth speaker with 12 hours of playtime and IP67 rating.',
                    'price' => 129.99,
                    'stock' => 25,
                    'image' => 'https://images.unsplash.com/photo-1572569511254-d8f925fe2cbb?w=800&auto=format&fit=crop&q=80' // JBL Flip 6 image
                ],
                [
                    'name' => 'Samsung Galaxy Watch 6 Classic',
                    'description' => 'Premium smartwatch with rotating bezel and Wear OS.',
                    'price' => 449.99,
                    'stock' => 12,
                    'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&auto=format&fit=crop&q=80' // Galaxy Watch 6 image
                ],
                [
                    'name' => 'Apple Watch Series 9',
                    'description' => 'Advanced smartwatch with S9 SiP and Always-On Retina display.',
                    'price' => 399.99,
                    'stock' => 15,
                    'image' => 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=800&auto=format&fit=crop&q=80' // Apple Watch Series 9 image
                ],
                [
                    'name' => 'Anker 737 Power Bank',
                    'description' => '24,000mAh portable charger with 140W USB-C Power Delivery.',
                    'price' => 149.99,
                    'stock' => 18,
                    'image' => 'https://images.unsplash.com/photo-1587730302136-2a3bafd1a4e6?w=800&auto=format&fit=crop&q=80' // Anker Power Bank image
                ],
                [
                    'name' => 'Logitech MX Master 3S',
                    'description' => 'Wireless mouse with MagSpeed scroll wheel and 8K DPI sensor.',
                    'price' => 99.99,
                    'stock' => 20,
                    'image' => 'https://images.unsplash.com/photo-1527814050087-3793815479db?w=800&auto=format&fit=crop&q=80' // Logitech MX Master image
                ],
                [
                    'name' => 'Keychron K8 Pro',
                    'description' => 'Wireless mechanical keyboard with hot-swappable switches and RGB backlight.',
                    'price' => 149.99,
                    'stock' => 10,
                    'image' => 'https://images.unsplash.com/photo-1601445637046-44c2d56a3b0d?w=800&auto=format&fit=crop&q=80' // Keychron K8 Pro image
                ],
                [
                    'name' => 'Sony WH-1000XM5',
                    'description' => 'Premium noise-canceling headphones with 30-hour battery life.',
                    'price' => 399.99,
                    'stock' => 8,
                    'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80' // Sony WH-1000XM5 image
                ],
                [
                    'name' => 'Samsung T7 Shield SSD',
                    'description' => '1TB portable SSD with USB 3.2 Gen 2 and IP65 rating.',
                    'price' => 89.99,
                    'stock' => 14,
                    'image' => 'https://images.unsplash.com/photo-1591488320449-011701bb0f67?w=800&auto=format&fit=crop&q=80' // Samsung T7 SSD image
                ],
                [
                    'name' => 'Belkin 3-in-1 Wireless Charger',
                    'description' => 'Charging stand for iPhone, Apple Watch, and AirPods simultaneously.',
                    'price' => 129.99,
                    'stock' => 16,
                    'image' => 'https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=800&auto=format&fit=crop&q=80' // Belkin Charger image
                ]
    }

    private function downloadAndSaveImage($url, $productName)
    {
        try {
            $imageName = Str::slug($productName) . '.jpg';
            $directory = 'images/products';
            $relativePath = $directory . '/' . $imageName;
            $fullPath = public_path($relativePath);

            // Create directory if it doesn't exist
            if (!File::exists(public_path($directory))) {
                File::makeDirectory(public_path($directory), 0755, true);
            }

            // Check if image already exists
            if (file_exists($fullPath)) {
                return $relativePath;
            }

            // Download the image with proper headers
            $client = new Client([
                'verify' => false,
                'timeout' => 15,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                    'Accept' => 'image/webp,image/apng,image/*,*/*;q=0.8',
                    'Accept-Language' => 'en-US,en;q=0.9',
                ]
            ]);

            $response = $client->get($url);
            
            if ($response->getStatusCode() === 200) {
                File::put($fullPath, $response->getBody());
                return $relativePath;
            }
        } catch (\Exception $e) {
            Log::error("Failed to download image for {$productName}: " . $e->getMessage());
        }

        // Return a default image if download fails
        return 'images/placeholder.jpg';
    }

    private function createProductsForCategory($category, $products)
    {
        foreach ($products as $productData) {
            try {
                // Download the image and get local path
                $productData['image'] = $this->downloadAndSaveImage(
                    $productData['image'],
                    $productData['name']
                );

                // Create the product
                $product = $category->products()->create([
                    'name' => $productData['name'],
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'stock' => $productData['stock'],
                    'image' => $productData['image'],
                ]);

                // Set additional product attributes
                $product->is_active = true;
                $product->is_featured = rand(0, 1) === 1;
                $product->is_new = rand(0, 1) === 1;
                $product->rating = rand(35, 50) / 10; // Random rating between 3.5 and 5.0
                $product->reviews_count = rand(5, 200);
                $product->save();
            } catch (\Exception $e) {
                Log::error("Failed to create product {$productData['name']}: " . $e->getMessage());
                continue;
            }
            $category->products()->save($product);
        }
    }
}

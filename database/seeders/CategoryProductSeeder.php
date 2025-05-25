<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
            $this->createProductsForCategory($category);
        }
    }

    /**
     * Create sample products for a category
     */
    private function createProductsForCategory(Category $category): void
    {
        $products = [];

        if ($category->name === 'Laptops') {
            $products = [
                [
                    'name' => 'UltraBook Pro',
                    'description' => 'Sleek and powerful laptop for professionals',
                    'price' => 1299.99,
                    'stock' => 15,
                ],
                [
                    'name' => 'Gaming Beast',
                    'description' => 'High-performance gaming laptop with dedicated GPU',
                    'price' => 1999.99,
                    'stock' => 8,
                ],
            ];
        } elseif ($category->name === 'Smartphones') {
            $products = [
                [
                    'name' => 'Galaxy X1',
                    'description' => 'Flagship smartphone with amazing camera',
                    'price' => 899.99,
                    'stock' => 25,
                ],
                [
                    'name' => 'iPhone Pro',
                    'description' => 'Premium smartphone with iOS',
                    'price' => 999.99,
                    'stock' => 20,
                ],
            ];
        } elseif ($category->name === 'Tablets') {
            $products = [
                [
                    'name' => 'Tab S8',
                    'description' => 'Powerful Android tablet with S Pen',
                    'price' => 699.99,
                    'stock' => 12,
                ],
                [
                    'name' => 'iPad Air',
                    'description' => 'Thin and light tablet with powerful performance',
                    'price' => 599.99,
                    'stock' => 10,
                ],
            ];
        } else {
            // Accessories
            $products = [
                [
                    'name' => 'Wireless Earbuds',
                    'description' => 'True wireless earbuds with noise cancellation',
                    'price' => 149.99,
                    'stock' => 50,
                ],
                [
                    'name' => 'Bluetooth Speaker',
                    'description' => 'Portable speaker with 20-hour battery life',
                    'price' => 79.99,
                    'stock' => 30,
                ],
            ];
        }

        foreach ($products as $productData) {
            $product = new Product($productData);
            $category->products()->save($product);
        }
    }
}

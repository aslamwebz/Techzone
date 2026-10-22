<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    public function run()
    {
        // Create main categories
        $categories = [
            ['name' => 'Laptops', 'slug' => 'laptops', 'description' => 'Latest laptops and notebooks', 'is_active' => true],
            ['name' => 'Smartphones', 'slug' => 'smartphones', 'description' => 'Latest smartphones and mobile devices', 'is_active' => true],
            ['name' => 'Tablets', 'slug' => 'tablets', 'description' => 'Portable tablets and e-readers', 'is_active' => true],
            ['name' => 'Accessories', 'slug' => 'accessories', 'description' => 'Tech accessories and peripherals', 'is_active' => true],
            ['name' => 'Audio', 'slug' => 'audio', 'description' => 'Headphones, speakers, and audio equipment', 'is_active' => true],
            ['name' => 'Gaming', 'slug' => 'gaming', 'description' => 'Gaming consoles and accessories', 'is_active' => true],
            ['name' => 'Wearables', 'slug' => 'wearables', 'description' => 'Smartwatches and fitness trackers', 'is_active' => true],
            ['name' => 'Cameras', 'slug' => 'cameras', 'description' => 'Digital cameras and accessories', 'is_active' => true],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        // Create 50 sample products
        Product::factory()->count(50)->create();

        $this->command->info('Sample data seeded successfully!');
    }
}

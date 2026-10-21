<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $name = $this->faker->unique()->words(3, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(3),
            'short_description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'sale_price' => $this->faker->optional(0.3, null)->randomFloat(2, 5, 800),
            'stock' => $this->faker->numberBetween(0, 100),
            'is_active' => true,
            'is_featured' => $this->faker->boolean(30), // 30% chance of being featured
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'weight' => $this->faker->randomFloat(2, 0.1, 20),
            'dimensions' => implode('x', [
                $this->faker->numberBetween(5, 50),
                $this->faker->numberBetween(5, 50),
                $this->faker->numberBetween(1, 30),
            ]),
            'sku' => strtoupper($this->faker->bothify('SKU-####-????')),
            'rating' => $this->faker->optional(0.8, null)->randomFloat(1, 1, 5), // 80% chance of having a rating
            'reviews_count' => $this->faker->numberBetween(0, 1000),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}

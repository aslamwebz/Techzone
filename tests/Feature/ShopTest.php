<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_displays_featured_products_and_categories(): void
    {
        $category = Category::factory()->create(['name' => 'Laptops']);
        $featuredProduct = Product::factory()->create([
            'name' => 'MacBook Pro',
            'category_id' => $category->id,
            'is_active' => true,
            'is_featured' => true,
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('MacBook Pro');
        $response->assertSee('Laptops');
    }

    public function test_inactive_products_are_not_displayed_on_home_page(): void
    {
        $inactiveProduct = Product::factory()->create([
            'name' => 'Secret Prototype',
            'is_active' => false,
            'is_featured' => true,
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertDontSee('Secret Prototype');
    }

    public function test_product_detail_page_can_be_rendered(): void
    {
        $category = Category::factory()->create(['name' => 'Smartphones']);
        $product = Product::factory()->create([
            'name' => 'iPhone 16 Pro',
            'category_id' => $category->id,
            'is_active' => true,
            'price' => 999.00,
            'description' => 'Latest flagship phone',
        ]);

        $response = $this->get(route('product.show', $product->slug));

        $response->assertStatus(200);
        $response->assertSee('iPhone 16 Pro');
        $response->assertSee('Latest flagship phone');
    }

    public function test_search_returns_matching_products(): void
    {
        $matchingProduct = Product::factory()->create([
            'name' => 'Sony Wireless Headphones',
            'is_active' => true,
        ]);
        $otherProduct = Product::factory()->create([
            'name' => 'Ergonomic Keyboard',
            'is_active' => true,
        ]);

        $response = $this->get(route('search', ['q' => 'Headphones']));

        $response->assertStatus(200);
        $response->assertSee('Sony Wireless Headphones');
        $response->assertDontSee('Ergonomic Keyboard');
    }

    public function test_categories_index_page_can_be_rendered(): void
    {
        $category = Category::factory()->create(['name' => 'Audio Devices']);
        Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        $response = $this->get(route('categories'));

        $response->assertStatus(200);
        $response->assertSee('Audio Devices');
    }
}

<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_cart_page(): void
    {
        $response = $this->get(route('cart.index'));

        $response->assertStatus(200);
        $response->assertSee('Shopping Cart');
    }

    public function test_can_add_product_to_cart(): void
    {
        $product = Product::factory()->create([
            'name' => 'Mechanical Keyboard',
            'price' => 120.00,
            'is_active' => true,
            'stock' => 10,
        ]);

        $response = $this->postJson(route('cart.add', $product->id), [
            'quantity' => 2,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'cart_count' => 2,
        ]);
    }

    public function test_can_get_cart_count(): void
    {
        $product = Product::factory()->create([
            'price' => 50.00,
            'is_active' => true,
            'stock' => 5,
        ]);

        $this->postJson(route('cart.add', $product->id), ['quantity' => 3]);

        $response = $this->getJson(route('cart.count'));

        $response->assertStatus(200);
        $response->assertJson(['count' => 3]);
    }

    public function test_can_update_cart_quantity(): void
    {
        $product = Product::factory()->create([
            'price' => 50.00,
            'is_active' => true,
            'stock' => 10,
        ]);

        $addResponse = $this->postJson(route('cart.add', $product->id), ['quantity' => 1]);
        $rowId = array_key_first($addResponse->json('cart_content'));

        $updateResponse = $this->postJson(route('cart.update'), [
            'rowId' => $rowId,
            'qty' => 5,
        ]);

        $updateResponse->assertStatus(200);
        $updateResponse->assertJson([
            'success' => true,
            'cart_count' => 5,
        ]);
    }

    public function test_can_remove_item_from_cart(): void
    {
        $product = Product::factory()->create([
            'price' => 50.00,
            'is_active' => true,
            'stock' => 10,
        ]);

        $addResponse = $this->postJson(route('cart.add', $product->id), ['quantity' => 1]);
        $rowId = array_key_first($addResponse->json('cart_content'));

        $removeResponse = $this->deleteJson(route('cart.remove', $rowId));

        $removeResponse->assertStatus(200);
        $removeResponse->assertJson([
            'success' => true,
            'cart_count' => 0,
        ]);
    }

    public function test_can_clear_cart(): void
    {
        $product = Product::factory()->create([
            'price' => 50.00,
            'is_active' => true,
            'stock' => 10,
        ]);

        $this->postJson(route('cart.add', $product->id), ['quantity' => 2]);

        $clearResponse = $this->postJson(route('cart.clear'));

        $clearResponse->assertStatus(200);
        $clearResponse->assertJson([
            'success' => true,
            'cart_count' => 0,
        ]);
    }
}

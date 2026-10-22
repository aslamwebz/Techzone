<?php

namespace Tests\Feature;

use App\Facades\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_checkout(): void
    {
        $response = $this->get(route('checkout'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_with_empty_cart_is_redirected_to_cart_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('checkout'));

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('warning');
    }

    public function test_authenticated_user_can_view_checkout_with_cart_items(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'name' => 'Dell XPS 15',
            'price' => 1500.00,
            'stock' => 5,
            'is_active' => true,
        ]);

        $this->actingAs($user);
        Cart::add($product->id, $product->name, 1, $product->price);

        $response = $this->get(route('checkout'));

        $response->assertStatus(200);
        $response->assertSee('Dell XPS 15');
        $response->assertSee('Billing Details');
    }

    public function test_successful_checkout_creates_order_and_decrements_stock(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'name' => 'Logitech Mouse',
            'price' => 50.00,
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->actingAs($user);
        Cart::add($product->id, $product->name, 2, $product->price);

        $orderPayload = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'address' => '123 Market St',
            'city' => 'San Francisco',
            'state' => 'CA',
            'postal_code' => '94105',
            'country' => 'USA',
            'payment_method' => 'cash_on_delivery',
        ];

        $response = $this->post(route('checkout.store'), $orderPayload);

        $response->assertRedirect(route('checkout.success'));
        $this->assertEquals(0, Cart::count());

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'email' => 'john@example.com',
            'total_amount' => 121.00, // $100 + 21% tax
            'payment_method' => 'cash_on_delivery',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $this->assertEquals(8, $product->fresh()->stock);
    }

    public function test_checkout_fails_when_product_is_out_of_stock(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'name' => 'Limited Edition GPU',
            'price' => 999.00,
            'stock' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($user);
        Cart::add($product->id, $product->name, 2, $product->price);

        $orderPayload = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'phone' => '+1234567890',
            'address' => '456 Elm St',
            'city' => 'Austin',
            'state' => 'TX',
            'postal_code' => '78701',
            'country' => 'USA',
            'payment_method' => 'cash_on_delivery',
        ];

        $response = $this->from(route('checkout'))->post(route('checkout.store'), $orderPayload);

        $response->assertRedirect(route('checkout'));
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('orders', 0);
        $this->assertEquals(1, $product->fresh()->stock);
    }

    public function test_checkout_validates_required_fields(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 5, 'is_active' => true]);

        $this->actingAs($user);
        Cart::add($product->id, $product->name, 1, $product->price);

        $response = $this->post(route('checkout.store'), []);

        $response->assertSessionHasErrors([
            'first_name',
            'last_name',
            'email',
            'phone',
            'address',
            'city',
            'state',
            'postal_code',
            'country',
            'payment_method',
        ]);
    }
}

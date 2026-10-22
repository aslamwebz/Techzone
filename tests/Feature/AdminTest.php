<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_receives_forbidden_for_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'is_admin' => false,
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_regular_user_cannot_access_admin_products(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'is_admin' => false,
        ]);

        $response = $this->actingAs($user)->get(route('admin.products.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    }

    public function test_admin_can_view_products_index(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create(['name' => 'Admin Product View']);

        $response = $this->actingAs($admin)->get(route('admin.products.index'));

        $response->assertStatus(200);
        $response->assertSee('Admin Product View');
    }

    public function test_admin_can_create_product(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create(['name' => 'Hardware']);

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'name' => 'NVMe SSD 2TB',
            'description' => 'Fast PCIe 4.0 SSD',
            'price' => 199.99,
            'stock' => 25,
            'category_id' => $category->id,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'NVMe SSD 2TB',
            'price' => 199.99,
            'stock' => 25,
            'category_id' => $category->id,
        ]);
    }

    public function test_product_creation_validates_required_fields(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.products.store'), []);

        $response->assertSessionHasErrors(['name', 'price', 'stock']);
    }

    public function test_admin_can_update_product(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create([
            'name' => 'Old Product Title',
            'price' => 50.00,
            'stock' => 5,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.products.update', $product), [
            'name' => 'Updated Product Title',
            'price' => 65.00,
            'stock' => 12,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product Title',
            'price' => 65.00,
            'stock' => 12,
        ]);
    }

    public function test_admin_can_delete_product(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create(['name' => 'Product To Delete']);

        $response = $this->actingAs($admin)->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_admin_can_view_orders_and_update_status(): void
    {
        $admin = User::factory()->admin()->create();
        $order = Order::factory()->create([
            'order_number' => 'ORD-TEST1234',
            'status' => 'pending',
        ]);

        $indexResponse = $this->actingAs($admin)->get(route('admin.orders.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('ORD-TEST1234');

        $updateResponse = $this->actingAs($admin)->put(route('admin.orders.update-status', $order), [
            'status' => 'delivered',
        ]);

        $updateResponse->assertRedirect();
        $this->assertEquals('delivered', $order->fresh()->status);
    }
}

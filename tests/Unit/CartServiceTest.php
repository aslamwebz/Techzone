<?php

namespace Tests\Unit;

use App\Facades\Cart;
use App\Services\Cart\CartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cart::destroy();
    }

    public function test_can_add_item_to_cart(): void
    {
        $item = Cart::add(1, 'Test Product', 2, 5000.00, ['slug' => 'test-product']);

        $this->assertInstanceOf(CartItem::class, $item);
        $this->assertEquals(1, $item->id);
        $this->assertEquals('Test Product', $item->name);
        $this->assertEquals(2, $item->qty);
        $this->assertEquals(5000.00, $item->price);
        $this->assertEquals('test-product', $item->options->slug);
        $this->assertEquals(2, Cart::count());
        $this->assertEquals('10,000.00', Cart::subtotal(2, '.', ','));
    }

    public function test_adding_existing_item_increments_quantity(): void
    {
        Cart::add(1, 'Test Product', 1, 1000.00, ['color' => 'blue']);
        Cart::add(1, 'Test Product', 2, 1000.00, ['color' => 'blue']);

        $this->assertEquals(3, Cart::count());
        $this->assertCount(1, Cart::content());
    }

    public function test_can_update_item_quantity(): void
    {
        $item = Cart::add(1, 'Test Product', 1, 1000.00);

        Cart::update($item->rowId, 5);

        $this->assertEquals(5, Cart::count());
        $this->assertEquals(5, Cart::get($item->rowId)->qty);
    }

    public function test_updating_quantity_to_zero_removes_item(): void
    {
        $item = Cart::add(1, 'Test Product', 1, 1000.00);

        Cart::update($item->rowId, 0);

        $this->assertEquals(0, Cart::count());
        $this->assertNull(Cart::get($item->rowId));
    }

    public function test_can_remove_item(): void
    {
        $item = Cart::add(1, 'Test Product', 1, 1000.00);

        $this->assertTrue(Cart::remove($item->rowId));
        $this->assertEquals(0, Cart::count());
    }

    public function test_can_destroy_cart(): void
    {
        Cart::add(1, 'Product 1', 2, 1000.00);
        Cart::add(2, 'Product 2', 3, 2000.00);

        $this->assertEquals(5, Cart::count());

        Cart::destroy();

        $this->assertEquals(0, Cart::count());
        $this->assertCount(0, Cart::content());
    }

    public function test_can_calculate_tax_and_total(): void
    {
        Cart::setGlobalTax(10.0); // 10% tax
        Cart::add(1, 'Test Product', 1, 100.00);

        $this->assertEquals('100.00', Cart::subtotal());
        $this->assertEquals('10.00', Cart::tax());
        $this->assertEquals('110.00', Cart::total());
    }

    public function test_can_search_cart_items(): void
    {
        Cart::add(1, 'Product A', 1, 100.00);
        Cart::add(2, 'Product B', 1, 200.00);

        $found = Cart::search(fn ($item) => $item->id === 2);

        $this->assertCount(1, $found);
        $this->assertEquals('Product B', $found->first()->name);
    }

    public function test_can_store_and_restore_cart(): void
    {
        Cart::add(1, 'Saved Product', 3, 250.00);

        Cart::store('user-123');
        Cart::destroy();

        $this->assertEquals(0, Cart::count());

        Cart::restore('user-123');

        $this->assertEquals(3, Cart::count());
        $this->assertEquals(1, Cart::content()->first()->id);
    }
}

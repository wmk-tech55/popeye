<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MixCode\Address;
use MixCode\Cart;
use MixCode\Discount;
use MixCode\Product;
use MixCode\User;
use Notification;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cart = create(Cart::class);
    }

    /** @test */
    public function it_belongs_to_customer()
    {
        $this->assertInstanceOf(User::class, $this->cart->customer);
    }

    /** @test */
    public function it_belongs_to_product()
    {
        $this->assertInstanceOf(Product::class, $this->cart->product);
    }
    
    /** @test */
    public function it_can_reserve_product_quantity()
    {
        $product = create(Product::class, ['quantity' => 100]);

        $this->cart->update(['product_id' => $product->id, 'quantity' => 25]);

        $this->assertEquals($product->id, $this->cart->product->id);
        $this->assertEquals(100, $product->fresh()->quantity);
        
        $this->cart->reserveQuantity();
        
        $this->assertEquals(75, $product->fresh()->quantity);
    }
    
    /** @test */
    public function it_can_generate_total_price()
    {
        $product = create(Product::class, ['price' => 100]);
        $discount   = create(Discount::class, ['discount' => 25]); // %25
        
        $this->cart->update(['product_id' => $product->id, 'quantity' => 1]);
        
        $this->assertEquals(100, $this->cart->total_price);
        
        $product->update(['discount_id' => $discount->id]);
        
        $this->assertEquals(75, $this->cart->fresh()->total_price);
    }
}

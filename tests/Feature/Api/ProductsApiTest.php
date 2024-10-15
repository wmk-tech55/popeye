<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use MixCode\Order;
use MixCode\Review;
use MixCode\Product;
use MixCode\ProductView;

class ProductsApiTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->product = create(Product::class);
    }

    /** @test */
    public function it_can_read_single_product()
    {
        // Given We Have A Product        
        // When We Hit The Product Read Endpoint
        $response = $this->getJson($this->product->apiPath());
        
        // Then It Should Get The Product That Was Created 
        // and view counter should be increments by 1
        $response->assertOk();
        $this->assertContains($this->product->en_name, $response->json());
        $this->assertEquals(1, $response->json()['payload']['views_count']);
    }

    /** @test */
    public function it_can_make_new_view_when_reding_if_user_authenticated()
    {
        // Given We Have A Product and user not logged in        
        // When We Hit The Product Read Endpoint
        $response = $this->getJson($this->product->apiPath());
        
        // Then It Should Save New View And View Counter Equals One
        $response->assertOk();
        $this->assertEquals(1, $response->json()['payload']['views_count']);
        $this->assertEquals(0, ProductView::count());
        
        // Given We Have A Product and user logged in
        $this->apiSignIn();

        // When We Hit The Product Read Endpoint
        $response = $this->getJson($this->product->apiPath());
        
        // Then It Should Save New View And View Counter Equals One
        $response->assertOk();
        $this->assertEquals(2, $response->json()['payload']['views_count']);
        $this->assertEquals(1, ProductView::count());
    }

    /** @test */
    public function it_can_read_all_product()
    {
        // Given We Have "3" Product        
        $products = create(Product::class, [], 3);

        // When We Hit The Product Read Endpoint
        $response = $this->getJson(route('api.products.index'));
        
        // Then It Should Get The "3" Product That Was Created
        $response->assertOk();
        $response->assertSee($products->first()->name);
        $response->assertSee($products->get(1)->name);
        $response->assertSee($products->last()->name);
    }
}
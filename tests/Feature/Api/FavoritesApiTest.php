<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use MixCode\Product;

class FavoritesApiTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->product = create(Product::class);
    }

    /** @test */
    public function it_can_mark_products_as_favorite()
    {
        $this->apiSignIn();
        
        // Given We Have Product
        // When We Hit The Mark Product As Favorite Endpoint
        $this->postJson(route('api.products.favorite.mark', $this->product))
            ->assertStatus(Response::HTTP_CREATED);
            
        // Then Product Should Be Favorite Successfully
        $this->assertTrue($this->product->isFavorited());
        $this->assertCount(1, $this->product->favorites);
    }
 
    /** @test */
    public function it_can_mark_products_as_un_favorite()
    {
        $this->apiSignIn();
        
        // Given We Have Product
        // When We Hit The Mark Product As Un Favorite Endpoint
        $this->deleteJson(route('api.products.favorite.un_mark', $this->product))
            ->assertOk();
            
        // Then Product Should Be Un Favorite Successfully
        $this->assertFalse($this->product->isFavorited());
        $this->assertCount(0, $this->product->favorites);
    }
    
    /** @test */
    public function it_can_favorite_products_once()
    {        
        $this->apiSignIn();

        
        // Given We Have Product
        // When We Hit The Mark Product As Favorite Endpoint 2 Times
        $this->postJson(route('api.products.favorite.mark', $this->product))->assertStatus(Response::HTTP_CREATED);
            
        $this->postJson(route('api.products.favorite.mark', $this->product));
        
        // Then we will have only one favorite "resource"
        //  and others favorites operations will be ignored
        $this->assertCount(1, $this->product->favorites);
    }

    /** @test */
    public function it_list_all_favorite_products()
    {
        $this->apiSignIn();
        
        // Given We Have 2 Favorited Product
        $productOne = $this->product;
        $productTwo = create(Product::class);

        $productOne->markAsFavorite();
        $productTwo->markAsFavorite();
        
        // When We Hit The Get All Favorite Products Endpoint
        $response = $this->getJson(route('api.products.favorite'))->assertStatus(Response::HTTP_OK);

        // Then Favorited Products Should Be Returned
        $response->assertSee($productOne->en_name);
        $response->assertSee($productTwo->en_name);
    }
}
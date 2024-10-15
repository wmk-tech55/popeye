<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use MixCode\City;
use MixCode\Product;

class CitiesApiTest extends TestCase
{
    use RefreshDatabase;
 
    /** @test */
    public function it_can_read_single_city()
    {
        // Given We Have A City        
        $city = create(City::class);
        
        // When We Hit The City Read Endpoint
        $response = $this->getJson($city->apiPath());
        
        // Then It Should Get The City That Was Created
        $response->assertOk();
        $this->assertContains($city->en_name, $response->json());
    }

    /** @test */
    public function it_can_read_all_cities()
    {
        // Given We Have "3" Cities        
        $cities = create(City::class, [], 3);

        // When We Hit The City Read Endpoint
        $response = $this->getJson(route('api.cities.index'));
        
        // Then It Should Get The "3" Cities That Was Created
        $response->assertOk();
        $response->assertSee($cities->first()->name);
        $response->assertSee($cities->get(1)->name);
        $response->assertSee($cities->last()->name);
    }
    
    /** @test */
    public function it_can_read_all_its_products()
    {
        // Given We Have a City and product belongs to it        
        $city = create(City::class);
        $product = create(Product::class, ['city_id' => $city->id]);

        // When We Hit The City Read Endpoint
        $response = $this->getJson(route('api.cities.show.products', $city));
        
        // Then It Should Get The City Products
        $response->assertOk();
        $response->assertSee($product->en_name);
    }
}
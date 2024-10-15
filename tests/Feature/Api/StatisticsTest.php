<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use MixCode\Addition;
use MixCode\Order;
use MixCode\Feature;
use MixCode\Language;
use MixCode\Product;
use MixCode\User;

class StatisticsTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();
    }
 
    /** @test */
    public function non_authenticate_admins_or_companies_or_tour_guides_cant_see_statistics()
    {
        $this->withExceptionHandling();
        
        // Given we have a guest
        // When we hit Statistics endpoint
        $response = $this->getJson(route('api.statistics'));
        
        // Then it should give An Error unauthorized|unauthenticated
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
 
    /** @test */
    public function authenticate_admins_or_companies_or_tour_guides_can_see_statistics()
    {
        $this->withExceptionHandling();
        
        // Given we have an authenticated company and Statistics data
        $this->apiSignIn($company = create(User::class, ['type' => User::COMPANY_TYPE]));
        
        create(Feature::class, ['creator_id' => $company->id], 2);
        create(Addition::class, ['creator_id' => $company->id], 3);
        create(Language::class, ['creator_id' => $company->id], 2);
        create(Product::class, ['creator_id' => $company->id]);
        
        create(Product::class, ['creator_id' => $company->id, 'status' => Product::ACTIVE_STATUS]);
        create(Product::class, ['creator_id' => $company->id, 'status' => Product::INACTIVE_STATUS]);
        create(Product::class, ['creator_id' => $company->id, 'order_status' => Product::BOOKING_ACTIVE_STATUS]);
        create(Product::class, ['creator_id' => $company->id, 'order_status' => Product::BOOKING_INACTIVE_STATUS]);
        
        create(Order::class, ['product_creator_id' => $company->id, 'paid_status' => Order::PAID_STATUS]);
        create(Order::class, ['product_creator_id' => $company->id, 'paid_status' => Order::NOT_PAID_STATUS]);
        create(Order::class, ['product_creator_id' => $company->id, 'completed_status' => Order::COMPLETED_STATUS]);
        create(Order::class, ['product_creator_id' => $company->id, 'completed_status' => Order::NOT_COMPLETED_STATUS]);
 
        // When we hit Statistics endpoint
        $response = $this->getJson(route('api.statistics'));
        
        // Then it should get all company statistics
        $this->assertEquals(2, $response->json('payload.features_count'));
        $this->assertEquals(3, $response->json('payload.additions_count'));
        $this->assertEquals(2, $response->json('payload.languages_count'));
        $this->assertEquals(5, $response->json('payload.products_count'));
        
        $this->assertEquals(4, $response->json('payload.published_products_count'));
        $this->assertEquals(1, $response->json('payload.pending_products_count'));
        $this->assertEquals(4, $response->json('payload.active_order_products_count'));
        $this->assertEquals(1, $response->json('payload.inactive_order_products_count'));

        $this->assertEquals(3, $response->json('payload.paid_orders_count'));
        $this->assertEquals(1, $response->json('payload.not_paid_orders_count'));

    }
}
<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use MixCode\Review;
use MixCode\Product;
use MixCode\User;

class ReviewsApiTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->product = create(Product::class);
    }

    /** @test */
    public function non_authenticate_customers_cant_submit_a_review()
    {
        $this->withExceptionHandling();
        
        // Given we have a guest
        // When We Hit The Product Submit Review Endpoint
        $response = $this->postJson(route('api.products.reviews.submit', $this->product));
        
        // Then it should give An Error unauthorized|unauthenticated
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
        
    /** @test */
    public function product_can_submit_new_review()
    {
        // Given We Have authenticated customer Product and Review
        $this->apiSignIn();

        // When We Hit The Product Submit Review Endpoint
        $this->postJson(route('api.products.reviews.submit', $this->product), create(Review::class)->toArray())
            ->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function review_require_a_valid_rate()
    {
        $this->createNewReview(['rate' => null])
            ->assertJsonValidationErrors('rate');
        
        // Valid Date Rate
        $this->createNewReview(['rate' => 1])
            ->assertJsonMissingValidationErrors('rate');
        
        $this->createNewReview(['rate' => 2])
            ->assertJsonMissingValidationErrors('rate');
    }

    /** @test */
    public function review_require_a_valid_review()
    {
        $this->createNewReview(['review' => null])->assertJsonValidationErrors('review');
    }

    protected function createNewReview($overrides)
    {
        $this->withExceptionHandling();

        // Given we have an authenticated customer and review data
        $this->apiSignIn($customer = create(User::class, ['type' => User::CUSTOMER_TYPE]));
        
        $data = make(Review::class, $overrides)->toArray();

        // When we hit review endpoint
        return $this->postJson(route('api.products.reviews.submit', $this->product), $data);
    }
}
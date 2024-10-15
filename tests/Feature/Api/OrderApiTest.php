<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use MixCode\Addition;
use MixCode\Order;
use MixCode\Notifications\OrderHasBeenPaid;
use MixCode\Notifications\OrderHasBeenPaidForCustomer;
use MixCode\Notifications\NewOrderHasBeenCreated;
use MixCode\Product;
use MixCode\User;
use Notification;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->product = create(Product::class, [
            'price'     => 10,
            'adults'    => 20.5,
            'children'  => 10.2,
            'infant'    => 5,
            'date_from' => today()->addDay()->toDateString(),
        ]);
    }
 
    /** @test */
    public function non_authenticate_customers_cant_book_product()
    {
        $this->withExceptionHandling();
        
        // Given we have a guest
        // When we hit order endpoint
        $response = $this->postJson(route('api.products.order', $this->product));
        
        // Then it should give An Error unauthorized|unauthenticated
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
 
    /** @test */
    public function authenticate_customers_can_book_product()
    {
        Notification::fake();

        $this->withExceptionHandling();
        
        // Given we have an authenticated customer and order data
        $this->apiSignIn($customer = create(User::class, ['type' => User::CUSTOMER_TYPE]));
        
        // Visitors number consist of ('adults', 'children', 'infant')
        $data = make(Order::class, [
            'product_price'        => 10,
            'adults_price'      => 20.5,
            'children_price'    => 10.2,
            'infant_price'      => 5,
            'adults_number'     => 2,
            'children_number'   => 1,
            'infant_number'     => 1,
            'product_id'           => $this->product->id,
            'date'              => null,
            'customer_id'       => auth()->id(),
        ])->makeHidden('price_ratios')->toArray();

        // When we hit order endpoint
        $response = $this->postJson(route('api.products.order', $this->product), $data);
        
        // Then it should book product for customer
        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertEquals(1, Order::count());
        $this->assertEquals(1, $this->product->order()->count());
        $this->assertEquals(1, auth()->user()->order()->count());

        Notification::assertSentTo($this->product->creator, NewOrderHasBeenCreated::class);
    }

    /** @test */
    public function authenticate_customers_can_book_product_with_additional_services()
    {
        // Given we have an authenticated customer and order data with additional services
        $this->apiSignIn($customer = create(User::class, ['type' => User::CUSTOMER_TYPE]));
        
        // Visitors number consist of ('adults', 'children', 'infant')
        $data = make(Order::class, [
            'product_price'        => 10,
            'adults_price'      => 20.5,
            'children_price'    => 10.2,
            'infant_price'      => 5,
            'adults_number'     => 2,
            'children_number'   => 1,
            'infant_number'     => 1,
            'product_id'           => $this->product->id,
            'date'              => null,
            'customer_id'       => auth()->id(),
        ])->makeHidden('price_ratios')->toArray();

        $data['additions_id'] = create(Addition::class, [], 3)->pluck('id')->toArray();

        // When we hit order endpoint
        $this->postJson(route('api.products.order', $this->product), $data);
        
        // Then it should book product with additional services
        $this->assertEquals(3, Order::first()->additions()->count());
    }

    /** @test */
    public function it_can_mark_it_self_as_paid()
    {
        Notification::fake();

        $this->apiSignIn();

        // Given We Have not paid order
        $order = create(Order::class, [
            'paid_status'   => Order::NOT_PAID_STATUS,
            'customer_id'   => auth()->id(),
        ]);

        // When We Hit The Product Read Endpoint
        $response = $this->patchJson(route('api.orders.mark_as.paid', $order));
        
        // Then It Should Update order paid status to paid
        $response->assertOk();
        $this->assertTrue($order->fresh()->isPaid());

        Notification::assertSentTo($order->creator, OrderHasBeenPaid::class);
        Notification::assertSentTo($order->customer, OrderHasBeenPaidForCustomer::class);
    }

    /** @test */
    public function order_may_accept_a_valid_date()
    {
        $this->createNewOrder(['date' => null])
            ->assertJsonMissingValidationErrors('date');
        
        // Invalid Fate Format
        $this->createNewOrder(['date' => '01-01-2020'])
            ->assertJsonValidationErrors('date');

        // Valid Date Format
        $this->createNewOrder(['date' => '2020-01-01'])
            ->assertJsonMissingValidationErrors('date');
    }

    /** @test */
    public function order_required_a_valid_integer_adults_number()
    {
        $this->createNewOrder(['adults_number' => null])
            ->assertJsonValidationErrors('adults_number');
    }

    /** @test */
    public function order_required_a_valid_integer_children_number()
    {
        $this->createNewOrder(['children_number' => null])
            ->assertJsonValidationErrors('children_number');
    }

    /** @test */
    public function order_required_a_valid_integer_infant_number()
    {
        $this->createNewOrder(['infant_number' => null])
            ->assertJsonValidationErrors('infant_number');
    }

    /** @test */
    public function order_require_a_valid_and_exists_additions()
    {
        $this->createNewOrder(['additions_id' => 'not-valid-languages'])
            ->assertJsonValidationErrors('additions_id');
        $this->createNewOrder(['additions_id' => null])
            ->assertJsonMissingValidationErrors('additions_id');
        $this->createNewOrder(['additions_id' => [create(Addition::class)->id]])
            ->assertJsonMissingValidationErrors('additions_id');
    }

    protected function createNewOrder($overrides)
    {
        $this->withExceptionHandling();

        // Given we have an authenticated customer and order data
        $this->apiSignIn($customer = create(User::class, ['type' => User::CUSTOMER_TYPE]));
        
        $data = make(Order::class, $overrides)->makeHidden('price_ratios')->toArray();

        // When we hit order endpoint
        return $this->postJson(route('api.products.order', $this->product), $data);
    }
}
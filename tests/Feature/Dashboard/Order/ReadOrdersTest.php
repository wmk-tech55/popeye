<?php

 
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MixCode\Order;
use MixCode\Notifications\OrderHasBeenCompleted;
use MixCode\Product;
use MixCode\User;
use Illuminate\Support\Facades\Notification;

class ReadOrdersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user     = create(User::class, ['type' => User::COMPANY_TYPE]);
        $this->userProduct = create(Product::class, ['creator_id' => $this->user->id]);
        $this->order  = create(Order::class, ['product_id' => $this->userProduct->id, 'product_creator_id' => $this->userProduct->creator_id]);
    }

    /** @test */
    public function non_authenticate_administrators_or_companies_cant_read_single_order()
    {
        $this->readOrder(create(Order::class)->path());
    }

    /** @test */
    public function non_authenticate_administrators_or_companies_cant_read_all_order()
    {
        $this->readOrder(route('dashboard.orders.index'));
    }
    
    /** @test */
    public function authenticate_administrators_can_read_only_their_single_order()
    {
        // Given We Have An Authenticated Admin
        $this->signInAsAdmin($this->user);

        // When We Hit The Order Read Endpoint
        $response = $this->getJson($this->order->path());
        
        // Then It Should Get The Order That Was Created
        $response->assertOk();
        $this->assertEquals($this->order->product_id, $response->json('product_id'));
    }



    /** @test */
    public function non_authenticate_product_owners_cant_change_order_paid_status()
    {
        $this->readOrder(route('dashboard.orders.mark_as.paid', $this->order));
    }
 
    /** @test */
    public function authenticate_product_owners_can_change_order_paid_status()
    {
        $this->withExceptionHandling();

        // Given We Have An Authenticated Company|Admin
        $this->signIn($this->user);
        
        // When We Hit The Update Order Paid Status Endpoint
        $response = $this->getJson(route('dashboard.orders.mark_as.paid', $this->order));
        
        // Then It Should update order paid status
        $response->assertRedirect($this->order->path());
        $this->assertTrue($this->order->fresh()->isPaid());
    }
    
    /** @test */
    public function non_authenticate_product_owners_cant_change_order_completed_status()
    {
        $this->readOrder(route('dashboard.orders.mark_as.completed', $this->order));
    }
 
    /** @test */
    public function authenticate_product_owners_can_change_order_completed_status()
    {
        Notification::fake();

        $this->withExceptionHandling();

        // Given We Have An Authenticated Company|Admin
        $this->signIn($this->user);
        
        // When We Hit The Update Order Completed Status Endpoint
        $response = $this->getJson(route('dashboard.orders.mark_as.completed', $this->order));
        
        // Then It Should update order completed status
        $response->assertRedirect($this->order->path());
        $this->assertTrue($this->order->fresh()->isCompleted());

        Notification::assertSentTo($this->order->customer, OrderHasBeenCompleted::class);
    }

    protected function readOrder($route)
    {
        $this->withExceptionHandling();
        
        // Given We Have A User Not An Admin or Company
        $this->signIn(create(User::class, ['type' => User::CUSTOMER_TYPE]));

        // When We Hit The Order Read Endpoint
        $response = $this->getJson($route);
        
        // Then it Should Give An Error un Authorized
        $response->assertRedirect('/login');

        // Given We Have A non authenticate User
        auth()->logout();

        // When We Hit The Order Read Endpoint
        $response = $this->getJson($route);
        
        // Then it Should Give An Error un Authorized
        $response->assertRedirect('/login');
        
    }
}
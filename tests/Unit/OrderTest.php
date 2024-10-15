<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MixCode\Address;
use MixCode\Order;
use MixCode\Notifications\OrderHasBeenCompleted;
use MixCode\Notifications\OrderHasBeenPaid;
use MixCode\Notifications\OrderHasBeenPaidForCustomer;
use MixCode\Notifications\NewOrderHasBeenCreated;
use MixCode\Product;
use MixCode\User;
use Notification;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->order = create(Order::class);
    }

    /** @test */
    public function it_belongs_to_customer()
    {
        $this->assertInstanceOf(User::class, $this->order->customer);
    }

    /** @test */
    public function it_belongs_to_driver()
    {
        $this->assertInstanceOf(User::class, $this->order->driver);
    }

    /** @test */
    public function it_belongs_to_address()
    {
        $this->assertInstanceOf(Address::class, $this->order->address);
    }

    /** @test */
    public function it_have_many_products()
    {
        $product = create(Product::class)
            ->makeVisible([
                'en_name',
                'ar_name',
                'en_overview',
                'ar_overview',
            ]);

        $this->order->real_products()->attach($product);

        $this->assertInstanceOf(Collection::class, $this->order->real_products);
        $this->assertInstanceOf(Product::class, $this->order->real_products->first());
        $this->assertEquals(1, $this->order->real_products->count());
    }

    /** @test */
    public function it_can_determine_if_customer_paid_or_not()
    {
        $paid       = create(Order::class, ['is_paid' => Order::PAID_STATUS]);
        $notPaid    = create(Order::class, ['is_paid' => Order::NOT_PAID_STATUS]);

        // Paid Unit Tests
        $this->assertTrue($paid->isPaid());
        $this->assertFalse($paid->isNotPaid());

        // Not Paid Unit Tests
        $this->assertTrue($notPaid->isNotPaid());
        $this->assertFalse($notPaid->isPaid());
    }

    /** @test */
    public function it_can_mark_as_paid()
    {
        $this->order->markAsPaid();

        $this->assertTrue($this->order->isPaid());
    }

    /** @test */
    public function it_can_mark_as_not_paid()
    {
        $this->order->markAsNotPaid();

        $this->assertTrue($this->order->isNotPaid());
    }
    
    /** @test */
    public function it_can_determine_if_customer_cancelled_or_not()
    {
        $cancelled      = create(Order::class, ['is_cancelled' => Order::CANCELLED_STATUS]);
        $notCancelled   = create(Order::class, ['is_cancelled' => Order::NOT_CANCELLED_STATUS]);

        // Cancelled Unit Tests
        $this->assertTrue($cancelled->isCancelled());
        $this->assertFalse($cancelled->isNotCancelled());

        // Not Cancelled Unit Tests
        $this->assertTrue($notCancelled->isNotCancelled());
        $this->assertFalse($notCancelled->isCancelled());
    }

    /** @test */
    public function it_can_mark_as_cancelled()
    {
        $this->order->markAsCancelled();

        $this->assertTrue($this->order->isCancelled());
    }

    /** @test */
    public function it_can_mark_as_not_cancelled()
    {
        $this->order->markAsNotCancelled();

        $this->assertTrue($this->order->isNotCancelled());
    }
    
    /** @test */
    public function it_can_determine_if_customer_approved_or_not()
    {
        $approved      = create(Order::class, ['is_approved' => Order::APPROVED_STATUS]);
        $notApproved   = create(Order::class, ['is_approved' => Order::NOT_APPROVED_STATUS]);

        // Approved Unit Tests
        $this->assertTrue($approved->isApproved());
        $this->assertFalse($approved->isNotApproved());

        // Not Approved Unit Tests
        $this->assertTrue($notApproved->isNotApproved());
        $this->assertFalse($notApproved->isApproved());
    }

    /** @test */
    public function it_can_mark_as_approved()
    {
        $this->order->markAsApproved();

        $this->assertTrue($this->order->isApproved());
    }

    /** @test */
    public function it_can_mark_as_not_approved()
    {
        $this->order->markAsNotApproved();

        $this->assertTrue($this->order->isNotApproved());
    }
    
    /** @test */
    public function it_can_determine_if_customer_preparing_or_not()
    {
        $preparing      = create(Order::class, ['is_preparing' => Order::PREPARED_STATUS]);
        $notPreparing   = create(Order::class, ['is_preparing' => Order::NOT_PREPARED_STATUS]);

        // Preparing Unit Tests
        $this->assertTrue($preparing->isPrepared());
        $this->assertFalse($preparing->isNotPrepared());

        // Not Preparing Unit Tests
        $this->assertTrue($notPreparing->isNotPrepared());
        $this->assertFalse($notPreparing->isPrepared());
    }

    /** @test */
    public function it_can_mark_as_preparing()
    {
        $this->order->markAsPrepared();

        $this->assertTrue($this->order->isPrepared());
    }

    /** @test */
    public function it_can_mark_as_not_preparing()
    {
        $this->order->markAsNotPrepared();

        $this->assertTrue($this->order->isNotPrepared());
    }
    
    /** @test */
    public function it_can_determine_if_customer_shipping_or_not()
    {
        $shipping      = create(Order::class, ['in_shipping' => Order::SHIPPED_STATUS]);
        $notShipping   = create(Order::class, ['in_shipping' => Order::NOT_SHIPPED_STATUS]);

        // Shipping Unit Tests
        $this->assertTrue($shipping->isShipped());
        $this->assertFalse($shipping->isNotShipped());

        // Not Shipping Unit Tests
        $this->assertTrue($notShipping->isNotShipped());
        $this->assertFalse($notShipping->isShipped());
    }

    /** @test */
    public function it_can_mark_as_shipping()
    {
        $this->order->markAsShipped();

        $this->assertTrue($this->order->isShipped());
    }

    /** @test */
    public function it_can_mark_as_not_shipping()
    {
        $this->order->markAsNotShipped();

        $this->assertTrue($this->order->isNotShipped());
    }
    
    /** @test */
    public function it_can_determine_if_customer_delivered_or_not()
    {
        $delivered      = create(Order::class, ['is_delivered' => Order::DELIVERED_STATUS]);
        $notDelivered   = create(Order::class, ['is_delivered' => Order::NOT_DELIVERED_STATUS]);

        // Delivered Unit Tests
        $this->assertTrue($delivered->isDelivered());
        $this->assertFalse($delivered->isNotDelivered());

        // Not Delivered Unit Tests
        $this->assertTrue($notDelivered->isNotDelivered());
        $this->assertFalse($notDelivered->isDelivered());
    }

    /** @test */
    public function it_can_mark_as_delivered()
    {
        $this->order->markAsDelivered();

        $this->assertTrue($this->order->isDelivered());
    }

    /** @test */
    public function it_can_mark_as_not_delivered()
    {
        $this->order->markAsNotDelivered();

        $this->assertTrue($this->order->isNotDelivered());
    }

    /** @test */
    public function it_can_notify_creators_when_new_order_created_by_customer()
    {
        Notification::fake();

        $admins = create(User::class, ['type' => User::ADMIN_TYPE], 10);
        $order  = create(Order::class);

        $order->notifyAdminsForNewOrder();

        Notification::assertSentTo($admins, NewOrderHasBeenCreated::class);
    }
}

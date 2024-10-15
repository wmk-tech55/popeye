<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use MixCode\Address;
use MixCode\Cart;
use MixCode\Order;
use MixCode\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = create(User::class);
    }

    /** @test */
    public function it_have_many_countries()
    {
        $this->assertInstanceOf(Collection::class, $this->user->countries);
    }

    /** @test */
    public function it_have_many_cities()
    {
        $this->assertInstanceOf(Collection::class, $this->user->cities);
    }

    /** @test */
    public function it_have_many_categories()
    {
        $this->assertInstanceOf(Collection::class, $this->user->categories);
    }

    /** @test */
    public function it_have_many_products()
    {
        $this->assertInstanceOf(Collection::class, $this->user->products);
    }

    /** @test */
    public function it_have_many_reviews()
    {
        $this->assertInstanceOf(Collection::class, $this->user->reviews);
    }

    /** @test */
    public function it_have_many_addresses()
    {
        $this->user->addresses()->create(make(Address::class)->toArray());

        $this->assertInstanceOf(Collection::class, $this->user->addresses);
        $this->assertEquals(1, $this->user->addresses->count());
    }

    /** @test */
    public function it_have_many_orders()
    {
        $this->user->order()->create(make(Order::class)->toArray());
        
        $this->assertInstanceOf(Collection::class, $this->user->order);
        $this->assertEquals(1, $this->user->order->count());
    }

    /** @test */
    public function it_have_many_carts()
    {
        $this->user->carts()->create(make(Cart::class)->toArray());
        
        $this->assertInstanceOf(Collection::class, $this->user->carts);
        $this->assertEquals(1, $this->user->carts->count());
    }

    /** @test */
    public function it_can_determine_its_dashboard_path()
    {
        $this->assertEquals(route('dashboard.users.show', $this->user), $this->user->path());
    }

    /** @test */
    public function it_can_determine_what_is_the_user_type()
    {
        $customer   = create(User::class, ['type' => User::CUSTOMER_TYPE]);
        $driver     = create(User::class, ['type' => User::DRIVER_TYPE]);
        $admin      = create(User::class, ['type' => User::ADMIN_TYPE]);
        $company    = create(User::class, ['type' => User::COMPANY_TYPE]);

        // Customer Unit Tests
        $this->assertTrue($customer->isType(User::CUSTOMER_TYPE));
        $this->assertTrue($customer->isCustomer());
        $this->assertFalse($driver->isCompany());
        $this->assertFalse($customer->isDriver());
        $this->assertFalse($customer->isAdmin());
        
        // Driver Unit Tests
        $this->assertTrue($driver->isType(User::DRIVER_TYPE));
        $this->assertTrue($driver->isDriver());
        $this->assertFalse($driver->isCompany());
        $this->assertFalse($driver->isCustomer());
        $this->assertFalse($driver->isAdmin());
        
        // Company Unit Tests
        $this->assertTrue($company->isType(User::COMPANY_TYPE));
        $this->assertTrue($company->isCompany());
        $this->assertFalse($company->isDriver());
        $this->assertFalse($company->isCustomer());
        $this->assertFalse($company->isAdmin());

        // Admin Unit Tests
        $this->assertTrue($admin->isType(User::ADMIN_TYPE));
        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($driver->isCompany());
        $this->assertFalse($admin->isCustomer());
        $this->assertFalse($admin->isDriver());
    }
    
    /** @test */
    public function it_can_determine_if_the_user_is_active_or_pending_or_disable()
    {
        $active     = create(User::class, ['status' => User::ACTIVE_STATUS]);
        $pending    = create(User::class, ['status' => User::PENDING_STATUS]);
        $disable   = create(User::class, ['status' => User::INACTIVE_STATUS]);

        // Active Unit Tests
        $this->assertTrue($active->hasStatus(User::ACTIVE_STATUS));
        $this->assertTrue($active->isActive());
        $this->assertFalse($active->isPending());
        $this->assertFalse($active->isInActive());
        
        // Pending Unit Tests
        $this->assertTrue($pending->hasStatus(User::PENDING_STATUS));
        $this->assertTrue($pending->isPending());
        $this->assertFalse($pending->isActive());
        $this->assertFalse($pending->isInActive());

        // InActive Unit Tests
        $this->assertTrue($disable->hasStatus(User::INACTIVE_STATUS));
        $this->assertTrue($disable->isInActive());
        $this->assertFalse($disable->isPending());
        $this->assertFalse($disable->isActive());
    }
}
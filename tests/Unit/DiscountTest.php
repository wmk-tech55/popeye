<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MixCode\Discount;
use MixCode\User;

class DiscountTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->discount = create(Discount::class);
    }

    /** @test */
    public function it_belongs_to_creator()
    {
        $this->assertInstanceOf(User::class, $this->discount->creator);
    }
    
    /** @test */
    public function it_have_many_products()
    {
        $this->assertInstanceOf(Collection::class, $this->discount->products);
    }
    
    /** @test */
    public function it_can_determine_its_dashboard_path()
    {
        $this->assertEquals(route('dashboard.discounts.show', $this->discount), $this->discount->path());
    }
    
    /** @test */
    public function it_can_determine_if_the_discount_is_active_or_disable()
    {
        $active     = create(Discount::class, ['status' => Discount::ACTIVE_STATUS]);
        $disable   = create(Discount::class, ['status' => Discount::INACTIVE_STATUS]);

        // Active Unit Tests
        $this->assertTrue($active->hasStatus(Discount::ACTIVE_STATUS));
        $this->assertTrue($active->isPublished());
        $this->assertFalse($active->isPending());

        // InActive Unit Tests
        $this->assertTrue($disable->hasStatus(Discount::INACTIVE_STATUS));
        $this->assertTrue($disable->isPending());
        $this->assertFalse($disable->isPublished());
    }
}
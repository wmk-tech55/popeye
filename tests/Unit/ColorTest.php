<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MixCode\Product;
use MixCode\Color;
use MixCode\User;

class ColorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->color = create(Color::class);
    }

    /** @test */
    public function it_belongs_to_creator()
    {
        $this->assertInstanceOf(User::class, $this->color->creator);
    }
    
    /** @test */
    public function it_have_many_products()
    {
        create(Product::class)->colors()->attach($this->color);
        
        $this->assertInstanceOf(Product::class, $this->color->products->first());
    }
    
    /** @test */
    public function it_can_determine_its_dashboard_path()
    {
        $this->assertEquals(route('dashboard.colors.show', $this->color), $this->color->path());
    }
}
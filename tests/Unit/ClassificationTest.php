<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MixCode\Categorization;
use MixCode\Classification;
use MixCode\User;

class ClassificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->classification = create(Classification::class);
    }

    /** @test */
    public function it_belongs_to_creator()
    {
        $this->assertInstanceOf(User::class, $this->classification->creator);
    }
    
    /** @test */
    public function it_have_many_categorizations()
    {
        create(Categorization::class)->classifications()->attach($this->classification);
        
        $this->assertInstanceOf(Categorization::class, $this->classification->categorizations->first());
    }
    
    /** @test */
    public function it_have_many_products()
    {
        $this->assertInstanceOf(Collection::class, $this->classification->products);
    }
    
    /** @test */
    public function it_can_determine_its_dashboard_path()
    {
        $this->assertEquals(route('dashboard.classifications.show', $this->classification), $this->classification->path());
    }
    
    // /** @test */
    // public function it_can_determine_its_api_path()
    // {
    //     $this->assertEquals(route('api.classifications.show', $this->classification), $this->classification->apiPath());
    // }
    
    // /** @test */
    // public function it_can_determine_its_view_path()
    // {
    //     $this->assertEquals(route('classifications.show', $this->classification), $this->classification->viewPath());
    // }
}
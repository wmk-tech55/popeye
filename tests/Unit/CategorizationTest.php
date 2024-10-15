<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MixCode\Categorization;
use MixCode\Classification;
use MixCode\User;

class CategorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->categorization = create(Categorization::class);
    }

    /** @test */
    public function it_belongs_to_creator()
    {
        $this->assertInstanceOf(User::class, $this->categorization->creator);
    }
    
    /** @test */
    public function it_have_many_classifications()
    {
        create(Classification::class)->categorizations()->attach($this->categorization);
        
        $this->assertInstanceOf(Classification::class, $this->categorization->classifications->first());
    }
    
    /** @test */
    public function it_can_determine_its_dashboard_path()
    {
        $this->assertEquals(route('dashboard.categorizations.show', $this->categorization), $this->categorization->path());
    }
    
    /** @test */
    // public function it_can_determine_its_api_path()
    // {
    //     $this->assertEquals(route('api.categorizations.show', $this->categorization), $this->categorization->apiPath());
    // }
    
    /** @test */
    // public function it_can_determine_its_view_path()
    // {
    //     $this->assertEquals(route('categorizations.show', $this->categorization), $this->categorization->viewPath());
    // }
}
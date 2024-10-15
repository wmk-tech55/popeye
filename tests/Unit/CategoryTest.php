<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MixCode\Category;
use MixCode\User;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = create(Category::class);
    }

    /** @test */
    public function it_belongs_to_creator()
    {
        $this->assertInstanceOf(User::class, $this->category->creator);
    }
    
    /** @test */
    public function it_belongs_to_parent()
    {
        $category = create(Category::class, ['parent_id' => $this->category]);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals($this->category->id, $category->parent_id);
    }

    /** @test */
    public function it_have_many_products()
    {
        $this->assertInstanceOf(Collection::class, $this->category->products);
    }
    
    /** @test */
    public function it_has_many_children()
    {
        create(Category::class, ['parent_id' => $this->category], 10);

        $this->assertInstanceOf(Collection::class, $this->category->children);
        $this->assertEquals(10, $this->category->children->count());
    }
    
    /** @test */
    public function it_can_determine_its_dashboard_path()
    {
        $this->assertEquals(route('dashboard.categories.show', $this->category), $this->category->path());
    }
    
    /** @test */
    public function it_can_determine_its_api_path()
    {
        $this->assertEquals(route('api.categories.show', $this->category), $this->category->apiPath());
    }
    
    /** @test */
    public function it_can_determine_its_view_path()
    {
        $this->assertEquals(route('categories.show', $this->category), $this->category->viewPath());
    }
    
    /** @test */
    public function it_can_determine_if_the_category_is_active_or_disable()
    {
        $active     = create(Category::class, ['status' => Category::ACTIVE_STATUS]);
        $disable   = create(Category::class, ['status' => Category::INACTIVE_STATUS]);

        // Active Unit Tests
        $this->assertTrue($active->hasStatus(Category::ACTIVE_STATUS));
        $this->assertTrue($active->isActive());
        $this->assertFalse($active->isInActive());

        // InActive Unit Tests
        $this->assertTrue($disable->hasStatus(Category::INACTIVE_STATUS));
        $this->assertTrue($disable->isInActive());
        $this->assertFalse($disable->isActive());
    }
}
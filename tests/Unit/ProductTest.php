<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MixCode\Brand;
use MixCode\Cart;
use MixCode\Classification;
use MixCode\Color;
use MixCode\Discount;
use MixCode\Order;
use MixCode\Review;
use MixCode\Product;
use MixCode\User;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = create(Product::class)->makeVisible([
            'en_name',
            'ar_name',
            'en_overview',
            'ar_overview',
        ]);
    }

    /** @test */
    public function it_belongs_to_creator()
    {
        $this->assertInstanceOf(User::class, $this->product->creator);
    }

    /** @test */
    public function it_belongs_to_order()
    {
        create(Order::class)->real_products()->attach($this->product);

        $this->assertInstanceOf(Order::class, $this->product->orders->first());
    }

    /** @test */
    public function it_belongs_to_cart()
    {
        create(Cart::class, ['product_id' => $this->product->id]);
        $this->assertInstanceOf(Cart::class, $this->product->cart);
    }

    /** @test */
    public function it_belongs_to_discount()
    {
        $discount = create(Discount::class);
        $this->product->update(['discount_id' => $discount->id]);

        $this->assertInstanceOf(Discount::class, $this->product->fresh()->discount);
    }

    /** @test */
    public function it_belongs_to_brand()
    {
        $brand = create(Brand::class);
        $this->product->update(['brand_id' => $brand->id]);

        $this->assertInstanceOf(Brand::class, $this->product->fresh()->brand);
    }

    /** @test */
    public function it_have_many_classifications()
    {
        create(Classification::class)->products()->attach($this->product);

        $this->assertInstanceOf(Classification::class, $this->product->classifications->first());
    }

    /** @test */
    public function it_have_many_colors()
    {
        create(Color::class)->products()->attach($this->product);

        $this->assertInstanceOf(Color::class, $this->product->colors->first());
    }

    /** @test */
    public function it_have_many_child_products()
    {
        $child = create(Product::class, ['primary_product' => $this->product]);

        $this->assertInstanceOf(Product::class, $this->product->childProducts->first());

        $this->assertEquals(
            $child->en_name,
            $this->product->childProducts->first()->en_name
        );
    }

    /** @test */
    public function it_belongs_to_a_parent_product()
    {
        $child = create(Product::class, ['primary_product' => $this->product]);

        $this->assertInstanceOf(Product::class, $child->parent);

        $this->assertEquals(
            $this->product->en_name,
            $child->parent->en_name
        );
    }

    /** @test */
    public function it_have_many_categories()
    {
        $this->assertInstanceOf(Collection::class, $this->product->categories);
    }

    /** @test */
    public function it_have_many_views()
    {
        $this->assertInstanceOf(Collection::class, $this->product->views);
    }

    /** @test */
    public function it_have_many_reviews()
    {
        $this->assertInstanceOf(Collection::class, $this->product->reviews);
    }

    /** @test */
    public function it_can_determine_its_dashboard_path()
    {
        $this->assertEquals(route('dashboard.products.show', $this->product), $this->product->path());
    }

    /** @test */
    public function it_can_determine_its_api_path()
    {
        $this->assertEquals(route('api.products.show', $this->product), $this->product->apiPath());
    }

    /** @test */
    public function it_can_determine_its_view_path()
    {
        $this->assertEquals(route('products.show', $this->product), $this->product->viewPath());
    }

    /** @test */
    public function it_can_generate_price_after_discount()
    {
        $product    = create(Product::class, ['price' => 100]);
        $discount   = create(Discount::class, ['discount' => 25]); // %25

        $this->assertEquals(100, $product->price_after_discount);

        $product->update(['discount_id' => $discount->id]);

        $this->assertEquals(75, $product->fresh()->price_after_discount);
    }

    /** @test */
    public function it_can_get_discount_percentage()
    {
        $product    = create(Product::class, ['price' => 100]);
        $discount   = create(Discount::class, ['discount' => 25]); // %25
        $product->update(['discount_id' => $discount->id]);

        $this->assertEquals(25, $product->fresh()->discount_percentage);
    }

    /** @test */
    public function it_can_submit_new_review()
    {
        $this->assertEquals(0, $this->product->reviews()->count());
        $this->assertFalse($this->product->reviews()->exists());

        $this->product->submitReview(create(Review::class)->toArray());

        $this->assertEquals(1, $this->product->reviews()->count());
        $this->assertTrue($this->product->reviews()->exists());
    }

    /** @test */
    public function it_can_determine_if_the_product_is_published_or_pending()
    {
        $published     = create(Product::class, ['status' => Product::ACTIVE_STATUS]);
        $pending   = create(Product::class, ['status' => Product::INACTIVE_STATUS]);

        // Published Unit Tests
        $this->assertTrue($published->hasStatus(Product::ACTIVE_STATUS));
        $this->assertTrue($published->isPublished());
        $this->assertFalse($published->isPending());

        // Pending Unit Tests
        $this->assertTrue($pending->hasStatus(Product::INACTIVE_STATUS));
        $this->assertTrue($pending->isPending());
        $this->assertFalse($pending->isPublished());
    }

    /** @test */
    public function it_can_mark_as_published()
    {
        $this->product->markAsPublished();

        $this->assertTrue($this->product->isPublished());
    }

    /** @test */
    public function it_can_mark_as_pending()
    {
        $this->product->markAsPending();

        $this->assertTrue($this->product->isPending());
    }

    /** @test */
    public function it_can_generate_reviews_rate()
    {
        // Given we Have product with 3 different views
        $product = create(Product::class);

        $product->submitReview(
            create(Review::class, [
                'product_id' => $product->id,
                'rate'      => 2,
            ])->toArray()
        );

        $product->submitReview(
            create(Review::class, [
                'product_id' => $product->id,
                'rate'      => 4,
            ])->toArray()
        );

        $product->submitReview(
            create(Review::class, [
                'product_id' => $product->id,
                'rate'      => 5,
            ])->toArray()
        );

        /* 
         Equation:
             average_rate: total sum of product reviews rate / total sum of product reviews
         
         EX:
             average_rate: (2 + 4 + 5) / 3
             average_rate: 11 / 3
             average_rate: 3.66 
             average_rate: 4 // with round()
         Note: 
             We using avg() method from laravel that it work to make query with AVG() MySql Function
             and it make our equation by it self
     */

        $this->assertEquals(4, $product->average_rate);
    }
}

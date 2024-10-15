<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MixCode\Category;
use MixCode\City;
use MixCode\Country;
use MixCode\Product;

class SearchAndFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->japan_country = create(Country::class, ['en_name' => 'Japan']);
        $this->tokyo_city = create(City::class, ['en_name' => 'Tokyo']);
        $this->kyoto_city = create(City::class, ['en_name' => 'Kyoto']);
        
        $this->safari_category = create(Category::class, ['en_name' => 'Safari']);
        $this->tour_category = create(Category::class, ['en_name' => 'tour']);
    }

    /** @test */
    public function it_can_search_by_destination()
    {
        // Given we have 4 products , 3 of them belongs to same country and 2 of them belongs to same city
        $tokyo_product_one = create(Product::class, ['country_id' => $this->japan_country, 'city_id' => $this->tokyo_city])->makeVisible('en_name');
        $tokyo_product_two = create(Product::class, ['country_id' => $this->japan_country, 'city_id' => $this->tokyo_city])->makeVisible('en_name');
        $tokyo_product_three = create(Product::class, ['country_id' => $this->japan_country, 'city_id' => $this->kyoto_city])->makeVisible('en_name');
        $product_four = create(Product::class)->makeVisible('en_name');

        // When we hit search endpoint to search with "Japan" country
        $response = $this->getJson(route('api.products.search', ['country' => $this->japan_country->id]));

        // Then we should see only all "Japan" related products
        $response->assertSee($tokyo_product_one->en_name);
        $response->assertSee($tokyo_product_two->en_name);
        $response->assertSee($tokyo_product_three->en_name);
        $response->assertDontSee($product_four->en_name);

        // When we hit search endpoint to search with "Tokyo" city
        $response = $this->getJson(route('api.products.search', ['city' => $this->tokyo_city->id]));

        // Then we should see only all "Tokyo" related products
        $response->assertSee($tokyo_product_one->en_name);
        $response->assertSee($tokyo_product_two->en_name);
        $response->assertDontSee($tokyo_product_three->en_name);
        $response->assertDontSee($product_four->en_name);

        // When we hit search endpoint to search with "Japan" Country and "Kyoto" city
        $response = $this->getJson(route('api.products.search', ['country' => $this->japan_country->id, 'city' => $this->kyoto_city->id]));

        // Then we should see only all "Japan" Country and "Kyoto" city related products
        $response->assertDontSee($tokyo_product_one->en_name);
        $response->assertDontSee($tokyo_product_two->en_name);
        $response->assertSee($tokyo_product_three->en_name);
        $response->assertDontSee($product_four->en_name);
    }
    
    /** @test */
    public function it_can_search_by_date()
    {
        // Given we have 4 products, 2 of them have date to take off this week
        $product_one = create(Product::class, [
            'date_from' => today()->startOfWeek()->toDateString(), 
            'date_to' => today()->endOfWeek()->toDateString()
        ])->makeVisible('en_name');
        
        $product_two = create(Product::class, [
            'date_from' => today()->startOfWeek()->toDateString(), 
            'date_to' => today()->endOfWeek()->toDateString()
        ])->makeVisible('en_name');

        $product_three = create(Product::class, [
            'date_from' => today()->addWeek()->startOfWeek()->toDateString(), 
            'date_to' => today()->addWeek()->endOfWeek()->toDateString()
        ])->makeVisible('en_name');
        
        $product_four = create(Product::class, [
            'date_from' => today()->subWeek()->startOfWeek()->toDateString(), 
            'date_to' => today()->subWeek()->endOfWeek()->toDateString()
        ])->makeVisible('en_name');
    
        // When we hit search endpoint to search with "date_from"
        $search_date = today()->startOfWeek()->toDateString();
        $response = $this->getJson(route('api.products.search', ['date_from' => $search_date]));

        // Then we should see only all products After Or Equal "$search_date"
        $response->assertSee($product_one->en_name);
        $response->assertSee($product_two->en_name);
        $response->assertSee($product_three->en_name);
        $response->assertDontSee($product_four->en_name);
        
        // When we hit search endpoint to search with "date_to"
        $search_date = today()->endOfWeek()->toDateString();
        $response = $this->getJson(route('api.products.search', ['date_to' => $search_date]));

        // Then we should see only all products Before Or Equal "$search_date"
        $response->assertSee($product_one->en_name);
        $response->assertSee($product_two->en_name);
        $response->assertDontSee($product_three->en_name);
        $response->assertSee($product_four->en_name);

        // When we hit search endpoint to search with "date_from" and "date_to"
        $search_start_date = today()->startOfWeek()->toDateString();
        $search_end_date = today()->endOfWeek()->toDateString();
        $response = $this->getJson(route('api.products.search', ['date_from' => $search_start_date, 'date_to' => $search_end_date]));

        // Then we should see only all products between "$search_start_date" and "$search_end_date"
        $response->assertSee($product_one->en_name);
        $response->assertSee($product_two->en_name);
        $response->assertDontSee($product_three->en_name);
        $response->assertDontSee($product_four->en_name);

    }
    
    /** @test */
    public function it_can_filter_by_price_range()
    {
        // Given we have 4 products with different prices
        $product_one = create(Product::class, ['price' => 1000])->makeVisible('en_name');
        
        $product_two = create(Product::class, ['price' => 2000])->makeVisible('en_name');

        $product_three = create(Product::class, ['price' => 4000])->makeVisible('en_name');
        
        $product_four = create(Product::class, ['price' => 500])->makeVisible('en_name');
    
        // When we hit search endpoint to search with "price_from"
        $search_price = 1000;
        $response = $this->getJson(route('api.products.search', ['price_from' => $search_price]));

        // Then we should see only all products After Or Equal "$search_price"
        $response->assertSee($product_one->en_name);
        $response->assertSee($product_two->en_name);
        $response->assertSee($product_three->en_name);
        $response->assertDontSee($product_four->en_name);
        
        // When we hit search endpoint to search with "price_to"
        $search_price = 2000;
        $response = $this->getJson(route('api.products.search', ['price_to' => $search_price]));

        // Then we should see only all products Before Or Equal "$search_price"
        $response->assertSee($product_one->en_name);
        $response->assertSee($product_two->en_name);
        $response->assertDontSee($product_three->en_name);
        $response->assertSee($product_four->en_name);

        // When we hit search endpoint to search with "price_from" and "price_to"
        $search_start_price = 950;
        $search_end_price = 2550;
        $response = $this->getJson(route('api.products.search', ['price_from' => $search_start_price, 'price_to' => $search_end_price]));

        // Then we should see only all products Equal "$search_price"
        $response->assertSee($product_one->en_name);
        $response->assertSee($product_two->en_name);
        $response->assertDontSee($product_three->en_name);
        $response->assertDontSee($product_four->en_name);
    }
    
    /** @test */
    public function it_can_filter_and_order_by_price()
    {
        // Given we have 4 products with different prices
        $product_one = create(Product::class, ['price' => 1000])->makeVisible('en_name');
        
        $product_two = create(Product::class, ['price' => 2000])->makeVisible('en_name');

        $product_three = create(Product::class, ['price' => 4000])->makeVisible('en_name');
        
        $product_four = create(Product::class, ['price' => 500])->makeVisible('en_name');
    
        // When we hit search endpoint to search with "price_low"
        $response = $this->getJson(route('api.products.search', ['price_low' => true]));

        // Then we should see only all products ordered by lower price
        $response->assertSeeInOrder([$product_four->en_name, $product_one->en_name, $product_two->en_name, $product_three->en_name]);
 
        // When we hit search endpoint to search with "price_high"
        $response = $this->getJson(route('api.products.search', ['price_high' => true]));

        // Then we should see only all products ordered by highest price
        $response->assertSeeInOrder([$product_three->en_name, $product_two->en_name, $product_one->en_name, $product_four->en_name]);
    }
    
    /** @test */
    public function it_can_filter_and_order_by_views()
    {
        // Given we have 4 products with different views count
        $product_one = create(Product::class, ['views_count' => 10])->makeVisible('en_name');
        
        $product_two = create(Product::class, ['views_count' => 20])->makeVisible('en_name');

        $product_three = create(Product::class, ['views_count' => 40])->makeVisible('en_name');
        
        $product_four = create(Product::class, ['views_count' => 5])->makeVisible('en_name');
    
        // When we hit search endpoint to search with "most_popular"
        $response = $this->getJson(route('api.products.search', ['most_popular' => true]));

        // Then we should see only all products ordered by highest views count
        $response->assertSeeInOrder([$product_three->en_name, $product_two->en_name, $product_one->en_name, $product_four->en_name]);
    }
    
    /** @test */
    public function it_can_filter_and_order_by_created_date()
    {
        // Given we have 4 products created in different times
        $product_one = create(Product::class, ['created_at' => now()->toDateString()])->makeVisible('en_name');
        
        $product_two = create(Product::class, ['created_at' => now()->addDay()->toDateString()])->makeVisible('en_name');

        $product_three = create(Product::class, ['created_at' => now()->addDays(2)->toDateString()])->makeVisible('en_name');
        
        $product_four = create(Product::class, ['created_at' => now()->subDay()->toDateString()])->makeVisible('en_name');
    
        // When we hit search endpoint to search with "most_recently"
        $response = $this->getJson(route('api.products.search', ['most_recently' => true]));

        // Then we should see only all products ordered by created_at date
        $response->assertSeeInOrder([$product_three->en_name, $product_two->en_name, $product_one->en_name, $product_four->en_name]);
    }

    /** @test */
    public function it_can_filter_by_categories()
    {
        // Given we have 4 products, 2 of them belongs to different category and 2 of them belongs to all categories
        $safari_product = create(Product::class)->makeVisible('en_name');
        $tour_product = create(Product::class)->makeVisible('en_name');
        $safari_and_tour_product_one = create(Product::class)->makeVisible('en_name');
        $safari_and_tour_product_two = create(Product::class)->makeVisible('en_name');
        
        // Attach Products To Categories
        $safari_product->categories()->attach($this->safari_category);
        $tour_product->categories()->attach($this->tour_category);
        
        $safari_and_tour_product_one->categories()->attach($this->safari_category);
        $safari_and_tour_product_one->categories()->attach($this->tour_category);

        $safari_and_tour_product_two->categories()->attach($this->safari_category);
        $safari_and_tour_product_two->categories()->attach($this->tour_category);        

        // When we hit search endpoint to search with "Safari" category
        $response = $this->getJson(route('api.products.search', ['categories' => [$this->safari_category->id]]));

        // Then we should see only all "Safari" related products
        $response->assertSee($safari_product->en_name);
        $response->assertSee($safari_and_tour_product_one->en_name);
        $response->assertSee($safari_and_tour_product_two->en_name);
        $response->assertDontSee($tour_product->en_name);
        
        // When we hit search endpoint to search with "Tour" category
        $response = $this->getJson(route('api.products.search', ['categories' => [$this->tour_category->id]]));

        // Then we should see only all "Tour" related products
        $response->assertDontSee($safari_product->en_name);
        $response->assertSee($safari_and_tour_product_one->en_name);
        $response->assertSee($safari_and_tour_product_two->en_name);
        $response->assertSee($tour_product->en_name);
        
        // When we hit search endpoint to search with "Safari" and "Tour" categories
        $response = $this->getJson(route('api.products.search', ['categories' => [$this->safari_category->id, $this->tour_category->id]]));

        // Then we should see only all "Safari" and "Tour" related products
        $response->assertSee($safari_product->en_name);
        $response->assertSee($safari_and_tour_product_one->en_name);
        $response->assertSee($safari_and_tour_product_two->en_name);
        $response->assertSee($tour_product->en_name);
    }

}
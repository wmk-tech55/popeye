<?php

namespace Tests\Feature\Dashboard\Products;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use MixCode\Addition;
use MixCode\Category;
use MixCode\City;
use MixCode\Country;
use MixCode\Feature;
use MixCode\Language;
use MixCode\Product;
use MixCode\User;
use Storage;

class UpdateProductsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('test');

        $this->visibleFields = [
            'en_name', 
            'ar_name', 
            'en_duration', 
            'ar_duration', 
            'en_address', 
            'ar_address', 
            'en_overview', 
            'ar_overview', 
            'en_highlights', 
            'ar_highlights'
        ];

        $this->relations = [
            'images'            => [UploadedFile::fake()->image('image.png')],
            'categories_id'     => [create(Category::class)->id],
            'features_id'       => [create(Feature::class)->id],
            'languages_id'      => [create(Language::class)->id],
        ];

        $this->admin = create(User::class, ['type' => User::ADMIN_TYPE]);
        $this->adminProduct = create(Product::class, ['creator_id' => $this->admin->id])->makeVisible($this->visibleFields);
        
        $this->company = create(User::class, ['type' => User::COMPANY_TYPE]);
        $this->companyProduct = create(Product::class, ['creator_id' => $this->company->id])->makeVisible($this->visibleFields);

        $this->Product = create(Product::class)->makeVisible($this->visibleFields);
    }

    /** @test */
    public function non_authenticate_administrators_or_companies_cant_update_existing_Product()
    {
        $this->withExceptionHandling();

        // Given We Have A User Not An Admin or Company
        $this->signIn(create(User::class, ['type' => User::CUSTOMER_TYPE]));

        // When We Hit The Product Update Endpoint
        $response = $this->patchJson(route('dashboard.Products.update', $this->Product));

        // Then it Should Give An Error un Authorized
        $response->assertRedirect('/login');

        // Given We Have A non authenticate User
        auth()->logout();

        // When We Hit The Product Update Endpoint
        $response = $this->patchJson(route('dashboard.Products.update', $this->Product));

        // Then it Should Give An Error un Authorized
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticate_administrators_can_update_their_existing_Product()
    {
        // Given We Have An Admin And Product
        $this->signInAsAdmin($this->admin);
        
        // Set Product Name
        $updated_data = ['en_name' => 'Updated Product Name'];
        $data = array_merge($this->adminProduct->toArray(),  $updated_data, $this->relations);

        // When We Hit The Product Update Endpoint
        $response = $this->patchJson(route('dashboard.Products.update', $this->adminProduct), $data);

        // Then it Should Redirect to the Product show page And The Product name Should Be Updated
        $response->assertRedirect($this->adminProduct->path());

        $this->assertEquals($updated_data, ['en_name' => $this->adminProduct->fresh()->en_name]);
    }

    /** @test */
    public function authenticate_companies_can_update_their_existing_Product()
    {
        // Given We Have A Company And Product
        $this->signIn($this->company);
        
        // Set Product Name
        $updated_data = ['en_name' => 'Updated Product Name'];
        $data = array_merge($this->companyProduct->toArray(), $updated_data, $this->relations);

        // When We Hit The Product Update Endpoint
        $response = $this->patchJson(route('dashboard.Products.update', $this->companyProduct), $data);

        // Then it Should Redirect to the Product show page And The Product name Should Be Updated
        $response->assertRedirect($this->companyProduct->path());

        $this->assertEquals($updated_data, ['en_name' => $this->companyProduct->fresh()->en_name]);
    }

    /** @test */
    public function authenticate_users_can_update_their_Product_custom_dates_by_overriding_it()
    {
        // Given We Have A Company And Product and custom dates
        $this->signIn(create(User::class, ['type' => User::COMPANY_TYPE]));

        $Product = create(Product::class, ['creator_id' => auth()->id()])->makeVisible($this->visibleFields);
        $dates = [
            now()->toDateString(),
            now()->addDay()->toDateString(),
            now()->addMonth()->toDateString(),
        ];

        $Product->addCustomDates($dates);

        $data = array_merge($Product->toArray(), $this->relations, [
            'dates' => [
                now()->addYear()->toDateString(),
            ],
        ]);

        // When We Hit The Product update Endpoint
        $this->patchJson(route('dashboard.Products.update', $Product), $data);

        // Then Product should has 1 custom dates
        $this->assertEquals(1, $Product->dates()->count());
    }

    /** @test */
    public function Product_required_a_valid_en_name()
    {
        $this->updateProduct(['en_name' => null])->assertSessionHasErrors('en_name');
    }
    
    /** @test */
    public function Product_required_a_valid_ar_name()
    {
        $this->updateProduct(['ar_name' => null])->assertSessionHasErrors('ar_name');
    }
    
    /** @test */
    public function Product_required_a_valid_price()
    {
        $this->updateProduct(['price' => 'not-valid-price'])->assertSessionHasErrors('price');
        $this->updateProduct(['price' => null])->assertSessionHasErrors('price');
        $this->updateProduct(['price' => 123.45])->assertSessionHasNoErrors('price');
    }
    
    /** @test */
    public function Product_required_a_valid_map_url()
    {
        $this->updateProduct(['map_url' => 'not-valid-map-url'])->assertSessionHasErrors('map_url');
        $this->updateProduct(['map_url' => null])->assertSessionHasErrors('map_url');
        $this->updateProduct(['map_url' => 'https://maps.google.com'])->assertSessionHasNoErrors('map_url');
    }

    /** @test */
    public function Product_required_a_valid_order_limit()
    {
        $this->updateProduct(['order_limit' => 'not-valid-order-limit'])->assertSessionHasErrors('order_limit');
        $this->updateProduct(['order_limit' => null])->assertSessionHasErrors('order_limit');
        $this->updateProduct(['order_limit' => 5])->assertSessionHasNoErrors('order_limit');
    }
    
    /** @test */
    public function Product_required_a_valid_en_duration()
    {
        $this->updateProduct(['en_duration' => null])->assertSessionHasErrors('en_duration');
    }

    /** @test */
    public function Product_required_a_valid_ar_duration()
    {
        $this->updateProduct(['ar_duration' => null])->assertSessionHasErrors('ar_duration');
    }

    /** @test */
    public function Product_required_a_valid_date_type()
    {
        $this->updateProduct(['date_type' => null])->assertSessionHasErrors('date_type');
        $this->updateProduct(['date_type' => Product::DATE_TYPE_CUSTOM])->assertSessionHasNoErrors('date_type');
        $this->updateProduct(['date_type' => Product::DATE_TYPE_RANGE])->assertSessionHasNoErrors('date_type');
    }

    /** @test */
    public function Product_may_accept_a_valid_date_from_and_it_must_be_before_or_equals_date_to()
    {
        $this->updateProduct(['dates' => '2020-01-01', 'date_from' => null, 'date_to' => null])->assertSessionHasNoErrors('date_from');
        
        // if "dates" not found then "date_from" will be required
        $this->updateProduct(['dates' => null, 'date_from' => null, 'date_to' => null])->assertSessionHasErrors('date_from');
        
        // if "date_to" found then "date_from" will be required
        $this->updateProduct(['dates' => null, 'date_from' => null, 'date_to' => '2020-01-01'])->assertSessionHasErrors('date_from');
        
        // if "dates" found and "date_to" found then "date_from" will be required
        $this->updateProduct(['dates' => '2020-01-01', 'date_from' => null, 'date_to' => '2020-01-01'])->assertSessionHasErrors('date_from');
        
        // "date_from" can't be before "date_to"
        $this->updateProduct(['date_from' => '2020-02-02', 'date_to' => '2020-02-01'])->assertSessionHasErrors('date_from');
        
        // "date_from" can be equal or after "date_to"
        $this->updateProduct(['date_from' => '2020-02-02', 'date_to' => '2020-02-02'])->assertSessionHasNoErrors('date_from');
        $this->updateProduct(['date_from' => '2020-02-02', 'date_to' => '2020-02-03'])->assertSessionHasNoErrors('date_from');
    }

    /** @test */
    public function Product_may_accept_a_valid_date_to_and_it_must_be_after_or_equals_date_from()
    {
        $this->updateProduct(['date_to' => null])->assertSessionHasNoErrors('date_to');

        // "date_to" can't be before "date_from"
        $this->updateProduct(['date_to' => '2020-02-01', 'date_from' => '2020-02-02'])->assertSessionHasErrors('date_to');

        // "date_to" can't be equal or after "date_from"
        $this->updateProduct(['date_to' => '2020-02-02', 'date_from' => '2020-02-02'])->assertSessionHasNoErrors('date_to');
        $this->updateProduct(['date_to' => '2020-02-03', 'date_from' => '2020-02-02'])->assertSessionHasNoErrors('date_to');
    }

    
    /** @test */
    public function Product_required_a_valid_en_overview()
    {
        $this->updateProduct(['en_overview' => null])->assertSessionHasErrors('en_overview');
    }
    
    /** @test */
    public function Product_required_a_valid_ar_overview()
    {
        $this->updateProduct(['ar_overview' => null])->assertSessionHasErrors('ar_overview');
    }
    
    /** @test */
    public function Product_required_a_valid_en_highlights()
    {
        $this->updateProduct(['en_highlights' => null])->assertSessionHasErrors('en_highlights');
    }

    /** @test */
    public function Product_required_a_valid_ar_highlights()
    {
        $this->updateProduct(['ar_highlights' => null])->assertSessionHasErrors('ar_highlights');
    }

    /** @test */
    public function Product_required_a_valid_en_address()
    {
        $this->updateProduct(['en_address' => null])->assertSessionHasErrors('en_address');
    }
    
    /** @test */
    public function Product_required_a_valid_ar_address()
    {
        $this->updateProduct(['ar_address' => null])->assertSessionHasErrors('ar_address');
    }
    
    /** @test */
    public function Product_required_a_valid_integer_adults()
    {
        $this->updateProduct(['adults' => null])->assertSessionHasErrors('adults');
    }
    
    /** @test */
    public function Product_required_a_valid_integer_children()
    {
        $this->updateProduct(['children' => null])->assertSessionHasErrors('children');
    }
    
    /** @test */
    public function Product_required_a_valid_integer_infant()
    {
        $this->updateProduct(['infant' => null])->assertSessionHasErrors('infant');
    }

    /** @test */
    public function Product_require_a_valid_and_exists_country()
    {
        $this->updateProduct(['country_id' => 'not-valid-country'])->assertSessionHasErrors('country_id');
        $this->updateProduct(['country_id' => null])->assertSessionHasErrors('country_id');
        $this->updateProduct(['country_id' => create(Country::class)->id])->assertSessionHasNoErrors('country_id');
    }

    /** @test */
    public function Product_require_a_valid_and_exists_city()
    {
        $this->updateProduct(['city_id' => 'not-valid-city'])->assertSessionHasErrors('city_id');
        $this->updateProduct(['city_id' => null])->assertSessionHasErrors('city_id');
        $this->updateProduct(['city_id' => create(City::class)->id])->assertSessionHasNoErrors('city_id');
    }

    /** @test */
    public function Product_require_a_valid_and_exists_categories()
    {
        $this->updateProduct(['categories_id' => 'not-valid-categories'])->assertSessionHasErrors('categories_id');
        $this->updateProduct(['categories_id' => null])->assertSessionHasErrors('categories_id');
        $this->updateProduct(['categories_id' => [create(Category::class)->id]])->assertSessionHasNoErrors('categories_id');
    }

    /** @test */
    public function Product_may_accept_a_valid_and_exists_features()
    {
        $this->updateProduct(['features_id' => 'not-valid-features'])->assertSessionHasErrors('features_id');
        $this->updateProduct(['features_id' => null])->assertSessionHasErrors('features_id');
        $this->updateProduct(['features_id' => [create(Feature::class)->id]])->assertSessionHasNoErrors('features_id');
    }

    /** @test */
    public function Product_require_a_valid_and_exists_languages()
    {
        $this->updateProduct(['languages_id' => 'not-valid-languages'])->assertSessionHasErrors('languages_id');
        $this->updateProduct(['languages_id' => null])->assertSessionHasErrors('languages_id');
        $this->updateProduct(['languages_id' => [create(Language::class)->id]])->assertSessionHasNoErrors('languages_id');
    }

    /** @test */
    public function Product_may_accept_a_valid_and_exists_additions()
    {
        $this->updateProduct(['additions_id' => 'not-valid-additions'])->assertSessionHasErrors('additions_id');
        $this->updateProduct(['additions_id' => []])->assertSessionHasErrors('additions_id');
        $this->updateProduct(['additions_id' => null])->assertSessionHasNoErrors('additions_id');
        $this->updateProduct(['additions_id' => [create(Addition::class)->id]])->assertSessionHasNoErrors('additions_id');
    }

    /** @test */
    public function Product_may_accept_a_valid_images()
    {
        $this->updateProduct(['images' => 'not-valid-url'])->assertSessionHasErrors('images');
        $this->updateProduct(['images' => null])->assertSessionHasNoErrors('images');
    }

    protected function updateProduct($data)
    {
        $this->withExceptionHandling();

        // Given We Have An Admin And Product
        $this->signInAsAdmin($this->admin);

        $data = array_merge($this->Product->toArray(), $this->relations, $data);

        // When We Hit The Product Update Endpoint
        return $this->patch(route('dashboard.Products.update', $this->Product), $data);
    }
}

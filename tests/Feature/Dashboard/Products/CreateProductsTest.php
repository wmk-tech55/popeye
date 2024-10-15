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
use MixCode\Notifications\NewProductHasBeenCreated;
use MixCode\Product;
use MixCode\User;
use Notification;
use Storage;

class CreateProductsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('test');

        $this->relations = [
            'images'            => [UploadedFile::fake()->image('image.png')],
            'categories_id'     => [create(Category::class)->id],
            'features_id'       => [create(Feature::class)->id],
            'languages_id'      => [create(Language::class)->id],
        ];

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
    }

    /** @test */
    public function non_authenticate_or_companies_administrators_cant_create_new_product()
    {
        $this->withExceptionHandling();

        // Given We Have A User Not An Admin Or Company
        $this->signIn(create(User::class, ['type' => User::CUSTOMER_TYPE]));

        // When We Hit The Product Store Endpoint
        $response = $this->postJson(route('dashboard.products.store'));

        // Then it Should Give An Error un Authorized
        $response->assertRedirect('/login');

        // Given We Have A non authenticate User
        auth()->logout();

        // When We Hit The Product Store Endpoint
        $response = $this->postJson(route('dashboard.products.store'));

        // Then it Should Give An Error un Authorized
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticate_administrators_can_create_new_product()
    {
        // Given We Have An Admin And Product
        $this->signInAsAdmin();

        $product = make(Product::class)->makeVisible($this->visibleFields);
        
        $data = array_merge($product->toArray(), $this->relations);

        // When We Hit The Product Store Endpoint
        $this->postJson(route('dashboard.products.store'), $data);

        // Then Products Count Should Be 1
        $this->assertEquals(1, Product::count());
    }

    /** @test */
    public function authenticate_companies_can_create_new_product()
    {
        // Given We Have A Company And Product
        $this->signIn(create(User::class, ['type' => User::COMPANY_TYPE]));

        $product = make(Product::class)->makeVisible($this->visibleFields);
        $data = array_merge($product->toArray(), $this->relations);
        
        // When We Hit The Product Store Endpoint
        $this->postJson(route('dashboard.products.store'), $data);

        // Then Products Count Should Be 1
        $this->assertEquals(1, Product::count());
    }

    /** @test */
    public function authenticate_users_can_create_new_product_with_custom_dates()
    {
        // Given We Have A Company And Product and custom dates
        $this->signIn(create(User::class, ['type' => User::COMPANY_TYPE]));

        $product = make(Product::class)->makeVisible($this->visibleFields);

        $data = array_merge($product->toArray(), $this->relations, [
            'dates' => [
                now()->toDateString(),
                now()->addDay()->toDateString(),
                now()->addMonth()->toDateString(),
            ],
        ]);
        
        // When We Hit The Product Store Endpoint
        $this->postJson(route('dashboard.products.store'), $data);

        // Then Products Count Should Be 1 and have 3 custom dates
        $this->assertEquals(1, Product::count());
        $this->assertEquals(3, Product::first()->dates()->count());
    }

    /** @test */
    public function an_email_should_be_sent_after_an_authenticate_users_can_create_a_new_product()
    {
        Notification::fake();

        // Given We Have An Admin , Company And Product and custom dates
        $admin = create(User::class, ['type' => User::ADMIN_TYPE]);
        $this->signIn(create(User::class, ['type' => User::COMPANY_TYPE]));

        $product = make(Product::class)->makeVisible($this->visibleFields);
        $data = array_merge($product->toArray(), $this->relations);

        // When We Hit The Product Store Endpoint
        $this->postJson(route('dashboard.products.store'), $data);

        // Then Notification should send to administrators
        Notification::assertSentTo($admin, NewProductHasBeenCreated::class);
    }

    /** @test */
    public function an_email_should_not_be_sent_after_an_authenticate_admin_can_create_a_new_product()
    {
        Notification::fake();

        // Given We Have An Admin , Company And Product and custom dates
        $admin = create(User::class, ['type' => User::ADMIN_TYPE]);
        $this->signIn(create(User::class, ['type' => User::ADMIN_TYPE]));

        $product = make(Product::class)->makeVisible($this->visibleFields);
        $data = array_merge($product->toArray(), $this->relations);

        // When We Hit The Product Store Endpoint
        $this->postJson(route('dashboard.products.store'), $data);

        // Then Notification should send to administrators
        Notification::assertNotSentTo($admin, NewProductHasBeenCreated::class);
    }

    /** @test */
    public function product_required_a_valid_en_name()
    {
        $this->createNewProduct(['en_name' => null])->assertSessionHasErrors('en_name');
    }

    /** @test */
    public function product_required_a_valid_ar_name()
    {
        $this->createNewProduct(['ar_name' => null])->assertSessionHasErrors('ar_name');
    }

    /** @test */
    public function product_required_a_valid_price()
    {
        $this->createNewProduct(['price' => 'not-valid-price'])->assertSessionHasErrors('price');
        $this->createNewProduct(['price' => null])->assertSessionHasErrors('price');
        $this->createNewProduct(['price' => 123.45])->assertSessionHasNoErrors('price');
    }

    /** @test */
    public function product_required_a_valid_map_url()
    {
        $this->createNewProduct(['map_url' => 'not-valid-map-url'])->assertSessionHasErrors('map_url');
        $this->createNewProduct(['map_url' => null])->assertSessionHasErrors('map_url');
        $this->createNewProduct(['map_url' => 'https://maps.google.com'])->assertSessionHasNoErrors('map_url');
    }

    /** @test */
    public function product_required_a_valid_order_limit()
    {
        $this->createNewProduct(['order_limit' => 'not-valid-order-limit'])->assertSessionHasErrors('order_limit');
        $this->createNewProduct(['order_limit' => null])->assertSessionHasErrors('order_limit');
        $this->createNewProduct(['order_limit' => 5])->assertSessionHasNoErrors('order_limit');
    }

    /** @test */
    public function product_required_a_valid_en_duration()
    {
        $this->createNewProduct(['en_duration' => null])->assertSessionHasErrors('en_duration');
    }

    /** @test */
    public function product_required_a_valid_ar_duration()
    {
        $this->createNewProduct(['ar_duration' => null])->assertSessionHasErrors('ar_duration');
    }

    /** @test */
    public function product_required_a_valid_date_type()
    {
        $this->createNewProduct(['date_type' => null])->assertSessionHasErrors('date_type');
        $this->createNewProduct(['date_type' => Product::DATE_TYPE_CUSTOM])->assertSessionHasNoErrors('date_type');
        $this->createNewProduct(['date_type' => Product::DATE_TYPE_RANGE])->assertSessionHasNoErrors('date_type');
    }

    /** @test */
    public function product_may_accept_a_valid_date_from_and_it_must_be_before_or_equals_date_to()
    {
        $this->createNewProduct(['dates' => '2020-01-01', 'date_from' => null, 'date_to' => null])->assertSessionHasNoErrors('date_from');
        
        // if "dates" not found then "date_from" will be required
        $this->createNewProduct(['dates' => null, 'date_from' => null, 'date_to' => null])->assertSessionHasErrors('date_from');
        
        // if "date_to" found then "date_from" will be required
        $this->createNewProduct(['dates' => null, 'date_from' => null, 'date_to' => '2020-01-01'])->assertSessionHasErrors('date_from');
        
        // if "dates" found and "date_to" found then "date_from" will be required
        $this->createNewProduct(['dates' => '2020-01-01', 'date_from' => null, 'date_to' => '2020-01-01'])->assertSessionHasErrors('date_from');
        
        // "date_from" can't be before "date_to"
        $this->createNewProduct(['date_from' => '2020-02-02', 'date_to' => '2020-02-01'])->assertSessionHasErrors('date_from');
        
        // "date_from" can be equal or after "date_to"
        $this->createNewProduct(['date_from' => '2020-02-02', 'date_to' => '2020-02-02'])->assertSessionHasNoErrors('date_from');
        $this->createNewProduct(['date_from' => '2020-02-02', 'date_to' => '2020-02-03'])->assertSessionHasNoErrors('date_from');
    }

    /** @test */
    public function product_may_accept_a_valid_date_to_and_it_must_be_after_or_equals_date_from()
    {
        $this->createNewProduct(['date_to' => null])->assertSessionHasNoErrors('date_to');

        // "date_to" can't be before "date_from"
        $this->createNewProduct(['date_to' => '2020-02-01', 'date_from' => '2020-02-02'])->assertSessionHasErrors('date_to');

        // "date_to" can't be equal or after "date_from"
        $this->createNewProduct(['date_to' => '2020-02-02', 'date_from' => '2020-02-02'])->assertSessionHasNoErrors('date_to');
        $this->createNewProduct(['date_to' => '2020-02-03', 'date_from' => '2020-02-02'])->assertSessionHasNoErrors('date_to');
    }

    /** @test */
    public function product_required_a_valid_en_overview()
    {
        $this->createNewProduct(['en_overview' => null])->assertSessionHasErrors('en_overview');
    }

    /** @test */
    public function product_required_a_valid_ar_overview()
    {
        $this->createNewProduct(['ar_overview' => null])->assertSessionHasErrors('ar_overview');
    }

    /** @test */
    public function product_required_a_valid_en_highlights()
    {
        $this->createNewProduct(['en_highlights' => null])->assertSessionHasErrors('en_highlights');
    }

    /** @test */
    public function product_required_a_valid_ar_highlights()
    {
        $this->createNewProduct(['ar_highlights' => null])->assertSessionHasErrors('ar_highlights');
    }

    /** @test */
    public function product_required_a_valid_en_address()
    {
        $this->createNewProduct(['en_address' => null])->assertSessionHasErrors('en_address');
    }

    /** @test */
    public function product_required_a_valid_ar_address()
    {
        $this->createNewProduct(['ar_address' => null])->assertSessionHasErrors('ar_address');
    }

    /** @test */
    public function product_required_a_valid_integer_adults()
    {
        $this->createNewProduct(['adults' => null])->assertSessionHasErrors('adults');
    }

    /** @test */
    public function product_required_a_valid_integer_children()
    {
        $this->createNewProduct(['children' => null])->assertSessionHasErrors('children');
    }

    /** @test */
    public function product_required_a_valid_integer_infant()
    {
        $this->createNewProduct(['infant' => null])->assertSessionHasErrors('infant');
    }

    /** @test */
    public function product_require_a_valid_and_exists_country()
    {
        $this->createNewProduct(['country_id' => 'not-valid-country'])->assertSessionHasErrors('country_id');
        $this->createNewProduct(['country_id' => null])->assertSessionHasErrors('country_id');
        $this->createNewProduct(['country_id' => create(Country::class)->id])->assertSessionHasNoErrors('country_id');
    }

    /** @test */
    public function product_require_a_valid_and_exists_city()
    {
        $this->createNewProduct(['city_id' => 'not-valid-city'])->assertSessionHasErrors('city_id');
        $this->createNewProduct(['city_id' => null])->assertSessionHasErrors('city_id');
        $this->createNewProduct(['city_id' => create(City::class)->id])->assertSessionHasNoErrors('city_id');
    }

    /** @test */
    public function product_require_a_valid_and_exists_categories()
    {
        $this->createNewProduct(['categories_id' => 'not-valid-categories'])->assertSessionHasErrors('categories_id');
        $this->createNewProduct(['categories_id' => null])->assertSessionHasErrors('categories_id');
        $this->createNewProduct(['categories_id' => [create(Category::class)->id]])->assertSessionHasNoErrors('categories_id');
    }

    /** @test */
    public function product_may_accept_a_valid_and_exists_features()
    {
        $this->createNewProduct(['features_id' => 'not-valid-features'])->assertSessionHasErrors('features_id');
        $this->createNewProduct(['features_id' => null])->assertSessionHasErrors('features_id');
        $this->createNewProduct(['features_id' => [create(Feature::class)->id]])->assertSessionHasNoErrors('features_id');
    }

    /** @test */
    public function product_require_a_valid_and_exists_languages()
    {
        $this->createNewProduct(['languages_id' => 'not-valid-languages'])->assertSessionHasErrors('languages_id');
        $this->createNewProduct(['languages_id' => null])->assertSessionHasErrors('languages_id');
        $this->createNewProduct(['languages_id' => [create(Language::class)->id]])->assertSessionHasNoErrors('languages_id');
    }

    /** @test */
    public function product_may_accept_a_valid_and_exists_additions()
    {
        $this->createNewProduct(['additions_id' => 'not-valid-additions'])->assertSessionHasErrors('additions_id');
        $this->createNewProduct(['additions_id' => []])->assertSessionHasErrors('additions_id');
        $this->createNewProduct(['additions_id' => null])->assertSessionHasNoErrors('additions_id');
        $this->createNewProduct(['additions_id' => [create(Addition::class)->id]])->assertSessionHasNoErrors('additions_id');
    }

    /** @test */
    public function product_may_accept_a_valid_images()
    {
        $this->createNewProduct(['images' => 'not-valid-url'])->assertSessionHasErrors('images');
        $this->createNewProduct(['images' => null])->assertSessionHasErrors('images');
    }

    protected function createNewProduct($overrides)
    {
        $this->withExceptionHandling();

        // Given We Have An Admin And Product
        $this->signInAsAdmin();

        $overrides = array_merge($this->relations, $overrides);
        
        $product = make(Product::class, $overrides)->makeVisible($this->visibleFields);

        // When We Hit The Product Store Endpoint
        return $this->post(route('dashboard.products.store'), $product->toArray());
    }
}

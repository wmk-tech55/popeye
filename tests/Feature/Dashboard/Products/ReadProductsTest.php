<?php

namespace Tests\Feature\Dashboard\Products;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MixCode\Notifications\ProductOrderHasActivated;
use MixCode\Notifications\ProductStatusHasBeenPending;
use MixCode\Notifications\ProductStatusHasBeenPublish;
use MixCode\Product;
use MixCode\User;
use Notification;

class ReadProductsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->visibleFields = [
            'en_name', 
            'ar_name', 
            'en_overview', 
            'ar_overview', 
            'en_highlights', 
            'ar_highlights'
        ];

        $this->admin = create(User::class, ['type' => User::ADMIN_TYPE]);
        $this->adminProduct = create(Product::class, ['creator_id' => $this->admin->id])->makeVisible($this->visibleFields);
        
        $this->company = create(User::class, ['type' => User::COMPANY_TYPE]);
        $this->companyProduct = create(Product::class, ['creator_id' => $this->company->id])->makeVisible($this->visibleFields);
    }

    /** @test */
    public function non_authenticate_administrators_or_companies_cant_read_single_product()
    {
        $this->readProduct(create(Product::class)->path());
    }

    /** @test */
    public function non_authenticate_administrators_or_companies_cant_read_all_product()
    {
        $this->readProduct(route('dashboard.products.index'));
    }

    /** @test */
    public function authenticate_administrators_can_read_their_single_product()
    {
        // Given We Have An Authenticated Admin
        $this->signInAsAdmin($this->admin);

        // When We Hit The Product Read Endpoint
        $response = $this->getJson($this->adminProduct->path());
        
        // Then It Should Get The Product That Was Created
        $response->assertOk();
        $this->assertEquals($this->adminProduct->en_name, $response->json()['name_by_lang']);
    }

    /** @test */
    public function authenticate_administrators_can_read_all_their_product()
    {
        // Given We Have An Authenticated Admin
        $this->signInAsAdmin($this->admin);
        
        $products = create(Product::class, ['creator_id' => $this->admin->id], 3);

        // When We Hit The Product Read Endpoint
        $response = $this->getJson(route('dashboard.products.index'));
        
        // Then It Should Get The "3" Products That Was Created
        $response->assertOk();
        $response->assertSee($products->first()->en_name);
        $response->assertSee($products->get(1)->en_name);
        $response->assertSee($products->last()->en_name);
    }
    
    /** @test */
    public function non_authenticate_administrators_cant_change_product_status()
    {
        $this->readProduct(route('dashboard.products.mark_as.published', $this->adminProduct));
    }
 
    /** @test */
    public function authenticate_administrators_can_change_product_status_to_publish()
    {
        Notification::fake();

        Notification::assertSentToTimes($this->admin, ProductStatusHasBeenPublish::class, 0);

        // Given We Have An Authenticated Admin
        $this->signInAsAdmin($this->admin);
        
        // When We Hit The Update Product Status Endpoint
        $response = $this->getJson(route('dashboard.products.mark_as.published', $this->adminProduct));
        $this->getJson(route('dashboard.products.mark_as.published', $this->companyProduct));
        
        // Then It Should update product status
        $response->assertRedirect($this->adminProduct->path());
        $this->assertTrue($this->adminProduct->fresh()->isPublished());

        Notification::assertSentToTimes($this->admin, ProductStatusHasBeenPublish::class, 0);
        Notification::assertSentToTimes($this->company, ProductStatusHasBeenPublish::class, 1);
    }
    
    /** @test */
    public function authenticate_administrators_can_change_product_status_to_pending()
    {
        Notification::fake();

        Notification::assertSentToTimes($this->admin, ProductStatusHasBeenPending::class, 0);

        // Given We Have An Authenticated Admin
        $this->signInAsAdmin($this->admin);
        
        // When We Hit The Update Product Status Endpoint
        $response = $this->getJson(route('dashboard.products.mark_as.pending', $this->adminProduct));
        $this->getJson(route('dashboard.products.mark_as.pending', $this->companyProduct));
        
        // Then It Should update product status
        $response->assertRedirect($this->adminProduct->path());
        $this->assertTrue($this->adminProduct->fresh()->isPending());

        Notification::assertSentToTimes($this->admin, ProductStatusHasBeenPending::class, 0);
        Notification::assertSentToTimes($this->company, ProductStatusHasBeenPending::class, 1);
    }
   
    /** @test */
    public function non_authenticate_product_owners_cant_change_their_product_order_status()
    {
        $this->readProduct(route('dashboard.products.mark_as.active', $this->companyProduct));
        $this->readProduct(route('dashboard.products.mark_as.active', $this->adminProduct));
    }
 
    /** @test */
    public function authenticate_product_owners_can_change_their_product_order_status()
    {
        Notification::fake();

        Notification::assertSentToTimes($this->admin, ProductOrderHasActivated::class, 0);

        $this->withExceptionHandling();

        // Given We Have An Authenticated Company
        $this->signIn($this->company);
        
        // When We Hit The Update Product Show Status Endpoint
        $response = $this->getJson(route('dashboard.products.mark_as.active', $this->companyProduct));
        
        // Then It Should update product show status
        $response->assertRedirect($this->companyProduct->path());
        $this->assertTrue($this->companyProduct->fresh()->isActive());

        Notification::assertSentToTimes($this->admin, ProductOrderHasActivated::class, 1);
        
        auth()->logout();

        // Given We Have An Authenticated Admin
        $this->signInAsAdmin($this->admin);
        
        // When We Hit The Update Product Show Status Endpoint
        $response = $this->getJson(route('dashboard.products.mark_as.active', $this->adminProduct));
        
        // Then It Should update product show status
        $response->assertRedirect($this->adminProduct->path());
        $this->assertTrue($this->adminProduct->fresh()->isActive());

        Notification::assertSentToTimes($this->admin, ProductOrderHasActivated::class, 1);

        // When We Hit The Update Product Show Status Endpoint
        $response = $this->getJson(route('dashboard.products.mark_as.inactive', $this->companyProduct));

        Notification::assertSentToTimes($this->admin, ProductOrderHasActivated::class, 1);
    }

    /** @test */
    public function when_product_order_status_be_inactive_it_will_be_pending_automatically()
    {
        $this->withExceptionHandling();

        // Given We Have An Authenticated Company and published activated product
        $this->signIn($this->company);
        
        $product = create(Product::class, [
            'order_status'   => Product::BOOKING_ACTIVE_STATUS,
            'status'        => Product::ACTIVE_STATUS,
            'creator_id'    => auth()->id(),
        ]);

        $this->assertTrue($product->fresh()->isActive());
        $this->assertTrue($product->fresh()->isPublished());

        // When We Hit The Update Product Show Status Endpoint to make it show status disable
        $this->getJson(route('dashboard.products.mark_as.inactive', $product));
        
        // Then It Should update product show status and update products status to pending
        $this->assertTrue($product->fresh()->isInActive());
        $this->assertTrue($product->fresh()->isPending());
    }

    /** @test */
    public function authenticate_companies_can_read_their_single_product()
    {
        // Given We Have A Authenticated Company
        $this->signIn($this->company);

        // When We Hit The Product Read Endpoint
        $response = $this->getJson($this->companyProduct->path());
        
        // Then It Should Get The Product That Was Created
        $response->assertOk();
        $this->assertEquals($this->companyProduct->en_name, $response->json()['name_by_lang']);
    }

    /** @test */
    public function authenticate_companies_can_read_all_their_product()
    {
        // Given We Have A Authenticated Company
        $this->signIn($this->company);
        
        $products = create(Product::class, ['creator_id' => $this->company->id], 3);

        // When We Hit The Product Read Endpoint
        $response = $this->getJson(route('dashboard.products.index'));
        
        // Then It Should Get The "3" Products That Was Created
        $response->assertOk();
        $response->assertSee($products->first()->en_name);
        $response->assertSee($products->get(1)->en_name);
        $response->assertSee($products->last()->en_name);
    }

    protected function readProduct($route)
    {
        $this->withExceptionHandling();
        
        // Given We Have A User Not An Admin or Company
        $this->signIn(create(User::class, ['type' => User::CUSTOMER_TYPE]));

        // When We Hit The Product Read Endpoint
        $response = $this->getJson($route);
        
        // Then it Should Give An Error un Authorized
        $response->assertRedirect('/login');

        // Given We Have A non authenticate User
        auth()->logout();

        // When We Hit The Product Read Endpoint
        $response = $this->getJson($route);
        
        // Then it Should Give An Error un Authorized
        $response->assertRedirect('/login');
        
    }
}
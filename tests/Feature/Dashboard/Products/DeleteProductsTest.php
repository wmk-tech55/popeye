<?php

namespace Tests\Feature\Dashboard\Products;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MixCode\Product;
use MixCode\ProductView;
use MixCode\User;

class DeleteProductsTest extends TestCase
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
    
        $this->product = create(Product::class)->makeVisible($this->visibleFields);
    }

    /** @test */
    public function non_authenticate_administrators_or_companies_cant_delete_existing_product()
    {
        $this->withExceptionHandling();
        
        // Given We Have A User Not An Admin Or Company
        $this->signIn(create(User::class, ['type' => User::CUSTOMER_TYPE]));
        
        // When We Hit The Product Delete Endpoint
        $response = $this->deleteJson(route('dashboard.products.destroy', $this->product));
        
        // Then it Should Give An Error un Authorized
        $response->assertRedirect('/login');

        // Given We Have A non authenticate User
        auth()->logout();

        // When We Hit The Product Delete Endpoint
        $response = $this->deleteJson(route('dashboard.products.destroy', $this->product));
        
        // Then it Should Give An Error un Authorized
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticate_administrators_can_delete_their_existing_product()
    { 
        // Given We Have An Admin And Product
        $this->signInAsAdmin($this->admin);
        
        // When We Hit The Product Delete Endpoint
        $response = $this->deleteJson(route('dashboard.products.destroy', $this->adminProduct));
        
        // Then it Should Redirect to the product index page after delete the product
        $response->assertRedirect(route('dashboard.products.index'));
        $this->assertDatabaseMissing('products', $this->adminProduct->makeHidden('price_ratios')->toArray());
        $this->assertEquals(2, Product::count());
    }

    /** @test */
    public function authenticate_administrators_can_restore_their_existing_product()
    { 
        // Given We Have An Admin And Deleted Product
        $this->signInAsAdmin($this->admin);

        $this->adminProduct->delete();

        // When We Hit The Product Delete Endpoint
        $response = $this->patchJson(route('dashboard.products.restore', $this->adminProduct));
                
        // Then it Should Redirect to the product trashed index page after delete the product
        $response->assertRedirect(route('dashboard.products.trashed'));
        $this->assertEquals(3, Product::count());
    }

    /** @test */
    public function authenticate_administrators_can_delete_their_existing_product_forever()
    { 
        // Given We Have An Admin And Deleted Product
        $this->signInAsAdmin($this->admin);
        
        $this->adminProduct->delete();
        
        // When We Hit The Product Delete Endpoint
        $response = $this->deleteJson(route('dashboard.products.force_delete', $this->adminProduct));
        
        // Then it Should Redirect to the product trashed index page after delete the product
        $response->assertRedirect(route('dashboard.products.trashed'));
        $this->assertEquals(2, Product::withTrashed()->count());
    }
    

    /** @test */
    public function authenticate_administrators_can_multi_delete_their_existing_products()
    { 
        // Given We Have An Admin And Products
        $this->signInAsAdmin($this->admin);
        $ids = create(Product::class, ['creator_id' => $this->admin->id], 3)->pluck('id')->toArray();
        $ids = array_merge($ids, [$this->adminProduct->id]);
        
        // When We Hit The Product multi Delete Endpoint
        $response = $this->deleteJson(route('dashboard.products.destroyGroup'), ['selected_data' => $ids]);
                
        // Then it Should Redirect to the product index page after delete the product
        $response->assertRedirect(route('dashboard.products.index'));
        $this->assertDatabaseMissing('products', $ids);
        $this->assertEquals(2, Product::count());
    }

    /** @test */
    public function authenticate_companies_can_delete_their_existing_product()
    { 
        // Given We Have A Company And Product
        $this->signIn($this->company);
        
        // When We Hit The Product Delete Endpoint
        $response = $this->deleteJson(route('dashboard.products.destroy', $this->companyProduct));
                
        // Then it Should Redirect to the product index page after delete the product
        $response->assertRedirect(route('dashboard.products.index'));
        $this->assertDatabaseMissing('products', $this->companyProduct->makeHidden('price_ratios')->toArray());
        $this->assertEquals(2, Product::count());
    }

    /** @test */
    public function authenticate_companies_can_restore_their_existing_product()
    { 
        // Given We Have A Company And Deleted Product
        $this->signIn($this->company);

        $this->companyProduct->delete();

        // When We Hit The Product Delete Endpoint
        $response = $this->patchJson(route('dashboard.products.restore', $this->companyProduct));
                
        // Then it Should Redirect to the product trashed index page after delete the product
        $response->assertRedirect(route('dashboard.products.trashed'));
        $this->assertEquals(3, Product::count());
    }

    /** @test */
    public function authenticate_companies_can_delete_their_existing_product_forever()
    { 
        // Given We Have A Company And Deleted Product
        $this->signIn($this->company);
        
        $this->companyProduct->delete();
        
        // When We Hit The Product Delete Endpoint
        $response = $this->deleteJson(route('dashboard.products.force_delete', $this->companyProduct));
        
        // Then it Should Redirect to the product trashed index page after delete the product
        $response->assertRedirect(route('dashboard.products.trashed'));
        $this->assertEquals(2, Product::withTrashed()->count());
    }
    

    /** @test */
    public function authenticate_companies_can_multi_delete_their_existing_products()
    { 
        // Given We Have A Company And Products
        $this->signIn($this->company);
        $ids = create(Product::class, ['creator_id' => $this->company->id], 3)->pluck('id')->toArray();
        $ids = array_merge($ids, [$this->companyProduct->id]);
        
        // When We Hit The Product multi Delete Endpoint
        $response = $this->deleteJson(route('dashboard.products.destroyGroup'), ['selected_data' => $ids]);
                
        // Then it Should Redirect to the product index page after delete the product
        $response->assertRedirect(route('dashboard.products.index'));
        $this->assertDatabaseMissing('products', $ids);
        $this->assertEquals(2, Product::count());
    }

    /** @test */
    public function it_delete_views_with_it()
    { 
        // Given We Have An Admin And Product have views relation
        $this->signInAsAdmin($this->admin);
        
        $this->adminProduct->views()->attach(auth()->id());

        // -- Increase Product View Field Count
        $this->adminProduct->increment('views_count');
        
        // When We Hit The Product Delete Endpoint
        $response = $this->deleteJson(route('dashboard.products.destroy', $this->adminProduct));
        
        // Then it Should Redirect to product index page after delete product and it's views
        $response->assertRedirect(route('dashboard.products.index'));
        $this->assertEquals(0, ProductView::count());
        $this->assertEquals(0, $this->adminProduct->fresh()->views_count);
    }
}
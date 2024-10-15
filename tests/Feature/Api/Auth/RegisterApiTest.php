<?php

namespace Tests\Feature\Api\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use MixCode\Notifications\NewUserHasBeenRegistered;
use MixCode\User;
use Notification;

class RegisterApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Every Request We Must Install Passport Clients Key to make a success token creation
        \Artisan::call('passport:install',['-vvv' => true]);

        Storage::fake('test');

        $this->admin = create(User::class, ['type' => User::ADMIN_TYPE]);
    }

    /** @test */
    public function guest_customer_can_create_new_account_from_api()
    {
        Notification::fake();

        $this->withExceptionHandling();
        
        // Given we have a customer
        $customer     = make(User::class, [
            'type' => User::CUSTOMER_TYPE, 
            'status' => User::PENDING_STATUS, 
            'password_confirmation' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        ]);
        
        // When We hit api register endpoint
        $this->postJson(route('api.register'), $customer->makeVisible(['password'])->toArray())
        ->assertStatus(Response::HTTP_CREATED);

        // Then We Should See the customer saved in Database
        
        $this->assertEquals(1, User::typeCustomer()->count());
        $this->assertTrue($customer->isCustomer());
        $this->assertTrue(User::typeCustomer()->first()->isActive());
        $this->assertDatabaseHas('users', ['username' => $customer->username]);

        Notification::assertSentTo($this->admin, NewUserHasBeenRegistered::class);
    }
    
    /** @test */
    public function guest_company_can_create_new_account_from_api()
    {
        Notification::fake();

        // Given we have a Company
        $company    = make(User::class, ['type' => User::COMPANY_TYPE, 'password_confirmation' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi']);
        
        $media = [
            'id_card'               => UploadedFile::fake()->create('id_card.pdf'),
            'commercial_license'     => UploadedFile::fake()->create('commercial_license.pdf'),
            'logo'                  => UploadedFile::fake()->create('logo.png'),
        ];

        $data = array_merge($company->makeVisible(['password'])->toArray(), $media);


        // When We hit register endpoint
        $this->postJson(route('api.register'), $data)
        ->assertStatus(Response::HTTP_CREATED);
        
        // Then We Should See the company saved in Database
        $this->assertEquals(1, User::typeCompany()->count());
        $this->assertTrue($company->isCompany());
        $this->assertTrue(User::typeCompany()->first()->isPending());
        $this->assertDatabaseHas('users', ['username' => $company->username]);

        Notification::assertSentTo($this->admin, NewUserHasBeenRegistered::class);
    }
    
    /** @test */
    public function guest_tour_guides_can_create_new_account_from_api()
    {
        Notification::fake();

        // Given we have a Tour Guide
        $tourGuide    = make(User::class, ['type' => User::TOUR_GUIDE_TYPE, 'password_confirmation' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi']);
        
        $media = [
            'id_card'               => UploadedFile::fake()->create('id_card.pdf'),
            'pledge'                => UploadedFile::fake()->create('pledge.pdf'),
        ];

        $data = array_merge($tourGuide->makeVisible(['password'])->toArray(), $media);


        // When We hit register endpoint
        $this->postJson(route('api.register'), $data)
        ->assertStatus(Response::HTTP_CREATED);
        
        // Then We Should See the tourGuide saved in Database
        $this->assertEquals(1, User::typeTourGuide()->count());
         $this->assertTrue(User::typeTourGuide()->first()->isPending());
        $this->assertDatabaseHas('users', ['username' => $tourGuide->username]);

        Notification::assertSentTo($this->admin, NewUserHasBeenRegistered::class);
    }

    /** @test */
    public function user_required_a_valid_username()
    {
        $this->registerUser(['username' => null])
            ->assertJsonValidationErrors('username')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    
    /** @test */
    public function user_required_a_valid_full_name()
    {
        $this->registerUser(['full_name' => null])
            ->assertJsonValidationErrors('full_name')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function user_required_a_valid_email()
    {
        $this->registerUser(['email' => null])
            ->assertJsonValidationErrors('email')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->registerUser(['email' => 'not-valid-email'])
            ->assertJsonValidationErrors('email')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function user_required_a_valid_password()
    {
        $this->registerUser(['password' => null])
            ->assertJsonValidationErrors('password')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function user_required_a_valid_phone()
    {
        $this->registerUser(['phone' => null])
            ->assertJsonValidationErrors('phone')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function user_require_a_valid_type()
    {
        $this->registerUser(['type' => 'not-valid-type'])
            ->assertJsonValidationErrors('type')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->registerUser(['type' => null])
            ->assertJsonValidationErrors('type')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->registerUser(['type' => User::CUSTOMER_TYPE])->assertJsonMissingValidationErrors('type');
        $this->registerUser(['type' => User::COMPANY_TYPE])->assertJsonMissingValidationErrors('type');
    }
    
    /** @test */
    public function user_may_accept_a_valid_id_card_file()
    {
        $this->registerUser(['id_card' => 'not-valid-url'])
            ->assertJsonValidationErrors('id_card')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->registerUser(['id_card' => null])->assertJsonMissingValidationErrors('id_card');
    }

    /** @test */
    public function user_may_accept_a_valid_pledge_file()
    {
        $this->registerUser(['pledge' => 'not-valid-url'])
            ->assertJsonValidationErrors('pledge')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->registerUser(['pledge' => null])->assertJsonMissingValidationErrors('pledge');
    }
    
    /** @test */
    public function user_may_accept_a_valid_commercial_license_file()
    {
        $this->registerUser(['commercial_license' => 'not-valid-url'])
            ->assertJsonValidationErrors('commercial_license')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->registerUser(['commercial_license' => null])->assertJsonMissingValidationErrors('commercial_license');
    }
    
    /** @test */
    public function user_may_accept_a_valid_logo_image()
    {
        $this->registerUser(['logo' => 'not-valid-url'])
            ->assertJsonValidationErrors('logo')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->registerUser(['logo' => null])->assertJsonMissingValidationErrors('logo');
    }
    
    protected function registerUser($overrides)
    {
        $this->withExceptionHandling();

        $file = UploadedFile::fake()->create('file.pdf');

        $media = [
            'id_card'               => $file,
            'pledge'                => $file,
            'commercial_license'     => $file,
            'logo'                  => UploadedFile::fake()->create('logo.png'),
        ];

        // Given We Have a User
        $overrides = array_merge($media, $overrides, ['password_confirmation' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi']);
        $user = make(User::class, $overrides);
    
        // When We Hit The User Register Endpoint
        return $this->postJson(route('api.register'), $user->makeVisible('password')->toArray());
    }
}
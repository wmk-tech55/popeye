<?php

namespace Tests\Feature\Dashboard\Users;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use MixCode\User;

class CreateUsersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('test');
    }

    /** @test */
    public function non_authenticate_administrators_cant_create_new_user()
    {
        $this->withExceptionHandling();
        
        // Given We Have A User Not An Admin
        $this->signIn();
        
        // When We Hit The User Store Endpoint
        $response = $this->postJson(route('dashboard.users.store'));
        
        // Then it Should Give An Error un Authorized
        $response->assertRedirect('/login');

        // Given We Have A non authenticate User
        auth()->logout();

        // When We Hit The User Store Endpoint
        $response = $this->postJson(route('dashboard.users.store'));
        
        // Then it Should Give An Error un Authorized
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticate_administrators_can_create_new_user()
    { 
        // Given We Have An Admin And User
        $this->signInAsAdmin();
        
        $user = make(User::class, ['password_confirmation' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi']);
    
        $media = [
            'id_card'               => UploadedFile::fake()->create('id_card.pdf'),
            'pledge'                => UploadedFile::fake()->create('pledge.pdf'),
            'commercial_license'     => UploadedFile::fake()->create('commercial_license.pdf'),
            'logo'                  => UploadedFile::fake()->create('logo.png'),
        ];

        $data = array_merge($user->makeVisible('password')->toArray(), $media);
        
        // When We Hit The User Store Endpoint
        $this->postJson(route('dashboard.users.store'), $data);
        
        // Then Users Count Should Be 2
        $this->assertEquals(2, User::count());
    }

    /** @test */
    public function user_required_a_valid_username()
    {
        $this->createNewUser(['username' => null])->assertSessionHasErrors('username');
    }

    /** @test */
    public function user_required_a_valid_full_name()
    {
        $this->createNewUser(['full_name' => null])->assertSessionHasErrors('full_name');
    }

    /** @test */
    public function user_required_a_valid_email()
    {
        $this->createNewUser(['email' => null])->assertSessionHasErrors('email');
        $this->createNewUser(['email' => 'not-valid-email'])->assertSessionHasErrors('email');
    }

    /** @test */
    public function user_required_a_valid_password()
    {
        $this->createNewUser(['password' => null])->assertSessionHasErrors('password');
    }

    /** @test */
    public function user_required_a_valid_phone()
    {
        $this->createNewUser(['phone' => null])->assertSessionHasErrors('phone');
    }

    /** @test */
    public function user_require_a_valid_type()
    {
        $this->createNewUser(['type' => 'not-valid-type'])->assertSessionHasErrors('type');
        $this->createNewUser(['type' => null])->assertSessionHasErrors('type');
        $this->createNewUser(['type' => User::CUSTOMER_TYPE])->assertSessionHasNoErrors('type');
        $this->createNewUser(['type' => User::COMPANY_TYPE])->assertSessionHasNoErrors('type');
        $this->createNewUser(['type' => User::TOUR_GUIDE_TYPE])->assertSessionHasNoErrors('type');
        $this->createNewUser(['type' => User::ADMIN_TYPE])->assertSessionHasNoErrors('type');
    }
    
    /** @test */
    public function user_require_a_valid_status()
    {
        $this->createNewUser(['status' => 'not-valid-status'])->assertSessionHasErrors('status');
        $this->createNewUser(['status' => null])->assertSessionHasErrors('status');
        $this->createNewUser(['status' => User::ACTIVE_STATUS])->assertSessionHasNoErrors('status');
        $this->createNewUser(['status' => User::PENDING_STATUS])->assertSessionHasNoErrors('status');
        $this->createNewUser(['status' => User::INACTIVE_STATUS])->assertSessionHasNoErrors('status');
    }
       
    /** @test */
    public function user_may_accept_a_valid_id_card_file()
    {
        $this->createNewUser(['id_card' => 'not-valid-media-file'])->assertSessionHasErrors('id_card');
        $this->createNewUser(['id_card' => null])->assertSessionHasNoErrors('id_card');
    }
       
    /** @test */
    public function user_may_accept_a_valid_pledge_file()
    {
        $this->createNewUser(['pledge' => 'not-valid-media-file'])->assertSessionHasErrors('pledge');
        $this->createNewUser(['pledge' => null])->assertSessionHasNoErrors('pledge');
    }
    
    /** @test */
    public function user_may_accept_a_valid_commercial_license_file()
    {
        $this->createNewUser(['commercial_license' => 'not-valid-media-file'])->assertSessionHasErrors('commercial_license');
        $this->createNewUser(['commercial_license' => null])->assertSessionHasNoErrors('commercial_license');
    }
    
    /** @test */
    public function user_may_accept_a_valid_logo_image()
    {
        $this->createNewUser(['logo' => 'not-valid-media-file'])->assertSessionHasErrors('logo');
        $this->createNewUser(['logo' => null])->assertSessionHasNoErrors('logo');
    }
    
    protected function createNewUser($overrides)
    {
        $this->withExceptionHandling();

        // Given We Have An Admin And User
        $this->signInAsAdmin();
  
        $file = UploadedFile::fake()->create('file.pdf');
        $media = [
            'id_card'               => $file,
            'pledge'                => $file,
            'commercial_license'     => $file,
            'logo'                  => UploadedFile::fake()->create('logo.png'),
        ];
       
        $overrides = array_merge($media, $overrides, ['password_confirmation' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi']);
        $user = make(User::class, $overrides);
    
        // When We Hit The User Store Endpoint
        return $this->post(route('dashboard.users.store'), $user->makeVisible('password')->toArray());
    }
}
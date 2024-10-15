<?php

namespace Tests\Feature\Dashboard\Users;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use MixCode\Notifications\UserAccountHasBeenActivated;
use MixCode\User;
use Notification;

class UpdateUsersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('test');

        $this->user = create(User::class);
    }

    /** @test */
    public function non_authenticate_administrators_cant_update_existing_user()
    {
        $this->withExceptionHandling();

        // Given We Have A User Not An Admin
        $this->signIn();

        // When We Hit The User Update Endpoint
        $response = $this->patchJson(route('dashboard.users.update', $this->user));

        // Then it Should Give An Error un Authorized
        $response->assertRedirect('/login');

        // Given We Have A non authenticate User
        auth()->logout();

        // When We Hit The User Update Endpoint
        $response = $this->patchJson(route('dashboard.users.update', $this->user));

        // Then it Should Give An Error un Authorized
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticate_administrators_can_update_existing_user()
    {
        // Given We Have An Admin And User
        $this->signInAsAdmin();
        
        // Set User Name
        $updated_data = ['username' => 'Updated User Name'];
        $data = array_merge($this->user->toArray(),  $updated_data);

        // When We Hit The User Update Endpoint
        $response = $this->patchJson(route('dashboard.users.update', $this->user), $data);

        // Then it Should Redirect to the user show page And The User name Should Be Updated
        $response->assertRedirect(route('dashboard.users.show', $this->user));

        $this->assertEquals($updated_data, ['username' => $this->user->fresh()->username]);
    }

    /** @test */
    public function an_email_should_be_send_after_make_user_account_active_by_administrators()
    {
        Notification::fake();

        // Given We Have An Admin And 2 Users
        $this->signInAsAdmin();
        $this->user->update(['status' => User::PENDING_STATUS]);
        $userWithNoNotification = create(User::class, ['status' => User::PENDING_STATUS]);
        
        // Set User Name and status
        $updated_data = ['username' => 'Updated User Name', 'status' => User::ACTIVE_STATUS];
        $data = array_merge($this->user->toArray(),  $updated_data);

        // When We Hit The User Update Endpoint
        $this->patchJson(route('dashboard.users.update', $this->user), $data);
        $this->patchJson(route('dashboard.users.update', $userWithNoNotification), $userWithNoNotification->toArray());

        // Then it Should send notification for updated user that have active status
        Notification::assertSentTo($this->user, UserAccountHasBeenActivated::class);
        Notification::assertNotSentTo($userWithNoNotification, UserAccountHasBeenActivated::class);
    }

    /** @test */
    public function user_required_a_valid_username()
    {
        $this->updateUser(['username' => null])->assertSessionHasErrors('username');
    }

    /** @test */
    public function user_required_a_valid_full_name()
    {
        $this->updateUser(['full_name' => null])->assertSessionHasErrors('full_name');
    }

    /** @test */
    public function user_required_a_valid_email()
    {
        $this->updateUser(['email' => null])->assertSessionHasErrors('email');
        $this->updateUser(['email' => 'not-valid-email'])->assertSessionHasErrors('email');
    }

    /** @test */
    public function user_required_a_valid_phone()
    {
        $this->updateUser(['phone' => null])->assertSessionHasErrors('phone');
    }

    /** @test */
    public function user_may_accept_a_valid_map_url()
    {
        $this->updateUser(['map_url' => 'not-valid-map-url'])->assertSessionHasErrors('map_url');
        $this->updateUser(['map_url' => null])->assertSessionHasNoErrors('map_url');
        $this->updateUser(['map_url' => 'https://maps.google.com'])->assertSessionHasNoErrors('map_url');
    }

    /** @test */
    public function user_require_a_valid_type()
    {
        $this->updateUser(['type' => 'not-valid-type'])->assertSessionHasErrors('type');
        $this->updateUser(['type' => null])->assertSessionHasErrors('type');
        $this->updateUser(['type' => User::CUSTOMER_TYPE])->assertSessionHasNoErrors('type');
        $this->updateUser(['type' => User::COMPANY_TYPE])->assertSessionHasNoErrors('type');
        $this->updateUser(['type' => User::TOUR_GUIDE_TYPE])->assertSessionHasNoErrors('type');
        $this->updateUser(['type' => User::ADMIN_TYPE])->assertSessionHasNoErrors('type');
    }
    
    /** @test */
    public function user_require_a_valid_status()
    {
        $this->updateUser(['status' => 'not-valid-status'])->assertSessionHasErrors('status');
        $this->updateUser(['status' => null])->assertSessionHasErrors('status');
        $this->updateUser(['status' => User::ACTIVE_STATUS])->assertSessionHasNoErrors('status');
        $this->updateUser(['status' => User::PENDING_STATUS])->assertSessionHasNoErrors('status');
        $this->updateUser(['status' => User::INACTIVE_STATUS])->assertSessionHasNoErrors('status');
    }

    /** @test */
    public function user_may_accept_a_valid_id_card_file()
    {
        $this->updateUser(['id_card' => 'not-valid-url'])->assertSessionHasErrors('id_card');
        $this->updateUser(['id_card' => null])->assertSessionHasNoErrors('id_card');
    }

    /** @test */
    public function user_may_accept_a_valid_pledge_file()
    {
        $this->updateUser(['pledge' => 'not-valid-url'])->assertSessionHasErrors('pledge');
        $this->updateUser(['pledge' => null])->assertSessionHasNoErrors('pledge');
    }

    /** @test */
    public function user_may_accept_a_valid_commercial_license_file()
    {
        $this->updateUser(['commercial_license' => 'not-valid-url'])->assertSessionHasErrors('commercial_license');
        $this->updateUser(['commercial_license' => null])->assertSessionHasNoErrors('commercial_license');
    }

    /** @test */
    public function user_may_accept_a_valid_logo_image()
    {
        $this->updateUser(['logo' => 'not-valid-url'])->assertSessionHasErrors('logo');
        $this->updateUser(['logo' => null])->assertSessionHasNoErrors('logo');
    }

    /** @test */
    public function user_required_a_valid_password()
    {
        $this->withExceptionHandling();

        // Given We Have An Admin 
        $this->signInAsAdmin();

        // When We Hit The User Update Endpoint
        $response = $this->patch(route('dashboard.users.update.password', $this->user), ['password' => null]);

        // then we except that session has errors for password
        $response->assertSessionHasErrors('password');
    }

    protected function updateUser($data)
    {
        $this->withExceptionHandling();

        // Given We Have An Admin 
        $this->signInAsAdmin();

        $file = UploadedFile::fake()->create('file.pdf');
        $media = [
            'id_card'               => $file,
            'pledge'                => $file,
            'commercial_license'     => $file,
            'logo'                  => UploadedFile::fake()->create('logo.png'),
        ];

        $media = array_merge($media, $data);

        $data = array_merge($this->user->toArray(), $media);

        // When We Hit The User Update Endpoint
        return $this->patch(route('dashboard.users.update', $this->user), $data);
    }
}

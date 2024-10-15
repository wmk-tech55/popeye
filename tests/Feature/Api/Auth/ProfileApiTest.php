<?php

namespace Tests\Feature\Api\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use MixCode\Order;
use MixCode\Trip;
use MixCode\User;

class ProfileApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Every Request We Must Install Passport Clients Key to make a success token creation
        \Artisan::call('passport:install', ['-vvv' => true]);

        Storage::fake('test');
    }

    /** @test */
    public function non_authenticated_users_cant_show_their_profile()
    {
        $this->withExceptionHandling();

        // Given we have a guest user
        create(User::class, ['email' => 'test@email.com', 'password' => Hash::make('123456')]);

        // When We hit update profile api endpoint
        $res = $this->getJson(route('api.profile'));

        // Then We Should receive unauthorized status
        $res->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function authenticated_users_can_show_their_profile()
    {
        // Given we have a user and make it login
        $this->apiSignIn($user = create(User::class, ['email' => 'test@email.com', 'password' => Hash::make('123456')]));

        // When We hit show profile api endpoint
        $res = $this->getJson(route('api.profile'));

        // Then We Should See Profile 
        $res->assertStatus(Response::HTTP_OK);
        $res->assertSee($user->email);
    }

    /** @test */
    public function authenticated_users_can_list_their_trips()
    {
        $this->withExceptionHandling();

        // Given we have an authenticated user and 3 trips
        $this->apiSignIn(create(User::class, ['type' => User::COMPANY_TYPE]));

        $trips = create(Trip::class, ['creator_id' => auth()->id()], 3);

        // When We hit list trips api endpoint
        $res = $this->getJson(route('api.profile.show.trips'));

        // Then We Should See the "3" trips
        $res->assertStatus(Response::HTTP_OK);
        $res->assertSee($trips->first()->en_name);
        $res->assertSee($trips->get(1)->en_name);
        $res->assertSee($trips->last()->en_name);
    }

    /** @test */
    public function authenticated_users_can_list_their_history()
    {
        // $this->withExceptionHandling();

        // Given we have an authenticated user and 2 trips history
        $this->apiSignIn(create(User::class, ['type' => User::CUSTOMER_TYPE]));

        $completedTrip = create(Trip::class);
        $notCompletedTrip = create(Trip::class);
        $notCompletedForAnotherUserTrip = create(Trip::class);
        
        create(Order::class, [
            'trip_id' => $completedTrip->id,
            'customer_id' => auth()->id(),
        ]);
        
        create(Order::class, [
            'trip_id' => $notCompletedTrip->id,
            'completed_status' => Order::NOT_COMPLETED_STATUS,
            'customer_id' => auth()->id(),
        ]);
        
        create(Order::class, [
            'trip_id' => $notCompletedTrip->id,
            'completed_status' => Order::NOT_COMPLETED_STATUS,
            'customer_id' => create(User::class),
        ]);

        // When We hit list history trips api endpoint
        $res = $this->getJson(route('api.profile.show.history'));

        // Then we will see all user only history trips
        $res->assertStatus(Response::HTTP_OK);
        $res->assertSee($completedTrip->id);
        $res->assertSee($notCompletedTrip->id);
        $res->assertDontSee($notCompletedForAnotherUserTrip->id);
    }

    /** @test */
    public function authenticated_users_can_update_their_profile()
    {
        // Given we have a user and make it login
        $this->apiSignIn($user = create(User::class, ['email' => 'test@email.com', 'password' => Hash::make('123456')]));
        $data = array_merge($user->toArray(), ['email' => 'new@email.com']);

        // When We hit update profile api endpoint
        $res = $this->patchJson(route('api.profile.update'), $data);

        // Then We Should See new updated data
        $res->assertStatus(Response::HTTP_OK);
        $res->assertSee('new@email.com');
    }

    /** @test */
    public function user_required_a_valid_full_name()
    {
        $this->updateUser(['full_name' => null])
            ->assertJsonValidationErrors('full_name')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function user_required_a_valid_email()
    {
        create(User::class, ['email' => 'duplicate@email.com']);

        $this->updateUser(['email' => null])
            ->assertJsonValidationErrors('email')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->updateUser(['email' => 'not-valid-email'])
            ->assertJsonValidationErrors('email')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->updateUser(['email' => 'duplicate@email.com'])
            ->assertJsonValidationErrors('email')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->updateUser(['email' => 'new@email.com'])
            ->assertJsonMissingValidationErrors('email')
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function user_required_a_valid_phone()
    {
        $this->updateUser(['phone' => null])
            ->assertJsonValidationErrors('phone')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function user_required_a_valid_logo()
    {
        $this->withExceptionHandling();

        // Given We Have a user
        $user = create(User::class);

        $this->apiSignIn($user);

        // When We Hit profile update logo api endpoint
        $res = $this->postJson(route('api.profile.update.logo'), ['logo' => null]);

        // then we except that response has errors for logo
        $res->assertJsonValidationErrors('logo');

        // Given we update logo
        // When We Hit profile update logo api endpoint
        $res = $this->postJson(route('api.profile.update.logo'), [
            'logo' => UploadedFile::fake()->image('logo.png')
        ]);
        
        // then we except that response has no errors for logo
        $res->assertJsonMissingValidationErrors('logo');
    }
    
    /** @test */
    public function user_required_a_valid_password()
    {
        $this->withExceptionHandling();

        // Given We Have a user
        $user = create(User::class, ['password' => '123456']);

        $this->apiSignIn($user);

        // When We Hit profile update password api endpoint
        $res = $this->patchJson(route('api.profile.update.password'), ['password' => null]);

        // then we except that response has errors for password
        $res->assertJsonValidationErrors('password');

        // Given we update password with out confirming
        // When We Hit profile update password api endpoint
        $res = $this->patchJson(route('api.profile.update.password'), ['password' => '654321']);

        // then we except that response has errors for password
        $res->assertJsonValidationErrors('password');

        // Given we update password with confirming
        // When We Hit profile update password api endpoint
        $res = $this->patchJson(route('api.profile.update.password'), [
            'password' => '654321', 
            'password_confirmation' => '654321'
        ]);

        // then we except that response has no errors for password
        $res->assertJsonMissingValidationErrors('password');

        $this->assertTrue(Hash::check('654321', auth()->user()->password));
        $this->assertFalse(Hash::check('123456', auth()->user()->password));
    }


    protected function updateUser($overrides, $type = ['type' => User::CUSTOMER_TYPE])
    {
        $this->withExceptionHandling();

        // Given We Have a User
        $user = create(User::class, $type);

        $this->apiSignIn($user);

        $data = array_merge($user->toArray(), $overrides);

        // When We Hit The User Login Endpoint
        return $this->patchJson(route('api.profile.update'), $data);
    }
}

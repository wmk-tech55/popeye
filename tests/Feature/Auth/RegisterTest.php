<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use MixCode\Notifications\NewUserHasBeenRegistered;
use MixCode\User;
use Notification;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('test');

        $this->admin = create(User::class, ['type' => User::ADMIN_TYPE]);
    }

    /** @test */
    public function guest_customer_can_create_new_account()
    {
        Notification::fake();

        // Given we have a customer
        $customer     = make(User::class, [
            'type' => User::CUSTOMER_TYPE, 
            'status' => User::PENDING_STATUS, 
            'password_confirmation' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        ]);
        
        // When We hit register endpoint
        $this->post(route('register'), $customer->makeVisible(['password'])->toArray());
        
        // Then We Should See the customer saved in Database
        $this->assertEquals(1, User::typeCustomer()->count());
        $this->assertTrue($customer->isCustomer());
        $this->assertTrue(User::typeCustomer()->first()->isActive());
        $this->assertDatabaseHas('users', ['username' => $customer->username]);

        Notification::assertSentTo($this->admin, NewUserHasBeenRegistered::class);
    }
    
    /** @test */
    public function guest_company_can_create_new_account()
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
        $this->post(route('register'), $data);
        
        // Then We Should See the company saved in Database
        $this->assertEquals(1, User::typeCompany()->count());
        $this->assertTrue($company->isCompany());
        $this->assertTrue(User::typeCompany()->first()->isPending());
        $this->assertDatabaseHas('users', ['username' => $company->username]);
        
        Notification::assertSentTo($this->admin, NewUserHasBeenRegistered::class);
    }
    
    /** @test */
    public function guest_tour_guide_can_create_new_account()
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
        $this->post(route('register'), $data);
        
        // Then We Should See the tourGuide saved in Database
        $this->assertEquals(1, User::typeTourGuide()->count());
         $this->assertTrue(User::typeTourGuide()->first()->isPending());
        $this->assertDatabaseHas('users', ['username' => $tourGuide->username]);

        Notification::assertSentTo($this->admin, NewUserHasBeenRegistered::class);
    }
}
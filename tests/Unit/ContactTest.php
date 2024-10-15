<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MixCode\Contact;
use MixCode\Notifications\NewContactMessage;
use MixCode\User;
use Notification;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->contact = create(Contact::class);
    }
    
    /** @test */
    public function it_can_send_notification_to_administrator_after_new_contact_message_created()
    {
        Notification::fake();
        
        $admin  = create(User::class, ['type' => User::ADMIN_TYPE]);
        $user   = create(User::class, ['type' => User::CUSTOMER_TYPE]);

        $this->contact->notifyAdminsForNewMessage();
        
        Notification::assertSentTo($admin, NewContactMessage::class);
        Notification::assertNotSentTo($user, NewContactMessage::class);
    }
}

<?php

namespace MixCode\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendResetPasswordLink extends Mailable
{
    use Queueable, SerializesModels;

    public $token ;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token)
    { 
       $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() 
    {
        return $this->markdown('emails.resetPassword.sendResetPassword_Link');
    }
}

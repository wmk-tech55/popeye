<?php

namespace MixCode\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use MixCode\User;

class NewUserHasBeenRegistered extends Notification
{
    use Queueable;

    /**
     * notification subject
     *
     * @var string
     */
    public $subject;

    /**
     * notification message
     *
     * @var string
     */
    public $message;

    /**
     * Link for review resource
     *
     * @var string
     */
    public $link;
    
    /**
     * Notification color
     *
     * @var string
     */
    public $color;
   
    /**
     * Notification icon
     *
     * @var string
     */
    public $icon;
    
    /**
     * Shared data to be send
     *
     * @var array
     */
    public $sharedData;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->link         = $user->path();

        $this->subject      = 'notifications.new_account';
        $this->message      = 'notifications.new_account_message';
        
        $this->color        = 'bg-warning';
        $this->icon         = 'fas fa-user-plus';

        if ($user->isCustomer()) {
            $this->message = 'notifications.new_account';
        }

        $this->sharedData = [
            'subject'       =>  $this->subject,
            'message'       =>  $this->message,
            'review_link'   =>  $this->link,
            'color'         =>  $this->color,
            'icon'          =>  $this->icon,
        ];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(trans($this->subject))
            ->markdown('emails.account.new_account', $this->sharedData);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->sharedData;
    }
}

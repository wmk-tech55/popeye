<?php

namespace MixCode\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use MixCode\Order;

class NewOrderHasBeenCreatedForDrivers extends Notification
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
    public function __construct(Order $order)
    {
        $this->link    = $order->path();
        $this->subject = 'notifications.you_have_new_request';
        $this->message = 'notifications.you_have_new_request_message';
        $this->color   = 'bg-info';
        $this->icon    = 'fas fa-ticket-alt';

        $this->sharedData = [
            'subject'     => trans($this->subject),
            'message'     => trans($this->message),
            'color'       => $this->color,
            'icon'        => $this->icon,
            'item_id'     => $order->id,
            'description' => $order->description,
            'address'     => $order->address,
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
            ->markdown('emails.order.new_order_created_for_driver', $this->sharedData);
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

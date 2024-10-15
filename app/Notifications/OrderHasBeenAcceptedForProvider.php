<?php

namespace MixCode\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use MixCode\Order;

class OrderHasBeenAcceptedForProvider extends Notification
{
    use Queueable;

    /**
     * order  
     *
     * @var string
     */
    public $order;
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
        $this->subject = 'notifications.order_status';
        $this->message = 'notifications.order_accepted_message';
        $this->color   = 'bg-info';
        $this->icon    = 'fas fa-ticket-alt';
        $this->order   = $order;

        $this->sharedData = [
            'subject'     => trans($this->subject),
            'message'     => trans($this->message),
            'color'       => $this->color,
            'icon'        => $this->icon,
            'item_id'     => $order->id,
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
        $order = ['order' => $this->order];
        $shredData = array_merge($this->sharedData, $order);

        return (new MailMessage)
            ->subject(trans($this->subject))
            ->markdown('emails.order.order_accepted_for_provider', $shredData);
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

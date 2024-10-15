<?php

namespace MixCode\Http\Controllers\Api\Auth;
use Illuminate\Notifications\DatabaseNotification;
use MoaAlaa\ApiResponder\ApiResponder;
use MixCode\Http\Controllers\Controller;

class OrdersNotificationsController extends Controller
{
    use ApiResponder;

    public function showAll()
    {
      
        return $this->api()->response(auth()->user()->notifications);
    }


    public function show(DatabaseNotification $notification)
    {
        $notification->markAsRead();
      
        return $this->api()->response($notification);
    }

 
    // public function markAllAsRead()
    // {
    //     auth()->user()->unreadNotifications()->update(['read_at' => now()]);

    //     return $this->api()->response(auth()->user()->notifications);

    // }
}
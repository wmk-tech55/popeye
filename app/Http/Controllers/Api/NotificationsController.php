<?php

namespace MixCode\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Notifications\DatabaseNotification;
use MixCode\Chat;
use MixCode\Http\Controllers\Controller;
use MixCode\NotificationFactory;
use MixCode\Notifications\SendVerificationMail;
use MoaAlaa\ApiResponder\ApiResponder;
use Notification;

class NotificationsController extends Controller
{
    use ApiResponder;

    public function index()
    {
        $user = auth()->user();

        // $user->unreadNotifications()->update(['read_at' => now()]);

        /** @var \Illuminate\Pagination\LengthAwarePaginator */
        $notifications = $user->notifications()->latest()->paginate(20);

        $translated_notifications = $notifications->getCollection()->map(function ($notification, $key) {
            $data = $notification->data;
            
            $data['subject'] = trans($data['subject'], [], request('lang', 'ar'));
            $data['message'] = trans($data['message'], [], request('lang', 'ar'));
            
            $notification->data = $data;
            
            return $notification;
        });

        // Set New Formatted Collection to pagination data
        $notifications->setCollection($translated_notifications);

        return $this->api()->response($notifications);
    }

    public function unreadNotificationsAndMessagesCount()
    {
        /** @var \MixCode\User */
        $user = auth()->user();

        return $this->api()->response([
            'unread_notifications_count'   => $user->unreadNotifications()->count(),
        ]);
    }

    public function show(DatabaseNotification $notification)
    {
        $notification->markAsRead();

        return $this->api()->response($notification);
    }

    /**
     * Send Notifications via SMS
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendViaSMS(Request $request)
    {
        $request->validate([
            'phone'     => 'required|string',
            'title'     => 'required|string',
            'message'   => 'required|string',
        ]);

        try {
            NotificationFactory::notifyBySmsMessages($request->phone, $request->title, $request->message);

            return $this->api()->response([], trans('main.message_was_sent'));
        } catch (\Exception $e) {
            return $this->api()->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Send Notifications via Email
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendViaEmail(Request $request)
    {
        $request->validate([
            'email'     => 'required|string|email',
            'title'     => 'required|string',
            'message'   => 'required|string',
        ]);

        try {
            Notification::route('mail', $request->email)->notify(new SendVerificationMail($request->title, $request->message));

            return $this->api()->response([], trans('main.message_was_sent'));
        } catch (\Exception $e) {
            \Log::channel('notification_errors')->error(get_class($this) . " -> " . __FUNCTION__ . " -> " . get_class($e) . " -> " . $e->getMessage());
            return $this->api()->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

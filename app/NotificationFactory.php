<?php

namespace MixCode;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;
use MixCode\Utils\Exceptions\FcmExceptions;
use MixCode\Exceptions\HiSmsExceptions;
use MixCode\Messages\FcmMessage;
use MixCode\Messages\HiSmsMessage;

class NotificationFactory
{
    /**
     * notify users with push notifications
     *
     * @param Collection|string|array $users
     * @param string $title
     * @param string $body
     * @param string $link
     * @param array $additional_data
     * @return void
     * @throws \MixCode\Utils\Exceptions\FcmExceptions
     */
    public static function notifyByPushNotification($users, string $title, ?string $body = null, ?string $link = null, array $additional_data = [])
    {
 
        if (app()->environment('testing')) return;
        
        $fcm_tokens = static::getFCMTokens($users);
        
        // Verify that there are tokens to send 
        if (count($fcm_tokens) <= 0) return null;

        $message = (new FcmMessage)
            ->setTitle($title)
            ->setBody($body)
            ->setSound('default')
            ->appendData(['redirect_link' => $link])
            ->appendData(['click_action' => "FLUTTER_NOTIFICATION_CLICK"])
            ->appendData($additional_data);
    
        $server_key = config('defaults.system.fcm_server_key');
        $data = $message->toArray();
        $data['registration_ids'] = $fcm_tokens;
        
        try {
            $client = new Client([
                'headers' => [
                    'Authorization' => "key={$server_key}",
                    'Content-Type'  => "application/json",
                    'Accept'        => "application/json",
                ]
            ]);

            $res        = $client->post("https://fcm.googleapis.com/fcm/send", ['json' => $data]);
            $result     =  json_decode($res->getBody()->getContents());
            
            FcmExceptions::getException($result->success, $result->failure, "Error in sending data");
            
        } catch (GuzzleException $th) {
            return null;
        } catch (FcmExceptions $e) {
            \Log::channel('notification_errors')->error(get_class($e) . " -> " . $e->getMessage());
        }
    }

       /**
     * notify users with   msegat SMS Provider
     *
     * @param Collection|string|array $users
     * @param string $title
     * @param string $body
     * @param string $link
     * @return void
     * @throws \MixCode\Exceptions\HiSmsExceptions
     */
    public static function notifyByMsegatSmsMessages($users, string $title, ?string $body = null, ?string $link = null)
    {
        

        if (app()->environment('testing')) return;

        $phone = $users;
        
        $msegat_username    = config('defaults.system.msegat_username');
        $msegat_api_key     = config('defaults.system.msegat_api_key');
        $msegat_sender_name = config('defaults.system.msegat_sendeName');
        
     
        $data = [
            'userName'   => $msegat_username,
            'numbers'    => $phone,
            'userSender' => $msegat_sender_name,
            'apiKey'     => $msegat_api_key,
            'msg'        => $body,
 
            ];
  
            $url    = "https://www.msegat.com/gw/sendsms.php"; 
           
            $res    = Http::withHeaders([ 'accept' => 'application/json' ])->post($url,$data)->json();
    
    }
    
    /**
     * notify users with SMS
     *
     * @param Collection|string|array $users
     * @param string $title
     * @param string $body
     * @param string $link
     * @return void
     * @throws \MixCode\Exceptions\HiSmsExceptions
     */
    public static function notifyBySmsMessages($users, string $title, ?string $body = null, ?string $link = null)
    {
        if (app()->environment('testing')) return;

        $phones = static::getSMSPhones($users);
        
        $hisms_username = config('defaults.system.hisms_username');
        $hisms_password = config('defaults.system.hisms_password');

        try {
            $client = new Client();

            $res    = $client->get("http://hisms.ws/api.php?send_sms&username={$hisms_username}&password={$hisms_password}&numbers={$phones}&sender=TRIPPER&message={$body}");
            // $res    = $client->get("http://hisms.ws/api.php?get_balance&username=966530635000&password=tripper2020");
            $code   =  json_decode($res->getBody()->getContents());
            
            HiSmsExceptions::getException($code);

            // to read body use "(string) $res->getBody()"
        } catch (GuzzleException $th) {
            return null;
        } catch (HiSmsExceptions $e) {
            \Log::channel('notification_errors')->error(get_class($e) . " -> " . $e->getMessage());
        }
    }

    /**
     * Get Phone Numbers for SMS Gateway
     *
     * @param Collection|string|array $users
     * @return string
     */
    protected static function getSMSPhones($users)
    {
        if ($users instanceof Collection) {
            return $users->map(function ($user) { 
                return $user->phone;
            })
            ->implode(',');
        }
        
        if (is_array($users)) {
            return implode(',', $users);
        }

        // it is string in this case
        return $users;
    }
    
    /**
     * Get FCM tokens
     *
     * @param Collection|string|array $users
     * @return array
     */
    protected static function getFCMTokens($users)
    {
        
        if ($users instanceof Collection) {
            return $users
                ->filter(function ($user) { 
                    return !! $user->firebase_cloud_messaging_token; 
                })
                ->pluck('firebase_cloud_messaging_token')
                ->toArray();
        }
        
        if (is_array($users)) {
            return collect($users)
                ->filter(function ($user) { 
                    return (
                        isset($user['firebase_cloud_messaging_token']) && 
                        !! $user['firebase_cloud_messaging_token']
                    );
                })
                ->pluck('firebase_cloud_messaging_token')
                ->toArray();
        }

        // it is string in this case (it's the token it self)
        return !! $users ? [$users] : [];
    }
}
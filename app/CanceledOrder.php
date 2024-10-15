<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Model;
use MixCode\Notifications\NewContactMessage;
use MixCode\Utils\UsingUuid;
use Notification;

class CanceledOrder extends Model
{
    use UsingUuid ;

    protected $fillable = ['driver_id', 'order_id'];
 
}

<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;
class Subscribe extends Model
{
    use UsingUuid ,UsingSerializeDate ,  Notifiable;

    protected $fillable = ['subscriber_email'];
}

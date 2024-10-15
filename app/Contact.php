<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MixCode\Notifications\NewContactMessage;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;
use Notification;

class Contact extends Model
{
    use UsingUuid, UsingSerializeDate,  SoftDeletes;

    protected $fillable = ['name', 'email', 'phone',  'message', 'country_id'];


    public function scopeByCountry(Builder $builder)
    {
        return $builder->where('country_id', countryId());
    }

    /**
     * Notify Admins for new message
     *
     * @return \MixCode\Contact
     */
    public function notifyAdminsForNewMessage()
    {
        Notification::send(User::typeAdmin()->get(), new NewContactMessage($this));

        return $this;
    }
}

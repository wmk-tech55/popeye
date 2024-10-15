<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;
class UserPromoCode extends Pivot
{
    use UsingUuid ,UsingSerializeDate  ;

    const ACTIVE_STATUS = 'active';
    const INACTIVE_STATUS = 'expired';

    protected $table = "user_promo_codes";

    protected $fillable = [
        'promo_code_id',
        'customer_id',
        'available_quantity',
        'status'
    ];


    public function hasStatus($status)
    {
        return $this->status == $status;
    }

    public function active()
    {
        return $this->hasStatus(static::ACTIVE_STATUS);
    }

    public function inActive()
    {
        return $this->hasStatus(static::INACTIVE_STATUS);
    }

    public function scopeActive(Builder $q)
    {
        return $q->where('status', static::ACTIVE_STATUS);
    }

    public function scopeInActive(Builder $q)
    {
        return $q->where('status', static::INACTIVE_STATUS);
    }


    public function markAsActive()
    {
        $this->update(['status' => static::ACTIVE_STATUS]);

        return $this;
    }

    public function markAsInActive()
    {
        $this->update(['status' => static::INACTIVE_STATUS]);

        return $this;
    }
}

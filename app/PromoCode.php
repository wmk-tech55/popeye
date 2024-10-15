<?php

namespace MixCode;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use MixCode\Utils\UsingPriceRatio;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;
class PromoCode extends Model
{
    use UsingUuid ,UsingSerializeDate  ,
        UsingPriceRatio;

    const CREATOR_RELATION_KEY = 'creator_id';

    const ACTIVE_STATUS = 'active';
    const INACTIVE_STATUS = 'expired';

    protected $fillable = [
        'code',
        'discount',
        'number_of_uses',
        'expiry_date',
        'status',
        'creator_id'
    ];

    public function path()
    {
        return route('dashboard.products.show', $this);
    }

    public function apiPath()
    {
        return route('api.products.show', $this);
    }

    public function viewPath()
    {
        return route('products.show', $this);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, static::CREATOR_RELATION_KEY)->withoutGlobalScopes();
    }

    public function customer()
    {
        return $this->belongsToMany(User::class, 'user_promo_codes', 'promo_code_id', 'customer_id')
            ->using(UserPromoCode::class)
            ->withoutGlobalScopes();
    }

    public function customers()
    {
        return $this->hasMany(UserPromoCode::class, 'promo_code_id')->withoutGlobalScopes();
    }

    /**
     * Look for promo-code by its code
     *
     * @param string|null $code
     * @return \MixCode\PromoCode|null
     */
    public static function lookFor(?string $code = null)
    {
        if (! $code) return null;
        
        return static::where('code', trim($code))->first();
    }

    /**
     * Check if promo Code Can Be Used By User
     *
     * @return bool
     */
    public function canBeUsedByUser()
    {
        $userId = auth()->id();

        if (request()->wantsJson()) $userId = auth('api')->id();
        
        // Reuse User with "web" guard in ajax calls
        if (request()->ajax()) $userId = auth()->id();

        $userPromoCode =  $this->customers()->where('customer_id', $userId)->first();
        
        // If user PromoCode history not exists that means the user didn't use it before so it's Ok to use it
        if (! $userPromoCode) return true;

        // If user PromoCode history exists that means the user used it before so let's check if the number of uses is not 0
        if ($userPromoCode->available_quantity > 0) return true;

        // If user PromoCode history exists and user used all available quantity
        return false;
    }

    /**
     * Update number of uses for the promo code
     *
     * @return \MixCode\PromoCode
     */
    public function updateUsedNumber()
    {
        $userId = auth()->id();

        if (request()->wantsJson()) $userId = auth('api')->id();
        
        // Reuse User with "web" guard in ajax calls
        if (request()->ajax()) $userId = auth()->id();

        $userPromoCode =  $this->customers()->where('customer_id', $userId)->first();

        if (!! $userPromoCode) {
            $available_quantity = $userPromoCode->available_quantity - 1;

            $this->customers()->update(['available_quantity' =>  $available_quantity]);
        } else {
            $available_quantity = $this->number_of_uses - 1;

            $this->customers()->create(['customer_id' => $userId, 'available_quantity' => $available_quantity]);
        }

        return $this;
    }

    /**
     * Check if promo code is expired or not
     *
     * Expiration Priority will be determined in 2 steps
     * 1. Check if Promo Code Status Expired or not and check if Expiration date is greater than today date
     * 2. Check if Promo Code Uses Count is greater than 0
     * @return boolean
     */
    public function isExpired()
    {
        // 1. Check if Promo Code Status Expired or not and check if Expiration date is greater than today date
        if (today()->greaterThan(Carbon::parse($this->expiry_date))  ) {
             return true;
        }
        if ($this->inActive()) {
             return true;
        }
 
        // 2. Check if Promo Code Uses Count is greater than 0
        return ($this->number_of_uses <= 0);
    }

    public function isNotExpired()
    {
        return ! $this->isExpired();
    }
    
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

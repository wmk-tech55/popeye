<?php

namespace  MixCode;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use MixCode\Utils\RefreshCache;
use MixCode\Utils\UsingApiScopes;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;

class OrderRequest extends Model
{
    use UsingUuid,
        UsingSerializeDate,
        UsingApiScopes,
        RefreshCache;


    protected $appends = [];
    protected $table = 'orders_requests';

    protected $with = ['order'];


    protected $fillable = [
        'status',
        'order_id',
        'driver_id',
    ];

    const ACCEPTED_STATUS     = 'accepted';
    const NOT_ACCEPTED_STATUS = 'not_accepted';

    const STATUSES = [
        self::ACCEPTED_STATUS,
        self::NOT_ACCEPTED_STATUS
    ];


    protected static function boot()
    {
        parent::boot();
    }


    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id')->withoutGlobalScopes();
    }


    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id')->withoutGlobalScopes();
    }


    public function isAccepted()
    {
        return $this->status == static::ACCEPTED_STATUS;
    }

    public function isNotAccepted()
    {
        return $this->status == static::NOT_ACCEPTED_STATUS;
    }


    public function scopeAccepted(Builder $builder)
    {
        return $builder->where('status', static::ACCEPTED_STATUS);
    }


    public function scopeNotAccepted(Builder $builder)
    {
        return $builder->where('status', static::NOT_ACCEPTED_STATUS);
    }


    public function markAsAccepted()
    {
        $this->update(['status' => static::ACCEPTED_STATUS]);

        return $this;
    }

    /**
     * return lang key if exists in request or fall back to "ar"
     *
     * @return string
     */
    protected function hasLang()
    {
        return (request()->has('lang') && request()->filled('lang')) ? request('lang') : 'en';
    }
}

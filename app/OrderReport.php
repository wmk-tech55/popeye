<?php

namespace  MixCode;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use MixCode\Utils\RefreshCache;
use MixCode\Utils\UsingApiScopes;
use MixCode\Utils\UsingMedia;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;

class OrderReport extends Model
{
    use UsingUuid,
        UsingSerializeDate,
        UsingMedia,
        UsingApiScopes,
        RefreshCache;


    protected $appends = [];
    protected $table = 'orders_reports';

    protected $with = ['order'];  


    protected $fillable = [
        'commission_percentage',
        'total_before_commission',
        'total_after_commission',
        'status',
        'order_id',
        'company_id',
      
    ];
    const NOT_PAID_STATUS = 'not_paid';
    const PAID_STATUS     = 'paid';

    const STATUSES = [
        self::NOT_PAID_STATUS,
        self::PAID_STATUS
    ];


    protected static function boot()
    {
        parent::boot();
    }
 

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id')->withoutGlobalScopes();
    }


    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id')->withoutGlobalScopes();
    }


    public function isPaid()
    {
        return $this->status == static::PAID_STATUS;
    }

    public function isNotPaid()
    {
        return $this->status == static::NOT_PAID_STATUS;
    }

 
    public function scopePaid(Builder $builder)
    {
        return $builder->where('status', static::PAID_STATUS);
    }


    public function scopeNotPaid(Builder $builder)
    {
        return $builder->where('status', static::NOT_PAID_STATUS);
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

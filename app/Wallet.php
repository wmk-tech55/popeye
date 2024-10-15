<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use MixCode\Utils\RefreshCache;
use MixCode\Utils\UsingApiScopes;
use MixCode\Utils\UsingUuid;



class Wallet extends Model
{
    use UsingUuid,  UsingApiScopes, RefreshCache;

    const  BALANCE_PAID       = true;

    const  BALANCE_NOT_PAID   = false;

    const BALANCE_STATUS = [
        self::BALANCE_NOT_PAID,
        self::BALANCE_PAID
    ];

    protected $casts = ['status' => 'boolean'];

    protected $fillable = [
        'balance',
        'status',
        'company_id',
        "order_id"
    ];

    protected static function boot()
    {
        parent::boot();
 
    }

    /*     public function path()
    {
        return route('dashboard.wallets.show', $this);
    }

    public function apiPath()
    {
        return route('api.wallets.show', $this);
    }

    public function viewPath()
    {
        return route('wallets.show', $this);
    } */



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
        return $this->status === static::BALANCE_PAID;
    }

    public function isNotPaid()
    {
        return $this->status === static::BALANCE_NOT_PAID;
    }

    public function hasStatus($status)
    {
        return $this->status == $status;
    }


    public function markAsPaid()
    {
        $this->update(['status' => static::BALANCE_PAID]);

        return $this;
    }

    public function markAsUnPaid()
    {
        $this->update(['status' => static::BALANCE_NOT_PAID]);

        return $this;
    }
 
 
    public function scopePaid(Builder $builder)
    {
        return $builder->where('status', static::BALANCE_PAID);
    }

    
    public function scopeUnPaid(Builder $builder)
    {
        return $builder->where('status', static::BALANCE_NOT_PAID);
    }

    /**
     * return lang key if exists in request or fall back to "ar"
     *
     * @return string
     */
    protected function hasLang()
    {
        return (request()->has('lang') && request()->filled('lang')) ? request('lang') : 'ar';
    }
}

<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use MixCode\Utils\RefreshCache;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;
class Discount extends Model
{
    use UsingUuid ,UsingSerializeDate ,
        RefreshCache,
        SoftDeletes;

    const CREATOR_RELATION_KEY = 'creator_id';

    const ACTIVE_STATUS = 'publish';
    const INACTIVE_STATUS = 'pending';


    protected $appends = ['name_by_lang'];

    protected $fillable = [
        'en_name',
        'ar_name',
        'discount',
        'status',
        'creator_id',
    ];

    protected $hidden = [
        'en_name',
        'ar_name'
    ];


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('has_creator_id', function (Builder $builder) {
            return $builder->has('creator');
        });
    }

    public function path()
    {
        return route('dashboard.discounts.show', $this);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'discount_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, static::CREATOR_RELATION_KEY)->withoutGlobalScopes();
    }

    public function hasStatus($status)
    {
        return $this->status == $status;
    }

    public function isPublished()
    {
        return $this->hasStatus(static::ACTIVE_STATUS);
    }

    public function isPending()
    {
        return $this->hasStatus(static::INACTIVE_STATUS);
    }

    public function scopePublished(Builder $q)
    {
        return $q->where('status', static::ACTIVE_STATUS);
    }

    public function scopePending(Builder $q)
    {
        return $q->where('status', static::INACTIVE_STATUS);
    }

    public function markAsPublished()
    {
        $this->update(['status' => static::ACTIVE_STATUS]);

        return $this;
    }

    public function markAsPending()
    {
        $this->update(['status' => static::INACTIVE_STATUS]);

        return $this;
    }

    /**
     * Create New Discounts With It's Relations
     *
     * @param Request $request
     * @return \MixCode\Discount
     */
    public function createNewDiscount($request)
    {
        $discount = static::create($request->all());

        return $discount;
    }

    /**
     * Update Discounts With It's Relations
     *
     * @param Request $request
     * @return \MixCode\Discount
     */
    public function updateDiscount($request)
    {
        $this->update($request->all());

        return $this;
    }

    public function getNameByLangAttribute()
    {
        $lang = app()->getLocale();

        if (request()->wantsJson()) {
            $lang = $this->hasLang();
        }

        $field = $lang . '_name';

        return $this->{$field};
    }

    /**
     * return lang key if exists in request or fall back to "en"
     *
     * @return string
     */
    protected function hasLang()
    {
        return (request()->has('lang') && request()->filled('lang')) ? request('lang') : 'en';
    }
}

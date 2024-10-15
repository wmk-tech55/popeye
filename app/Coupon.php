<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use MixCode\Notifications\NewProductHasBeenCreated;
use MixCode\Notifications\ProductOrderHasActivated;
use MixCode\Notifications\ProductStatusHasBeenPending;
use MixCode\Notifications\ProductStatusHasBeenPublish;
use MixCode\Utils\RefreshCache;
use MixCode\Utils\UsingFavorite;
use MixCode\Utils\UsingApiScopes;
use MixCode\Utils\UsingFilters;
use MixCode\Utils\UsingMedia;
use MixCode\Utils\UsingPriceRatio;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate
;use Notification;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use MixCode\Classification ;
use MixCode\Category;

class Coupon extends Model implements HasMedia
{
    use UsingUuid ,UsingSerializeDate , 
        UsingMedia, 
        UsingApiScopes, 
        HasMediaTrait, 
        RefreshCache, 
        SoftDeletes;

    const CREATOR_RELATION_KEY = 'creator_id';
    
    const ACTIVE_STATUS = 'publish';
    const INACTIVE_STATUS = 'pending';
   
 
    
    protected $appends = [
        'name_by_lang', 
        'overview_by_lang', 
         'media_links', 
      
    ];
    
    protected $with = ['media'];

    protected $fillable = [
        'en_name',
        'ar_name',
        'price',
        "quantity",
        'en_overview',
        'ar_overview',
        'status',
        'creator_id',
    ];

    protected $hidden = [
        'en_name', 
        'ar_name', 
        'en_overview', 
        'ar_overview', 
        'media',
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

 

   
  

    
  
    public function getMediaLinksAttribute()
    {
        $media = $this->allMedia();

        $links = $media->map(function ($m){
            return $this->safeMediaUrl($m->getUrl());
        });
        
        return $links;
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

  

   
    
    public function getOverviewByLangAttribute()
    {
        $lang = app()->getLocale();
        $clean = false;

        if (request()->wantsJson()) {
            $lang = $this->hasLang();
            $clean = true;
        }
        
        $field = $lang . '_overview';
        
        $field = $this->{$field};

        return $clean ? shortCleanString($field, $limit = null) : $field;
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

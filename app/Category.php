<?php

namespace MixCode;

use Artisan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use MixCode\Utils\RefreshCache;
use MixCode\Utils\UsingApiScopes;
use MixCode\Utils\UsingMedia;
use MixCode\Utils\UsingStatus;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Category extends Model implements HasMedia
{
    use UsingUuid,
        UsingSerializeDate,
        UsingStatus,
        HasMediaTrait,
        UsingMedia,
        UsingApiScopes,
        RefreshCache,
        SoftDeletes;

    const ACTIVE_STATUS = 'active';
    const INACTIVE_STATUS = 'disable';

    const CREATOR_RELATION_KEY = 'creator_id';

    /**
     * The attributes that should be appended.
     *
     * @var array
     */
    protected $appends = ['name_by_lang', 'icon', 'slug_by_lang'];


    protected $fillable = [
        'en_slug',
        'ar_slug',
        'en_name',
        'ar_name',
        'status',
        'categorization_id',
        'parent_id',
        'creator_id',
    ];

    protected $hidden = ['media'];


    protected static function boot()
    {
        parent::boot();

        // When Deleting Category
        static::deleting(function (Category $category) {
            // Clear children   Relation
            $category->children()->delete();

            // Clear Product Categories Relation
            foreach ($category->products as $product) {
               

                // Option 2: If you want to delete the related products instead:
                 $product->delete();
            }


            $category->save();
        });

        static::saving(function (Category $category) {

            $category->en_slug = Str::slug($category->en_name);
            $category->ar_slug = Str::replaceFirst(' ', '-', $category->ar_name);

            Artisan::call('cache:clear');
        });
    }

    public function path()
    {
        return route('dashboard.categories.show', $this);
    }

    public function apiPath()
    {
        return route('api.categories.show', $this);
    }

    public function viewPath()
    {
        return route('categories.show', $this->slug_by_lang);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, static::CREATOR_RELATION_KEY)->withoutGlobalScopes();
    }

    public function products()
    {
        /*  return $this->belongsToMany(Product::class, 'product_categories', 'category_id', 'product_id')
            ->using(ProductCategory::class)
            ->withoutGlobalScopes(); */

        return $this->hasMany(Product::class, 'category_id')->withoutGlobalScopes();
    }

    public function categorization()
    {
        return $this->belongsTo(Categorization::class, 'categorization_id')->withoutGlobalScopes();
    }

    public function parent()
    {
        return $this->belongsTo($this, 'parent_id')->withoutGlobalScopes();
    }

    public function children()
    {
        return $this->hasMany($this, 'parent_id', 'id')->withoutGlobalScopes();
    }


    public function isCatParent()
    {
        return $this->parent == null;
    }

    public function isCatChild()
    {
        return $this->parent != null;
    }

    public function scopeIsParent(Builder $builder)
    {
        return $builder->whereNull('parent_id');
    }

    public function scopeIsChild(Builder $builder)
    {
        return $builder->whereNotNull('parent_id');
    }

    public function getSlugByLangAttribute()
    {
        $lang = app()->getLocale();

        if (request()->wantsJson()) {
            $lang = $this->hasLang();
        }

        $field = $lang . '_slug';

        return $this->{$field};
    }

    public function getNameByLangAttribute()
    {
        $field = $this->getLang() . '_name';

        return $this->{$field};
    }

    /**
     * Return lang key based on if request wants json response for api
     *
     * @return string
     */
    protected function getLang()
    {
        return request()->wantsJson() ? $this->hasLang() : app()->getLocale();
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

    public function getIconAttribute()
    {
        return $this->safeMediaUrl($this->mainMediaUrl('icon'));
    }
}

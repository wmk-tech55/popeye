<?php

namespace MixCode;

use Artisan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use MixCode\Utils\RefreshCache;
use MixCode\Utils\UsingApiScopes;
use MixCode\Utils\UsingFilters;
use MixCode\Utils\UsingMedia;
use MixCode\Utils\UsingFavorite;
use MixCode\Utils\UsingPriceRatio;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use MixCode\Category;
use MixCode\Notifications\NewProductHasBeenCreated;
use Notification;
use Spatie\MediaLibrary\Models\Media;

class Product extends Model implements
    HasMedia
{
    use UsingUuid,
        UsingSerializeDate,
        UsingMedia,
        UsingApiScopes,
        UsingFilters,
        UsingPriceRatio,
        HasMediaTrait,
        RefreshCache,
        UsingFavorite,
        SoftDeletes;

    const CREATOR_RELATION_KEY            = 'creator_id';

    const ACTIVE_STATUS                   = 'publish';
    const INACTIVE_STATUS                 = 'pending';

    const HAS_SPECIAL_PRICE               = 'yes';
    const DONT_HAVE_SPECIAL_PRICE         = 'no';

    const ONE_OPTION                      = "one_option";
    const MULTIPLE_OPTION                 = "multiple_options";

    const ONE_OPTION_GROUP_TYPE           = "one_option_group_type";
    const MULTIPLE_OPTION_GROUP_TYPE      = "multiple_options_group_type";
    const OPTIONS_WITH_COUNTER_GROUP_TYPE = "options_with_counter_group_type";


    const TYPE_OPTIONS    = [self::ONE_OPTION, self::MULTIPLE_OPTION];

    const GROUP_TYPES    = [
        self::MULTIPLE_OPTION_GROUP_TYPE,
        self::ONE_OPTION_GROUP_TYPE,
        self::OPTIONS_WITH_COUNTER_GROUP_TYPE
    ];

    protected $appends = [
        'slug_by_lang',
        'name_by_lang',
        'overview_by_lang',
        'main_media_url',
        'media_links',
        'is_favorited',
        'is_in_cart',
        'has_additions',
        'has_discount'

    ];

    protected $with = ['media'];

    protected $casts = [
        'price'                => 'float',
        'price_after_discount' => 'float',
    ];

    protected $fillable = [
        'en_slug',
        'ar_slug',
        'en_name',
        'ar_name',
        'en_overview',
        'ar_overview',
        'status',
        'discount_id',
        'order_limit',
        'type',
        'creator_id',
        'categorization_id',
        'category_id',
        'data_country_id',
        'price',
        'price_after_discount',
        'discount_percentage',
    ];

    protected $guarded = [
        'views_count',
        'average_rate',
    ];

    protected $hidden = ['media'];


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('has_creator_id', function (Builder $builder) {
            return $builder->has('creator');
        });

        // When Deleting Product
        static::deleting(function (Product $product) {
            if ($product->isForceDeleting()) {
                // Clear Product Cart Relation
                $product->carts()->delete();

                // Clear Product Order Relation
                $product->order()->delete();

                // Clear Product Categories Relation
                $product->categories()->detach();

                // Clear Product Reviews Relation
                $product->reviews()->delete();


                $product->productAdditions()->delete();

                $product->productGroupsWithAdditions()->delete();

                $product->productVariations()->delete();

                // // Clear Product Views Relation
                $product->views()->detach();

                // Clear Product Views Field
                $product->views_count = 0;
                $product->save();
            }
        });

        static::saving(function (Product $product) {

            $product->en_slug = Str::slug($product->en_name);
            $product->ar_slug = Str::replaceFirst(' ', '-', $product->ar_name);

            Artisan::call('cache:clear');
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
        return route('products.show', $this->slug_by_lang);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id')
            ->using(ProductCategory::class)
            ->with('parent')
            ->withoutGlobalScopes();
    }

    public function lastCategory()
    {

        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id')
            ->using(ProductCategory::class)
            ->with('parent')
            ->latest()
            ->limit('1')
            ->withoutGlobalScopes();
    }

    public function views()
    {
        return $this->belongsToMany(User::class, 'product_views', 'product_id', 'user_id')
            ->using(ProductView::class)
            ->withoutGlobalScopes();
    }

    public function categorization()
    {
        return $this->belongsTo(Categorization::class, 'categorization_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }

    public function classifications()
    {
        return $this->belongsToMany(Classification::class, 'product_variations', 'product_id', 'classification_id')
            ->using(ProductVariation::class)
            ->withoutGlobalScopes();
    }

    public function productVariations()
    {
        return $this->hasMany(ProductVariation::class, 'product_id');
    }

    public function productAdditions()
    {
        return $this->hasMany(ProductAddition::class, 'product_id');
    }

    public function productGroupsWithAdditions()
    {
        return $this->hasMany(ProductGroupAddition::class, 'product_id');
    }


    public function oneOptionVariation()
    {
        return $this->hasOne(ProductVariation::class, 'product_id')->where('type', static::ONE_OPTION);
    }

    public function multipleOptionVariations()
    {
        return $this->hasMany(ProductVariation::class, 'product_id')->where('type', static::MULTIPLE_OPTION);
    }
    public function defaultMultipleOptionVariation()
    {
        return $this->hasOne(ProductVariation::class, 'product_id')
            ->where('type', static::MULTIPLE_OPTION)
            ->where('is_primary', true);
    }



    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, static::CREATOR_RELATION_KEY)->withoutGlobalScopes();
    }

    public function order()
    {
        return $this->belongsTo(ProductOrder::class, 'product_id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'product_orders', 'product_id', 'order_id')
            ->using(ProductOrder::class)
            ->withoutGlobalScopes();
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'product_id');
    }


    public function isOneOption()
    {
        return $this->type == static::ONE_OPTION;
    }

    public function isMultipleOption()
    {
        return $this->type == static::MULTIPLE_OPTION;
    }

    public function isAllowedToUpdateOrDelete()
    {
        return  auth()->user()->isAdmin() || ($this->creator_id == auth()->id() && auth()->user()->isActive());
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

    public function scopeByCountry(Builder $builder)
    {
        return $builder->where('data_country_id', countryId());
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


    public function bestSellingProductByCategory()
    {
        $orders_products       = product::published()
            ->whereHas('categories', function ($q) {
                return $q->whereIn('category_id', $this->categories->pluck('id')->toArray());
            })
            ->primary()
            ->orderBy('views_count', 'desc');

        $bestSellingProducts   = product::published()
            ->whereHas('categories', function ($q) {
                return $q->whereIn('category_id', $this->categories->pluck('id')->toArray());
            })
            ->primary()
            ->whereHas('orders')
            ->union($orders_products)
            ->take(10)
            ->get();

        return $bestSellingProducts;
    }



    /**
     * Submit a Review for a Trip
     *
     * @param array $data
     * @return \MixCode\Review
     */
    public function submitReview($data)
    {
        $review = $this->reviews()->create($data);

        $this->average_rate = round($this->reviews()->avg('rate'));
        $this->save();

        return $review;
    }



    /**
     * Add Product to cart
     *
     * Reuse User with "api" guard in api
     * Reason for that is "list all Products" endpoint don't require use to be authenticated
     * so if we pass the Bearer Token, it will not work
     * Because we don't use "auth:api" that is using "api" guard for us
     * So We Need to use "api" Guard manually
     * 
     * @param int $quantity
     * @return \MixCode\Cart
     */
    public function addToCart($product, $request)
    {

        $quantity      = 1;
        $additions     = [];
        $userId        = auth()->id();

        if ($request->has('quantity') && $request->filled('quantity') && $request->quantity > 0) {
            $quantity = $request->quantity;
        }

        if (request()->wantsJson()) $userId = auth('api')->id();

        // Reuse User with "web" guard in ajax calls
        if (request()->ajax()) $userId = auth()->id();


        if ($request->has('additions') && $request->filled('additions') &&   count($request->additions) > 0) {

            foreach ($request->additions as $additionValue) {

                $addition = ProductAddition::find($additionValue['id']);

                if ($addition) {

                    $additions[] =   [
                        'price'                  => $addition->price,
                        'total_price'            => $additionValue['quantity'] * $addition->price,
                        'quantity'               => $additionValue['quantity'],
                        "addition_id"            => $addition->id,
                        "cart_group_addition_id" => null,
                        "cart_id"                => null,
                    ];
                }
            }
        }

        $total_price = $product->price_after_discount * $quantity;

        $data = [
            'product_id'                   => $product->id,
            'customer_id'                  => $userId,
            'store_id'                     => $product->creator_id,
            'quantity'                     => $quantity,
            'price'                        => $product->price_after_discount,
            'total_price'                  => $total_price,
            'total_price_before_additions' => $total_price
        ];

        $cart =  $this->carts()->create($data);

        if ($additions && count($additions) > 0) {
            $cart = $cart->attachAdditions($additions, $total_price);
        }

        return $cart;
    }


    /**
     * Is Product in cart
     *
     * Reuse User with "api" guard in api
     * Reason for that is "list all Products" endpoint don't require use to be authenticated
     * so if we pass the Bearer Token, it will not work
     * Because we don't use "auth:api" that is using "api" guard for us
     * So We Need to use "api" Guard manually
     * 
     * @return bool
     */
    public function isInCart()
    {
        $userId = auth()->id();

        if (request()->wantsJson()) $userId = auth('api')->id();

        // Reuse User with "web" guard in ajax calls
        if (request()->ajax()) $userId = auth()->id();

        return $this->carts()->where('customer_id', $userId)->exists();
    }



    public function getIsInCartAttribute()
    {
        return $this->isInCart();
    }



    /**
     * Create New Products With It's Relations
     *
     * @param Request $request
     * @return \MixCode\Product
     */
    public function createNewProduct($request)
    {
        /** @var \MixCode\Product */

        $data                   = resolveDataValues($request->all(),  $forStore = true);
        $data['data_country_id']      = auth()->user()->active_country_id;


        if (array_key_exists($data['discount_percentage'], $request->all())  || $data['discount_percentage'] > 0 || $data['discount_percentage'] <= 100) {

            $discount = ($data['price'] *  $data['discount_percentage']) / 100;

            $price_after_discount = $data['price'] - $discount;

            $data['price_after_discount'] = $price_after_discount;
        } else {
            $data['price_after_discount'] = $data['price'];
            $data['discount_percentage']  = 0;
        }


        $product           = static::create($data);
        // $product->attachVariations($request);
        $product->attachAdditions($request);
        //    $product->attachGroupsWithAdditions($request);

        return $product;
    }

    /**
     * Update Products With It's Relations
     *
     * @param Request $request
     * @return \MixCode\Product
     */
    public function updateProduct($request)
    {
        $data  = resolveDataValues($request->all());


        if (array_key_exists($data['discount_percentage'], $request->all())  || $data['discount_percentage'] > 0 || $data['discount_percentage'] <= 100) {

            $discount = ($data['price'] *  $data['discount_percentage']) / 100;

            $price_after_discount = $data['price'] - $discount;

            $data['price_after_discount'] = $price_after_discount;
        } else {
            $data['price_after_discount'] = $data['price'];
            $data['discount_percentage']  = 0;
        }

        $this->update($data);
        //   $this->syncVariations($request);
        $this->syncAdditions($request);
        // $this->syncGroupsWithAdditions($request);

        return $this;
    }

    /*  public function attachVariations($request)
    {
        $prices = [];

        if ($this->isMultipleOption()) {

            for ($i = 0; $i < count($request->variations_prices); $i++) {

                $i == 0 ? $is_primary = true : $is_primary = false;
                $prices[]  = [
                    'en_name'    => $request->variations_en_names[$i],
                    'ar_name'    => $request->variations_ar_names[$i],
                    'price'      => $request->variations_prices[$i],
                    'type'       => $request->type,
                    'product_id' => $this->id,
                    'is_primary' => $is_primary,
                ];
            }

            $this->productVariations()->createMany($prices);
        } else {

            $data = [
                'en_name'    => null,
                'ar_name'    => null,
                'price'      => $request->price,
                'type'       => $request->type,
                'product_id' => $this->id,
                'is_primary' => true,
            ];
            $this->productVariations()->create($data);
        }

        return $this;
    } */

    public function attachAdditions($request)
    {
        $prices = [];

        if ($request->has_additions == 'yes') {

            for ($i = 0; $i < count($request->additions_prices); $i++) {

                for ($i = 0; $i < count($request->additions_prices); $i++) {
                    $prices[]  = [

                        'ar_name'    => $request->additions_ar_names[$i],
                        'en_name'    => $request->additions_ar_names[$i], //$request->additions_en_names[$i],
                        'price'      => $request->additions_prices[$i],
                        'product_id' => $this->id,
                    ];
                }
            }


            $this->productAdditions()->createMany($prices);
        }


        return $this;
    }

    /*    public function attachGroupsWithAdditions($request)
    {
        $prices = [];

        if ($request->has_additions == 'yes') {

            for ($x = 0; $x < count($request->group_ar_names); $x++) {
                $data = [
                    'ar_name'    => $request->group_ar_names[$x],
                    'en_name'    => $request->group_ar_names[$x],
                    'type'       => $request->group_types[$x],
                    'product_id' =>  $this->id
                ];

                $group =  ProductGroupAddition::create($data);

                for ($i = 0; $i < count($request->additions_ar_names[$x]); $i++) {
                    $prices  = [
                        'en_name'    => $request->additions_ar_names[$x][$i],
                        'ar_name'    => $request->additions_ar_names[$x][$i],
                        'price'      => $request->additions_prices[$x][$i],
                        'product_id' => $this->id,
                        'group_id'   => $group->id,
                    ];
                    ProductAddition::create($prices);
                }
            }
        }


        return $this;
    } */


    /*  public function syncGroupsWithAdditions($request)
    {

        $this->productGroupsWithAdditions->each->delete();

        $this->attachGroupsWithAdditions($request);

        return $this;
    } */

    /* public function syncVariations($request)
    {
        $this->productVariations()->delete();

        $this->attachVariations($request);

        return $this;
    }
*/
    public function syncAdditions($request)
    {

        $this->productAdditions()->delete();

        $this->attachAdditions($request);

        return $this;
    }


    /*     public function getPriceAttribute()
    {
        if ($this->isOneOption())   return $this->oneOptionVariation->price ?? 0;

        return $this->defaultMultipleOptionVariation->price ?? 0;
    } */

    public function getHasAdditionsAttribute()
    {
        return $this->productAdditions()->exists();
    }

    public function getHasDiscountAttribute()
    {
        return $this->discount_percentage  > 0;
    }


    public function getMediaLinksAttribute()
    {
        /*  $media = $this->allMedia();

        $links = $media->map(function ($m) {
            return $this->safeMediaUrl($m->getUrl());
        }); */
        //    return $links;

        $media = $this->allMedia();

        return $media->map(function (Media $m) {
            return [
                'id'        => $m->id,
                'filename'  => $m->file_name,
                'size'      => $m->size,
                'link'      => $this->safeMediaUrl($m->getUrl()),
                'mime_type' => $m->mime_type,
            ];
        });
    }

    public function getVideoAttribute()
    {
        return  $this->mainMediaUrl('video');
    }


    protected function defaultMediaUrl()
    {
        return null;
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
     * Notify Subscribers  for new product
     *
     * @return \MixCode\Product
     */
    public function notifySubscribersForNewProducts($type = 'create')
    {
        Notification::send(Subscribe::get(), new NewProductHasBeenCreated($this, $type));

        return $this;
    }


    /**
     * Notify Admin  for new product
     *
     * @return \MixCode\Product
     */
    public function notifyAdminForNewProducts($type = 'create')
    {

        try {

            $notifiers = User::where('active_country_id', $this->data_country_id)->typeAdmin()->get();

            Notification::send($notifiers, new NewProductHasBeenCreated($this, $type));
        } catch (\Exception $e) {
            \Log::channel('notification_errors')->error(get_class($this) . " -> " . __FUNCTION__ . " -> " . get_class($e) . " -> " . $e->getMessage());
        }

        return $this;
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

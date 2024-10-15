<?php

namespace MixCode;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;
use MixCode\Utils\UsingMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Laravel\Passport\HasApiTokens;
use MixCode\Notifications\NewUserHasBeenRegistered;
use MixCode\Notifications\AccountStatusChanges;
use Notification;
use MixCode\NotificationFactory;
use MixCode\Cart;
use MixCode\Utils\UsingFavorite;
use Spatie\MediaLibrary\Models\Media;
use Str;

class User extends Authenticatable implements HasMedia
{
    use Notifiable, HasApiTokens, UsingUuid, UsingMedia, HasMediaTrait, SoftDeletes;

    const CUSTOMER_TYPE  = 'customer';
    const ADMIN_TYPE     = 'admin';
    const DRIVER_TYPE    = 'driver';
    const COMPANY_TYPE   = 'company';

    const USER_TYPES    = [
        self::CUSTOMER_TYPE,
        self::ADMIN_TYPE,
        self::COMPANY_TYPE,
        self::DRIVER_TYPE
    ];

    const ACTIVE_STATUS = 'active';
    const PENDING_STATUS = 'pending';
    const INACTIVE_STATUS = 'disable';
    const USER_STATUS = [
        self::ACTIVE_STATUS,
        self::PENDING_STATUS,
        self::INACTIVE_STATUS
    ];

    const CREATOR_RELATION_KEY = 'creator_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name',
        'username',
        'email',
        'password',
        'phone',
        'phone_code', // Came From frontend only
        'national_id',
        'bio',
        'type',
        'status',
        'lang',
        'active_country_id',
        'country_id',
        'city_id',
        'facebook',
        'twitter',
        'linkedin',
        'instagram',
        'snapchat',
        'youtube',
        'provider',
        'provider_id',
        'verification_code',
        'longitude',
        'latitude',
        'zoom',
        'address',
        'country_code',
        'city',
        'area',
        'country',
        'firebase_cloud_messaging_token',
        'categorization_id',
        'shipping_fee',
        'is_availability',
        'on_trip',
        'account_social_provider',
        'account_social_provider_id',
        'total_current_wallet_amount',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'media'
    ];

    /**
     * The attributes that should be appended.
     *
     * @var array
     */

    protected $appends = ['allUserMedia', 'reports_total', 'top_discount']; //, 'logo_url'
    protected $with = ['media', 'work_times'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_availability'   => 'boolean',
        'on_trip'           => 'boolean'
    ];

    /** 
     * Attached Media Names
     * @var array
     */
    protected $attachedMedia = [
        'logo',
        'id_card',
        'pledge',
        'commercial_license',
        'vehicle_license',
    ];

    protected static function boot()
    {
        parent::boot();

        // When Deleting User
        static::deleting(function (User $user) {
            // Clear User Order Relation
            $user->order()->delete();

            // Clear User Carts Relation
            $user->carts()->delete();

            // Clear User addresses Relation
            $user->addresses()->delete();

            // Clear User Reviews Relation
            $user->reviews()->delete();

            // Clear User Views Relation
            $user->views()->detach();

            // Clear User Notifications Relation
            $user->notifications()->delete();
        });
    }

    public function path()
    {
        return route('dashboard.users.show', $this);
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function userCountry()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, static::CREATOR_RELATION_KEY);
    }
    public function addresses()
    {
        return $this->hasMany(Address::class, "owner_id");
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'owner_id');
    }
    public function categorization()
    {
        return $this->belongsTo(Categorization::class, 'categorization_id');
    }

    public function work_times()
    {
        return $this->hasMany(WorkTime::class, 'user_id')->oldest('day_order')->withoutGlobalScopes();
    }


    /*     public function categorizations()
    {
        return $this->belongsToMany(Categorization::class, 'user_categorizations', 'user_id', 'categorization_id')
            ->using(CategorizationUser::class)
            ->withoutGlobalScopes();
    } */

    public function products()
    {
        return $this->hasMany(Product::class, static::CREATOR_RELATION_KEY);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'customer_id');
    }

    public function order()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function ownedOrders()
    {
        return $this->hasMany(Order::class, 'provider_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'customer_id');
    }

    public function views()
    {
        return $this->belongsToMany(Product::class, 'product_views', 'user_id', 'product_id')
            ->using(ProductView::class)
            ->withoutGlobalScopes();
    }

    public function promoCodes()
    {
        return $this->belongsToMany(PromoCode::class, 'user_promo_codes', 'customer_id', 'promo_code_id')
            ->using(UserPromoCode::class)
            ->withoutGlobalScopes();
    }


    public function favorites()
    {

        return Favorite::where('user_id', auth()->id())->with('favorited')->latest()->get();
    }

    public function reports()
    {
        return $this->hasMany(OrderReport::class, 'company_id');
    }

    public function getUserFavorites()
    {
        if (auth()->check()) {

            return    $this->favoriteProductFromDataBase();
        } else {

            return   $this->favoriteProductFromSession();
        }
    }


    public function favoriteProductFromDataBase()
    {

        return auth()->user()->favorites()->map->favorited;
    }

    public function favoriteProductFromSession()
    {
        $products_in_session =  session()->get('favorite_products');

        if ($products_in_session) {

            return Product::whereIn('id', $products_in_session)->get();
        }
    }


    public function isType($type)
    {
        return $this->type == $type;
    }

    public function isCustomer()
    {
        return $this->isType(static::CUSTOMER_TYPE);
    }

    public function isDriver()
    {
        return $this->isType(static::DRIVER_TYPE);
    }

    public function isAdmin()
    {
        return $this->isType(static::ADMIN_TYPE);
    }

    public function isCompany()
    {
        return $this->isType(static::COMPANY_TYPE);
    }


    public function isAllowed()
    {
        return ($this->isAdmin() || $this->isCompany());
    }

    public function hasStatus($status)
    {
        return $this->status == $status;
    }

    public function isActive()
    {
        return $this->hasStatus(static::ACTIVE_STATUS);
    }

    public function isPending()
    {
        return $this->hasStatus(static::PENDING_STATUS);
    }

    public function isInActive()
    {
        return $this->hasStatus(static::INACTIVE_STATUS);
    }

    public function hasRunningTrip()
    {
        return $this->on_trip == true;
    }


    public function markAsOnTrip()
    {
        $this->update(['on_trip' => true]);

        return $this;
    }

    public function markAsFinishTrip()
    {
        $this->update(['on_trip' => false]);

        return $this;
    }

    public function markAsActive()
    {
        $this->update(['status' => static::ACTIVE_STATUS]);

        return $this;
    }

    public function markAsPending()
    {
        $this->update(['status' => static::PENDING_STATUS]);

        return $this;
    }

    public function scopeByCountry(Builder $builder)
    {

        return $builder->where('active_country_id', countryId());
    }

    public function scopeIsAvailable(Builder $builder)
    {
        return $builder->where('is_availability', true);
    }

    public function scopeFinishedTrips(Builder $builder)
    {
        return $builder->where('on_trip', false);
    }

    public function scopeTypeCustomer(Builder $builder)
    {
        return $builder->where('type', static::CUSTOMER_TYPE);
    }

    public function scopeTypeDriver(Builder $builder)
    {
        return $builder->where('type', static::DRIVER_TYPE);
    }

    public function scopeTypeAdmin(Builder $builder)
    {
        return $builder->where('type', static::ADMIN_TYPE);
    }
    public function scopeTypeCompany(Builder $builder)
    {
        return $builder->where('type', static::COMPANY_TYPE);
    }

    public function scopeActive(Builder $builder)
    {
        return $builder->where('status', static::ACTIVE_STATUS);
    }

    public function scopePending(Builder $builder)
    {
        return $builder->where('status', static::PENDING_STATUS);
    }

    public function scopeInActive(Builder $builder)
    {
        return $builder->where('status', static::INACTIVE_STATUS);
    }


    public function scopeByLocation(Builder $builder)
    {
        $location = userLocation();
        $lat      = $location['latitude'];
        $long     = $location['longitude'];
        $distance = $location['distance'];

        if (!$lat && !$long)    return $builder;

        return $builder->select(DB::raw("*, ( 6371 * acos( cos( radians($lat) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians($long) ) + sin( radians($lat) ) * sin( radians( latitude ) ) ) ) AS distance"))
            ->havingRaw("distance < $distance")->oldest('distance');;
    }

    /**
     * Generate New Bearer Token to User
     *
     * @return array
     */
    public function generateNewToken()
    {
        $token = $this->createToken("User Token");

        return [
            "user_id"          => $this->id,
            "user_full_name"   => $this->full_name,
            "user_logo"        => $this->userLogoMedia(),
            "user_type"        => $this->type,
            "token_type"       => "Bearer",
            "token"            => $token->accessToken,
            "token_expires_at" => $token->token->expires_at
        ];
    }

    public function changeCountryForUser($country)
    {
        auth()->user()->update(['active_country_id' => $country]);
    }

    public function register($data, $request_is_api = false)
    {
        $data['password']   = Hash::make($data['password']);


        if ($request_is_api) {
            $data['active_country_id'] = $data['country_id'];
        } else {
            $data = $this->setCountryIdProperly($data);
        }

        //$data['type'] == static::CUSTOMER_TYPE && $data['type'] == static::COMPANY_TYPE &&
        if ($request_is_api === true) {

            $data['status'] = static::ACTIVE_STATUS;
        }


        /** @var \MixCode\User $user */
        $user = $this->create($data);

        $user->attachMedia($data);

        if ($user->isCompany()) { //&& array_key_exists('day_is_vacation', $data)

            $user->attachWorkHours($data);
        }

        if (array_key_exists('vehicle_photos', $data)) {

            $user->uploadMultiMediaFromRequest('vehicle_photos');
        }

        // tap(new NewUserHasBeenRegistered($user), function ($notification) {
        //     Notification::send(User::typeAdmin()->get(), $notification);

        //     notifyMainNotificationMail($notification);
        // });

        return $user;
    }


    /**
     * Update Existed User With Media
     *
     * @param array $data
     * @return \MixCode\Profile
     */
    public function updateWithMedia($data)
    {
        $data = $this->setCountryIdProperly($data);

        $this->update($data);

        if ($this->isCompany()) {
            $this->syncWorkHours($data);
        }


        $this->syncMedia($data);

        return $this;
    }

    public function setCountryIdProperly($data)
    {
        if (array_key_exists('country_data_id', $data)) {
            $data['active_country_id'] = $data['country_data_id'];
            $data['country_id']        = $data['country_data_id'];
        } elseif (array_key_exists('country_id', $data)) {
            $data['active_country_id'] = $data['country_id'];
        } else {
            $data['active_country_id'] = auth()->user()->active_country_id;
        }

        return $data;
    }

    public function initWorkTimes($work_time_data = null)
    {

        $workTimesData = WorkTime::defaultWorkTimes();

        if ($work_time_data != null) {

            $workTimesData = $work_time_data;
        }

        $this->work_times()->createMany($workTimesData);
    }



    /**
     * attach user work hours
     *
     * @param array $data
     * @return $this
     */
    public function attachWorkHours($data)
    {
        $work_time_data = [];
        $dayIsVacation  = in_array('day_is_vacation',   $data) ? $data['day_is_vacation'] : false;
        $openTime       = in_array('open_time',   $data) ? $data['open_time'] : false;
        $closeTime      = in_array('close_time',   $data) ? $data['close_time'] : false;


        foreach (WorkTime::defaultWorkTimes() as $key => $v) {

            $day_is_vacation =  $v['day_is_vacation'];


            if ($dayIsVacation && in_array($v['raw_order'],   $dayIsVacation)) {

                $day_is_vacation = true;
            }

            $new_open_time  = $openTime  ? $openTime[$key] . ":00"  : $v['open_time'];
            $new_close_time = $closeTime  ? $closeTime[$key] . ":00"  : $v['close_time'];


            $work_time_data[] = [
                "en_day_name"     => $v['en_day_name'],
                "ar_day_name"     => $v['ar_day_name'],
                "day_order"       => $v['day_order'],
                "raw_order"       => $v['raw_order'],
                "day_is_vacation" => $day_is_vacation,
                "open_time"       => $new_open_time,
                "close_time"      => $new_close_time,
            ];
        }

        $this->initWorkTimes($work_time_data);

        return $this;
    }

    /**
     * sync user work hours
     *
     * @param array $data
     * @return $this
     */
    public function syncWorkHours($data)
    {
        $work_time_data = [];
        $dayIsVacation  = array_key_exists('day_is_vacation',   $data) ? $data['day_is_vacation'] : false;
        $openTime       = array_key_exists('open_time',   $data) ? $data['open_time'] : false;
        $closeTime      = array_key_exists('close_time',   $data) ? $data['close_time'] : false;

        foreach ($this->work_times->toArray() as $key => $v) {

            $day_is_vacation =  $v['day_is_vacation'];


            if (!!$dayIsVacation && in_array($v['raw_order'],   $dayIsVacation)) {

                $day_is_vacation = true;
            } else {
                $day_is_vacation = false;
            }

            $new_open_time  = $openTime  ? $openTime[$key] . ":00"  : $v['open_time'];
            $new_close_time = $closeTime  ? $closeTime[$key] . ":00"  : $v['close_time'];

            $work_time_data[] = [
                "id"              => $v['id'],
                "en_day_name"     => $v['en_day_name'],
                "ar_day_name"     => $v['ar_day_name'],
                "day_order"       => $v['day_order'],
                "raw_order"       => $v['raw_order'],
                "day_is_vacation" => $day_is_vacation,
                "open_time"       => $new_open_time,
                "close_time"      => $new_close_time,
            ];
        }


        foreach ($work_time_data as $workTimeData) {

            $work_time = WorkTime::find($workTimeData['id']);

            $work_time->update($workTimeData);
        }
    }
    /**
     * attach Media to User
     *
     * @param array $data
     * @return $this
     */
    public function attachMedia($data)
    {
        // Filter Array Data And Return Just Wanted Media Key/Value Pairs
        $media = array_filter(Arr::only($data, $this->attachedMedia));

        // If There is Media
        if (count($media) > 0) {
            foreach ($media as $media_name => $media_value) {
                // Define Media Type Post Key "For More Readability"
                $post_media_type = 'file';

                if ($media_name === 'logo') {
                    $post_media_type = 'image';
                }


                $this->uploadSingleMedia($media_value, $media_name, $post_media_type);
            }
        }

        return $this;
    }

    /**
     * Sync Media of User
     *
     * @param array $data
     * @return $this
     */
    public function syncMedia($data)
    {
        // Filter Array Data And Return Just Wanted Media Key/Value Pairs
        $media = array_filter(Arr::only($data, $this->attachedMedia));

        // If There is Media
        if (count($media) > 0) {
            foreach ($media as $media_name => $media_value) {
                // Define Media Type Post Key "For More Readability"
                $post_media_type = 'file';

                if ($media_name === 'logo') {
                    $post_media_type = 'image';
                }

                // Update Single Media
                $this->updateSingleMedia($media_value, $media_name, $post_media_type);
            }
        }

        return $this;
    }

    /**
     * Get All User Media Link
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function allUserMedia()
    {

        return [
            'logo'               => $this->userLogoMedia(),
            'commercial_license' => $this->userCommercialLicenseMedia(),
            'vehicle_license'    => $this->userVehicleLicenseMedia(),
            'vehicle_photos'     => $this->userVehiclePhotosMedia(),
        ];
    }

    /**
     * Get All User Media Link
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getAllUserMediaAttribute()
    {
        return $this->allUserMedia();
    }


    /**
     * Get User Media
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function userMedia()
    {
        return [
            'logo'               => $this->userLogoMedia(),
            'commercial_license' => $this->userCommercialLicenseMedia(),
            'vehicle_license'    => $this->userVehicleLicenseMedia(),
            'vehicle_photos'     => $this->userVehiclePhotosMedia(),
        ];
    }

    public function getCommercialLicenseUrlAttribute()
    {
        return $this->userCommercialLicenseMedia();
    }

    public function userCommercialLicenseMedia()
    {
        $image = $this->safeMediaUrl($this->mainMediaUrl('commercial_license', 'image'));
        $file = $this->safeMediaUrl($this->mainMediaUrl('commercial_license', 'file'));

        if ($image) {
            return  $image;
        }

        return  $file;
    }
    public function userVehicleLicenseMedia()
    {
        $image = $this->safeMediaUrl($this->mainMediaUrl('vehicle_license', 'image'));
        $file = $this->safeMediaUrl($this->mainMediaUrl('vehicle_license', 'file'));

        if ($image) {
            return  $image;
        }

        return  $file;
    }

    public function userVehiclePhotosMedia()
    {
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

    public function  userLogoMedia()
    {
        return $this->safeMediaUrl($this->mainMediaUrl('logo'));
    }

    public function getLogoUrlAttribute()
    {
        return $this->userLogoMedia();
    }

    public function userIdCardMedia()
    {
        return $this->safeMediaUrl($this->mainMediaUrl('id_card', 'file'));
    }

    public function userBusinessRegisterMedia()
    {
        return $this->safeMediaUrl($this->mainMediaUrl('commercial_license', 'file'));
    }

    protected function defaultMediaUrl()
    {
        return asset('/assets/img/user-avatar.png');
    }



    public function  getReportsTotalAttribute()
    {

        $data['total_order_count']                = $this->reports()->count() ?? 0;
        $data['total_not_paid_order_count']       = $this->reports()->notPaid()->count() ?? 0;
        $data['total_before_commission']          = $this->reports()->notPaid()->sum('total_before_commission') ?? 0;
        $data['total_after_commission']           = $this->reports()->notPaid()->sum('total_after_commission') ?? 0;
        $data['total_amount_owed_to_the_company'] = $this->reports()->notPaid()->sum('total_after_commission') ?? 0;
        $data['total_amount_for_the_application'] = $data['total_before_commission']  - $data['total_amount_owed_to_the_company'] ?? 0;

        return $data;
    }


    public function getTopDiscountAttribute()
    {
        $discount = null;

        $product = $this->products()
            ->where('discount_percentage', '!=', '')
            ->orderBy('discount_percentage', 'desc')
            ->first();

        if ($product) $discount = $product->discount_percentage;

        return $discount;
    }

    public function getIsOpenTodayAttribute()
    {
        $day = $this->work_times->where('raw_order', today()->dayOfWeek)->first();

        if (! $day) return false;

        if ($day->day_is_vacation) return false;

        // Before Opening Hours
        if (now()->isBefore($day->open_time)) return false;

        // After Closing Hours
        if (now()->isAfter($day->close_time)) return false;

        return true;
    }

    public function sendResetPasswordCodeViaSms($phone, $body)
    {

        NotificationFactory::notifyByMsegatSmsMessages($phone, '', $body);

        return $this;
    }


    /**
     * Notify Admin  for new account
     *
     * @return \MixCode\User
     */
    public function notifyAdminForNewRegistration()
    {

        try {

            $notifiers = User::where('active_country_id', $this->active_country_id)->typeAdmin()->get();

            Notification::send($notifiers, new NewUserHasBeenRegistered($this));
        } catch (\Exception $e) {
            \Log::channel('notification_errors')->error(get_class($this) . " -> " . __FUNCTION__ . " -> " . get_class($e) . " -> " . $e->getMessage());
        }

        return $this;
    }

    /**
     * Notify User For activating account 
     *
     * @return \MixCode\Product
     */
    public function notifyUserForActivatingAccount()
    {

        try {

            Notification::send($this, new AccountStatusChanges($this, 'account_activated_successfully'));
        } catch (\Exception $e) {
            \Log::channel('notification_errors')->error(get_class($this) . " -> " . __FUNCTION__ . " -> " . get_class($e) . " -> " . $e->getMessage());
        }

        return $this;
    }

    /**
     * Notify User For disabled and mark it as pending account 
     *
     * @return \MixCode\Product
     */
    public function notifyUserForMadeAccountPending()
    {

        try {

            Notification::send($this, new AccountStatusChanges($this, 'account_has_been_disabled'));
        } catch (\Exception $e) {
            \Log::channel('notification_errors')->error(get_class($this) . " -> " . __FUNCTION__ . " -> " . get_class($e) . " -> " . $e->getMessage());
        }

        return $this;
    }
}

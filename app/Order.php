<?php

namespace MixCode;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MixCode\Notifications\NewOrderHasBeenAssignedToDriver;
use MixCode\Notifications\OrderHasBeenPaid;
use MixCode\Notifications\OrderHasBeenPaidForCustomer;
use MixCode\Notifications\NewOrderHasBeenCreated;
use MixCode\Notifications\NewOrderHasBeenCreatedForCustomer;
use MixCode\Notifications\NewOrderHasBeenCreatedForDrivers;
use MixCode\Notifications\OrderActivated;
use MixCode\Notifications\OrderApproved;
use MixCode\Notifications\OrderHasBeenAcceptedForProvider;
use MixCode\Notifications\OrderInShipping;
use MixCode\Notifications\OrderInShippingForAdmin;
use MixCode\Notifications\OrderIsCanceled;
use MixCode\Notifications\OrderIsDelivered;
use MixCode\Notifications\OrderIsDeliveredForAdmin;
use MixCode\Notifications\OrderIsPending;
use MixCode\Notifications\OrderNotApproved;
use MixCode\Notifications\OrderRequestReturn;
use MixCode\Notifications\ProductOrderCancelled;
use MixCode\Notifications\ProductOrderRequestReturn;
use MixCode\Utils\UsingPaymentMethodsStatus;
use MixCode\Utils\UsingPlatformStatus;
use MixCode\Utils\UsingPriceRatio;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;
use Notification;
use Str;

class Order extends Model
{
    use UsingUuid,
        UsingSerializeDate,
        UsingPriceRatio,
        UsingPaymentMethodsStatus,
        UsingPlatformStatus,
        SoftDeletes;

    // Payment Status Constants
    // -- Don't Make Paid Status === 'paid' because 
    // -- DataTables will search wildcard and get Not Paid Status Also

    const PAID_STATUS =  true; //'finished';
    const NOT_PAID_STATUS = false; // 'not_paid';

    // delivery status 

    const DELIVERED_STATUS = true; // 'delivered';
    const NOT_DELIVERED_STATUS = false; // 'not_delivered';

    // cancelation status
    const CANCELLED_STATUS = true; //'cancelled';
    const NOT_CANCELLED_STATUS = false; //'not_cancelled';

    // approval status  
    const APPROVED_STATUS =  true; //'approved';
    const NOT_APPROVED_STATUS = false; // 'not_approved'; 

    // pending status  
    const PREPARED_STATUS =  true; //'prepared';
    const NOT_PREPARED_STATUS = false; // 'not_prepared'; 

    // shipping status  
    const SHIPPED_STATUS =  true; //'shipped';
    const NOT_SHIPPED_STATUS = false; // 'not_shipped'; 

    // return status  
    const RETURNED_STATUS     = true;   //'is returned';
    const NOT_RETURNED_STATUS = false;  // 'not_returned'; 

    // preparing status  
    const ACCEPTED_STATUS     = true;   //'accepted';
    const NOT_ACCEPTED_STATUS = false;  // 'not_accepted'; 

    // Payment Methods
    const PAYPAL_PAYMENT_METHOD   = 'paypal';
    const TAP_PAYMENT_METHOD      = 'tap';
    const PAY_TAPS_PAYMENT_METHOD = 'pay_tabs';
    const WALLET_PAYMENT_METHOD   = 'wallet';

    const CASH_PAYMENT_METHOD     = 'cash';
    // const OTHER_PAYMENT_METHOD    = 'other';
    const VISA_PAYMENT_METHOD     = 'visa';

    const PAYMENT_METHODS = [
        self::CASH_PAYMENT_METHOD,
        self::VISA_PAYMENT_METHOD
    ];

    // Platform
    const WEB_PLATFORM = 'web';
    const ANDROID_PLATFORM = 'android';
    const IOS_PLATFORM = 'ios';
    const PLATFORMS = [
        self::WEB_PLATFORM,
        self::ANDROID_PLATFORM,
        self::IOS_PLATFORM,
    ];

    protected $casts = [
        'is_paid'                        => 'boolean',
        'is_cancelled'                   => 'boolean',
        'is_approved'                    => 'boolean',
        'in_shipping'                    => 'boolean',
        'is_delivered'                   => 'boolean',
        'is_accepted'                    => 'boolean',
        'marked_as_deleted_for_driver'   => 'boolean',
        'marked_as_deleted_for_customer' => 'boolean',
        'marked_as_deleted_for_provider' => 'boolean'
    ];

    protected $fillable = [
        'total',
        'total_before_fees',
        'full_name',
        'phone',
        'email',
        'area',
        'country',
        'country_id',
        'city',
        'city_id',
        'postal_code',
        'block_number',
        'floor_number',
        'flat_number',
        'address',
        'longitude',
        'latitude',
        'date',
        'time',
        'comment',
        'promo_code',
        'invoice_id',
        'is_paid',
        'is_returned',
        'is_cancelled',
        'is_approved',
        'in_shipping',
        'is_delivered',
        'payment_method',
        'address_id',
        'data_country_id',
        'customer_id',
        'driver_id',
        'provider_id',
        'payment_id',
        'shipping_fee',
        'tax_fee',
        'accept_time',
        'commission_percentage',
        'is_accepted',
        'marked_as_deleted_for_driver',
        'marked_as_deleted_for_provider',
        'marked_as_deleted_for_customer',
    ];

    protected $guarded = [
        'refund_id',
        'platform',
    ];

    /**
     * The attributes that should be appended.
     *
     * @var array
     */
    protected $with = [];
    /**
     * The attributes that should be appended.
     *
     * @var array
     */
    protected $appends =  [
        'order_status',
        'order_total_and_Fees'
    ];

    protected static function boot()
    {
        parent::boot();

        // When Deleting Product
        static::deleting(function (Order $order) {
            if ($order->isForceDeleting()) {

                // Clear Product in order Relation
                $order->products()->delete();

                $order->requests()->delete();

                $order->save();
            }
        });
    }
    public function path()
    {
        return route('dashboard.orders.show', $this);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(ProductOrder::class, 'order_id')->withoutGlobalScopes();
    }

    public function real_products()
    {
        return $this->belongsToMany(Product::class, 'product_orders', 'order_id', 'product_id')
            ->using(ProductOrder::class)
            ->withoutGlobalScopes();
    }

    public function report()
    {
        return $this->belongsTo(OrderReport::class, 'order_id')->withoutGlobalScopes();
    }

    public function requests()
    {
        return $this->hasMany(OrderRequest::class, 'order_id')->withoutGlobalScopes();
    }


    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id')->withoutGlobalScopes();
    }
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id')->withoutGlobalScopes();
    }

    public function creator()
    {
        return $this->products()->first()->product->creator;
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id')->withoutGlobalScopes();
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id')->withoutGlobalScopes();
    }

    public function canceledRequests()
    {
        return $this->hasOne(CanceledOrder::class, 'order_id')->withoutGlobalScopes();
    }

    public function paymentMethodIsCash()
    {
        return $this->payment_method === static::CASH_PAYMENT_METHOD;
    }

    public function isPaid()
    {
        return $this->is_paid === static::PAID_STATUS;
    }

    public function isNotPaid()
    {
        return $this->is_paid === static::NOT_PAID_STATUS;
    }

    public function markOrderAsPaidByPaymentMethod()
    {

        if ($this->payment_method == static::WALLET_PAYMENT_METHOD) {
            $this->markAsPaid();
        }

        return $this;
    }

    public function scopeByCountry(Builder $builder)
    {
        return $builder->where('data_country_id', countryId());
    }

    public function markAsPaid()
    {
        $this->update(['is_paid' => static::PAID_STATUS]);

        return $this;
    }

    public function markAsNotPaid()
    {
        $this->update(['is_paid' => static::NOT_PAID_STATUS]);

        return $this;
    }

    public function scopePaid(Builder $builder)
    {
        return $builder->where('is_paid', static::PAID_STATUS);
    }

    public function isDelivered()
    {
        return $this->is_delivered === static::DELIVERED_STATUS;
    }

    public function isNotDelivered()
    {
        return $this->is_delivered === static::NOT_DELIVERED_STATUS;
    }

    public function markAsDelivered()
    {
        $this->update(['is_delivered' => static::DELIVERED_STATUS]);

        return $this;
    }

    public function markAsNotDelivered()
    {
        $this->update(['is_delivered' => static::NOT_DELIVERED_STATUS]);

        return $this;
    }

    public function scopeDelivered(Builder $builder)
    {
        return $builder->where('is_delivered', static::DELIVERED_STATUS);
    }

    public function scopeNotDelivered(Builder $builder)
    {
        return $builder->where('is_delivered', static::NOT_DELIVERED_STATUS);
    }

    public function isCancelled()
    {
        return $this->is_cancelled === static::CANCELLED_STATUS;
    }

    public function isNotCancelled()
    {
        return $this->is_cancelled === static::NOT_CANCELLED_STATUS;
    }

    public function markAsCancelled()
    {
        $this->update(['is_cancelled' => static::CANCELLED_STATUS]);

        return $this;
    }

    public function requestIsCancelled()
    {
        return $this->canceledRequests()->where('driver_id', auth()->id())->first();
    }


    public function markAsNotCancelled()
    {
        $this->update(['is_cancelled' => static::NOT_CANCELLED_STATUS]);

        return $this;
    }

    public function scopeCancelled(Builder $builder)
    {
        return $builder->where('is_cancelled', static::CANCELLED_STATUS);
    }

    public function scopeNotCancelled(Builder $builder)
    {
        return $builder->where('is_cancelled', static::NOT_CANCELLED_STATUS);
    }



    public function isApproved()
    {
        return $this->is_approved === static::APPROVED_STATUS;
    }

    public function isNotApproved()
    {
        return $this->is_approved === static::NOT_APPROVED_STATUS;
    }

    public function markAsApproved()
    {

        $this->update(['is_approved' => static::APPROVED_STATUS]);

        return $this;
    }

    public function markAsNotApproved()
    {
        $this->update(['is_approved' => static::NOT_APPROVED_STATUS]);

        return $this;
    }

    public function scopeApproved(Builder $builder)
    {
        return $builder->where('is_approved', static::APPROVED_STATUS);
    }

    public function scopeNotApproved(Builder $builder)
    {
        return $builder->where('is_approved', static::NOT_APPROVED_STATUS);
    }



    public function isShipped()
    {
        return $this->in_shipping === static::SHIPPED_STATUS;
    }

    public function isNotShipped()
    {
        return $this->in_shipping === static::NOT_SHIPPED_STATUS;
    }

    public function isAccepted()
    {
        return $this->is_accepted === static::ACCEPTED_STATUS;
    }

    public function isNotAccepted()
    {
        return $this->is_accepted === static::NOT_ACCEPTED_STATUS;
    }

    public function markAsShipped()
    {
        $this->update(['in_shipping' => static::SHIPPED_STATUS]);

        return $this;
    }

    public function markAsNotShipped()
    {
        $this->update(['in_shipping' => static::NOT_SHIPPED_STATUS]);

        return $this;
    }

    public function scopeShipped(Builder $builder)
    {
        return $builder->where('in_shipping', static::SHIPPED_STATUS);
    }


    public function scopeNotShipped(Builder $builder)
    {
        return $builder->where('in_shipping', static::NOT_SHIPPED_STATUS);
    }


    public function markAsNotAccepted()
    {
        $this->update([
            'is_accepted' => static::NOT_ACCEPTED_STATUS,
            'driver_id' => null,
            'accept_time' => null
        ]);

        return $this;
    }

    public function markAsDeleted()
    {
        if (auth()->user()->isCustomer()) {

            $this->update(['marked_as_deleted_for_customer' => true]);
        } elseif (auth()->user()->isDriver()) {

            $this->update(['marked_as_deleted_for_driver' => true]);
        } else {

            $this->update(['marked_as_deleted_for_provider' => true]);
        }

        return $this;
    }

    public function scopeNotDeleted(Builder $builder)
    {

        if (auth()->user()->isCustomer()) {

            $builder->where('marked_as_deleted_for_customer', false);
        } elseif (auth()->user()->isDriver()) {

            $builder->where('marked_as_deleted_for_driver', false);
        } else {

            $builder->where('marked_as_deleted_for_provider', false);
        }

        return $builder;
    }

    public function scopeNotAccepted(Builder $builder)
    {
        return $builder->where('is_accepted', static::NOT_ACCEPTED_STATUS);
    }

    public function markAsAccepted()
    {
        $this->update([
            'is_accepted' => static::ACCEPTED_STATUS,
            'driver_id' => auth()->id(),
            'accept_time' => Carbon::now()->toDateTimeString()
        ]);

        $this->load('driver');

        return $this;
    }

    /**
     *  Prepare product in cart 
     *
     * @param string $payment_id
     * @return \MixCode\Order
     */

    public function orderFormats($carts)
    {
        $response = [];

        foreach ($carts as $cart) {

            $product = Product::find($cart->product_id);

            if ($product && $product->isPublished()) {


                $response[$product->id][] = [
                    'orderProducts' => [
                        'product_id'   => $cart->product_id,
                        'quantity'     => $cart->quantity,
                        'price'        => $cart->price,
                        'final_price'  => $cart->total_price_before_additions,
                        'creator_id'   => $product->creator_id,
                    ],
                    'orderProductAdditions'    => $cart->cartAdditions->toArray(),
                    'cart_product_item_status' => true

                ];
            }
        }

        return $response;
    }

    /**
     *  Create New Order  
     *
     * @param string $payment_id
     * @return \MixCode\Order
     */
    public function  createOrder($response, $request, $summery)
    {
        $data     = $request->all();
        $address  = Address::find($request->address_id);
        $auth     = auth();
        $customer = $auth->user();

        if (request()->wantsJson())   $auth = auth('api');

        if (request()->ajax())        $auth = auth();

        if (count($response) < 0) return  false;

        if (!$address) return false;

        $data['customer_id']           = $customer->id;
        $data['invoice_id']            = 'INV-' . substr(md5(time() . Str::random(10)), 0, 7);
        $data['shipping_fee']          = $request->shipping_fee;    //$summery['shippingFee'];
        $data['commission_percentage'] = getSettings()->commission_percentage ?? 0;
        $data['total']                 = $summery['final_total'];
        $data['total_before_fees']     = $summery['sub_total'];
        $data['full_name']             = $request->full_name ?? $customer->full_name;
        $data['phone']                 = $request->phone ?? $customer->phone;
        $data['email']                 = $request->email ?? $customer->email;
        $data['address']               = $address->address ?? null;
        $data['address_description']   = $address->address_description ?? null;
        $data['city']                  = $address->city ?? null;
        $data['area']                  = $address->area ?? null;
        $data['longitude']             = $address->longitude ?? null;
        $data['latitude']              = $address->latitude ?? null;
        $data['address_id']            = $request->address_id;
        $data['data_country_id']       = auth()->user()->country_id;

        $order = Order::create($data);

        foreach ($response as  $orderProducts) {

            foreach ($orderProducts as $orderProduct) {

                if (!$orderProduct['cart_product_item_status']) return   false;
            }

            $x = 0;
            foreach ($orderProducts as  $orderProduct) {

                $productOrderItem = $order->products()->create($orderProduct['orderProducts']);

                if ($x == 0) {
                    $order->update(['provider_id' => $orderProduct['orderProducts']['creator_id']]);
                }

                foreach ($orderProduct['orderProductAdditions'] as $addition) {

                    $data = [
                        'addition_id' => $addition['addition_id'],
                        'price'       => $addition['price'],
                        'quantity'    => $addition['quantity'],
                        'final_price' => $addition['total_price']
                    ];
                    $productOrderItem->additions()->create($data);
                }

                $x++;
            }
        }


        $order->makeReport();

        return $order;
    }


    public function makeReport()
    {
        $productCreators = [];
        $prices          = [];
        $commission      = getSettings()->commission_percentage;

        $products = ProductOrder::where('order_id', $this->id)->get();

        foreach ($products as $product) {

            $productCreators[$product->product->creator_id][] = $product->final_price;
        }


        foreach ($productCreators as $ke => $productCreator) {

            $prices[$ke]  =   array_sum($productCreator);
        }

        foreach ($prices as $key => $price) {

            if ($commission > 0) {

                $total_after_commission = $price - ($price * $commission / 100);
            } else {
                $total_after_commission = $price;
            }

            $data = [
                'commission_percentage'   => $commission,
                'total_before_commission' => $price,
                'total_after_commission'  => $total_after_commission,
                'company_id'              => $key,
                'order_id'                => $this->id,
            ];

            OrderReport::create($data);
        }

        return $this;
    }



    /**
     * SGet user Cart data from (session Or Database)
     *
     * @param string $payment_id
     * @return \MixCode\Order
     */
    public function getUserCartData()
    {

        $carts                      = [];


        if (auth()->check()) {

            foreach (auth()->user()->carts as  $cart) {

                $productVariation = ProductVariation::find($cart->variation_id);
                $store            = User::find($cart->store_id);

                $carts[] = [
                    "id"                   => $cart->id,
                    "quantity"             => $cart->quantity,
                    "slug_by_lang"         => $productVariation->product->slug_by_lang,
                    "name_by_lang"         => $productVariation->product->name_by_lang,
                    "store_name"           => $store->username,
                    "store_shipping_fee"   => $store->shipping_fee,
                    "store_tax_fee_status" => $store->tax_fee_status,
                    "main_media_url"       => $productVariation->product->main_media_url,
                    "price"                => $cart->price,
                    "total_price"          => $cart->price * $cart->quantity,
                    "total"                => $cart->price * $cart->quantity

                ];
            }
        } else {

            $cartProductsInSession = session()->get('products_in_cart');

            if (!empty($cartProductsInSession)) {
                foreach ($cartProductsInSession as  $productInCart) {

                    $productVariation = ProductVariation::find($productInCart['variation_id']);
                    $store            = User::find($productInCart['store_id']);


                    $carts[] = [
                        "id"                   => $productInCart['product_id'],
                        "quantity"             => $productInCart['quantity'],
                        "slug_by_lang"         => $productVariation->product->slug_by_lang,
                        "name_by_lang"         => $productVariation->product->name_by_lang,
                        "store_name"           => $store->username,
                        "store_shipping_fee"   => $store->shipping_fee,
                        "store_tax_fee_status" => $store->tax_fee_status,
                        "main_media_url"       => $productVariation->product->main_media_url,
                        "price"                => $productInCart['product_price'],
                        "total_price"          => $productInCart['product_price'] * $productInCart['quantity'],
                        "total"                => $productInCart['product_price'] * $productInCart['quantity']
                    ];
                }
            }
        }

        return  collect($carts);
    }


    /**
     * get Order data total with fees 
     *
     * @param string $payment_id
     * @return \MixCode\Order
     */
    public function getOrderTotalAndFeesAttribute()
    {

        $shippingFee     = $this->shipping_fee;
        $total_sub_order = $this->products()->sum('final_price');
        $total_additions = $this->products->map(function ($product) {

            return $product->additions()->sum('final_price');
        })->sum();

        $sub_Total   = $total_sub_order +   $total_additions;
        $final_total = $sub_Total +   $shippingFee;

        return [
            'sub_total_before_additions' => $total_sub_order,
            'total_additions'            => $total_additions,
            'sub_total_after_additions'  => $sub_Total,
            'shippingFee'                => intval($shippingFee),
            'final_total'                => $final_total
        ];
    }

    /**
     * Save Payment Id From Payment provider
     *
     * @param string $payment_id
     * @return \MixCode\Order
     */
    public function setPaymentId(string $payment_id)
    {
        $this->payment_id = $payment_id;
        $this->save();

        return $this;
    }

    /**
     * Clear Payment Id
     *
     * @return \MixCode\Order
     */
    public function clearPaymentId()
    {
        $this->payment_id = null;
        $this->save();

        return $this;
    }

    /**
     * Save Refund Id From Refund provider
     *
     * @param string $refund_id
     * @return \MixCode\Order
     */
    public function setRefundId(string $refund_id)
    {
        $this->refund_id = $refund_id;
        $this->save();

        return $this;
    }

    /**
     * Clear Refund Id
     *
     * @return \MixCode\Order
     */
    public function clearRefundId()
    {
        $this->refund_id = null;
        $this->save();

        return $this;
    }

    /**
     * Set Order Total Price
     *
     * @param float $total
     * @return \MixCode\Order
     */
    public function setTotal($total)
    {
        $shippingFee = getSettings()->original_shipping_fee;
        $taxFee      = getSettings()->tax_fee;

        // Check if promo code exists to update total with it
        if (!!$this->promo_code) {
            $total   = $total - (($total * $this->promo_code) / 100);
            $tax_Fee = ($total * $taxFee) / 100;
        }

        $tax_Fee     = ($total * $taxFee) / 100;

        $this->total = $total +  $shippingFee +  $tax_Fee;
        $this->save();
    }

    /**
     * Register Price Fields to Generate Price Ratio
     *
     * @return array
     */
    protected function priceFields()
    {
        return [
            'product_price',
            'total',
        ];
    }

    /*   public function getTotalBeforePromoCodeAttribute()
    {
        return  $this->products()->sum('final_price');
    } */


    public function getOrderStatusAttribute()
    {
        $status = '';
        if ($this->isCancelled()) {
            $status = 'order_is_cancelled';
        } elseif ($this->isDelivered()) {
            $status = 'order_is_delivered';
        } elseif ($this->isShipped()) {
            $status = 'order_in_shipping';
        } elseif ($this->isApproved()) {
            $status = 'order_is_approved';
        } else {
            $status = 'order_not_approved_yet';
        }

        return $status;
    }


    public function getDriversByByLocation()
    {
        $lat        = $this->longitude;
        $lng        = $this->lng;
        /* 
   
        $getProviders =  DB::select("
		SELECT * FROM ( SELECT *,  ( ( ( acos(
								sin(( '$lat' * pi() / 180))
								*
								sin(( `lat` * pi() / 180)) + cos(( '$lat' * pi() /180 ))
								*
								cos(( `lat` * pi() / 180)) * cos((( '$lng' - `lng`) * pi()/180)))
						) * 180/pi()
					) * 60 * 1.1515 * 1.609344
				  )
			as distance FROM `users`
		) users
		WHERE distance <  15
		and `work_fields` like '%$service_id%'
		"); */

        //  method one to get providers by location
        /*   $providers_method_1 = collect($getProviders)->map(function ($provider) {
            return (array)$provider;
        })->map(function ($provider) {
            if (isset($provider['password'])) {
                unset($provider['password']);
            }
            return $provider;
        }); */

        //  method two to get providers by location
        /*         $providers_method_2 = User::where('work_fields', 'like', '%' . $service_id . '%')
            ->select(DB::raw("*, ( 6371 * acos( cos( radians($lat) ) *
        cos( radians( lat ) ) * cos( radians( lng ) - radians($lng) ) + sin( radians($lat) ) * 
        sin( radians( lat ) ) ) ) AS distance "))
            ->havingRaw("distance < 15")->get(); */
        //  method two to get providers 
        //$providers_method_2 = User::where('work_fields', 'like', '%' . $service_id . '%')->get();

        $area    = $this->provider->area;
        $drivers = User::query()
            ->where('area', 'like', '%' . $area . '%')
            ->typeDriver()
            ->byCountry()
            ->isAvailable()
            ->finishedTrips()
            ->get();

        return $drivers;
    }



    public function notifyDriversForNewOrder()
    {

        $drivers =  $this->getDriversByByLocation();

        try {

            if ($drivers) {

                foreach ($drivers as $driver) {
                    OrderRequest::create([
                        'order_id'  => $this->id,
                        'driver_id' => $driver->id,
                    ]);
                }
            }

            Notification::send($drivers, new NewOrderHasBeenCreatedForDrivers($this));
            NotificationFactory::notifyByPushNotification(
                $drivers,
                trans('notifications.you_have_new_request'),
                trans('notifications.you_have_new_request_message'),
                null,
                [
                    'for'         => 'order',
                    'action'      => 'you_have_new_request',
                    'item_id'     => $this->id,
                    'description' => $this->description,
                    'address'     => $this->address,
                ]
            );
        } catch (\Exception $e) {
            \Log::channel('notification_errors')->error(get_class($this) . " -> " . __FUNCTION__ . " -> " . get_class($e) . " -> " . $e->getMessage());
        }

        return $this;
    }


    public function notifyUsersForNewOrder()
    {
        $this->notifyAdminsAndProvidersForNewOrder();
        $this->notifyDriversForNewOrder();
    }

    public function notifyProviderForOrderAccepted()
    {
        if (!!$this->provider) {

            $this->provider->notify(new OrderHasBeenAcceptedForProvider($this));

            NotificationFactory::notifyByPushNotification(
                $this->provider->firebase_cloud_messaging_token,
                trans('notifications.your_request_has_been_accepted'),
                trans('notifications.your_request_has_been_accepted_message'),
                null,
                [
                    'for'         => 'order',
                    'action'      => 'your_request_has_been_accepted',
                    'item_id'     => $this->id,
                    'description' => $this->description,
                    'address'     => $this->address,
                ]
            );
        }

        return $this;
    }


    public function notifyAdminsAndProvidersForNewOrder()
    {

        try {
            $this->notifyCreatorForNewOrder();

            //  $notifiers = User::typeAdmin()->get();

            // Notification::send($notifiers, new NewOrderHasBeenCreated($this));
        } catch (\Exception $e) {
            \Log::channel('notification_errors')->error(get_class($this) . " -> " . __FUNCTION__ . " -> " . get_class($e) . " -> " . $e->getMessage());
        }

        return $this;
    }


    public function notifyCreatorForNewOrder()
    {

        $creator = $this->creator();

        if (!!$creator) {

            Notification::send($creator, new NewOrderHasBeenCreated($this));

            NotificationFactory::notifyByPushNotification(
                $creator->firebase_cloud_messaging_token,
                trans('notifications.new_order'),
                trans('notifications.new_order_message'),
                null,
                [
                    'for'     => 'order',
                    'action'  => 'new_order_created',
                    'item_id' => $this->id
                ]
            );
        }


        return $this;
    }
    public function notifyCustomerForNewOrder()
    {

        if (!!$this->customer) {

            Notification::send($this->customer, new NewOrderHasBeenCreatedForCustomer($this));

            NotificationFactory::notifyByPushNotification(
                $this->customer->firebase_cloud_messaging_token,
                trans('notifications.new_order'),
                trans('notifications.new_order_message'),
                null,
                [
                    'for'     => 'order',
                    'action'  => 'new_order_created',
                    'item_id' => $this->id
                ]
            );
        }


        return $this;
    }



    public function notifyCustomerOrderApproved()
    {

        if (!!$this->customer) {

            Notification::send($this->customer, new OrderApproved($this));

            NotificationFactory::notifyByPushNotification(
                $this->customer->firebase_cloud_messaging_token,
                trans('notifications.order_status'),
                trans('notifications.order_has_been_approved'),
                null,
                [
                    'for'     => 'order',
                    'action'  => 'order_has_been_approved',
                    'item_id' => $this->id
                ]
            );
        }


        return $this;
    }
    public function notifyCustomerOrderNotApproved()
    {

        if (!!$this->customer) {

            Notification::send($this->customer, new OrderNotApproved($this));

            NotificationFactory::notifyByPushNotification(
                $this->customer->firebase_cloud_messaging_token,
                trans('notifications.order_status'),
                trans('notifications.order_has_been_disapproved'),
                null,
                [
                    'for'     => 'order',
                    'action'  => 'order_has_been_disapproved',
                    'item_id' => $this->id
                ]
            );
        }


        return $this;
    }

    public function notifyCustomerOrderIsCancelled()
    {

        if (!!$this->customer) {

            Notification::send($this->customer, new ProductOrderCancelled($this));

            NotificationFactory::notifyByPushNotification(
                $this->customer->firebase_cloud_messaging_token,
                trans('notifications.order_status'),
                trans('notifications.product_order_canceled'),
                null,
                [
                    'for'     => 'order',
                    'action'  => 'product_order_canceled',
                    'item_id' => $this->id
                ]
            );
        }


        return $this;
    }


    /**
     *  Notify admin product order has been cancelled
     *
     */

    public function notifyAdminProductOrderCancelled()
    {

        $notifiers = User::typeAdmin()->get();
        $notification = new ProductOrderCancelled($this);

        Notification::send($notifiers, $notification);

        try {
            Notification::send($notifiers, $notification);
        } catch (\Exception $e) {
            \Log::channel('notification_errors')->error(get_class($this) . " -> " . __FUNCTION__ . " -> " . get_class($e) . " -> " . $e->getMessage());
        }


        return $this;
    }

    public function notifyAdminsForCancelOrder()
    {

        $notifiers = User::typeAdmin()->get();
        $notification = new OrderIsCanceled($this);

        Notification::send($notifiers, $notification);

        try {
            Notification::send($notifiers, $notification);
        } catch (\Exception $e) {
            \Log::channel('notification_errors')->error(get_class($this) . " -> " . __FUNCTION__ . " -> " . get_class($e) . " -> " . $e->getMessage());
        }


        return $this;
    }


    public function notifyCreatorForOrderPaid()
    {

        $notifiers = User::typeAdmin()->get();
        $notification =  new OrderHasBeenPaid($this);

        Notification::send($notifiers, $notification);

        try {
            Notification::send($notifiers, $notification);
        } catch (\Exception $e) {
            \Log::channel('notification_errors')->error(get_class($this) . " -> " . __FUNCTION__ . " -> " . get_class($e) . " -> " . $e->getMessage());
        }

        return $this;
    }

    public function notifyCustomerForOrderPaid()
    {
        if (!!$this->customer) {
            $this->customer->notify(new OrderHasBeenPaidForCustomer($this));
        }

        return $this;
    }



    public function notifyCustomerOrderActivated()
    {
        if (!!$this->customer) {
            $this->customer->notify(new OrderActivated($this));
            NotificationFactory::notifyByPushNotification(
                $this->customer->firebase_cloud_messaging_token,
                trans('notifications.order_activated_successfully'),
                trans('notifications.order_activated_successfully'),
                null,
                [
                    'for'     => 'order',
                    'action'  => 'order_activated',
                    'item_id' => $this->id
                ]
            );
        }

        return $this;
    }


    public function notifyCustomerOrderInShipping()
    {
        if (!!$this->customer) {
            $this->customer->notify(new OrderInShipping($this));

            NotificationFactory::notifyByPushNotification(
                $this->customer->firebase_cloud_messaging_token,
                trans('notifications.order_in_shipping'),
                trans('notifications.order_in_shipping'),
                null,
                [
                    'for'     => 'order',
                    'action'  => 'order_in_shipping',
                    'item_id' => $this->id
                ]
            );
        }

        return $this;
    }

    public function notifyAdminOrderInShipping()
    {
        Notification::send(User::typeAdmin()->get(), new OrderInShippingForAdmin($this));
        return $this;
    }

    public function notifyCustomerOrderIsDelivered()
    {
        if (!!$this->customer) {
            $this->customer->notify(new OrderIsDelivered($this));
            NotificationFactory::notifyByPushNotification(
                $this->customer->firebase_cloud_messaging_token,
                trans('notifications.order_is_delivered'),
                trans('notifications.order_is_delivered'),
                null,
                [
                    'for'     => 'order',
                    'action'  => 'order_is_delivered',
                    'item_id' => $this->id
                ]
            );
        }

        return $this;
    }


    public function notifyAdminOrderIsDelivered()
    {
        Notification::send(User::typeAdmin()->get(), new OrderIsDeliveredForAdmin($this));

        return $this;
    }


    public function notifyDriverForNewOrder()
    {
        if (!!$this->driver) {
            $this->driver->notify(new NewOrderHasBeenAssignedToDriver($this));
        }

        return $this;
    }
}

<?php

namespace MixCode\Http\Controllers\Api\Auth;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use MixCode\Order;
use MixCode\Http\Controllers\Controller;
use MixCode\Http\Requests\ProfileRequest;
use MixCode\OrderRequest;
use MixCode\Product;
use MixCode\User;
use MoaAlaa\ApiResponder\ApiResponder;

class ProfileController extends Controller
{
    use ApiResponder;

    /**
     * Get User Profile
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        return $this->api()->response(auth()->user());
    }

    /**
     * Update User Profile
     *
     * @param \MixCode\Http\Requests\ProfileRequest $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(ProfileRequest $request)
    {

        $data                = $request->validated();
        $data['active_country_id'] = $request->country_id;
        auth()->user()->update($data);

        return $this->api()->response(auth()->user());
    }



    /**
     * Delete User account.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteAccount()
    {
        auth()->user()->delete();

        return $this->api()->response([], trans('site.account_deleted_successfully'), Response::HTTP_OK);
    }

    /**
     * Update User (Provider location [lat - lng])
     *
     * @param \MixCode\Http\Requests\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateLocation(Request $request)
    {

        auth()->user()->update($request->all());



        return $this->api()->response(auth()->user());
    }


    /**
     * Update User online status
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateAvailabilityStatus(Request $request)
    {
        $request->validate([
            'is_availability'  => ['required', 'boolean'],
        ]);

        abort_unless(auth()->user()->isDriver(), Response::HTTP_UNAUTHORIZED, trans('main.you_are_not_authorized_for_this_action_for_divers_type'));

        auth()->user()->update($request->all());

        return $this->api()->response(auth()->user(), trans('main.updated'), Response::HTTP_OK);
    }


    /**
     * Update User Logo
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateLogo(Request $request)
    {
        $request->validate([
            'logo'  => ['required', 'image', 'mimes:jpg,jpeg,png'],
        ]);

        auth()->user()->syncMedia($request->all());

        return $this->api()->response(auth()->user());
    }

    /**
     * Update   Commercial License
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateCommercialLicense(Request $request)
    {
        $request->validate([
            'commercial_license' => ['required', 'image', 'mimes:jpg,jpeg,png,pdf'],
        ]);

        auth()->user()->syncMedia($request->all());

        return $this->api()->response(auth()->user());
    }
    /**
     * Update    Vehicle Photos  
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateVehiclePhotos(Request $request)
    {
        $request->validate([
            'vehicle_photos'   => ['required', 'array', 'min:1'],
            'vehicle_photos.*' => ['required', 'image', 'mimes:jpeg,jpg,png'],
        ]);


        if ($request->has('vehicle_photos')) {

            auth()->user()->media()->delete();
            auth()->user()->uploadMultiMediaFromRequest('vehicle_photos');
        }


        return $this->api()->response(auth()->user());
    }

    /**
     * Update User Token
     *
     * @param \MixCode\Http\Requests\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateFirebaseCloudMessagingToken(Request $request)
    {

        if ($request->has('user_token')) {
            auth()->user()->update(['firebase_cloud_messaging_token' => $request->user_token]);
        }

        $data = [
            'user'                       => auth()->user(),
            /* 'item_in_cart_count'         => auth()->user()->carts()->count(), */
            "items_in_favorite_count"    => auth()->user()->favorites()->count(),
            "unread_notifications_count" => auth()->user()->unreadNotifications()->count(),

        ];

        return $this->api()->response($data);
    }

    /**
     * Update User Profile
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $password = ['password' => Hash::make($request->password)];

        auth()->user()->update($password);

        return $this->api()->response(auth()->user());
    }


    /**
     * Update User Profile
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request)
    {

        $request->validate([
            'password' => ['required', 'string'],
            'email' =>    ['required', 'email'],
        ]);

        $password = ['password' => Hash::make($request->password)];

        $user =  User::where('email', $request->email)->update($password);
        //   auth()->user()->update($password);
        if ($user) {
            return $this->api()->response([], 'password updated successfully', Response::HTTP_OK);
        } else {
            return $this->api()->error('Email is Not found', Response::HTTP_NOT_FOUND);
        }
    }


    /**
     * List all user delivered orders for customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function customerDeliveredOrders()
    {

        $order = Order::where('is_delivered', 1)
            ->notCancelled()
            ->where('customer_id', auth()->id())
            ->notDeleted()
            ->latest()
            ->paginate(20);

        return $this->api()->response($order);
    }


    /**
     * List all user active orders for customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function customerActiveOrders()
    {

        $order = Order::query()
            ->where('is_delivered', 0)
            ->notCancelled()
            ->where('customer_id', auth()->id())
            ->notDeleted()
            ->latest()
            ->paginate(20);

        $order->load('products');

        return $this->api()->response($order);
    }

    /**
     * List all user approved orders for customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function customerApprovedOrders()
    {

        $order = Order::query()
            ->where('is_delivered', 0)
            ->where('is_approved', 1)
            ->notCancelled()
            ->where('customer_id', auth()->id())
            ->notDeleted()
            ->latest()
            ->paginate(20);

        $order->load('products');

        return $this->api()->response($order);
    }

    /**
     * List all user approved orders for customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function customerCanceledOrders()
    {

        $order = Order::query()
            ->where('is_cancelled', 1)
            ->where('customer_id', auth()->id())
            ->notDeleted()
            ->latest()
            ->paginate(20);

        $order->load('products');

        return $this->api()->response($order);
    }




    /**
     * List all user delivered orders for driver.
     *
     * @return \Illuminate\Http\Response
     */
    public function driverDeliveredOrders(Request $request)
    {

        $orders_data = Order::with('products')
            ->where('is_delivered', 1)
            ->where('driver_id', auth()->id())
            ->notCancelled()
            ->notDeleted()
            ->when($request->has('date') && $request->filled('date'), function ($q) use ($request) {
                $q->whereDate('created_at', $request->date);
            })
            ->latest();

        $orders       = $orders_data->paginate(20);
        $total_profit = $orders_data->sum('total');

        $data = [
            'orders'       => $orders,
            'total_profit' => $total_profit
        ];


        return $this->api()->response($data);
    }


    /**
     * List all user active orders for driver.
     *
     * @return \Illuminate\Http\Response
     */
    public function driverActiveOrders()
    {

        $order = Order::where('is_delivered', 0)
            ->notCancelled()
            ->where('driver_id', auth()->id())
            ->notDeleted()
            ->latest()
            ->paginate(20);

        $order->load('products');
        return $this->api()->response($order);
    }


    /**
     * List all user  orders requests for driver.
     *
     * @return \Illuminate\Http\Response
     */
    public function driverOrdersRequests()
    {

        /*  $order = OrderRequest::where('driver_id', auth()->id())
        ->with('order')
        ->NotAccepted()
        ->latest()
        ->paginate(20); */

        $order = Order::with('products')->whereHas('requests', function ($q) {
            $q->where('driver_id', auth()->id())
                ->NotAccepted();
        })
            ->notCancelled()
            ->NotAccepted()
            ->notDeleted()
            ->latest()
            ->paginate(20);

        return $this->api()->response($order);
    }




    /**
     * List all user Accomplished orders for driver.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeAccomplishedOrders()
    {

        $order = Order::approved()
            ->notCancelled()
            ->with(['products', 'customer'])
            ->where('provider_id', auth()->id())
            ->notDeleted()
            ->latest()
            ->paginate(20);

        return $this->api()->response($order);
    }


    /**
     * List all user Running orders for driver.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeRunningOrders()
    {

        $order = Order::notApproved()
            ->with('products')
            ->notCancelled()
            ->where('provider_id', auth()->id())
            ->notDeleted()
            ->latest()
            ->paginate(20);

        return $this->api()->response($order);
    }

    /**
     * List all user Delivered orders for driver.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeDeliveredOrders(Request $request)
    {
        $orders_data = Order::Delivered()
            ->with('products')
            ->where('provider_id', auth()->id())
            ->notDeleted()
            ->when($request->has('date') && $request->filled('date'), function ($q) use ($request) {
                $q->whereDate('created_at', $request->date);
            })
            ->latest();

        $orders       = $orders_data->paginate(20);
        $total_profit = $orders_data->sum('total');

        $data = [
            'orders'       => $orders,
            'total_profit' => $total_profit
        ];

        return $this->api()->response($data);
    }


    /**
     * List all canceled orders for store
     *
     * @return \Illuminate\Http\Response
     */
    public function storeCanceledOrders()
    {

        $order = Order::query()
            ->where('is_cancelled', 1)
            ->where('provider_id', auth()->id())
            ->notDeleted()
            ->latest()
            ->paginate(20);

        $order->load('products');

        return $this->api()->response($order);
    }


    /**
     * List all user history products.
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function history(Request $request)
    {
        $relations = ($request->has('with') && $request->filled('with'));
        $crossRelations = ($request->has('cross_with') && $request->filled('cross_with'));

        $orders = Order::where('customer_id', auth()->id());

        if ($relations) {
            $orders->with(explode(',', $request->with));
        }

        if ($crossRelations) {
            $orders->with(['products' => function ($builder) use ($request) {
                return $builder->with(explode(',', $request->cross_with));
            }]);
        } else {
            $orders->with('products');
        }

        return $this->api()->response($orders->latest()->paginate(20));
    }
}

<?php

namespace MixCode\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use MixCode\Address;
use MixCode\Order;
use MixCode\Category;
use MixCode\Http\Requests\AddressRequest;
use MixCode\Http\Requests\ProfileRequest;
use MixCode\Service;
use MixCode\ServiceOrder;
use MixCode\Product;
use MixCode\ProductView;
use MixCode\User;
use MixCode\Utils\UsingSEO;
use Str;

class ProfileController extends Controller
{
    use UsingSEO;

    public function providerProducts()
    {
        tap(auth()->user()->full_name . ' ' . trans('site.profile'), function ($seoTitle) {
            $this->seo()->generate(['title' => $seoTitle, 'description' => $seoTitle]);
        });

        $products = Product::where('creator_id', auth()->id())->latest()->paginate(6);

        $products->load(['media', 'creator',  'classifications' ]);


        return view('profile.provider_products', compact('products'));
    }


    public function providerPublicProfileForProducts(User $user)
    {
        tap($user->full_name . ' - ' . trans('main.products'), function ($seoTitle) {
            $this->seo()->generate(['title' => $seoTitle, 'description' => $seoTitle]);
        });

        $products = Product::published()->where('creator_id', $user->id)->latest()->paginate(6);

        $products->load(['media', 'creator',  'categories', 'classifications' ]);

        return view('profile.providers_public_profile_for_products', compact('products', 'user'));
    }



    public function myProductsOrders()
    {
        tap(auth()->user()->full_name . ' ' . trans('site.orders_history'), function ($seoTitle) {
            $this->seo()->generate(['title' => $seoTitle, 'description' => $seoTitle]);
        });

        $orders = Order::where('customer_id', auth()->id())
            ->with('products', 'address')
            ->latest()
            ->get();

        $total  = $orders->map(function ($order) {
            return $order->total;
        })->sum();

        return view('user-profile-account.order-history', compact('orders', 'total'));
    }


    public function favorites(User $user)
    {
        tap(trans('site.favorites'), function ($seoTitle) {
            $this->seo()->generate(['title' => $seoTitle, 'description' => $seoTitle]);
        });

        /*         $favoriteProducts = Product::published()->whereHas('favorites', function (Builder $builder) {
            $builder->where('favorites.user_id', auth()->id());
        })->get(); */

        $favoriteProducts =   $user->getUserFavorites();


        return view('user-profile-account.favorites', compact('favoriteProducts'));
    }

    public function editProfile()
    {

        tap(trans('site.edit_profile'), function ($seoTitle) {
            $this->seo()->generate(['title' => $seoTitle, 'description' => $seoTitle]);
        });

        $user = auth()->user();

        $address = Address::where('owner_id', auth()->id())->get();

        return view('user-profile-account.my-account', compact('user', 'address'));
    }

    public function updateProfile(ProfileRequest $request)
    {
              $data                = $request->validated();
        
        auth()->user()->update($data);

        notify('success', trans('main.updated'));

        return redirect()->route('customers.editProfile');
    }


    public function updateAddress(AddressRequest $request)
    {
        $data                   = $request->validated();
        $userData               = [];
        $userData               =  $data;
        $userData['owner_id']   = auth()->user()->id;



        if ($address = Address::where('owner_id', $userData['owner_id'])->first()) {
            $address->update($data);
        } else {
            $address = Address::create($userData);
        }


        notify('success', trans('main.updated'));

        if (request()->has('page') && $request->page == 'checkout') {

            return redirect()->route('show.checkout');
        }
        return redirect()->route('customers.editProfile');
    }

    public function updateLogo(Request $request)
    {
        $request->validate([
            'logo'  => ['required', 'image', 'mimes:jpg,jpeg,png'],
        ]);

        auth()->user()->syncMedia($request->all());

        notify('success', trans('main.updated'));

        return redirect()->route('customers.editProfile');
    }

    public function editPassword(Request $request)
    {
        $user = auth()->user();

        tap(trans('site.edit_password'), function ($seoTitle) {
            $this->seo()->generate(['title' => $seoTitle, 'description' => $seoTitle]);
        });

        return view('user-profile-account.change-password', compact('user'));
    }


    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $password = ['password' => Hash::make($request->password)];

        auth()->user()->update($password);

        notify('success', trans('main.updated'));

        return redirect()->route('customers.editProfile');
    }

    public function pending()
    {
        tap(trans('site.pending_account'), function ($seoTitle) {
            $this->seo()->generate(['title' => $seoTitle, 'description' => $seoTitle]);
        });

        return view('account_status');
    }

    public function verifications(Request $request)
    {
        abort_unless(auth()->user()->isIndividual(), Response::HTTP_UNAUTHORIZED);

        tap(trans('site.verify_account'), function ($seoTitle) {
            $this->seo()->generate(['title' => $seoTitle, 'description' => $seoTitle]);
        });

        return view('profile.verify_account');
    }

    public function verify(Request $request)
    {
        /** @var \MixCode\User $user */
        $user = auth()->user();

        abort_unless($user->isIndividual(), Response::HTTP_UNAUTHORIZED);

        $request->validate([
            'id_card'               => ['required', 'file', 'mimes:pdf'],
            'commercial_license'     => ['required', 'file', 'mimes:pdf'],
        ]);

        $user->syncMedia($request->all());
        //  $user->notifyAdminsForAccountVerificationRequest();

        notify('success', trans('site.verify_sent'));

        session()->flash('verify_waiting', trans('site.verification_pending'));


        return redirect()->route('profile.verifications');
    }



    public function sendCodeViaSms(Request $request, User $user)
    {


        $request->validate([
            'phone' => ['required', 'string'],
        ]);

        $checkUser = User::where('phone', $request->phone)->first();

        if (!!$checkUser) {

            //$code   =  Str::random(6);

            $digits = 5;

            $code   =  rand(pow(10, $digits - 1), pow(10, $digits) - 1);

            $reverser  = User::where('verification_code', $code)->where('phone', '!=', $request->phone)->first();

            if (!!$reverser) {

                $digits = 5;

                $code   =  rand(pow(10, $digits - 1), pow(10, $digits) - 1);
            }

            $checkUser->update(['verification_code' => $code]);

            $body  = trans('site.you_code_is') . ' : ' . $code;

            $phone = $checkUser->phone_code . ltrim($checkUser->phone, '0');

            $user->sendResetPasswordCodeViaSms($phone, $body);

            return redirect()->route('show.sms.code.form');
        } else {

            notify('error', trans('site.phone_not_found'));

            session()->flash('phone_not_found', trans('site.phone_not_found'));

            return redirect()->back();
        }
    }


    public function showCodeVerificationForm()
    {
        return view('auth.passwords.reset');
    }


    public function ResetPassword(Request $request)
    {
        $request->validate([
            'phone'                 => ['required', 'string'],
            'verification_code'     => ['required', 'string'],
            'password'              => ['required', 'string', 'min:6'],

        ]);

        $checkUser =  User::where('phone', $request->phone)
            ->where('verification_code', $request->verification_code)
            ->first();

        if (!!$checkUser) {

            $password = ['password' => Hash::make($request->password)];

            $checkUser->update($password);

            notify('success', trans('main.updated'));

            return view('/auth/login');
        }

        notify('error', trans('site.phone_or_code_is_not_valid'));

        session()->flash('reset_password_error', trans('site.phone_or_code_is_not_valid'));

        return redirect()->back();
    }
}

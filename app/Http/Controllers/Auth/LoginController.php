<?php

namespace MixCode\Http\Controllers\Auth;

use \Illuminate\Http\Request;
use MixCode\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use MixCode\Utils\UsingSEO;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, UsingSEO;


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


       /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        if (request()->has('return_url') && request()->filled('return_url')) {
            redirect()->setIntendedUrl(request('return_url'));
        }

        tap(trans('main.login'), function ($seoTitle) {
            $this->seo()->generate(['title' => $seoTitle, 'description' => $seoTitle]);
        });        

        return view('auth.login');
    }


    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if($user->isAdmin()) {
            return redirect()->route('dashboard.dashboard');
        }

        if($user->isCompany() && $user->isActive()) {
           
             return redirect()->route('dashboard.dashboard');
     
              }elseif($user->isCompany() && $user->isPending()){
           
              return redirect()->route('account_status');
        }

        return redirect()->intended(route('home'));
    }
}

<?php

namespace MixCode\Http\Controllers\Api\Auth;

use Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;
use MixCode\Http\Controllers\Controller;
use MixCode\User;
use MoaAlaa\ApiResponder\ApiResponder;

class ApiForgetPasswordController extends Controller
{
    use ApiResponder;

    /**
     * Reset Password
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function resetByPhone(Request $request)
    {
        // Phone should not validate it as unique because it will be send by mobile app not user so it is safe 
        $request->validate([
            'phone'     => 'required|regex:/^([0-9\s\+\(\)]*)$/',
            'password'  => 'required|confirmed|min:6',
        ]);

        $user =  User::where('phone', $request->phone)->update(['password' => Hash::make($request->password)]);


        abort_unless($user, Response::HTTP_NOT_FOUND, trans("main.phone_not_found"));

        return $this->api()->response([], trans('passwords.reset'));
    }

    /**
     * Reset Password By Email
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function resetByEmail(Request $request)
    {
        // Email should not validate it as unique because it will be send by mobile app not user so it is safe
        $request->validate([
            'email'     => 'required|string|email|max:191',
            'password'  => 'required|confirmed|min:6',
        ]);

     $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);

        abort_unless($user, Response::HTTP_NOT_FOUND, trans("main.phone_not_found"));
        
        return $this->api()->response([], trans('passwords.reset'));
    }
}

<?php

namespace MixCode\Http\Controllers\Api\Auth;

use MixCode\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    //use ResetsPasswords;

    public function resetPassword(Request $request)
    {
         
        DB::table('password_resets')->where('email', $request->email)->where('token', $request->token)->select();
        return $this->api()->response([], 'Mail has been sent successfully', Response::HTTP_OK);
       
    }

    
     
      

    
}

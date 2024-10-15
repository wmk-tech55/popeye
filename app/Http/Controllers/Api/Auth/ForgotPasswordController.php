<?php

namespace MixCode\Http\Controllers\Api\Auth;

use MixCode\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\Notifications\ForgetPasswordToken;
use MoaAlaa\ApiResponder\ApiResponder;
use Illuminate\Support\Str;
use MixCode\User;
use MixCode\PasswordReset;
use Carbon\Carbon;
use DB;
use Mail;
use MixCode\Mail\sendResetPasswordLink;



class ForgotPasswordController extends Controller
{
    use ApiResponder;
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    //use SendsPasswordResetEmails;


    public function resetPassword(Request $request)
    {
      $user = PasswordReset::where('email' , $request->email)->where('token', $request->token)->first();
   
      if($user ){
        
        return $this->api()->response([], 'The Code is valid', Response::HTTP_OK);
}else{
    return $this->api()->error('Email Or Code is Not found', Response::HTTP_NOT_FOUND);
}
      
    }

    

    public function  sendResetLinkEmail (Request $request){

        try {
            
       
           $user = User::where("email" ,$request->email)->first() ;
            if ( ! $user) {
                return $this->api()->error('email not found', Response::HTTP_NOT_FOUND);
            }
              
            $token = str::random(6) ;
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            
            Mail::to($request->email)->send(new sendResetPasswordLink($token));

   
     return $this->api()->response([], 'Mail has been sent successfully', Response::HTTP_OK);
          
            //code...
        }  catch (\Exception $ex) {
 
            return $this->api()->safeError($ex);
        }

    }


   

 
}

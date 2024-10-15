<?php

namespace MixCode\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\User;
use MixCode\Http\Controllers\Controller;
use MixCode\Http\Requests\ApiRegisterRequest;
use MoaAlaa\ApiResponder\ApiResponder;

class ApiRegisterController extends Controller
{
    use ApiResponder;


    /**
     * Check if phone number exists in database or not
     *
     * @param \Illuminate\Http\Request $request
     * @return json
     */
    public function checkPhoneAvailability(Request $request)
    {
        $request->validate(['phone' => 'required']);

        if (User::where('phone', $request->phone)->exists()) {
            return $this->api()->response([], trans('main.phone_number_exists'), Response::HTTP_OK);
        }

        return $this->api()->error(trans('main.phone_number_not_exists'), Response::HTTP_FORBIDDEN);
    }

    /**
     * Check if email exists in database or not
     *
     * @param \Illuminate\Http\Request $request
     * @return json
     */
    public function checkEmailAvailability(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        if (User::where('email', $request->email)->exists()) {
            return $this->api()->response([], trans('main.email_exists'), Response::HTTP_OK);
        }

        return $this->api()->error(trans('main.email_not_exists'), Response::HTTP_FORBIDDEN);
    }

    /**
     * Register New User And Generate his token
     *
     * @param ApiRegisterRequest $request
     * @return json
     */
    public function register(ApiRegisterRequest $request)
    {
        try {
            $data  = $request->all();
            $user  = (new User())->register($data,true);
            $token = $user->createToken("User Token");

            $response = [
                'user_info'         =>  $user,
                'token_type'        => 'Bearer',
                'token'             => $token->accessToken,
                'token_expires_at'  => $token->token->expires_at
            ];

            if ( ! $user->isCustomer()) {
                 
                $user->notifyAdminForNewRegistration();
            }

            return $this->api()->response($response, null, Response::HTTP_CREATED);
        } catch (\Exception $ex) {
            return $this->api()->safeError($ex);
        }
    }
}

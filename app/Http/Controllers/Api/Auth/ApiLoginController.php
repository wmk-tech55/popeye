<?php

namespace MixCode\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use MixCode\User;
use MixCode\Http\Controllers\Controller;
use MixCode\Http\Requests\ApiLoginRequest;
use MoaAlaa\ApiResponder\ApiResponder;

class ApiLoginController extends Controller
{
    use ApiResponder;
    
    /**
     * Login New User And Generate his token
     *
     * @param ApiLoginRequest $request
     * @return json
     */
    public function login(ApiLoginRequest $request)
    {
        try {
            $data = $request->validated();
       
            if (! auth()->attempt($data)) {
                return $this->api()->error('phone number or Password is wrong', Response::HTTP_NOT_FOUND);
            }
            
            $token = auth()->user()->createToken("User Token");
            
            $response = [
                'user_info'         =>  auth()->user(),
                "token_type"        => "Bearer",
                "token"             => $token->accessToken,
                "token_expires_at"  => $token->token->expires_at
            ];

            return $this->api()->response($response, null, Response::HTTP_OK);
            
        } catch (\Exception $ex) {
            return $this->api()->safeError($ex);
        }
    }
}

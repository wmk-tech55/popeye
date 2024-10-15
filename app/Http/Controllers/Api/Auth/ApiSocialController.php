<?php

namespace MixCode\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use MixCode\User;
use MixCode\Http\Controllers\Controller;
use MixCode\Http\Requests\ApiSocialRequest;
use MoaAlaa\ApiResponder\ApiResponder;

class ApiSocialController extends Controller
{
    use ApiResponder;

    /**
     * Process Social Data then register or get User finally Generate his token
     *
     * @param ApiSocialRequest $request
     * @return json
     */
    public function process(ApiSocialRequest $request)
    {



        try {
            if ($request->account_social_provider == 'apple' && $request->account_social_provider_id != '') {

                $isUserHaveNormalAccount = User::query()
                    ->where('account_social_provider_id', $request->account_social_provider_id)
                    ->where('account_social_provider', 'normal_account')
                    ->withoutGlobalScopes()
                    ->exists();
            } else {
                $isUserHaveNormalAccount = User::query()
                    ->where('email', $request->email)
                    ->where('account_social_provider', 'normal_account')
                    ->withoutGlobalScopes()
                    ->exists();
            }

            // Check if email already exists for normal account
            if ($isUserHaveNormalAccount) {
                return $this->api()->error(
                    trans('main.account_is_registered_with_password'),
                    Response::HTTP_UNAUTHORIZED
                );
            }

            $response_status = null;
            if ($request->account_social_provider == 'apple' && $request->account_social_provider_id != '') {

                $user = User::query()->where('account_social_provider_id', $request->account_social_provider_id)->first();
            } else {
                $user = User::query()->where('email', $request->email)->first();
            }

            // Check if email already exists for social account
            if (!!$user) {
                $response_status = Response::HTTP_OK;
            } else {
                //  $user = (new User())->register($this->buildUserDataToRegister($request->only(['name', 'email'])));
                $user = (new User())->register($this->buildUserDataToRegister($request->all()));

              //  $user->account_social_provider = $request->account_social_provider;
                $user->save();

                $response_status = Response::HTTP_CREATED;
            }

            if ($user->isPending()) {
                return $this->api()->response([], trans('site.pending_account'), Response::HTTP_OK);
            }

            return $this->api()->response($user->generateNewToken(), null, $response_status);
        } catch (\Exception $ex) {
            return $this->api()->safeError($ex);
        }
    }

    protected function buildUserDataToRegister(array $data)
    {
        if (!array_key_exists('account_social_provider_id', $data)) {
            $data['account_social_provider_id'] = null;
        } 
      
        return [
            'full_name' => $data['name'],
            'email'     => $data['email'],
            'phone'     => null,
            'status'    => User::ACTIVE_STATUS,
            'password'  => Hash::make(md5(time() . $data['email'])),
            'type'      => User::CUSTOMER_TYPE,
          /*   'dashboard'                  => false, */
            'account_social_provider'    => $data['account_social_provider'],
            'account_social_provider_id' => $data['account_social_provider_id'],
            'country_id'                 => $data['country_id'],
            'active_country_id'          => $data['country_id'],
        ];
    }
}

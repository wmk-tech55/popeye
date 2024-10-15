<?php

namespace MixCode\Http\Controllers\Auth;

use MixCode\User;
use MixCode\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        $isCompanyType  = $data['type'] === User::COMPANY_TYPE;
        $isCustomerType = $data['type'] === User::CUSTOMER_TYPE;

        $roles = [];

        $roles = [
            'full_name'           => ['required', 'string', 'max:191'],
            'email'               => ['required', 'string', 'email', 'max:191', 'unique:users'],
            'password'            => ['required', 'string', 'min:6'],
            'phone'               => ['required'],
            'type'                => ['nullable', Rule::in([User::COMPANY_TYPE,   User::CUSTOMER_TYPE])],
            'map_url'             => ['nullable', 'url'],
            'bank_name'           => ['nullable', 'string'],
            'bank_account_number' => ['nullable', 'string'],
        ];

        if ($isCompanyType) {
            $roles = [
                'logo'     => ['nullable'],
                'id_card'  => ['required'],
                    ];
                            }

        $fields = array_merge($roles);

        return Validator::make($data, $fields);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \MixCode\User
     */
    protected function create(array $data)
    {
        if ($data['type'] == User::COMPANY_TYPE) {

            $data['status'] = User::PENDING_STATUS;
        }


        return (new User())->register($data);
    }
}

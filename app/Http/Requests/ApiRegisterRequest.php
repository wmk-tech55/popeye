<?php

namespace MixCode\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use MixCode\User;
use MixCode\Rules\Uuid;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $type = $this->input('type');

        $isCompany  = $type === User::COMPANY_TYPE;
        $isDriver   = $type === User::DRIVER_TYPE;
        $isProvider = $type != User::CUSTOMER_TYPE;

        $rules = [
            'full_name'          => ['required', 'string', 'max:191'],
            'username'           => [Rule::requiredIf($isCompany), 'nullable', 'string', 'unique:users'],
            'email'              => ['nullable', 'string', 'email', 'max:191', 'unique:users'],
            'password'           => ['required', 'string', 'min:6'],
            'phone'              => ['required', 'unique:users', 'regex:/^([0-9\s\+\(\)]*)$/'],
            'type'               => ['required', Rule::in([User::COMPANY_TYPE, User::CUSTOMER_TYPE, User::DRIVER_TYPE])],
            'country_code'       => [Rule::requiredIf($isProvider), 'string'],
            'country'            => [Rule::requiredIf($isProvider), 'string'],
            'city'               => [Rule::requiredIf($isProvider), 'string'],
            'address'            => [Rule::requiredIf($isProvider), 'string'],
            'area'               => [Rule::requiredIf($isProvider), 'string'],
            'zoom'               => ['nullable'],
            'longitude'          => [Rule::requiredIf($isProvider), 'string'],
            'latitude'           => [Rule::requiredIf($isProvider), 'string'],
            'country_id'         => ['required', new Uuid, Rule::exists('countries', 'id')],
            'city_id'            => ['nullable', new Uuid, Rule::exists('cities', 'id')],
            'categorization_id'  => [Rule::requiredIf($isCompany), new Uuid, Rule::exists('categorizations', 'id')],
            'logo'               => [Rule::requiredIf($isCompany), 'image', 'mimes:jpg,jpeg,png'],
            'commercial_license' => [Rule::requiredIf($isCompany), 'image', 'mimes:pdf,jpg,jpeg,png'],
            'vehicle_license'    => [Rule::requiredIf($isDriver), 'image', 'mimes:pdf,jpg,jpeg,png'],
            'vehicle_photos'     => [Rule::requiredIf($isDriver), 'array', 'min:1'],
            'vehicle_photos.*'   => [Rule::requiredIf($isDriver), 'image', 'mimes:jpeg,jpg,png'],
        ];

        return $rules;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }
}

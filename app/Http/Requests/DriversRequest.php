<?php

namespace MixCode\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use MixCode\Rules\Uuid;
use MixCode\User;

class DriversRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $basic_fields = [
            'full_name'        => ['required', 'string', 'max:191'],
            'email'            => ['nullable', 'string', 'email', 'max:191', 'unique:users'],
            'password'         => ['required', 'string', 'min:6'],
            'phone'            => ['required', 'unique:users'],
            'type'             => ['nullable', Rule::in(User::DRIVER_TYPE)],
            'status'           => ['nullable', Rule::in(User::USER_STATUS)],
            'country_code'     => ['nullable'],
            'country'          => ['nullable'],
            'city'             => ['nullable'],
            'address'          => ['required'],
            'area'             => ['nullable'],
            'zoom'             => ['nullable'],
            'longitude'        => ['nullable', 'string'],
            'latitude'         => ['nullable', 'string'],
            'country_data_id'  => ['nullable', new Uuid, Rule::exists('countries', 'id')],
            'vehicle_photos'   => ['nullable', 'array', 'min:1'],
            'vehicle_photos.*' => ['nullable', 'image', 'mimes:jpeg,jpg,png'],
            'vehicle_license'  => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png'],
        ];




        $rules = array_merge($basic_fields);

        if ($this->isMethod('PATCH')) {
                   $id      = $this->route()->parameter('driver')->getKey();
            $rules['email'] = ['required', 'email', 'max:191', Rule::unique('users')->ignore($id)];
            $rules['phone'] = ['required',   Rule::unique('users')->ignore($id)];
            unset($rules['password']); // Remove Password It Has It's Own Function

            $rules['vehicle_photos']   = ['nullable', 'array', 'min:1'];
            $rules['vehicle_photos.*'] = ['nullable', 'image', 'mimes:jpeg,jpg,png'];
            $rules['vehicle_license']  = ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png'];

            $rules = array_merge($rules);
        }

        return $rules;
    }
}

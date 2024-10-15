<?php

namespace MixCode\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use MixCode\Rules\Uuid;
use MixCode\User;

class CompaniesRequest extends FormRequest
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

     

        $rules = [
            'full_name'          => ['required', 'string', 'max:191'],
            'username'           => ['required', 'string', 'max:191'],
            'email'              => ['nullable', 'string', 'email', 'max:191', 'unique:users'],
            'password'           => ['required', 'string', 'min:6'],
            'phone'              => ['required', 'regex:/^([0-9\s\+\(\)]*)$/', 'unique:users'],
            'type'               => ['nullable', Rule::in(User::COMPANY_TYPE)],
            'status'             => ['nullable', Rule::in(User::USER_STATUS)],
            'country_code'       => ['nullable'],
            'country'            => ['nullable'],
            'city'               => ['nullable'],
            'address'            => ['nullable'],
            'area'               => ['nullable'],
            'zoom'               => ['nullable'],
            'longitude'          => ['nullable', 'string'],
            'latitude'           => ['nullable', 'string'],
            'country_id'         => ['nullable', new Uuid, Rule::exists('countries', 'id')],
            'city_id'            => ['nullable', new Uuid, Rule::exists('cities', 'id')],
            'categorization_id'  => ['nullable',  new Uuid, Rule::exists('categorizations', 'id')],
            'logo'               => ['nullable', 'image', 'mimes:jpg,jpeg,png'],
            'commercial_license' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png'],
            'work_time_id'       => ['nullable'],
            'day_is_vacation'    => ['nullable'],
            'open_time'          => ['nullable', 'array', 'min:1'],
            'open_time.*'        => ['nullable'],
            'close_time'         => ['nullable', 'array', 'min:1'],
            'close_time.*'       => ['nullable']
        ];

        if ($this->isMethod('PATCH')) {

            $id = $this->route()->parameter('company')->getKey();

            $rules['email'] = ['nullable', 'email', 'max:191', Rule::unique('users')->ignore($id)];
            $rules['phone'] = ['required',   Rule::unique('users')->ignore($id)];

            unset($rules['password']); // Remove Password It Has It's Own Function

            $rules['type'] =  ['nullable'];

            $files_fields = [
                'logo'               => ['nullable', 'image', 'mimes:jpg,jpeg,png'],
                'commercial_license' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png'],
            ];

            $rules = array_merge($rules, $files_fields);
        }

        return $rules;
    }
}

<?php

namespace MixCode\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use MixCode\Rules\Uuid;
use MixCode\User;

class UsersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() &&  (auth()->user()->isAdmin());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $type = $this->input('type');
 
        $isCustomerType     = $type === User::CUSTOMER_TYPE;
        $isAdminType        = $type === User::ADMIN_TYPE;
        $isCompanyType      = $type === User::COMPANY_TYPE;


 
        $basic_fields = [
            'full_name'       => ['required', 'string', 'max:191'],
            'email'           => ['nullable', 'string', 'email', 'max:191', 'unique:users'],
            'password'        => ['required', 'string', 'min:6'],
            'phone'           => ['required','regex:/^([0-9\s\+\(\)]*)$/'],
            'type'            => ['required', Rule::in(User::USER_TYPES)],
            'status'          => ['required', Rule::in(User::USER_STATUS)],
            'country_data_id' => ['nullable', new Uuid, Rule::exists('countries', 'id')],
    
        ];

        $profile_social_links = [
            'facebook'      => ['nullable', 'url', 'max:191'],
            'twitter'       => ['nullable', 'url', 'max:191'],
            'linkedin'      => ['nullable', 'url', 'max:191'],
            'instagram'     => ['nullable', 'url', 'max:191'],
            'snapchat'      => ['nullable', 'url', 'max:191'],
            'youtube'       => ['nullable', 'url', 'max:191'],
        ];

        $files_fields = [
            'logo'               => ['nullable', 'image', 'mimes:jpg,jpeg,png'],
            'id_card'            => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png'],
            'commercial_license' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png'],
            'vehicle_license'    => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png'],
        ];

        $rules = array_merge($basic_fields, $profile_social_links, $files_fields);

        if ($this->isMethod('PATCH')) {
            $id = $this->route()->parameter('user')->getKey();

            $rules['email'] =  ['nullable', 'email', 'max:191', Rule::unique('users')->ignore($id)];
            $rules['status'] = ['nullable', Rule::in(User::USER_STATUS)];

            unset($rules['password']); // Remove Password It Has It's Own Function

            $files_fields = [
                'logo'                  => ['nullable', 'image', 'mimes:jpg,jpeg,png'],
                'id_card'               => ['nullable', 'file', 'mimes:pdf'],
                 'commercial_license'     => ['nullable', 'file', 'mimes:pdf'],
            ];

            $rules = array_merge($rules, $files_fields);
        }

        return $rules;
    }


    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'full_name'          => trans('main.full_name'),
            'email'              => trans('main.email'),
            'password'           => trans('main.password'),
            'phone'              => trans('main.phone'),
            'map_url'            => trans('main.map_url'),
            'country_id'         => trans('main.country'),
            'city_id'            => trans('main.city'),
            'type'               => trans('main.type'),
            'status'             => trans('main.status'),
            'facebook'           => trans('main.facebook'),
            'twitter'            => trans('main.twitter'),
            'linkedin'           => trans('main.linkedin'),
            'instagram'          => trans('main.instagram'),
            'snapchat'           => trans('main.snapchat'),
            'youtube'            => trans('main.youtube'),
            'logo'               => trans('main.logo_or_profile'),
            'id_card'            => trans('main.id_card'),
            'commercial_license' => trans('main.commercial_license'),
        ];
    }
}

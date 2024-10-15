<?php

namespace MixCode\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use MixCode\Rules\Uuid;
use MixCode\User;

class CompanyProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() &&  (auth()->user()->isCompany());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $id = auth()->id();
        $rules = [
            'full_name'          => ['required', 'string', 'max:191'],
            'email'              => ['required', 'string', 'email', 'max:191', Rule::unique('users')->ignore($id)],
            'password'           => ['required', 'string', 'min:6'],
            'phone'              => ['required', 'regex:/^([0-9\s\+\(\)]*)$/', Rule::unique('users')->ignore($id)],
            'address'            => ['nullable'],
            'zoom'               => ['nullable'],
            'longitude'          => ['required', 'string'],
            'latitude'           => ['required', 'string'],
            'country_id'         => ['required', new Uuid, Rule::exists('countries', 'id')],
            'city_id'            => ['required', new Uuid, Rule::exists('cities', 'id')],
            'categorization_id'  => ['required',  new Uuid, Rule::exists('categorizations', 'id')],
            'logo'               => ['nullable', 'image', 'mimes:jpg,jpeg,png'],
            'commercial_license' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png'],
        ];

        unset($rules['password']);

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
            'categorization_id'  => trans('main.categorization'),
            'city_id'            => trans('main.city'),
            'type'               => trans('main.type'),
            'status'             => trans('main.status'),
            'facebook'           => trans('main.facebook'),
            'twitter'            => trans('main.twitter'),
            'linkedin'           => trans('main.linkedin'),
            'instagram'          => trans('main.instagram'),
            'snapchat'           => trans('main.snapchat'),
            'youtube'            => trans('main.youtube'),
            'logo'               => trans('main.logo'),
            'commercial_license' => trans('main.commercial_license'),
        ];
    }
}

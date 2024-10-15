<?php

namespace MixCode\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use MixCode\Rules\Uuid;
use MixCode\User;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = auth()->id();

        $basic_fields = [
            'full_name'           => ['required', 'string', 'max:191'],
            'email'               => ['required', 'email', 'max:191', Rule::unique('users')->ignore($id)],
            'phone'               => ['required'],
            'country_id'          => ['required', new Uuid, Rule::exists('countries', 'id')],
            'map_url'             => ['nullable', 'url'],
            'bank_name'           => ['nullable', 'string'],
            'bank_account_number' => ['nullable', 'string'],
        ];

        $profile_social_links = [
            'facebook'      => ['nullable', 'url', 'max:191'],
            'twitter'       => ['nullable', 'url', 'max:191'],
            'linkedin'      => ['nullable', 'url', 'max:191'],
            'instagram'     => ['nullable', 'url', 'max:191'],
            'snapchat'      => ['nullable', 'url', 'max:191'],
            'youtube'       => ['nullable', 'url', 'max:191'],
        ];
 
        $rules = array_merge($basic_fields, $profile_social_links);

        if ($this->isMethod('PATCH')) {
            $id = auth('api')->id();
 
            $rules['email'] =  ['nullable', 'email', 'max:191', Rule::unique('users')->ignore($id)];
        }
        return $rules;
    }
}

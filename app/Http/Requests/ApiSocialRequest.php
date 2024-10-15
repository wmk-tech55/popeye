<?php

namespace MixCode\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use MixCode\Rules\Uuid;
use MixCode\User;

class ApiSocialRequest extends FormRequest
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
        $type           = $this->input('account_social_provider');
        $isAppleType    = $type === 'apple';
        $isNotAppleType = $type != 'apple';

        $rules = [
            'name'                       => [Rule::requiredIf($isNotAppleType)],
            'email'                      => [Rule::requiredIf($isNotAppleType), 'nullable', 'string', 'email', 'max:191'],
            'account_social_provider'    => ['required', 'string', 'max:191'],
            'account_social_provider_id' => [Rule::requiredIf($isAppleType)],
            'country_id'                 => ['required', new Uuid, Rule::exists('countries', 'id')],
        ];

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
            'name'                    => trans('main.name'),
            'email'                   => trans('main.email'),
            'account_social_provider' => trans('main.social_provider'),
        ];
    }
}

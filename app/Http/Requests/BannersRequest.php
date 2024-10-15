<?php

namespace MixCode\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use MixCode\Rules\Uuid;
use MixCode\Banner;

class BannersRequest extends FormRequest
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
            // Basic Info
            'en_name'  => ['required', 'string', 'max:191'],
            'ar_name'  => ['required', 'string', 'max:191'],
            'url'      => ['nullable','url'],
            'position' => ['nullable', Rule::in(Banner::WEBSITE_SECTIONS)],
            'store_id' => ['nullable', new Uuid, Rule::exists('users', 'id')],
  
        ];

        
        return $rules;
    }
}

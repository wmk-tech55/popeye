<?php

namespace MixCode\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use MixCode\Address;

class AddressRequest extends FormRequest
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
 
        return  [
            'type'         => ['required', 'string'],
            'address'      => ['required', 'string', 'max:191'],
            'city'         => ['required', 'string', 'max:191'],
            'country'      => ['required', 'string', 'max:191'],
            'country_code' => ['required', 'string', 'max:191'],
            'area'         => ['required', 'string', 'max:191'],
            'latitude'     => ['required'],
            'longitude'    => ['required'],
            'zoom'         => ['nullable'],
 
 
        ];

      
    }
}

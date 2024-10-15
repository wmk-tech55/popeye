<?php

namespace MixCode\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use MixCode\Rules\Uuid;
use MixCode\Product;

class PromoCodesRequest extends FormRequest
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
               
            'discount'         => ['required', 'integer'],
            'number_of_uses'   => ['required', 'integer'],
            'expiry_date'      => ['nullable', 'date_format:Y-m-d', 'after_or_equal:date_from'],
 
            
        ];

         

        return $rules;
    }

 
}

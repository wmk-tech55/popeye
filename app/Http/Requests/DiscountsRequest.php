<?php

namespace MixCode\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use MixCode\Rules\Uuid;
use MixCode\Product;

class DiscountsRequest extends FormRequest
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
            'en_name'       => ['required', 'string', 'max:191'],
            'ar_name'       => ['required', 'string', 'max:191'],
             
            'discount'      => ['required', 'integer'],

               
          //  'products_id'     => ['required', 'array', 'min:1'],
          //  'products_id.*'   => ['required', new Uuid, Rule::exists('products', 'id')],
             
            'category_parent_id'      => ['required'],
             'category_parent_id.*'    => ['required', new Uuid, Rule::exists('categories', 'id')],
             
            
        ];

         

        return $rules;
    }

 
}

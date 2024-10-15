<?php

namespace MixCode\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use MixCode\Rules\Uuid;
use MixCode\Product;

class ProductsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() && auth()->user()->isAllowed();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $type         = $this->input('type');
        $hasAdditions = $this->input('has_additions');

        $has_additions      = $hasAdditions === 'yes';


        $rules = [
            // Basic Info
            'en_name'              => ['nullable', 'string', 'max:191'],
            'ar_name'              => ['required', 'string', 'max:191'],
            'en_overview'          => ['nullable', 'string'],
            'ar_overview'          => ['nullable', 'string'],
            'has_additions'        => ['required', rule::in(['yes', 'no'])],
            'price'                => ['required'],
            'discount_percentage'  => ['required', 'min:0', 'max:100', 'integer'],
            'additions_ar_names'   => [Rule::requiredIf($has_additions), 'nullable', 'array', 'min:1'],
            'additions_ar_names.*' => [Rule::requiredIf($has_additions), 'nullable', 'string'],
            //'additions_en_names'   => [Rule::requiredIf($has_additions), 'nullable', 'array', 'min:1'],
            // 'additions_en_names.*' => [Rule::requiredIf($has_additions), 'nullable', 'string'],
            'additions_prices'     => [Rule::requiredIf($has_additions), 'nullable', 'array', 'min:1'],
            'additions_prices.*'   => [Rule::requiredIf($has_additions), 'nullable'],
            'category_id'          => ['required', new Uuid, Rule::exists('categories', 'id')],
            'images'               => ['required', 'array', 'min:1'],
            'images.*'             => ['required', 'image', 'mimes:jpeg,jpg,png'],
        ];

        if ($this->isMethod('PATCH')) {
            $rules['category_id'] = ['nullable'];
            $rules['images']      = ['nullable', 'array', 'min:1'];
            $rules['images.*']    = ['nullable', 'image', 'mimes:jpeg,jpg,png'];
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
            'en_name'              => trans('main.en_name'),
            'ar_name'              => trans('main.ar_name'),
            'price'                => trans('main.price'),
            'quantity'             => trans('main.quantity'),
            'en_overview'          => trans('main.en_overview'),
            'ar_overview'          => trans('main.ar_overview'),
            'category_id'          => trans('main.category'),
            'categories_id'        => trans('main.categories'),
            'categories_id.*'      => trans('main.categories'),
            'categorization_id'    => trans('main.categorizations'),
            'images'               => trans('main.images'),
            'images.*'             => trans('main.images'),
            'group_ar_names'       => trans('main.group_name'),
            'group_ar_names.*'     => trans('main.group_name'),
            'group_types'          => trans('main.group_types'),
            'group_types.*'        => trans('main.group_types'),
            'additions_ar_names'   => trans('main.addition_name'),
            'additions_ar_names.*' => trans('main.addition_name'),
            'additions_en_names'   => trans('main.addition_name'),
            'additions_en_names.*' => trans('main.addition_name'),
            'additions_prices'     => trans('main.additions_price'),
            'additions_prices.*'   => trans('main.additions_price'),

        ];
    }
}

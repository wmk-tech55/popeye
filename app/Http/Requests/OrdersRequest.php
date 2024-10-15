<?php

namespace MixCode\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use MixCode\Order;
use MixCode\Rules\Uuid;

class OrdersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return  true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'street_name'                     => ['required', 'string', 'max:191'],
            'name'                            => ['required'],
            'phone'                           => ['required'],
            'block_number'                    => ['required'],
            'flat_number'                     => ['required'],
            'city'                            => ['required'],
            'country'                         => ['required'],
            'payment_method'                  => ['required', Rule::in(Order::PAYMENT_METHODS)],
            'accept_our_terms_and_conditions' => ['required'],

        ];
    }
}

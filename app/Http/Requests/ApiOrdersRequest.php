<?php

namespace MixCode\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use MixCode\Order;
use MixCode\Rules\Uuid;

class ApiOrdersRequest extends FormRequest
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

       

        return [
            'address_id'     => ['required'],
            'shipping_fee'   => ['required'],
            'time'           => ['nullable'],
            'date'           => ['nullable'],
            'payment_method' => ['nullable'],
        ];
    }
}

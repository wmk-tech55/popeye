<?php

namespace MixCode\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use MixCode\City;
use MixCode\Rules\Uuid;

class CitiesRequest extends FormRequest
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
        return [
            'en_name'       => ['required', 'string', 'max:191'],
            'ar_name'       => ['required', 'string', 'max:191'],
            'country_id'    => ['required', new Uuid, Rule::exists('countries', 'id')],
            'status'        => ['required', Rule::in([City::ACTIVE_STATUS, City::INACTIVE_STATUS])],
        ];
    }
}

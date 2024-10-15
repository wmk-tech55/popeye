<?php

namespace MixCode\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use MixCode\WorkTime;
use MixCode\Rules\Uuid;

class WorkTimesRequest extends FormRequest
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
        $rules = [
            'day_is_vacation' => ['required', 'boolean'],
            'open_time'       => ['required', 'date_format:H:i', 'before:close_time'],
            'close_time'      => ['required', 'date_format:H:i', 'after:open_time'],
        ];

        if (!request()->wantsJson() && is_string($this->input('day_is_vacation'))) {
            $rules['day_is_vacation'] = ['required', Rule::in(['yes', 'no'])];
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
            'day_is_vacation' => trans('main.day_is_vacation'),
            'open_time'       => trans('main.open_time'),
            'close_time'      => trans('main.close_time'),
        ];
    }
}

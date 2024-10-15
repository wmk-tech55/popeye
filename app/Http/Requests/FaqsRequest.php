<?php

namespace MixCode\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use MixCode\Faq;

class FaqsRequest extends FormRequest
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
            'en_question'       => ['required', 'string', 'max:191'],
            'ar_question'       => ['required', 'string', 'max:191'],
            'en_answer'         => ['required', 'string'],
            'ar_answer'         => ['required', 'string'],
            'status'            => ['required', Rule::in([Faq::ACTIVE_STATUS, Faq::INACTIVE_STATUS])],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'en_question'   => trans('main.en_question'),
            'ar_question'   => trans('main.ar_question'),
            'en_answer'     => trans('main.en_answer'),
            'ar_answer'     => trans('main.ar_answer'),
            'status'        => trans('main.status'),
        ];
    }
}

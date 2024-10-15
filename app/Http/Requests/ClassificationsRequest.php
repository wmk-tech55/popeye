<?php

namespace MixCode\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use MixCode\Classification;
use MixCode\Rules\Uuid;

class ClassificationsRequest extends FormRequest
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
            'en_name'           => ['required', 'string', 'max:191'],
            'ar_name'           => ['required', 'string', 'max:191'],
            'categorization_id' => ['required', new Uuid, Rule::exists('categorizations', 'id')],
        ];
    }
}

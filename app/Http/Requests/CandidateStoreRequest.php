<?php

namespace App\Http\Requests;

use App\Utilities\ValidationUtilities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;


class CandidateStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return ValidationUtilities::CandidateStoreValidationRules($this->input('academy'));
    }
    public function messages()
    {
        return ValidationUtilities::customMessages();
    }
    protected function failedValidation(Validator $validator)
    {
        ValidationUtilities::failedValidation($validator);
    }


    protected function prepareForValidation()
    {
    }
}

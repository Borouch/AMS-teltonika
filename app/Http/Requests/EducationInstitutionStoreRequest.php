<?php

namespace App\Http\Requests;

use App\Utilities\ValidationUtilities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class EducationInstitutionStoreRequest extends FormRequest
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
        return [
            'name' => 'required|LTalpha_spaces_dash',
            'abbreviation'=>'required|LTalpha_spaces_dash'
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        ValidationUtilities::failedValidation($validator);
    }
}

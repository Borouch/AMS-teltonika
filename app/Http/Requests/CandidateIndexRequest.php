<?php

namespace App\Http\Requests;

use App\Models\Academy;
use Illuminate\Validation\Rule;
use App\Utilities\ValidationUtilities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class CandidateIndexRequest extends FormRequest
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
            'should_group_by_academy' => 'nullable|boolean',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->get('should_group_by_academy') != null) {

            $shouldGroupByAcademy= strtolower($this->get('should_group_by_academy'));
            $shouldGroupByAcademy= filter_var($shouldGroupByAcademy, FILTER_VALIDATE_BOOLEAN);
            $this->merge([
                'should_group_by_academy' => $shouldGroupByAcademy,

            ]);
        }
    }

    protected function failedValidation(Validator $validator)
    {
        ValidationUtilities::failedValidation($validator);
    }
}

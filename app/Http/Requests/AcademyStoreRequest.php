<?php

namespace App\Http\Requests;

use App\Models\Academy;
use Illuminate\Validation\Rule;
use App\Utilities\ValidationUtilities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class AcademyStoreRequest extends FormRequest
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
        $academiesNames = Academy::all()->map(fn($academy)=>$academy->name)->toArray();
        $academiesAbv = Academy::all()->map(fn($academy)=>$academy->abbreviation)->toArray();
        return [
            'name' => 'required|Letter_space|' . Rule::notIn($academiesNames),
            'abbreviation' => 'nullable|Letter_num_space|' . Rule::notIn($academiesAbv)
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        ValidationUtilities::failedValidation($validator);
    }
    public function messages()
    {
        return ValidationUtilities::customMessages();
    }
}

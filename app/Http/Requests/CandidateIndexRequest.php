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
        $academies = Academy::all();
        $names = $academies->map(fn ($academy) => $academy->name);
        $names_abv = $academies->map(fn ($academy) => $academy->abbreviation);
        return [
            'academy_name' => 'nullable|regex:/^[ a-zA-Ž]*$/|' . Rule::in($names),
            'academy_name_abbreviation' => 'nullable|alpha_num|' . Rule::in($names_abv)
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        ValidationUtilities::failedValidation($validator);
    }
}

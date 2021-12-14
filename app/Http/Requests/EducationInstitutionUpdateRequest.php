<?php

namespace App\Http\Requests;

use App\Models\EducationInstitution;
use App\Utilities\ValidationUtilities;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EducationInstitutionUpdateRequest extends FormRequest
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
        $edusId = EducationInstitution::all()->map(fn($edu): string => $edu->id);
        return [
            'name' => 'nullable|Letter_space|unique:education_institutions,name|min:2',
            'abbreviation'=>'nullable|Letter_space||unique:education_institutions,abbreviation|min:2',
            'education_institution_id' => 'required|' . Rule::in($edusId)
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all();
        $data['education_institution_id'] = $this->route('id');
        return $data;
    }

    public function messages()
    {
        return ValidationUtilities::customMessages();
    }

    protected function failedValidation(Validator $validator)
    {
        ValidationUtilities::failedValidation($validator);
    }
}

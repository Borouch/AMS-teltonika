<?php

namespace App\Http\Requests;

use App\Models\Academy;
use App\Models\Candidate;
use Illuminate\Validation\Rule;
use App\Models\EducationInstitution;
use App\Utilities\ValidationUtilities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator as ValidatorSupport;
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
        $institutions = EducationInstitution::all();
        $institutionsId = $institutions->map(fn ($institution): string => $institution->id);
        $acId = $this->input('academy_id');
        ValidationUtilities::validateAcademyId($acId);
        $academy = Academy::find($acId);
        $positions = $academy->positions()->get();
        $positionsId = $positions->map(fn ($position) => $position->id);
        return [
            'name' => 'required|Letter_space|min:2',
            'surnname' => 'required|Letter_space|min:2',
            'city' => 'required|Letter_space|min:2',
            'comments' => 'nullable|text|max:1000',
            'gender' => 'required|' . Rule::in(Candidate::GENDERS),
            'email' => 'required|email',
            'application_date' => 'required|date_format:Y-m-d',
            'education_institution_id' => 'required|' . Rule::in($institutionsId),
            'course' => 'required|' . Rule::in(Candidate::COURSES),
            'can_manage_data' => 'required|' . Rule::in(['0', '1']),
            'positions.*' => 'required|distinct|' . Rule::in($positionsId),
            'status' => 'nullable|' . Rule::in(Candidate::STATUSES),
            'phone' => 'nullable|phone',
            'CV' => 'nullable|max:10000|mimes:pdf',

        ];
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

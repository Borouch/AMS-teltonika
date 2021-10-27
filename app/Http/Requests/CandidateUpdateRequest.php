<?php

namespace App\Http\Requests;

use App\Models\Academy;
use App\Models\Candidate;
use Illuminate\Validation\Rule;
use App\Models\EducationInstitution;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class CandidateUpdateRequest extends FormRequest
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

    public function rules()
    {
        $institutions = EducationInstitution::all();
        $institutions = $institutions->map(fn ($institution): string => $institution->name); {
            $academies = Academy::all();
            $academies = $academies->map(fn ($academy): string => $academy->name);;
            return [
                'name' => 'nullable|alpha',
                'surnname' => 'nullable|alpha',
                'gender' => 'nullable|' . Rule::in(Candidate::GENDERS),
                'phone' => 'nullable|regex:/^([\+][0-9]*)$/|min:9',
                'email' => 'nullable|email',
                'application_date' => 'nullable|date',
                'education_institution' => 'nullable|' . Rule::in($institutions),
                'city' => 'nullable|alpha',
                'course' => 'nullable|' . Rule::in(Candidate::COURSES),
                'academy' => 'nullable|' . Rule::in($academies),
                'comment' => 'nullable|alpha_num|max:1000',
                'CV' => 'nullable|max:10000|mimes:pdf'
            ];
        }
    }
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $response = response()->json([
            'error' => 'Invalid data sent',
            'details' => $errors->messages(),
        ]);
        throw new ValidationException($validator, $response);
    }
}

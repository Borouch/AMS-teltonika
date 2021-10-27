<?php

namespace App\Http\Requests;

use App\Models\Academy;
use App\Models\Candidate;
use Illuminate\Validation\Rule;
use App\Models\EducationInstitution;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

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
        $institutions = $institutions->map(fn ($institution): string => $institution->name); {
            $academies = Academy::all();
            $academies = $academies->map(fn ($academy): string => $academy->name);;
            return [
                'name' => 'required|alpha',
                'surnname' => 'required|alpha',
                'gender' => 'required|' . Rule::in(Candidate::GENDERS),
                'phone' => 'required|regex:/^([\+][0-9]*)$/|min:9',
                'email' => 'required|email',
                'application_date' => 'required|date',
                'education_institution' => 'required|' . Rule::in($institutions),
                'city' => 'required|alpha',
                'course' => 'required|' . Rule::in(Candidate::COURSES),
                'academy' => 'required|' . Rule::in($academies),
                'comment' => 'nullable|alpha_num|1000',
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

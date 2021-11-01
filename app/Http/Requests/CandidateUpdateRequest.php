<?php

namespace App\Http\Requests;

use App\Models\Academy;
use App\Models\Position;
use App\Models\Candidate;
use Illuminate\Validation\Rule;
use App\Models\EducationInstitution;
use Illuminate\Support\Facades\Input;
use App\Utilities\ValidationUtilities;
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
        $candidateId = $this->id;
        $positions = Position::all();
        $institutions = EducationInstitution::all();
        $institutions = $institutions->map(fn ($institution): string => $institution->name);
        $academies = Academy::all();
        $academies = $academies->map(fn ($academy): string => $academy->name);;
        if ($this->filled('academy')) {
            $positions = Position::all()->where('academy', '=', $this->input('academy'));
        } else {
            $academy = Candidate::findOrFail($candidateId)->academy;
            $positions = Position::all()->where('academy', '=', $academy);
        }
        $positionsNames = $positions->map(fn ($position) => $position->name);
        return [
            'name' => 'nullable|alpha',
            'surnname' => 'nullable|alpha',
            'gender' => 'nullable|' . Rule::in(Candidate::GENDERS),
            'phone' => 'nullable|regex:/^([\+][0-9]*)$/|min:9',
            'positions.*' => 'nullable|distinct|' . Rule::in($positionsNames),
            'email' => 'nullable|email',
            'application_date' => 'nullable|date_format:Y-m-d',
            'education_institution' => 'nullable|' . Rule::in($institutions),
            'status' => 'nullable|'.Rule::in(Candidate::STATUSES),
            'city' => 'nullable|alpha',
            'course' => 'nullable|' . Rule::in(Candidate::COURSES),
            'academy' => 'nullable|' . Rule::in($academies),
            'comment' => 'nullable|alpha_num|max:1000',
            'CV' => 'nullable|max:10000|mimes:pdf'
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

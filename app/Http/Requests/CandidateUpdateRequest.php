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
        $academies = $academies->map(fn ($academy): string => $academy->name);
        ;

        if ($this->filled('academy')) {
            $academy = Academy::where('name', '=', $this->input('academy'))->first();
        } else {
            $academy = Candidate::findOrFail($candidateId)->academy()->get()->first();
        }
        $positions = $academy->positions()->get();
        $positionsNames = $positions->map(fn ($position) => $position->name);
        return [
            'name' => 'nullable|LTalpha_spaces_dash',
            'surnname' => 'nullable|LTalpha_spaces_dash',
            'city' => 'nullable|LTalpha_spaces_dash',
            'gender' => 'nullable|' . Rule::in(Candidate::GENDERS),
            'phone' => 'nullable|regex:/^([\+]{0,1}[0-9]*)$/|min:9',
            'positions.*' => 'nullable|distinct|' . Rule::in($positionsNames),
            'email' => 'nullable|email',
            'application_date' => 'nullable|date_format:Y-m-d',
            'education_institution_id' => 'nullable|' . Rule::in($institutions),
            'status' => 'nullable|' . Rule::in(Candidate::STATUSES),
            'course' => 'nullable|' . Rule::in(Candidate::COURSES),
            'academy_id' => 'nullable|' . Rule::in($academies),
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

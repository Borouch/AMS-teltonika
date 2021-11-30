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
use Illuminate\Support\Facades\Validator as SupportValidator;

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
        $candidatesIds = Candidate::all()->map(fn ($c) => $c->id);
        $candidateId = SupportValidator::make(
            $this->only('candidate_id'),
            [
                'candidate_id' => 'required|' . Rule::in($candidatesIds),
            ],
            ValidationUtilities::customMessages()
        )->validate()['candidate_id'];
        $positions = Position::all();
        $institutions = EducationInstitution::all();
        $institutions = $institutions->map(fn ($institution): string => $institution->name);
        $academies = Academy::all();
        $academies = $academies->map(fn ($academy): string => $academy->name);


        if ($this->filled('academy')) {
            $academy = Academy::where('name', '=', $this->input('academy'))->first();
        } else {
            $academy = Candidate::find($candidateId)->academy()->get()->first();
        }
        $positions = $academy->positions()->get();
        $positionsNames = $positions->map(fn ($position) => $position->name);
        return [
            'name' => 'nullable|Letter_space|min:2',
            'surnname' => 'nullable|Letter_space|min:2',
            'city' => 'nullable|Letter_space|min:2',
            'gender' => 'nullable|' . Rule::in(Candidate::GENDERS),
            'phone' => 'nullable|phone',
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
    /**
     * @param null $keys
     * 
     * @return array
     */
    public function all($keys = null)
    {
        $data = parent::all();
        $data['candidate_id'] = $this->route('candidate_id');
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

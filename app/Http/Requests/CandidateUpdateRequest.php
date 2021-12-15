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
        $candidatesId = Candidate::all()->map(fn ($c) => $c->id);
        $academiesId = Academy::all()->map(fn ($academy): string => $academy->id);
        $candidateId = SupportValidator::make(
            $this->only('candidate_id','academy_id'),
            [
                'candidate_id' => 'required|' . Rule::in($candidatesId),
                'academy_id' => 'nullable|' . Rule::in($academiesId)

            ],
            ValidationUtilities::customMessages()
        )->validate()['candidate_id'];
        $institutions = EducationInstitution::all();
        $institutions = $institutions->map(fn ($institution): string => $institution->id);

        if ($this->filled('academy_id')) {
            $academy = Academy::find($this->input('academy_id'));
        } else {
            $academy = Candidate::find($candidateId)->academy()->get()->first();
        }
        $positions = $academy->positions()->get();
        $positionsId = $positions->map(fn ($position) => $position->id);
        return [
            'name' => 'nullable|Letter_space|min:2',
            'surnname' => 'nullable|Letter_space|min:2',
            'city' => 'nullable|Letter_space|min:2',
            'gender' => 'nullable|' . Rule::in(Candidate::GENDERS),
            'phone' => 'nullable|phone',
            'positions.*' => 'nullable|distinct|' . Rule::in($positionsId),
            'email' => 'nullable|email',
            'application_date' => 'nullable|date_format:Y-m-d',
            'education_institution_id' => 'nullable|' . Rule::in($institutions),
            'status' => 'nullable|' . Rule::in(Candidate::STATUSES),
            'course' => 'nullable|' . Rule::in(Candidate::COURSES),
            'CV' => 'nullable|max:10000|mimes:pdf'
        ];
    }


    /**
     * @param $keys
     * @return array
     */
    public function all($keys = null)
    {
        $data = parent::all();
        $data['candidate_id'] = $this->route('id');
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

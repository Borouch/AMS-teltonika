<?php

namespace App\Http\Requests;

use App\Models\Academy;
use App\Models\Position;
use App\Models\Candidate;
use Illuminate\Validation\Rule;
use App\Utilities\ValidationUtilities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class CandidateFilterRequest extends FormRequest
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
        $positions = Position::all();
        $positionsNames = $positions->map(fn ($position) => $position->name);
        $academies = Academy::all();
        $academies = $academies->map(fn ($academy): string => $academy->name);
        return [
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'positions.*' => 'nullable|distinct|' . Rule::in($positionsNames),
            'academy' => 'nullable|' . Rule::in($academies),
            'course' => 'nullable|' . Rule::in(Candidate::COURSES),
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        ValidationUtilities::failedValidation($validator);
    }
}

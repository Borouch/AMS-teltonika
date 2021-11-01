<?php

namespace App\Utilities;

use App\Models\Academy;
use App\Models\Position;
use App\Models\Candidate;
use Illuminate\Validation\Rule;
use App\Models\EducationInstitution;
use Illuminate\Validation\ValidationException;

class ValidationUtilities
{
    public static function failedValidation($validator)
    {
        $errors = $validator->errors();
        $response = response()->json([
            'error' => 'Invalid data has been sent',
            'details' => $errors->messages(),
        ]);
        throw new ValidationException($validator, $response);
    }
    public static function customMessages()
    {
        return ['positions.*.in' => 'Input position is invalid as it does not belong to the academy to which candidate is applying'];
    }
    public static function CandidateStoreValidationRules($academy)
    {
        $institutions = EducationInstitution::all();
        $institutions = $institutions->map(fn ($institution): string => $institution->name);
        $academies = Academy::all();
        $academies = $academies->map(fn ($academy): string => $academy->name);;
        $positions = Position::all();
        if ($academy != null) {
            $positions = $positions->where('academy', '=', $academy);
        }
        $positionsNames = $positions->map(fn ($position) => $position->name);
        return [
            'name' => 'required|alpha',
            'surnname' => 'required|alpha',
            'gender' => 'required|' . Rule::in(Candidate::GENDERS),
            'email' => 'required|email',
            'application_date' => 'required|date_format:Y-m-d',
            'education_institution' => 'required|' . Rule::in($institutions),
            'city' => 'required|alpha',
            'course' => 'required|' . Rule::in(Candidate::COURSES),
            'academy' => 'required|' . Rule::in($academies),
            'can_manage_data' => 'required|'.Rule::in(['true','false']),
            'positions.*' => 'required|distinct|' . Rule::in($positionsNames),
            'status' => 'nullable|'.Rule::in(Candidate::STATUSES),
            'comment' => 'nullable|alpha_num|1000',
            'phone' => 'nullable|regex:/^([\+]{0,1}[0-9]*)$/|min:9',
            'CV' => 'nullable|max:10000|mimes:pdf',

        ];
    }
}

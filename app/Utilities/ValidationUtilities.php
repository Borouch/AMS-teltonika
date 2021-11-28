<?php

namespace App\Utilities;

use App\Models\Academy;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ValidationUtilities
{
    /**
     * @param \Illuminate\Contracts\Validation\Validator $validator
     *
     * @return void
     */
    public static function failedValidation($validator)
    {
        $errors = $validator->errors();
        $response = response()->json([
            'error' => 'Invalid data has been sent',
            'details' => $errors->messages(),
        ]);
        throw new ValidationException($validator, $response);
    }
    public static function validateAcademy($acId)
    {
        $academies = Academy::all()->map(fn ($academy): string => $academy->id);
        Validator::make(
            ['academy_id' => $acId],
            ['academy_id' => 'required|' . Rule::in($academies)]
        )->validate();
    }
    /**
     * @return array
     */
    public static function customMessages()
    {
        return [
            'positions.*.in' =>
            'Input position is invalid as it does not belong to the academy to which candidate is applying
            or such position id doesn\'t exist',
            'course.in' => 'No course with such id exsits',
            'academy.in' => 'No academy with such id exists',
            'can_manage_data.in' => 'Field must be either 0 or 1',
            'gender.in' => 'No such gender exists',
            'education_institution_id.in' => 'No education institution with such id exists',
            'status.in' => "No such status exist",
            'academies.*.in'=>'No academy with such id exists',
            'name.not_in'=>'Name must be unique',
            'abbreviation.not_in'=>"Abbreviation must be unique",
            'candidate_id.in'=>"No candidate with such id exists",
        ];
    }
}

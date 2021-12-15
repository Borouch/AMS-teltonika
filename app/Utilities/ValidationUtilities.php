<?php

namespace App\Utilities;

use App\Models\Academy;
use App\Models\EducationInstitution;
use App\Models\Position;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ValidationUtilities
{


    /**
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return mixed
     * @throws ValidationException
     */
    public static function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors();
        $response = response()->json([
            'error' => 'Invalid data has been sent',
            'details' => $errors->messages(),
        ]);
        throw new ValidationException($validator, $response);
    }

    /**
     * @param int $acId
     * @return void
     * @throws ValidationException
     */
    public static function validateAcademyId(int $acId)
    {
        $academiesId = Academy::all()->map(fn($academy): string => $academy->id);
        Validator::make(
            ['academy_id' => $acId],
            ['academy_id' => 'required|' . Rule::in($academiesId)],
            self::customMessages()
        )->validate();
    }

    /**
     * @param int $eduId
     * @return void
     * @throws ValidationException
     */
    public static function validateEducationInstitutionId(int $eduId)
    {
        $edusId = EducationInstitution::all()->map(fn($edu): string => $edu->id);
        Validator::make(
            ['education_institution_id' => $eduId],
            ['education_institution_id' => 'required|' . Rule::in($edusId)],
            self::customMessages()
        )->validate();
    }


    /**
     * @param int $posId
     * @return void
     * @throws ValidationException
     */
    public static function validatePositionId(int $posId)
    {
        $positionsId = Position::all()->map(fn($pos): string => $pos->id);
        Validator::make(
            ['position_id' => $posId],
            ['position_id' => 'required|' . Rule::in($positionsId)],
            self::customMessages()
        )->validate();
    }

    /**
     * @return array
     */
    public static function customMessages()
    {
        return [
            'group_by_academy.in' => 'Field must be either 0 or 1',
            'positions.*.in' =>
                'Input position doesn\'t exist it or does not belong to the academy to which candidate is applying',
            'course.in' => 'No course with such id exists',
            'academy.in' => 'No academy with such id exists',
            'can_manage_data.in' => 'Field must be either 0 or 1',
            'gender.in' => 'No such gender exists',
            'status.in' => "No such status exists",
            'academies.*.in' => 'No academy with such id exists',
            'name.not_in' => 'Name must be unique',
            'abbreviation.not_in' => "Abbreviation must be unique",
            'education_institution_id.in' => 'No education institution with such id exists',
            'candidate_id.in' => "No candidate with such id exists",
            'academy_id.in' => "No academy with such id exists",
            'comment_id.in' => "No comment with such id exists",
            'position_id.in' => "No position with such id exists",
            'role_id.in' => 'No role with such id exists',
            'user_id.in' => 'No user with such id exists',
            'roles.*.in' => "No role with such id exists",
            'roles.*.not_in' => "User already has a role with such id",
        ];
    }
}

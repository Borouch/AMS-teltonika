<?php

namespace App\Services;

use App\Utilities\ValidationUtilities;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\EducationInstitution;

class EducationInstitutionService
{

    /**
     * @return void
     */
    public static function storeInitialEdu()
    {
        $institutions = EducationInstitution::EDUCATION_INSTITUTIONS;
        foreach ($institutions as $i) {
            $edu = new EducationInstitution();
            $edu->name = $i;
            $edu->save();
        }
    }

    /**
     * @return JsonResponse
     */
    public static function indexEdu()
    {
        return response()->json(['education_institutions' => EducationInstitution::all()], 200);
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public static function showEdu(int $id)
    {
        ValidationUtilities::validateEducationInstitutionId($id);
        $edu = EducationInstitution::find($id);
        return response()->json(['education_institution' => $edu], 200);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public static function storeEdu(Request $request)
    {
        $edu = new EducationInstitution();
        $edu->name = $request->get('name');
        if ($request->filled('abbreviation')) {
            $edu->abbreviation = $request->get('abbreviation');
        }
        $edu->save();
        return response()->json([
            'message' => '
            Education institution saved successfully',
            'education_institution' => $edu
        ], 200);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public static function updateEdu(Request $request, int $id)
    {
        $hasValue = false;
        $edu = EducationInstitution::find($id);
        if ($request->filled('name')) {
            $hasValue = true;
            $edu->update(['name' => $request->input('name')]);
        }
        if ($request->filled('abbreviation')) {
            $hasValue = true;
            $edu->update(['abbreviation' => $request->input('abbreviation')]);
        }
        if (!$hasValue) {
            throw new Exception('All valid input fields are empty', 406);
        }
        $edu = EducationInstitution::find($id);
        return response()->json([
            'message' => 'Education institution updated successfully',
            'education_institution' => $edu
        ], 200);
    }
}

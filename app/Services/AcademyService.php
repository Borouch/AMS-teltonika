<?php

namespace App\Services;

use App\Utilities\ValidationUtilities;
use App\Models\Academy;
use Illuminate\Http\Request;
use  \Illuminate\Http\JsonResponse;

class AcademyService
{

    /**
     * @return JsonResponse
     */
    public static function indexAcademies()
    {
        return response()->json(['academies' => Academy::all()], 200);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public static function showAcademy(int $id)
    {
        ValidationUtilities::validateAcademyId($id);
        $academy = Academy::find($id);
        return response()->json(['academy' => $academy], 200);
    }

    public static function updateAcademy(Request $request, int $id)
    {
        $hasValue = false;
        $academy = Academy::find($id);
        if ($request->filled('name')) {
            $hasValue = true;
            $academy->update(['name' => $request->input('name')]);
        }
        if ($request->filled('abbreviation')) {
            $academy->update(['abbreviation' => $request->input('abbreviation')]);
        }
        if (!$hasValue) {
            throw new Exception('All valid input fields are empty', 406);
        }
        $academy = Academy::find($id);
        return response()->json(['message' => 'Academy updated successfully', 'academy' => $academy]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public static function storeAcademy(Request $request)
    {
        $academy = new Academy();
        $academy->name = $request->input('name');
        if ($request->filled('abbreviation')) {
            $academy->abbreviation = $request->input('abbreviation');
        }

        $academy->save();

        return response()->json(['message' => 'Academy saved successfully', 'academy' => $academy]);
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public static function showAcademyPositions(int $id)
    {
        ValidationUtilities::validateAcademyId($id);
        $academy = Academy::find($id);
        $academy->positions = $academy->positions->makeHidden('academies')->toArray();
        return response()->json(['academy' => $academy], 200);
    }

}

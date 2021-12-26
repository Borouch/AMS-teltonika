<?php

namespace App\Services;

use App\Utilities\ValidationUtilities;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Models\AcademiesPositions;

class PositionService
{


    /**
     * @return JsonResponse
     */
    public static function indexPositions()
    {
        return response()->json(['positions' => Position::with('academies')->get()], 200);
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public static function showPosition(int $id)
    {
        ValidationUtilities::validatePositionId($id);
        $position = Position::find($id);
        return response()->json(['position' => $position], 200);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public static function storePosition(Request $request)
    {
        $position = new Position();
        $position->name = $request->input('name');
        if ($request->filled('abbreviation')) {
            $position->abbreviation = $request->input('abbreviation');
        }
        $position->save();
        $academiesId = $request->input('academies');
        self::storeAcademyPositions($academiesId, $position->id);

        $position = Position::find($position->id);
        $position->academies = $position->academies()->get();
        return response()->json(['message' => 'Position saved successfully', 'position' => $position], 200);
    }


    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public static function updatePosition(Request $request, int $id)
    {
        $position = Position::find($id);
        $hasValue = false;
        if ($request->filled('name')) {
            $hasValue = true;
            $position->update(['name' => $request->input('name')]);
        }
        if ($request->filled('abbreviation')) {
            $hasValue = true;
            $position->update(['abbreviation' => $request->input('abbreviation')]);
        }
        if ($request->filled('academies')) {
            $hasValue = true;
            AcademiesPositions::where('position_id', '=', $id)->delete();
            $academiesId = $request->input('academies');
            self::storeAcademyPositions($academiesId, $id);
        }

        if (!$hasValue) {
            throw new Exception('All valid input fields are empty', 406);
        }
        $position = Position::find($position->id);
        $position->academies = $position->academies()->get();
        return response()->json(['message' => 'Position updated successfully', 'position' => $position], 200);
    }

    /**
     * @param array $academiesId
     * @param int $posId
     * @return void
     */
    public static function storeAcademyPositions(array $academiesId, int $posId)
    {
        foreach ($academiesId as $acId) {
            $acPosition = new AcademiesPositions();
            $acPosition->academy_id = $acId;
            $acPosition->position_id = $posId;
            $acPosition->save();
        }
    }
}

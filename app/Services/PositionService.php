<?php

namespace App\Services;

use Throwable;
use App\Models\Academy;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Models\AcademiesPositions;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PositionService
{

        /**
     * @param int|null $id
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public static function indexPositions( $id)
    {
        if ($id != null) {
            try {
                $position= Position::findOrFail($id);
            } catch (Throwable $e) {
                //Rethrown in order to be catched by handler
                throw new NotFoundHttpException(message: "Position with such id does not exist", code: 404);
            }
            return response()->json(['position' => $position], 200);
        }else 
        {
            return response()->json(['positions' => Position::all()], 200);
        }
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function storePosition(Request $request)
    {
        $position = new Position();
        $position->name = $request->input('name');
        if ($request->filled('abbreviation')) {
            $position->abbreviation = $request->input('abbreviation');
        }
        $position->save();
        //Reassigned in order to fetch id
        $position = Position::all()->last();
        $academies = $request->input('academies');
        foreach ($academies as $acId) {
            $acPosition = new AcademiesPositions();
            $acPosition->academy_id = $acId;
            $acPosition->position_id = $position->id;
            $acPosition->save();
        }
        //Reassigned in order to display associated academies
        $position = Position::all()->last();
        return response()->json(['message' => 'Position stored successfully', 'position' => $position], 200);
    }
}

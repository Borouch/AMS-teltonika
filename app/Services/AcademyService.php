<?php

namespace App\Services;

use Throwable;
use App\Models\Academy;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AcademyService
{
    /**
     * @param int|null $id
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public static function indexAcademy( $id)
    {
        if ($id != null) {
            $academy = self::findAcademyOrFail($id);
            return response()->json(['academy' => $academy], 200);
        }else 
        {
            return response()->json(['academies' => Academy::all()], 200);
        }
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
     */
    public static function getAcademyWithPositions($id)
    {
        $academy = self::findAcademyOrFail($id);
        $academy->positions = $academy->positions->makeHidden('academies')->toArray();
        return response()->json(['academy' => $academy], 200);
    }

    /**
     * @param int $id
     * 
     * @return Academy
     */
    public static function findAcademyOrFail($id)
    {
        try {
            $academy= Academy::findOrFail($id);
        } catch (Throwable $e) {
            //Rethrown in order to be catched by handler
            throw new NotFoundHttpException(message: "Academy with such id does not exist", code: 404);
        }
        return $academy;
    }
}

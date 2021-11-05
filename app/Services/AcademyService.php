<?php

namespace App\Services;

use Throwable;
use App\Models\Academy;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class AcademyService
{
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
    public static function getAcademyWithPositions($id)
    {
        try
        {
            $academy = Academy::with('positions')->findOrFail($id);
        }catch(Throwable $e)
        {
            //Rethrown in order to be catched by handler
             throw new NotFoundHttpException(message: $e->getMessage(),code: 404);
        }
        $academy->positions = $academy->positions->makeHidden('academies')->toArray();
         return response()->json(['academy'=>$academy],200);
    }
}

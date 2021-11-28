<?php

namespace App\Services;

use Throwable;
use Illuminate\Http\Request;
use App\Models\EducationInstitution;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EducationInstitutionService
{
    /**
     * @param int|null $id
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public static function indexEdu($id)
    {
        if ($id != null) {
            try {
                $edu = EducationInstitution::findOrFail($id);
            } catch (Throwable $e) {
                //Rethrown in order to be catched by handler
                throw new NotFoundHttpException(
                    message: "Education institution with such id does not exist",
                    code: 404
                );
            }
            return response()->json(['education_institution' => $edu], 200);
        } else {
            return response()->json(['education_institutions' => EducationInstitution::all()], 200);
        }
    }

    /**
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public static function storeEdu(Request $request)
    {
        $edu = new EducationInstitution();
        $edu->name = $request->get('name');
        if ($request->filled('abbreviation')) {

            $edu->abbreviation = $request->get('abbreviation');
        }
        $edu->save();
        return response()->json(['message' => '
            Education institution saved successfully', 'education_institution' => $edu], 200);
    }
}

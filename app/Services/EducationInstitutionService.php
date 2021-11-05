<?php
namespace App\Services;

use App\Models\EducationInstitution;

class EducationInstitutionService
{
    public static function storeEducationInstitution($request)
    {
        $edu = new EducationInstitution();
        $edu->name=$request->get('name');
        $edu->save();
        return response()->json(['message'=>'Education institution saved successfully','education_institution'=>$edu],200);
    }
}
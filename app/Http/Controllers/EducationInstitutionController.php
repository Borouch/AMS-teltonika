<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EducationInstitution;
use App\Services\EducationInstitutionService;
use App\Http\Requests\EducationInstitutionStoreRequest;

class EducationInstitutionController extends Controller
{
    public function store(EducationInstitutionStoreRequest $request)
    {
        return EducationInstitutionService::storeEducationInstitution($request);
    }
    public function index(Request $request)
    {
        return Response()->json(['education_institutions'=>EducationInstitution::all()]);
    }
}

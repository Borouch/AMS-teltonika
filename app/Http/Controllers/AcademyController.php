<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Academy;
use Illuminate\Http\Request;
use App\Services\AcademyService;
use App\Http\Requests\AcademyStoreRequest;

class AcademyController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(['academies'=> Academy::all()],200);
    }
    public function store(AcademyStoreRequest $request)
    {
        $request->validated();
        return AcademyService::storeAcademy($request);
    }
}

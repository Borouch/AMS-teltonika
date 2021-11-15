<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Academy;
use Illuminate\Http\Request;
use App\Services\AcademyService;
use App\Http\Requests\AcademyStoreRequest;
use App\Services\AcademyStatisticsService;

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
    public function academyWithPositions(Request $request, $id)
    {
        return AcademyService::getAcademyWithPositions($id);
    }
    public function statByPositions(Request $request, $academyId = null)
    {
        return AcademyStatisticsService::getStatByPositions($academyId);
    }
    public function statByEducationInstitutions(Request $request, $academyId = null)
    {
        return AcademyStatisticsService::getStatByEducationInstitutions($academyId);
    }
    public function statByCourses(Request $request, $academyId = null)
    {
        return AcademyStatisticsService::getStatByCourses($academyId);
    }
    public function statByGenders(Request $request, $academyId = null)
    {
        return AcademyStatisticsService::getStatByGenders($academyId);
    }
    public function statByStatuses(Request $request, $academyId = null)
    {
        return AcademyStatisticsService::getStatByStatuses($academyId);
    }
    public function statByApplicationDate(Request $request,$academyId = null)
    {
        return AcademyStatisticsService::getStatByApplicationDate($academyId);
    }
    public function statByMonth(Request $request, $month,$academyId = null)
    {
        return AcademyStatisticsService::getStatByMonth($month,$academyId);
    }
}

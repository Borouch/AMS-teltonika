<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AcademyService;
use App\Http\Requests\AcademyStoreRequest;
use App\Services\AcademyStatisticsService;

class AcademyController extends Controller
{
    /**
     * @param Request $request
     * @param null|int $id
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request,$id = null)
    {
        return AcademyService::indexAcademy($id);
    }

    /**
     * @param AcademyStoreRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AcademyStoreRequest $request)
    {
        $request->validated();
        return AcademyService::storeAcademy($request);
    }

    /**
     * @param Request $request
     * @param int $id
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function academyWithPositions(Request $request, $id)
    {
        return AcademyService::getAcademyWithPositions($id);
    }

    /**
     * @param Request $request
     * @param null|int $academyId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function statByPositions(Request $request, $academyId = null)
    {
        return AcademyStatisticsService::getStatByPositions($academyId);
    }
    
    /**
     * @param Request $request
     * @param int|null $academyId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function statByEducationInstitutions(Request $request, $academyId = null)
    {
        return AcademyStatisticsService::getStatByEducationInstitutions($academyId);
    }

    /**
     * @param Request $request
     * @param null|int $academyId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function statByCourses(Request $request, $academyId = null)
    {
        return AcademyStatisticsService::getStatByCourses($academyId);
    }

    /**
     * @param Request $request
     * @param null $academyId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function statByGenders(Request $request, $academyId = null)
    {
        return AcademyStatisticsService::getStatByGenders($academyId);
    }

    /**
     * @param Request $request
     * @param null|int $academyId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function statByStatuses(Request $request, $academyId = null)
    {
        return AcademyStatisticsService::getStatByStatuses($academyId);
    }

    /**
     * @param Request $request
     * @param null|int $academyId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function statByApplicationDate(Request $request, $academyId = null)
    {
        return AcademyStatisticsService::getStatByApplicationDate($academyId);
    }

    /**
     * @param Request $request
     * @param mixed $month
     * @param null|int $academyId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function statByMonth(Request $request, $month, $academyId = null)
    {
        return AcademyStatisticsService::getStatByMonth($month, $academyId);
    }
}

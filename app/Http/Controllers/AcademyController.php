<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcademyUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\AcademyService;
use App\Http\Requests\AcademyStoreRequest;
use App\Http\Requests\statByMonthRequest;
use App\Services\AcademyStatisticService;

class AcademyController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        return AcademyService::indexAcademies();
    }

    /**
     * @param Request $request
     * @param int $academyId
     * @return JsonResponse
     */
    public function show(Request $request, int $academyId)
    {
        return AcademyService::showAcademy($academyId);
    }

    /**
     * @param AcademyUpdateRequest $request
     * @param int $academyId
     * @return JsonResponse
     */
    public function update(AcademyUpdateRequest $request, int $academyId)
    {
        return AcademyService::updateAcademy($request, $academyId);
    }

    /**
     * @param AcademyStoreRequest $request
     *
     * @return JsonResponse
     */
    public function store(AcademyStoreRequest $request)
    {
        return AcademyService::storeAcademy($request);
    }

    /**
     * @param Request $request
     * @param int $academyId
     *
     * @return JsonResponse
     */
    public function showAcademyPositions(Request $request, int $academyId)
    {
        return AcademyService::showAcademyPositions($academyId);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function indexStatByPosition(Request $request)
    {
        $stat = AcademyStatisticService::getIndexStatByPosition();
        return response()->json($stat);
    }


    /**
     * @param Request $request
     * @param int $academyId
     *
     * @return JsonResponse
     */
    public function showStatByPosition(Request $request, int $academyId)
    {
        $stat = AcademyStatisticService::getShowStatByPosition($academyId);
        return response()->json($stat);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function indexStatByEducationInstitution(Request $request)
    {
        $stat = AcademyStatisticService::getIndexStatByEducationInstitution();
        return response()->json($stat);
    }


    /**
     * @param Request $request
     * @param int $academyId
     *
     * @return JsonResponse
     */
    public function showStatByEducationInstitution(Request $request, int $academyId)
    {
        $stat = AcademyStatisticService::getShowStatByEducationInstitution($academyId);
        return response()->json($stat);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function indexStatByCourse(Request $request)
    {
        $stat = AcademyStatisticService::getIndexStatByCourse();
        return response()->json($stat);
    }

    /**
     * @param Request $request
     * @param int $academyId
     *
     * @return JsonResponse
     */
    public function showStatByCourse(Request $request, int $academyId)
    {
        $stat = AcademyStatisticService::getShowStatByCourse($academyId);
        return response()->json($stat);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function indexStatByGender(Request $request)
    {
        $stat = AcademyStatisticService::getIndexStatByGender();
        return response()->json($stat);
    }

    /**
     * @param Request $request
     * @param int $academyId
     *
     * @return JsonResponse
     */
    public function showStatByGender(Request $request, int $academyId)
    {
        $stat = AcademyStatisticService::getShowStatByGender($academyId);
        return response()->json($stat);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function indexStatByStatus(Request $request)
    {
        $stat = AcademyStatisticService::getIndexStatByStatus();
        return response()->json($stat);
    }

    /**
     * @param Request $request
     * @param int $academyId
     *
     * @return JsonResponse
     */
    public function showStatByStatus(Request $request, int $academyId)
    {
        $stat = AcademyStatisticService::getShowStatByStatus($academyId);
        return response()->json($stat);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function indexStatByApplicationDate(Request $request)
    {
        $stat = AcademyStatisticService::getIndexStatByApplicationDate();
        return response()->json($stat);
    }

    /**
     * @param Request $request
     * @param int $academyId
     *
     * @return JsonResponse
     */
    public function showStatByApplicationDate(Request $request, int $academyId)
    {
        $stat = AcademyStatisticService::getShowStatByApplicationDate($academyId);
        return response()->json($stat);
    }


    /**
     * @param statByMonthRequest $request
     * @param int $monthNumber
     * @return JsonResponse
     */
    public function indexStatByMonth(StatByMonthRequest $request, int $monthNumber)
    {
        $stat = AcademyStatisticService::getIndexStatByMonth($monthNumber);
        return response()->json($stat);
    }


    /**
     * @param statByMonthRequest $request
     * @param int $academyId
     * @param int $monthNumber
     * @return JsonResponse
     */
    public function showStatByMonth(StatByMonthRequest $request, int $academyId, int $monthNumber)
    {
        $stat = AcademyStatisticService::getShowStatByMonth($academyId, $monthNumber);
        return response()->json($stat);
    }

}

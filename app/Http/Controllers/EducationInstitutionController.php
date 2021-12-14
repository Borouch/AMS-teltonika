<?php

namespace App\Http\Controllers;

use App\Http\Requests\EducationInstitutionUpdateRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\EducationInstitutionService;
use App\Http\Requests\EducationInstitutionStoreRequest;
use Illuminate\Validation\ValidationException;

class EducationInstitutionController extends Controller
{
    /**
     * @param EducationInstitutionStoreRequest $request
     * @return JsonResponse
     */
    public function store(EducationInstitutionStoreRequest $request)
    {
        return EducationInstitutionService::storeEdu($request);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        return EducationInstitutionService::indexEdu();
    }

    /**
     * @param Request $request
     * @param int $eduId
     * @return JsonResponse
     * @throws ValidationException
     */
    public function show(Request $request,int $eduId)
    {
        return EducationInstitutionService::showEdu($eduId);
    }


    /**
     * @param EducationInstitutionUpdateRequest $request
     * @param int $eduid
     * @return JsonResponse
     * @throws Exception
     */
    public function update(EducationInstitutionUpdateRequest $request,int $eduid)
    {
        return EducationInstitutionService::updateEdu($request,$eduid);
    }
}

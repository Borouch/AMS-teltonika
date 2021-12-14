<?php

namespace App\Http\Controllers;

use App\Http\Requests\PositionUpdateRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\PositionService;
use App\Http\Requests\PositionStoreRequest;
use Illuminate\Validation\ValidationException;

class PositionController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        return PositionService::indexPositions();
    }

    /**
     * @param Request $request
     * @param int $positionId
     * @return JsonResponse
     * @throws ValidationException
     */
    public function show(Request $request, int $positionId)
    {
        return PositionService::showPosition($positionId);
    }

    /**
     * @param PositionStoreRequest $request
     *
     * @return JsonResponse
     */
    public function store(PositionStoreRequest $request)
    {
        return PositionService::storePosition($request);
    }

    /**
     * @param PositionUpdateRequest $request
     * @param int $positionId
     * @return JsonResponse
     * @throws Exception
     */
    public function update(PositionUpdateRequest $request,int $positionId)
    {
        return PositionService::updatePosition($request,$positionId);
    }
}

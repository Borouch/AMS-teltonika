<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use App\Services\PositionService;
use App\Http\Requests\StorePositionRequest;

class PositionController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request,$id = null)
    {
        return PositionService::indexPositions($id);
    }
    
    /**
     * @param StorePositionRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePositionRequest $request)
    {
        return PositionService::storePosition($request);
    }
}

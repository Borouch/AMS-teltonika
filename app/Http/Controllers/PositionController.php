<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use App\Services\PositionService;
use App\Http\Requests\StorePositionRequest;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([Position::all(),200]);
    }
    public function store(StorePositionRequest $request)
    {
        return PositionService::storePosition($request);
    }
}

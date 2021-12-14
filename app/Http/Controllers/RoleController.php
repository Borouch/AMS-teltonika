<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleShowRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\RoleService;
use App\Http\Requests\RoleIndexRequest;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;

class RoleController extends Controller
{


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        return  RoleService::indexRoles();
    }

    /**
     * @param RoleShowRequest $request
     * @param int $roleId
     * @return JsonResponse
     */
    public function show (RoleShowRequest $request, int $roleId)
    {
        return  RoleService::showRole($roleId);
    }

    /**
     * @param RoleStoreRequest $request
     * @return JsonResponse
     */
    public function store(RoleStoreRequest $request)
    {
        return RoleService::storeRole($request);
    }

    /**
     * @param RoleUpdateRequest $request
     * @param int $roleId
     * @return JsonResponse
     */
    public function update(RoleUpdateRequest $request, int $roleId)
    {
        return RoleService::updateRole($request,$roleId);
    }

}

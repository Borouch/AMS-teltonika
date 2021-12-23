<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignRoleRequest;
use App\Http\Requests\RemoveRoleRequest;
use App\Http\Requests\RoleShowRequest;
use App\Services\UserService;
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
        return RoleService::indexRoles();
    }

    /**
     * @param RoleShowRequest $request
     * @param int $roleId
     * @return JsonResponse
     */
    public function show(RoleShowRequest $request, int $roleId)
    {
        return RoleService::showRole($roleId);
    }

    /**
     * @param AssignRoleRequest $request
     * @param int $userId
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function assign(AssignRoleRequest $request, int $userId)
    {
        return RoleService::assignRoles($request, $userId);
    }


    /**
     * @param RemoveRoleRequest $request
     * @param int $userId
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function remove(RemoveRoleRequest $request, int $userId)
    {
        return RoleService::removeRoles($request, $userId);
    }
}

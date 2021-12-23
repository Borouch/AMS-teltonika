<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignPermissionRequest;
use App\Http\Requests\PermissionShowRequest;
use App\Http\Requests\RemovePermissionRequest;
use App\Services\PermissionService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        return PermissionService::indexPermissions();
    }

    /**
     * @param PermissionShowRequest $request
     * @param int $roleId
     * @return JsonResponse
     */
    public function show(PermissionShowRequest $request, int $roleId)
    {
        return PermissionService::showPermission($roleId);
    }

    /**
     * @param AssignPermissionRequest $request
     * @param int $userId
     * @return JsonResponse
     * @throws Exception
     */
    public function assign(AssignPermissionRequest $request, int $userId)
    {
        return PermissionService::assignPermissions($request, $userId);
    }


    /**
     * @param RemovePermissionRequest $request
     * @param int $userId
     * @return JsonResponse
     * @throws Exception
     */
    public function remove(RemovePermissionRequest $request, int $userId)
    {
        return PermissionService::removePermissions($request, $userId);
    }

}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserShowRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\UserIndexRequest;
use App\Http\Requests\AssignRoleRequest;
use App\Http\Requests\RemoveRoleRequest;

class UserController extends Controller
{


    public function index(Request $request)
    {
        return UserService::indexUsers();
    }

    public function show(UserShowRequest $request, $userId)
    {
        return UserService::showUsers($userId);
    }


    /**
     * @param AssignRoleRequest $request
     * @param int $userId
     *
     * @return JsonResponse
     */
    public function assignRoles(AssignRoleRequest $request, $userId)
    {
        return UserService::assignUserRoles($request, $userId);
    }


    /**
     * @param RemoveRoleRequest $request
     * @param mixed $userId
     *
     * @return JsonResponse
     */
    public function removeRoles(RemoveRoleRequest $request, $userId)
    {
        return UserService::removeUserRoles($request, $userId);
    }
}

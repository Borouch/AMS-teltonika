<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\UserIndexRequest;
use App\Http\Requests\AssignRoleRequest;
use App\Http\Requests\RemoveRoleRequest;

class UserController extends Controller
{

    /**
     * @param UserIndexRequest $request
     * @param int|null $id=null
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(UserIndexRequest $request, $userId=null)
    {
        return UserService::indexUsers($userId);
    }


    /**
     * @param AssignRoleRequest $request
     * @param int $userId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignRoles(AssignRoleRequest $request, $userId)
    {
        return UserService::assignUserRoles($request, $userId);
    }


    /**
     * @param RemoveRoleRequest $request
     * @param mixed $userId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeRoles(RemoveRoleRequest $request, $userId)
    {
        return UserService::removeUserRoles($request, $userId);
    }
}

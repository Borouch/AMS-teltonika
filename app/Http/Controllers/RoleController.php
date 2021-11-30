<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RoleService;
use App\Http\Requests\RoleIndexRequest;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;

class RoleController extends Controller
{

    public function index(RoleIndexRequest $request,$roleId = null)
    {
        // var_dump(json_encode($request->user()->getRoleNames()));
        return  RoleService::indexRoles($roleId);
    }

    public function store(RoleStoreRequest $request)
    {
        return RoleService::storeRole($request);
    }

    public function update(RoleUpdateRequest $request,$roleId)
    {
        return RoleService::updateRole($request,$roleId);
    }

}

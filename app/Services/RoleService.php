<?php

namespace App\Services;

use Throwable;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RoleService
{

    public static function validationMessages()
    {
        return [
            'role_id.in'=> 'Role with such id does not exist',
            'name.not_in'=>'A name with such value already exists'
        ];
    }

    /**
     * @return void
     */
    public static function storeInitialRoles()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'read']);
        Role::create(['name' => 'update']);
        Role::create(['name' => 'write']);
        Role::create(['name' => 'delete']);
    }

    /**
     * @param int|null $roleId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public static function indexRoles($roleId)
    {
        
        if ($roleId != null) {
            $role = Role::find($roleId);
            return response()->json(['role' => $role], 200);
        }
        return response()->json(['roles' => Role::all()], 200);
    }

    /**
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public static function storeRole(Request $request)
    {
        $role = Role::create($request->only(['name', 'guard_name']));
        return response()->json(['message' => "Role created successfully", 'role' => $role], 200);
    }


    /**
     * @param Request $request
     * @param mixed $roleId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public static function updateRole(Request $request, $roleId)
    {
        $role = Role::find($roleId);
        if ($request->filled('name')) {
            $role->update($request->only(['name']));
        }
        if ($request->filled('guard_name')) {
            $role->update($request->only(['guard_name']));
        }
        return response()->json(['message' => "Role updated successfully", 'role' => $role], 200);
    }
}

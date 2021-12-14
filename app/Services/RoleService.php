<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Throwable;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RoleService
{


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
     * @return JsonResponse
     */
    public static function indexRoles()
    {
        return response()->json(['roles' => Role::all()], 200);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public static function showRole(int $id)
    {

        return response()->json(['role' => Role::find($id)], 200);

    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public static function storeRole(Request $request)
    {
        $role = Role::create($request->only(['name', 'guard_name']));
        return response()->json(['message' => "Role created successfully", 'role' => $role], 200);
    }


    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public static function updateRole(Request $request, int $id)
    {
        $role = Role::find($id);
        if ($request->filled('name')) {
            $role->update($request->only(['name']));
        }
        if ($request->filled('guard_name')) {
            $role->update($request->only(['guard_name']));
        }
        return response()->json(['message' => "Role updated successfully", 'role' => $role], 200);
    }
}

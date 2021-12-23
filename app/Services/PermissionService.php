<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionService
{

    /**
     * @return void
     */
    public static function storeInitialPermissions()
    {
        Permission::create(['name' => 'candidate']);
        Permission::create(['name' => 'academy']);
        Permission::create(['name' => 'education_institution']);
        Permission::create(['name' => 'position']);
        Permission::create(['name' => 'statistic']);
    }

    /**
     * @return JsonResponse
     */
    public static function indexPermissions()
    {
        return Response()->json(['Permissions' => Permission::all()]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public static function showPermission(int $id)
    {
        $permission = Permission::find($id);
        return Response()->json(['Permission' => $permission]);
    }

    /**
     * @param Request $request
     * @param int $userId
     * @return JsonResponse
     * @throws Exception
     */
    public static function assignPermissions(Request $request, int $userId)
    {
        $user = User::find($userId);
        if ($request->isNotFilled('permissions')) {
            throw new Exception('All valid input fields are empty', 406);
        }
        $permissions = $request->permissions;
        foreach ($permissions as $permissionId) {
            $permission = Permission::find($permissionId);
            $user->givePermissionTo($permission->name);
        }
        $user = User::find($userId);

        return response()->json([
            'message' => "Permission(s) has been successfully assigned to user",
            'user' => $user,
        ]);
    }

    /**
     * @param Request $request
     * @param int $userId
     * @return JsonResponse
     * @throws Exception
     */
    public static function removePermissions(Request $request, int $userId)
    {
        $user = User::find($userId);
        if ($request->isNotFilled('permissions')) {
            throw new Exception('All valid input fields are empty', 406);
        }
        $permissions = $request->permissions;
        foreach ($permissions as $permissionId) {
            $permission = Permission::find($permissionId);
            $user->revokePermissionTo($permission->name);
        }
        $user = User::find($userId);
        return response()->json([
            'message' => "Permission(s) has been successfully removed from user",
            'user' => $user,
        ]);
    }


}

<?php

namespace App\Services;

use App\Models\Role;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Http\Request;

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
        Role::create(['name' => 'create']);
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
     * @param int $userId
     * @return JsonResponse
     * @throws Exception
     */
    public static function assignRoles(Request $request, int $userId)
    {
        $user = User::find($userId);
        if ($request->isNotFilled('roles')) {
            throw new Exception('All valid input fields are empty', 406);
        }
        $roles = $request->roles;
        foreach ($roles as $roleId) {
            $role = Role::find($roleId);
            $user->assignRole($role->name);
        }
        $user = User::find($userId);
        return response()->json([
            'message' => "Role(s) has been successfully assigned to user",
            'user' => $user,
        ]);
    }


    /**
     * @param Request $request
     * @param int $userId
     * @return JsonResponse
     * @throws Exception
     */
    public static function removeRoles(Request $request, int $userId)
    {
        $user = User::find($userId);

        if ($request->isNotFilled('roles')) {
            throw new Exception('All valid input fields are empty', 406);
        }
        $roles = $request->roles;
        foreach ($roles as $roleId) {
            $role = Role::find($roleId);
            $user->removeRole($role->name);
        }
        $user = User::find($userId);
        return response()->json([
            'message' => "Role(s) has been successfully removed from user",
            'user' => $user,
        ]);
    }
}

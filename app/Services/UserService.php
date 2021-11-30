<?php

namespace App\Services;


use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;

class UserService
{
    public static function validationMessages()
    {
        return [
            'roles.*.in' => "Role with such id does not exist",
            'roles.*.not_in' => "User already has role with such id",
            'user_id.in' => "user with such id does not exist"
        ];
    }
    public static function storeInitialAdminUser()
    {

        $data = Validator::make(
            [
                'email' => config('app.admin_email_address'),
                'password' => config('app.admin_password')
            ],
            [
                'email' => 'required|email',
                'password' => 'required'
            ],
            [
                'email.email' => "Admin email is incorrect format"
            ]
        )->validate();
        $data['password'] = bcrypt($data['password']);
        $admin = User::Create($data);
        $admin->assignRole('admin');
        
    }

    /**
     * @param int|null $userId
     * 
     * @return  \Illuminate\Http\JsonResponse
     */
    public static function indexUsers($userId)
    {
        if ($userId != null) {
            $user = User::find($userId);
            return response()->json(['user' => $user]);
        }
        return response()->json(['users' => User::all()]);
    }




    

    /**
     * @param Request $request
     * @param int $userId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public static function assignUserRoles(Request $request, $userId)
    {

        $user = User::find($userId);
        $roles = $request->roles;
        foreach ($roles as $roleId)
        {
            $role=Role::find($roleId);
            $user->assignRole($role->name);
        }
        return response()->json([
            'message' => "Role(s) has been successfully assigned to user",
            'user' => $user,
        ]);
    }

    /**
     * @param Request $request
     * @param int $userId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public static function removeUserRoles(Request $request, $userId)
    {
        $user = User::find($userId);
        $roles = $request->roles;
        foreach ($roles as $roleId)
        {
            $role = Role::find($roleId);
            $user->removeRole($role->name);
        }
        return response()->json([
            'message' => "Role(s) has been successfully removed from user",
            'user' => $user,
        ]);
    }
}

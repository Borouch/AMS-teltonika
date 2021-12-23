<?php

namespace App\Services;


use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;

class UserService
{
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
                'email.email' => "Admin email is in incorrect format"
            ]
        )->validate();
        $data['password'] = bcrypt($data['password']);
        $admin = User::Create($data);
        $admin->assignRole('admin');
    }

    /**
     * @return JsonResponse
     */
    public static function indexUsers()
    {
        return response()->json(['users' => User::all()]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public static function showUsers(int $id)
    {
        return response()->json(['User' => User::find($id)]);
    }


}

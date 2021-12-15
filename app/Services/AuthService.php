<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Notifications\RegistrationNotification;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public static function register(Request $request)
    {
        $user = new User();
        $user->email = $request->email;
        $pass = Str::random(10);
        $user->password = bcrypt($pass);
        $user->save();
        $token = Str::random(64);
        PasswordReset::create(['email' => $user->email, 'token' => bcrypt($token)]);
        $url = config('app.url') . '/reset_password?token=' . $token;
        $user->notify(new RegistrationNotification(['email' => $user->email, 'password' => $pass], $url));

        return response()->json([
            "message" =>
                "Registration successful, an email has been sent to user email address with its credentials",
        ]);
    }

    public static function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                throw new JWTException('Invalid Credentials');
            }
        } catch (JWTException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
        return response()->json(["message" => "Login was successful", 'token' => $token]);
    }
}

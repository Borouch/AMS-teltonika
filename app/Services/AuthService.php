<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use App\Notifications\RegistrationNotification;

class AuthService
{
    /**
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
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
        $user = User::where('email', $request->email)->first();
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $auth = $request->user();
            $token =  $auth->createToken('API token')->plainTextToken;

            return response()->json(["message" => "Login was successful", 'token' => $token]);
        } else {
            throw new AuthenticationException('User with these credentials does not exist');
        }
    }
}

<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user->password = $pass;
        $user->save();

        return response()->json([
            "message" => "Registration successful, you have been assigned a password, to change it reset  your password",
            'credentials' => ['email' => $user->email, 'password' => $pass]
        ]);
    }

    public static function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $auth = $request->user();
            $token =  $auth->createToken('LaravelSanctumAuth')->plainTextToken;

            return response()->json(["message" => "Login was successful", 'token' => $token]);
        } else {
            throw new AuthenticationException('No user exist with these credentials');
        }
    }
}
//Validation rule and input type fixes, merge filter,search,get candidates routes, user auth setup and password reset
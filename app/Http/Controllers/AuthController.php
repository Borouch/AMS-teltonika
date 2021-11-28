<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    /**
     * @param LoginRequest $request
     * 
     * @return [type]
     */
    public function login(LoginRequest $request)
    {
       return AuthService::login($request);
    }

    /**
     * @param RegisterRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {

        return AuthService::register($request);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\NewPasswordService;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\SendResetLinkRequest;

class NewPasswordController extends Controller
{
    /**
     * @param SendResetLinkRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLink(SendResetLinkRequest $request)
    {

       return NewPasswordService::sendPasswordResetLink($request);
       
    }

    /**
     * @param PasswordResetRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(PasswordResetRequest $request)
    {
       return NewPasswordService::resetPassword($request);

    }
}

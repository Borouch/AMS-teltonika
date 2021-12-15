<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\NewPasswordService;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\ResetLinkRequest;
use Exception;
use Illuminate\Http\JsonResponse;

class NewPasswordController extends Controller
{

    /**
     * @param ResetLinkRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function resetLink(ResetLinkRequest $request)
    {

       return NewPasswordService::sendPasswordResetLink($request);

    }

    /**
     * @param PasswordResetRequest $request
     *
     * @return JsonResponse
     */
    public function reset(PasswordResetRequest $request)
    {
       return NewPasswordService::resetPassword($request);

    }
}

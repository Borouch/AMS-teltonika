<?php

namespace App\Services;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class NewPasswordService
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public static function resetPassword(Request $request)
    {

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => bcrypt($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );
        if ($status != Password::PASSWORD_RESET) {
            throw new Exception(__($status), 500);
        }
        return response()->json([
            'message' => 'Password reset successfully'
        ]);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public static function sendPasswordResetLink(Request $request)
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );
        if ($status != Password::RESET_LINK_SENT) {
            throw new Exception(__($status), 500);
        }
        return response()->json([
            'message' => __($status)
        ], 200);
    }
}

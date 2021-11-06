<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Response;
use App\Traits\Api\PasswordTrait;

class PasswordController extends Controller
{
    use PasswordTrait;
    /**
     * Handle an incoming password reset link request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function forgotPassword(Request $request)
    {
        $this->validateEmail($request);

        return ($status = $this->getResetLinkStatus($request)) == Password::RESET_LINK_SENT
            ? response(['message' => 'We have emailed your password reset link!'], Response::HTTP_ACCEPTED)
            : response(['email' => __($status)], Response::HTTP_BAD_REQUEST);
    }

    public function resetPassword(Request $request)
    {
        $this->validResetPassword($request);

        // If the password was successfully reset, it will return a json to frontend.
        // If there is an error, it will send a json error message back.
        return ($status = $this->getResetStatus($request)) == Password::PASSWORD_RESET
            ? response(['message' => 'Congrats, your password reset is successful.'], Response::HTTP_ACCEPTED)
            : response(['email' => __($status)], Response::HTTP_BAD_REQUEST);
    }
}

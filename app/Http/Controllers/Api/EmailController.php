<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\Api\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;

class EmailController extends Controller
{
    use SendEmail;

    /**
     * Send a reset password link.
     *
     * @param Request $request
     * @return void
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        $response = $this->broker()->sendResetLink(
            $this->emailCredentials($request)
        );

        return $response == Password::RESET_LINK_SENT
            ? response(['message' => 'Reset link has sent to your register email.'], Response::HTTP_OK)
            : response(['message' => 'Oops, reset link sent failure.'], Response::HTTP_BAD_REQUEST);
    }

    public function showPasswordResetForm(Request $request)
    {
        return response($request->token, Response::HTTP_OK);
    }

    /**
     * Reset the given user's password.
     * The token is in the forgot password email link.
     *
     * @param Request $request
     * @return void
     */
    public function passwordReset(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

        $response = $this->broker()->reset(
            $this->resetPasswordCredentials($request),
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );
        // dd($response);
        return $response == Password::PASSWORD_RESET
            ? response(['message' => 'Reset password is successful'], Response::HTTP_OK)
            : response(['message' => 'Oops, reset password fails.'], Response::HTTP_BAD_REQUEST);
    }
}

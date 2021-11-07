<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmailController extends Controller
{
    /**
     * If a user email does not verify, send a post request to this method, resend a verify link.
     * The request should include a bearer token.
     *
     * @param Request $request
     * @return void
     */
    public function sendVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response(['message' => 'Already verified.'], Response::HTTP_OK);
        }

        $request->user()->sendEmailVerificationNotification();

        return response(['message' => 'Verification link has sent to your email.'], Response::HTTP_OK);
    }

    /**
     * It is used to verify the email. 
     * In api, it should have an id and token, always the url also include expires and signature.
     *
     * @param Request $request
     * @return void
     */
    public function verifyEmail(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response(['message' => 'Already verified.'], Response::HTTP_OK);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return response(['message' => 'You account has been active, you can login now.'], Response::HTTP_OK);
    }
}

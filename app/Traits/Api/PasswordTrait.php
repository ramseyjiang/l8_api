<?php

namespace App\Traits\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

trait PasswordTrait
{
    /**
     * Validate the email for the given request is in the users table.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users']);
    }

    /**
     * We will send the password reset link to this user. Once we have attempted to send the link, we will examine the 
     * response then see the message we need to show to the user. Finally, we'll send out a proper response.
     *
     * @param Request $request
     * @return void
     */
    protected function getResetLinkStatus(Request $request)
    {
        return Password::sendResetLink(
            $request->only('email')
        );
    }

    protected function validResetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
    }

    /**
     * Here we will attempt to reset the user's password. If it is successful we will update the password on an actual user
     * model and persist it to the database. Otherwise we will parse the error and return the response.
     *
     * @param Request $request
     * @return void
     */
    protected function getResetStatus(Request $request)
    {
        return Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );
    }
}

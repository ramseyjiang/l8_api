<?php

namespace App\Traits\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\PasswordReset;

trait SendEmail
{
    /**
     * Validate the email for the given request is in the uses table.
     * It is used by sendResetPasswordLink.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users']);
    }

    /**
     * Get the needed authentication credentials from the request.
     * It is used by sendResetPasswordLink.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function emailCredentials(Request $request)
    {
        return $request->only('email');
    }

    /**
     * Get the password reset credentials from the request.
     * It is used by resetPassword.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function resetPasswordCredentials(Request $request)
    {
        return $request->only(
            'email',
            'password',
            'password_confirmation',
            'token'
        );
    }

    /**
     * Get the broker to be used during password reset.
     * It is used by both.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }

    /**
     * Get the password reset validation rules.
     * It is used by resetPassword.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email|exists:users',
            'password' => ['required', 'min:12', 'confirmed', Rules\Password::defaults()],
        ];
    }

    /**
     * Get the password reset validation error messages.
     * It is used by resetPassword.
     *
     * @return array
     */
    protected function validationErrorMessages()
    {
        return [];
    }

    /**
     * Reset the given user's password.
     * It is used by resetPassword.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = $password;
        // $user->setRememberToken(Str::random(60));
        $user->save();

        event(new PasswordReset($user));
    }
}

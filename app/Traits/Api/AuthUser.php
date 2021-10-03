<?php

namespace App\Traits\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Response;
// use App\Traits\Api\UserAccount;
// use Illuminate\Support\Facades\Mail;
// use App\Mail\CsrNotice;

/**
 * This trait is used for API login, API login many attempts, API register and forgotten password.
 * Many methods are copied from "Illuminate\Foundation\Auth\AuthenticatesUsers and Illuminate\Foundation\Auth\RegistersUsers trait",
 * except sendLoginResponse, validateLogin, sendApiLockOutResponse, sendFailedLoginResponse and logout.
 */
trait AuthUser
{
    use ThrottlesLogins;

    //decayMinutes is used for how long needs to wait after over attempts.
    protected $decayMinutes = 1;

    //maximum attempts
    protected $maxAttempts = 3;

    /**
     * Validate the user login request.
     * If status is pending, return error. After status is not pending, it will pass.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string', //To do:min:12 when release
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );
    }

    /**
     * Clear the login locks for the given user credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function clearLoginAttempts(Request $request)
    {
        $this->limiter()->clear($this->throttleKey($request));
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Send the response and clear attempts after the user was authenticated.
     * Modify from "Illuminate\Foundation\Auth\AuthenticatesUsers"
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request, array $response = [])
    {
        //Make session is invalid, if not, session which sent to frontend make users always login
        $request->session()->invalidate();

        $request->session()->regenerate();
        $this->clearLoginAttempts($request);

        return response($response, Response::HTTP_OK);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return response([
            'message' => trans('auth.failed')
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * After 5 wrong attempts, the account will be locked 30 minutes.
     * Modify the sendLockoutResponse which is from "Illuminate\Foundation\Auth\ThrottlesLogins trait", let it work in API
     *
     * @param Request $request
     * @return void
     */
    protected function sendApiLockOutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        return response([
            'message' => trans('auth.throttle', ['seconds' => $seconds, 'minutes' => ceil($seconds / 60)]),
        ], Response::HTTP_TOO_MANY_REQUESTS);
    }

    protected function createToken(object $user)
    {
        $token = $user->createToken('auth_token')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return $response;
    }

    /**
     * Validate the user register request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateUserRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed',
            'account_info' => 'required|json',
            'company_name' => 'required|string',
            'telephone_number' => 'required|string',
            'postal_address' => 'required|string',
            'subscribe' => 'required|bool',
        ]);
    }
}

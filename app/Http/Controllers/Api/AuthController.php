<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Api\AuthUser;
use App\Contracts\Repositories\UserRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    use AuthUser;

    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (method_exists($this, 'hasTooManyLoginAttempts') && $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendApiLockOutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $user = $this->userRepository->findByEmail($request->email); //Get login user

            if ($user) {
                $this->userRepository->active($user->id);
                $response = $this->createToken($user);
            } else {
                $response = [
                    'message' => 'The user is not active yet.'
                ];
            }

            return $this->sendLoginResponse($request, $response);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Log out a user from API.
     * This method put it here or put in the Traits/Api/AuthUser.php, both is ok
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        //Make session is invalid, if not, session which sent to frontend make users always login
        $request->session()->invalidate();

        $request->user()->tokens()->delete();    //delete current user all tokens

        return response(['message' => 'Logged out'], Response::HTTP_OK);
    }

    /**
     * This method put it here or put in the Traits/Api/AuthUser.php, both is ok
     *
     * @param Request $request
     * @return void
     */
    public function register(Request $request)
    {
        $this->validateUserRegister($request);

        event(new Registered($user = $this->userRepository->create($request->all())));

        $userId = $this->userRepository->findIdByEmail($request->email); //Get register user's id, here cannot get user model directly!!
        $user = $this->userRepository->findById($userId);
        // Mail::to($user)->send(new EmailNotice());

        $response = [
            'message' => 'Register success, please wait for approval.'
        ];
        return response($response, Response::HTTP_CREATED);
    }
}

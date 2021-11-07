<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use App\Contracts\UserContract;

class AuthController extends Controller
{
    private $userContract;

    public function __construct(UserContract $userContract)
    {
        $this->userContract = $userContract;
    }

    public function login(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        //Get login user
        $user = User::where('email', $request->email)->first();

        //Generate a user auth token
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        //If email isn't verified, a user cannot login.
        if (empty($user->email_verified_at)) {
            return response([
                'message' => 'Please verify your email first.',
                'token' => $token
            ], Response::HTTP_UNAUTHORIZED);
        } else {
            return response($response, Response::HTTP_ACCEPTED);
        }
    }

    public function logout(Request $request)
    {
        //Make session is invalid, if not, session which sent to frontend make users always login
        $request->session()->invalidate();

        //Make previous token is invalid and generate a new one.
        $request->session()->regenerateToken();

        // auth()->user()->currentAccessToken()->delete();     //delete current user the most recent token
        $request->user()->tokens()->delete();    //delete current user all tokens

        return response(['message' => 'Logged out'], Response::HTTP_ACCEPTED);
    }

    public function register(RegisterRequest $request)
    {
        $userForm = $request->all();
        $userForm['password'] = Hash::make($request->password);
        $userForm['password_confirmation'] = Hash::make($request->password_confirmation);

        event(new Registered($user = $this->userContract->create($userForm)));
        $user = $this->userContract->findUserByEmail($request->email);

        $user->sendEmailVerificationNotification(); //Send a verify email 

        $message = [
            'message' => 'Congrats ' . $user->name . '. Register success, please check your email and active your account.'
        ];
        return response($message, Response::HTTP_CREATED);
    }
}

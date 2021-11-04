<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use App\Http\Requests\Auth\LoginRequest;


class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        //Get login user
        $user = User::where('email', $request->email)->first();

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, Response::HTTP_ACCEPTED);
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // auth()->user()->currentAccessToken()->delete();     //delete current user the most recent token
        $request->user()->tokens()->delete();    //delete current user all tokens

        return response(['message' => 'Logged out'], Response::HTTP_ACCEPTED);
    }
}

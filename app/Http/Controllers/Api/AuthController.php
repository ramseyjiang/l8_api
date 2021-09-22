<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        //check email
        $user = User::where('email', $request->email)->first();

        //check password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'These credentials do not match our records!'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, Response::HTTP_CREATED);
    }

    public function logout(Request $request)
    {
        // auth()->user()->currentAccessToken()->delete();     //delete current user the most recent token
        $request->user()->tokens()->delete();    //delete current user all tokens

        return response(['message' => 'Logged out'], Response::HTTP_CREATED);
    }
}

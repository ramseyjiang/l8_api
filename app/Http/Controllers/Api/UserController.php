<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repository\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function userInfo(Request $request)
    {
        return response()->json($request->user(), Response::HTTP_OK);
    }

    public function getAllUsers()
    {
        return response()->json($this->userRepository->findAll(), Response::HTTP_OK);
    }

    public function delAllUsersLeftCurrent(Request $request)
    {
        return response()->json($this->userRepository->delAllLeftCurrent([$request->user()->id]), Response::HTTP_OK);
    }
}

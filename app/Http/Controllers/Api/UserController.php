<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repository\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getCurrentUser(Request $request)
    {
        return response()->json($request->user(), Response::HTTP_OK);
    }

    public function getAllUsers()
    {
        return response()->json($this->userRepository->findAll(), Response::HTTP_OK);
    }

    public function show(int $id)
    {
        try {
            $data = $this->userRepository->findById($id);
            $status = Response::HTTP_OK;
        } catch (ModelNotFoundException $e) {
            $data = ['message' => 'User does not exist.'];
            $status = Response::HTTP_NOT_FOUND;
        }
        return response()->json($data, $status);
    }
}

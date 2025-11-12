<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    // POST /api/users
    public function store(StoreUserRequest $request): JsonResponse
    {
        $data = $request->only(['name','email','password']);
        $user = $this->service->create($data);

        return response()->json(['data' => $user], 201);
    }

    // PUT/PATCH /api/users/{user}
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $data = $request->only(['name','email','password']);
        $user = $this->service->update($user, $data);

        return response()->json(['data' => $user], 200);
    }

    // GET /api/users/{id}
    public function show($id): JsonResponse
    {
        $user = $this->service->findById((int)$id);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(['data' => $user], 200);
    }

    // GET /api/users
    public function index(): JsonResponse
    {
        $users = $this->service->all();
        return response()->json(['data' => $users], 200);
    }
}

<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\User\StoreRequest;
use App\Http\Requests\Api\V1\Admin\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): AnonymousResourceCollection
    {
        $admins = User::where('role', 'admin')->latest()->get();
        return UserResource::collection($admins);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $admin = $this->userService->createAdminUser($request->validated());
        return response()->json([
            'message' => 'Akun admin berhasil dibuat.',
            'data' => new UserResource($admin),
        ], 201);
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function update(UpdateRequest $request, User $user): JsonResponse
    {
        $admin = $this->userService->updateAdminUser($user, $request->validated());
        return response()->json([
            'message' => 'Akun admin berhasil diperbarui.',
            'data' => new UserResource($admin),
        ]);
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        if ($request->user()->id === $user->id) {
            return response()->json(['message' => 'Anda tidak dapat menghapus akun Anda sendiri.'], 403);
        }

        try {
            $this->userService->deleteUser($user);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }

        return response()->json(['message' => 'Akun admin berhasil dihapus.']);
    }
}

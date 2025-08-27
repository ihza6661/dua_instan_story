<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\V1\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * Tampilkan data profil pengguna yang sedang login.
     */
    public function show(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'User data retrieved successfully.',
            'data' => new UserResource($request->user()),
        ]);
    }

    /**
     * Perbarui profil pengguna yang sedang login.
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();

        // Simpan perubahan ke database
        $user->update($request->validated());

        // ✅ PERBAIKAN: Kembalikan objek `$user` secara langsung
        // Ini memastikan data terbaru yang baru saja disimpan dikirim ke frontend.
        return response()->json([
            'message' => 'Profile updated successfully.',
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Ubah password pengguna yang sedang login.
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Password saat ini tidak cocok.'],
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully.']);
    }
}

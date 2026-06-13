<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    public function show(): JsonResponse
    {
        return response()->json([
            'data' => new UserResource(request()->user()),
        ]);
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->update($request->validated());

        return response()->json([
            'message' => 'Dane profilu zostały zaktualizowane.',
            'data' => new UserResource($user->fresh()),
        ]);
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->update([
            'password' => $request->validated('password'),
        ]);

        return response()->json([
            'message' => 'Hasło zostało zmienione.',
        ]);
    }
}

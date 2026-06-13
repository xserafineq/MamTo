<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUpdatePermissionsRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AdministratorController extends Controller
{
    public function index(): JsonResponse
    {
        $administrators = User::query()
            ->where('isAdmin', true)
            ->where('id', '!=', Auth::id())
            ->orderBy('lastName')
            ->orderBy('firstName')
            ->get();

        return response()->json([
            'data' => UserResource::collection($administrators),
            'isMainAdmin' => (bool) Auth::user()->isMainAdmin,
        ]);
    }

    public function updatePermissions(AdminUpdatePermissionsRequest $request, User $user): JsonResponse
    {
        if ($user->isMainAdmin) {
            return response()->json([
                'message' => 'Nie można zmienić uprawnień głównego administratora.',
                'errors' => [
                    'permissions' => ['Nie można zmienić uprawnień głównego administratora.'],
                ],
            ], 422);
        }

        if ((int) $user->id === (int) Auth::id()) {
            abort(403);
        }

        $user->update([
            'isAdmin' => $request->boolean('isAdmin'),
        ]);

        return response()->json([
            'message' => 'Uprawnienia użytkownika zostały zaktualizowane.',
            'data' => new UserResource($user->fresh()),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'firstName' => $request->validated('firstName'),
            'lastName' => $request->validated('lastName'),
            'email' => $request->validated('email'),
            'phoneNumber' => $request->validated('phoneNumber'),
            'password' => $request->validated('password'),
            'joinedAt' => now(),
            'lastOnline' => now(),
            'isAdmin' => false,
        ]);

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'message' => 'Konto zostało utworzone.',
            'user' => new UserResource($user),
            'token' => $token,
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (! Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Podane dane logowania są nieprawidłowe.',
                'errors' => [
                    'email' => ['Podane dane logowania są nieprawidłowe.'],
                ],
            ], 422);
        }

        /** @var User $user */
        $user = Auth::user();
        $user->update(['lastOnline' => now()]);

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'message' => 'Zalogowano pomyślnie.',
            'user' => new UserResource($user),
            'token' => $token,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Wylogowano pomyślnie.',
        ]);
    }

    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'data' => new UserResource($request->user()),
        ]);
    }
}

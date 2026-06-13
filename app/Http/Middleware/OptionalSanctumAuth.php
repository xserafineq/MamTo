<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class OptionalSanctumAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($token = $request->bearerToken()) {
            $accessToken = PersonalAccessToken::findToken($token);

            if ($accessToken?->tokenable) {
                Auth::guard('sanctum')->setUser($accessToken->tokenable);
                Auth::shouldUse('sanctum');
            }
        }

        return $next($request);
    }
}

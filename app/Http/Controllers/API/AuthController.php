<?php

namespace App\Http\Controllers\API;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RefreshTokenRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        // Assign student role
        $user->assignRole(Role::Student->value);

        // Generate tokens
        $token = auth()->login($user);
        $refreshToken = RefreshToken::generate(
            $user,
            $request->userAgent(),
            $request->ip()
        );

        return $this->respondWithToken($token, $refreshToken->token);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth()->user();
        $refreshToken = RefreshToken::generate(
            $user,
            $request->userAgent(),
            $request->ip()
        );

        return $this->respondWithToken($token, $refreshToken->token);
    }

    public function refresh(RefreshTokenRequest $request)
    {
        $refreshTokenString = $request->validated();

        $refreshToken = RefreshToken::where('token', $refreshTokenString)->first();

        if (! $refreshToken || ! $refreshToken->isValid()) {
            return response()->json(['error' => 'Invalid or expired refresh token'], 401);
        }

        $user = $refreshToken->user;

        // Generate new access token
        $token = auth()->login($user);

        // Revoke old refresh token and generate new one
        $refreshToken->revoke();
        $newRefreshToken = RefreshToken::generate(
            $user,
            $request->userAgent(),
            $request->ip()
        );

        return $this->respondWithToken($token, $newRefreshToken->token);
    }

    protected function respondWithToken(string $accessToken, string $refreshToken): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'refresh_expires_in' => config('jwt.refresh_ttl') * 60,
        ]);
    }

    public function me()
    {
        $user = auth()->user();

        return response()->json(UserResource::make($user));
    }

    public function logout(Request $request)
    {
        // Revoke all refresh tokens for the user
        $user = auth()->user();
        $user->refreshTokens()->whereNull('revoked_at')->update(['revoked_at' => now()]);

        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}

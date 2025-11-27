<?php

namespace App\Http\Controllers\API;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RefreshTokenRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\UserResource;
use App\Models\RefreshToken;
use App\Models\Skill;
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
            return response()->json([
                'message' => 'The provided credentials are incorrect',
            ], 422);
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

    public function profile()
    {
        $user = auth()->user();

        // Load enrolled exams with pivot data
        $user->load(['exams' => function ($query) {
            $query->with('skill')->orderBy('exam_user.created_at', 'desc');
        }]);

        // Get unique skills from enrolled exams
        $enrolledSkills = collect();
        $skillIds = $user->exams->pluck('skill_id')->unique();

        foreach ($skillIds as $skillId) {
            $skill = Skill::with(['exams' => function ($query) use ($user) {
                $query->whereHas('users', function ($q) use ($user) {
                    $q->where('users.id', $user->id);
                });
            }])->find($skillId);

            if ($skill) {
                // Get user's exams for this skill
                $userExamsForSkill = $user->exams->where('skill_id', $skill->id);
                $completedExams = $userExamsForSkill->where('pivot.status', 'closed');

                $skill->statistics = [
                    'total_exams' => $userExamsForSkill->count(),
                    'completed_exams' => $completedExams->count(),
                    'average_score' => $completedExams->isNotEmpty()
                        ? round($completedExams->avg('pivot.score'), 2)
                        : null,
                    'highest_score' => $completedExams->isNotEmpty()
                        ? $completedExams->max('pivot.score')
                        : null,
                    'total_time_spent' => $completedExams->sum('pivot.time_mins'),
                ];

                // Manually set the exams for this skill with pivot data
                $skill->setRelation('exams', $userExamsForSkill->values());

                $enrolledSkills->push($skill);
            }
        }

        // Calculate overall statistics
        $completedExams = $user->exams->where('pivot.status', 'closed');
        $user->statistics = [
            'total_enrolled_skills' => $enrolledSkills->count(),
            'total_enrolled_exams' => $user->exams->count(),
            'total_completed_exams' => $completedExams->count(),
            'overall_average_score' => $completedExams->isNotEmpty()
                ? round($completedExams->avg('pivot.score'), 2)
                : null,
            'total_time_spent' => $completedExams->sum('pivot.time_mins'),
        ];

        $user->setRelation('enrolledSkills', $enrolledSkills);

        return UserProfileResource::make($user);
    }

    public function logout(Request $request)
    {
        // Revoke all refresh tokens for the user
        $user = auth()->user();
        $user->refreshTokens()->whereNull('revoked_at')->update(['revoked_at' => now()]);

        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => UserResource::make($user),
        ]);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        // Verify current password
        if (! \Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'message' => 'The current password is incorrect',
                'errors' => [
                    'current_password' => ['The current password is incorrect'],
                ],
            ], 422);
        }

        // Update password
        $user->update([
            'password' => $validated['password'],
        ]);

        return response()->json([
            'message' => 'Password updated successfully',
        ]);
    }
}

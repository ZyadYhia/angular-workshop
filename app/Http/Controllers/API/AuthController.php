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
use App\Models\Course;
use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Authentication', description: 'User authentication and profile management')]
class AuthController extends Controller
{
    #[OA\Post(
        path: '/auth/register',
        summary: 'Register a new user',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'username', 'email', 'password', 'password_confirmation'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                    new OA\Property(property: 'username', type: 'string', example: 'johndoe'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
                    new OA\Property(property: 'phone_number', type: 'string', nullable: true, example: '+1234567890'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
                    new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'password123'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'User registered successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'access_token', type: 'string'),
                        new OA\Property(property: 'refresh_token', type: 'string'),
                        new OA\Property(property: 'token_type', type: 'string', example: 'bearer'),
                        new OA\Property(property: 'expires_in', type: 'integer'),
                        new OA\Property(property: 'refresh_expires_in', type: 'integer'),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'] ?? null,
            'password' => $validated['password'],
        ]);

        // Assign student role
        $user->assignRole(Role::Student->value);

        // Generate tokens
        $token = auth('api')->login($user);
        $refreshToken = RefreshToken::generate(
            $user,
            $request->userAgent(),
            $request->ip()
        );

        return $this->respondWithToken($token, $refreshToken->token);
    }

    #[OA\Post(
        path: '/auth/login',
        summary: 'Login user',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login successful',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'access_token', type: 'string'),
                        new OA\Property(property: 'refresh_token', type: 'string'),
                        new OA\Property(property: 'token_type', type: 'string', example: 'bearer'),
                        new OA\Property(property: 'expires_in', type: 'integer'),
                        new OA\Property(property: 'refresh_expires_in', type: 'integer'),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Invalid credentials'),
        ]
    )]
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect',
            ], 422);
        }

        $user = auth('api')->user();
        $refreshToken = RefreshToken::generate(
            $user,
            $request->userAgent(),
            $request->ip()
        );

        return $this->respondWithToken($token, $refreshToken->token);
    }

    #[OA\Post(
        path: '/auth/refresh',
        summary: 'Refresh access token',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['refresh_token'],
                properties: [
                    new OA\Property(property: 'refresh_token', type: 'string', example: 'your-refresh-token-here'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Token refreshed successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'access_token', type: 'string'),
                        new OA\Property(property: 'refresh_token', type: 'string'),
                        new OA\Property(property: 'token_type', type: 'string', example: 'bearer'),
                        new OA\Property(property: 'expires_in', type: 'integer'),
                        new OA\Property(property: 'refresh_expires_in', type: 'integer'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Invalid or expired refresh token'),
        ]
    )]
    public function refresh(RefreshTokenRequest $request)
    {
        $refreshTokenString = $request->validated();

        $refreshToken = RefreshToken::where('token', $refreshTokenString)->first();

        if (! $refreshToken || ! $refreshToken->isValid()) {
            return response()->json(['error' => 'Invalid or expired refresh token'], 401);
        }

        $user = $refreshToken->user;

        // Generate new access token
        $token = auth('api')->login($user);

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
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'refresh_expires_in' => config('jwt.refresh_ttl') * 60,
        ]);
    }

    #[OA\Get(
        path: '/auth/me',
        summary: 'Get current authenticated user',
        security: [['bearerAuth' => []]],
        tags: ['Authentication'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User data',
                content: new OA\JsonContent(ref: '#/components/schemas/User')
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function me()
    {
        $user = auth('api')->user();

        return response()->json(UserResource::make($user));
    }

    #[OA\Get(
        path: '/auth/profile',
        summary: 'Get user profile with enrolled exams and statistics',
        security: [['bearerAuth' => []]],
        tags: ['Authentication'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User profile with statistics',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(property: 'name', type: 'string'),
                        new OA\Property(property: 'email', type: 'string'),
                        new OA\Property(
                            property: 'statistics',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'total_enrolled_skills', type: 'integer'),
                                new OA\Property(property: 'total_enrolled_exams', type: 'integer'),
                                new OA\Property(property: 'total_completed_exams', type: 'integer'),
                                new OA\Property(property: 'overall_average_score', type: 'number', format: 'float', nullable: true),
                                new OA\Property(property: 'total_time_spent', type: 'integer'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function profile()
    {
        $user = auth('api')->user();

        // Load enrolled exams with pivot data
        $user->load(['exams' => function ($query) {
            $query->with('course')->orderBy('exam_user.created_at', 'desc');
        }]);

        // Get unique courses from enrolled exams
        $enrolledCourses = collect();
        $courseIds = $user->exams->pluck('course_id')->unique();

        foreach ($courseIds as $courseId) {
            $course = Course::with(['exams' => function ($query) use ($user) {
                $query->whereHas('users', function ($q) use ($user) {
                    $q->where('users.id', $user->id);
                });
            }])->find($courseId);

            if ($course) {
                // Get user's exams for this course
                $userExamsForCourse = $user->exams->where('course_id', $course->id);
                $completedExams = $userExamsForCourse->where('pivot.status', 'closed');

                $course->statistics = [
                    'total_exams' => $userExamsForCourse->count(),
                    'completed_exams' => $completedExams->count(),
                    'average_score' => $completedExams->isNotEmpty()
                        ? round($completedExams->avg('pivot.score'), 2)
                        : null,
                    'highest_score' => $completedExams->isNotEmpty()
                        ? $completedExams->max('pivot.score')
                        : null,
                    'total_time_spent' => $completedExams->sum('pivot.time_mins'),
                ];

                // Manually set the exams for this course with pivot data
                $course->setRelation('exams', $userExamsForCourse->values());

                $enrolledCourses->push($course);
            }
        }

        // Calculate overall statistics
        $completedExams = $user->exams->where('pivot.status', 'closed');
        $user->statistics = [
            'total_enrolled_courses' => $enrolledCourses->count(),
            'total_enrolled_exams' => $user->exams->count(),
            'total_completed_exams' => $completedExams->count(),
            'overall_average_score' => $completedExams->isNotEmpty()
                ? round($completedExams->avg('pivot.score'), 2)
                : null,
            'total_time_spent' => $completedExams->sum('pivot.time_mins'),
        ];

        $user->setRelation('enrolledCourses', $enrolledCourses);

        return UserProfileResource::make($user);
    }

    #[OA\Post(
        path: '/auth/logout',
        summary: 'Logout user',
        security: [['bearerAuth' => []]],
        tags: ['Authentication'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Logout successful',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Successfully logged out'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function logout(Request $request)
    {
        // Revoke all refresh tokens for the user
        $user = auth('api')->user();
        $user->refreshTokens()->whereNull('revoked_at')->update(['revoked_at' => now()]);

        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    #[OA\Put(
        path: '/auth/profile',
        summary: 'Update user profile',
        security: [['bearerAuth' => []]],
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                    new OA\Property(property: 'username', type: 'string', example: 'johndoe'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
                    new OA\Property(property: 'phone_number', type: 'string', nullable: true, example: '+1234567890'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Profile updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Profile updated successfully'),
                        new OA\Property(property: 'user', ref: '#/components/schemas/User'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = auth('api')->user();
        $validated = $request->validated();

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => UserResource::make($user),
        ]);
    }

    #[OA\Put(
        path: '/auth/password',
        summary: 'Update user password',
        security: [['bearerAuth' => []]],
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['current_password', 'password', 'password_confirmation'],
                properties: [
                    new OA\Property(property: 'current_password', type: 'string', format: 'password', example: 'oldpassword'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'newpassword123'),
                    new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'newpassword123'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Password updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Password updated successfully'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation error or incorrect current password'),
        ]
    )]
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = auth('api')->user();
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

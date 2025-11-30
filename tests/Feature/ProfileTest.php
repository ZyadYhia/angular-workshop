<?php

use App\Models\Cat;
use App\Models\Course;
use App\Models\Exam;
use App\Models\User;

it('returns user profile with enrolled courses and exams', function () {
    $user = User::factory()->create();

    // Create categories and courses with exams
    $cat1 = Cat::factory()->create();
    $cat2 = Cat::factory()->create();

    $course1 = Course::factory()->create(['cat_id' => $cat1->id]);
    $course2 = Course::factory()->create(['cat_id' => $cat2->id]);

    $exam1 = Exam::factory()->create(['course_id' => $course1->id]);
    $exam2 = Exam::factory()->create(['course_id' => $course1->id]);
    $exam3 = Exam::factory()->create(['course_id' => $course2->id]);

    // Enroll user in exams with scores
    $user->exams()->attach($exam1->id, [
        'score' => 85.50,
        'time_mins' => 45,
        'status' => 'closed',
    ]);

    $user->exams()->attach($exam2->id, [
        'score' => 92.00,
        'time_mins' => 50,
        'status' => 'closed',
    ]);

    $user->exams()->attach($exam3->id, [
        'score' => 78.25,
        'time_mins' => 40,
        'status' => 'closed',
    ]);

    $response = $this->actingAs($user, 'api')->getJson('/api/auth/profile');

    $response->assertSuccessful()
        ->assertJsonStructure([
            'data' => [
                'enrolled_courses' => [
                    '*' => [
                        'id',
                        'name' => ['en', 'ar'],
                        'image',
                        'active',
                        'cat_id',
                        'exams',
                        'statistics' => [
                            'total_exams',
                            'completed_exams',
                            'average_score',
                            'highest_score',
                            'total_time_spent',
                        ],
                    ],
                ],
                'enrolled_exams' => [
                    '*' => [
                        'id',
                        'name' => ['en', 'ar'],
                        'desc' => ['en', 'ar'],
                        'image',
                        'questions_no',
                        'difficulty',
                        'duration_mins',
                        'active',
                        'course_id',
                        'enrollment' => [
                            'score',
                            'time_mins',
                            'status',
                            'enrolled_at',
                            'updated_at',
                        ],
                    ],
                ],
                'statistics' => [
                    'total_enrolled_courses',
                    'total_enrolled_exams',
                    'total_completed_exams',
                    'overall_average_score',
                    'total_time_spent',
                ],
            ],
        ]);

    expect($response->json('data.enrolled_exams'))->toHaveCount(3);
    expect($response->json('data.enrolled_courses'))->toHaveCount(2);
    expect($response->json('data.statistics.total_enrolled_exams'))->toBe(3);
    expect($response->json('data.statistics.total_completed_exams'))->toBe(3);
    expect($response->json('data.statistics.overall_average_score'))->toBe(85.25);
});

it('requires authentication to access profile', function () {
    $response = $this->getJson('/api/auth/profile');

    $response->assertUnauthorized();
});

it('calculates correct statistics for courses', function () {
    $user = User::factory()->create();
    $cat = Cat::factory()->create();
    $course = Course::factory()->create(['cat_id' => $cat->id]);

    $exam1 = Exam::factory()->create(['course_id' => $course->id]);
    $exam2 = Exam::factory()->create(['course_id' => $course->id]);

    $user->exams()->attach($exam1->id, [
        'score' => 90.00,
        'time_mins' => 30,
        'status' => 'closed',
    ]);

    $user->exams()->attach($exam2->id, [
        'score' => 80.00,
        'time_mins' => 35,
        'status' => 'closed',
    ]);

    $response = $this->actingAs($user, 'api')->getJson('/api/auth/profile');

    $response->assertSuccessful();

    $courseData = collect($response->json('data.enrolled_courses'))->firstWhere('id', $course->id);

    expect($courseData['statistics']['total_exams'])->toBe(2);
    expect($courseData['statistics']['completed_exams'])->toBe(2);
    expect((float) $courseData['statistics']['average_score'])->toBe(85.0);
    expect((float) $courseData['statistics']['highest_score'])->toBe(90.0);
    expect($courseData['statistics']['total_time_spent'])->toBe(65);
});

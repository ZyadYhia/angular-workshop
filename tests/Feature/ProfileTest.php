<?php

use App\Models\Cat;
use App\Models\Exam;
use App\Models\Skill;
use App\Models\User;

it('returns user profile with enrolled skills and exams', function () {
    $user = User::factory()->create();

    // Create categories and skills with exams
    $cat1 = Cat::factory()->create();
    $cat2 = Cat::factory()->create();

    $skill1 = Skill::factory()->create(['cat_id' => $cat1->id]);
    $skill2 = Skill::factory()->create(['cat_id' => $cat2->id]);

    $exam1 = Exam::factory()->create(['skill_id' => $skill1->id]);
    $exam2 = Exam::factory()->create(['skill_id' => $skill1->id]);
    $exam3 = Exam::factory()->create(['skill_id' => $skill2->id]);

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
                'enrolled_skills' => [
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
                        'skill_id',
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
                    'total_enrolled_skills',
                    'total_enrolled_exams',
                    'total_completed_exams',
                    'overall_average_score',
                    'total_time_spent',
                ],
            ],
        ]);

    expect($response->json('data.enrolled_exams'))->toHaveCount(3);
    expect($response->json('data.enrolled_skills'))->toHaveCount(2);
    expect($response->json('data.statistics.total_enrolled_exams'))->toBe(3);
    expect($response->json('data.statistics.total_completed_exams'))->toBe(3);
    expect($response->json('data.statistics.overall_average_score'))->toBe(85.25);
});

it('requires authentication to access profile', function () {
    $response = $this->getJson('/api/auth/profile');

    $response->assertUnauthorized();
});

it('calculates correct statistics for skills', function () {
    $user = User::factory()->create();
    $cat = Cat::factory()->create();
    $skill = Skill::factory()->create(['cat_id' => $cat->id]);

    $exam1 = Exam::factory()->create(['skill_id' => $skill->id]);
    $exam2 = Exam::factory()->create(['skill_id' => $skill->id]);

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

    $skillData = collect($response->json('data.enrolled_skills'))->firstWhere('id', $skill->id);

    expect($skillData['statistics']['total_exams'])->toBe(2);
    expect($skillData['statistics']['completed_exams'])->toBe(2);
    expect((float) $skillData['statistics']['average_score'])->toBe(85.0);
    expect((float) $skillData['statistics']['highest_score'])->toBe(90.0);
    expect($skillData['statistics']['total_time_spent'])->toBe(65);
});

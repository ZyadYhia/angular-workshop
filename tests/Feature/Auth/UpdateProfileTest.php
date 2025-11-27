<?php

use App\Models\User;

it('can update profile name', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    $token = auth()->login($user);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->putJson('/api/auth/profile', [
        'name' => 'Jane Doe',
    ]);

    $response->assertSuccessful()
        ->assertJson([
            'message' => 'Profile updated successfully',
            'user' => [
                'name' => 'Jane Doe',
                'email' => 'john@example.com',
            ],
        ]);

    $user->refresh();
    expect($user->name)->toBe('Jane Doe');
});

it('can update profile email', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    $token = auth()->login($user);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->putJson('/api/auth/profile', [
        'email' => 'newemail@example.com',
    ]);

    $response->assertSuccessful()
        ->assertJson([
            'message' => 'Profile updated successfully',
            'user' => [
                'name' => 'John Doe',
                'email' => 'newemail@example.com',
            ],
        ]);

    $user->refresh();
    expect($user->email)->toBe('newemail@example.com');
});

it('can update both name and email', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    $token = auth()->login($user);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->putJson('/api/auth/profile', [
        'name' => 'Jane Smith',
        'email' => 'jane@example.com',
    ]);

    $response->assertSuccessful()
        ->assertJson([
            'message' => 'Profile updated successfully',
            'user' => [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
            ],
        ]);

    $user->refresh();
    expect($user->name)->toBe('Jane Smith');
    expect($user->email)->toBe('jane@example.com');
});

it('validates email uniqueness when updating profile', function () {
    $existingUser = User::factory()->create([
        'email' => 'existing@example.com',
    ]);

    $user = User::factory()->create([
        'email' => 'john@example.com',
    ]);

    $token = auth()->login($user);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->putJson('/api/auth/profile', [
        'email' => 'existing@example.com',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

it('can update to same email', function () {
    $user = User::factory()->create([
        'email' => 'john@example.com',
    ]);

    $token = auth()->login($user);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->putJson('/api/auth/profile', [
        'email' => 'john@example.com',
    ]);

    $response->assertSuccessful();
});

it('validates email format', function () {
    $user = User::factory()->create();
    $token = auth()->login($user);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->putJson('/api/auth/profile', [
        'email' => 'invalid-email',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

it('validates name max length', function () {
    $user = User::factory()->create();
    $token = auth()->login($user);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->putJson('/api/auth/profile', [
        'name' => str_repeat('a', 256),
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['name']);
});

it('requires authentication to update profile', function () {
    $response = $this->putJson('/api/auth/profile', [
        'name' => 'Jane Doe',
    ]);

    $response->assertUnauthorized();
});

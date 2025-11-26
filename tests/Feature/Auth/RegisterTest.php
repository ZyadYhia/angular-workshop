<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can register a new user with student role', function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);

    $response = $this->postJson('/api/auth/register', [
        'name' => 'Test Student',
        'email' => 'student@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertSuccessful();
    $response->assertJsonStructure([
        'access_token',
        'refresh_token',
        'token_type',
        'expires_in',
        'refresh_expires_in',
    ]);

    expect(User::where('email', 'student@example.com')->exists())->toBeTrue();

    $user = User::where('email', 'student@example.com')->first();
    expect($user->hasRole('student'))->toBeTrue();
});

it('validates required fields', function () {
    $response = $this->postJson('/api/auth/register', []);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['name', 'email', 'password']);
});

it('validates email format', function () {
    $response = $this->postJson('/api/auth/register', [
        'name' => 'Test User',
        'email' => 'invalid-email',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['email']);
});

it('validates unique email', function () {
    User::factory()->create(['email' => 'existing@example.com']);

    $response = $this->postJson('/api/auth/register', [
        'name' => 'Test User',
        'email' => 'existing@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['email']);
});

it('validates password confirmation', function () {
    $response = $this->postJson('/api/auth/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'different123',
    ]);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['password']);
});

it('validates minimum password length', function () {
    $response = $this->postJson('/api/auth/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'short',
        'password_confirmation' => 'short',
    ]);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['password']);
});

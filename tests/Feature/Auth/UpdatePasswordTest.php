<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('can update password with correct current password', function () {
    $user = User::factory()->create([
        'password' => 'oldpassword123',
    ]);

    $token = auth()->login($user);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->putJson('/api/auth/password', [
        'current_password' => 'oldpassword123',
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertSuccessful()
        ->assertJson([
            'message' => 'Password updated successfully',
        ]);

    $user->refresh();
    expect(Hash::check('newpassword123', $user->password))->toBeTrue();
});

it('cannot update password with incorrect current password', function () {
    $user = User::factory()->create([
        'password' => 'oldpassword123',
    ]);

    $token = auth()->login($user);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->putJson('/api/auth/password', [
        'current_password' => 'wrongpassword',
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertUnprocessable()
        ->assertJson([
            'message' => 'The current password is incorrect',
            'errors' => [
                'current_password' => ['The current password is incorrect'],
            ],
        ]);

    $user->refresh();
    expect(Hash::check('oldpassword123', $user->password))->toBeTrue();
});

it('validates new password confirmation', function () {
    $user = User::factory()->create([
        'password' => 'oldpassword123',
    ]);

    $token = auth()->login($user);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->putJson('/api/auth/password', [
        'current_password' => 'oldpassword123',
        'password' => 'newpassword123',
        'password_confirmation' => 'differentpassword',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['password']);
});

it('validates new password minimum length', function () {
    $user = User::factory()->create([
        'password' => 'oldpassword123',
    ]);

    $token = auth()->login($user);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->putJson('/api/auth/password', [
        'current_password' => 'oldpassword123',
        'password' => 'short',
        'password_confirmation' => 'short',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['password']);
});

it('requires current password', function () {
    $user = User::factory()->create([
        'password' => 'oldpassword123',
    ]);

    $token = auth()->login($user);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->putJson('/api/auth/password', [
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['current_password']);
});

it('requires new password', function () {
    $user = User::factory()->create([
        'password' => 'oldpassword123',
    ]);

    $token = auth()->login($user);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->putJson('/api/auth/password', [
        'current_password' => 'oldpassword123',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['password']);
});

it('requires authentication to update password', function () {
    $response = $this->putJson('/api/auth/password', [
        'current_password' => 'oldpassword123',
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertUnauthorized();
});

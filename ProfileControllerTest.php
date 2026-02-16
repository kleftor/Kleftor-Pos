<?php

use App\Models\Tenants\User;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

test('user can get the profile', function () {
    $user = User::first();

    $response = actingAs($user)->getJson('/api/auth/me');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'phone',
                'address',
                'photo',
                'locale',
                'roles',
                'created_at',
                'updated_at',
            ],
        ]);
});

test('user can update the profile', function () {
    $user = User::first();

    $response = actingAs($user)->putJson('/api/auth/me', [
        'name' => 'John Arthur',
        'email' => 'johnsgraphicx@gmail.com',
    ]);

    $response->assertOk()
        ->assertJsonPath('message', 'Profile updated successfully');

    $this->assertDatabaseHas('users', [
        'name' => 'John Arthur',
        'email' => 'johnsgraphicx@gmail.com',
    ]);
});

test('user can update the profile with photo', function () {
    $user = User::first();

    $response = actingAs($user)->putJson('/api/auth/me', [
        'name' => 'John Arthur',
        'email' => 'johnsgraphicx@gmail.com',
        // 'photo_url' => 'https://picsum.photos/200.jpg',
    ]);

    $response->assertOk()
        ->assertJsonPath('message', 'Profile updated successfully');
})->markTestSkipped('Need to mock the file upload');

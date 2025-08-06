<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class GoogleOAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Google OAuth login with valid token
     */
    public function test_google_oauth_login_success()
    {
        // Mock Google API response
        $mockGoogleUser = [
            'id' => '123456789',
            'email' => 'test@gmail.com',
            'name' => 'Test User',
            'picture' => 'https://example.com/avatar.jpg',
            'email_verified' => true
        ];

        // Create a partial mock of AuthController
        $this->partialMock(\App\Http\Controllers\Api\AuthController::class, function ($mock) use ($mockGoogleUser) {
            $mock->shouldReceive('getGoogleUserInfo')
                 ->once()
                 ->andReturn($mockGoogleUser);
        });

        $response = $this->postJson('/api/auth/google', [
            'access_token' => 'mock_access_token'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Đăng nhập Google thành công'
                 ])
                 ->assertJsonStructure([
                     'data' => [
                         'user' => ['id', 'name', 'email', 'phone', 'avatar', 'google_id'],
                         'token'
                     ]
                 ]);

        // Check if user was created in database
        $this->assertDatabaseHas('users', [
            'email' => 'test@gmail.com',
            'google_id' => '123456789'
        ]);
    }

    /**
     * Test Google OAuth login with invalid token
     */
    public function test_google_oauth_login_invalid_token()
    {
        // Mock Google API to return null (invalid token)
        $this->partialMock(\App\Http\Controllers\Api\AuthController::class, function ($mock) {
            $mock->shouldReceive('getGoogleUserInfo')
                 ->once()
                 ->andReturn(null);
        });

        $response = $this->postJson('/api/auth/google', [
            'access_token' => 'invalid_token'
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Token Google không hợp lệ'
                 ]);
    }

    /**
     * Test Google OAuth login with existing user
     */
    public function test_google_oauth_login_existing_user()
    {
        // Create existing user
        $existingUser = User::factory()->create([
            'email' => 'test@gmail.com',
            'google_id' => null
        ]);

        $mockGoogleUser = [
            'id' => '123456789',
            'email' => 'test@gmail.com',
            'name' => 'Test User Updated',
            'picture' => 'https://example.com/avatar.jpg',
            'email_verified' => true
        ];

        $this->partialMock(\App\Http\Controllers\Api\AuthController::class, function ($mock) use ($mockGoogleUser) {
            $mock->shouldReceive('getGoogleUserInfo')
                 ->once()
                 ->andReturn($mockGoogleUser);
        });

        $response = $this->postJson('/api/auth/google', [
            'access_token' => 'mock_access_token'
        ]);

        $response->assertStatus(200);

        // Check if user was updated with Google ID
        $this->assertDatabaseHas('users', [
            'id' => $existingUser->id,
            'email' => 'test@gmail.com',
            'google_id' => '123456789'
        ]);
    }
}

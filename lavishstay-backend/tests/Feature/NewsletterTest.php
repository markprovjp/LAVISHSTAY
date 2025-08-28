<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Mail\NewsletterWelcome;

class NewsletterTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_newsletter_subscription_success()
    {
        Mail::fake();

        $email = $this->faker->email;

        $response = $this->postJson('/api/newsletter', ['email' => $email]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'discount_code'
                 ])
                 ->assertJson(['success' => true]);

        // Assert coupon was created
        $this->assertDatabaseHas('coupons', [
            'type' => 'percent',
            'value' => 20.00,
            'active' => 1,
        ]);

        // Assert newsletter subscriber was created
        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => $email,
            'active' => true,
        ]);

        // Assert welcome email was queued
        Mail::assertQueued(NewsletterWelcome::class);
        
        // Get the discount code from response
        $discountCode = $response->json('discount_code');
        $this->assertStringStartsWith('LAVISH20-', $discountCode);
    }

    public function test_newsletter_duplicate_subscription()
    {
        Mail::fake();

        $email = $this->faker->email;

        // First subscription
        DB::table('newsletter_subscribers')->insert([
            'email' => $email,
            'active' => true,
            'unsubscribe_token' => 'test-token',
            'subscribed_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $existingCoupon = DB::table('coupons')->insertGetId([
            'code' => 'LAVISH20-123456',
            'type' => 'percent',
            'value' => 20.00,
            'currency' => 'VND',
            'description' => "20% giảm giá từ newsletter - $email",
            'start_at' => now(),
            'end_at' => now()->addDays(90),
            'active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->postJson('/api/newsletter', ['email' => $email]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'discount_code' => 'LAVISH20-123456'
                 ]);
    }

    public function test_newsletter_email_validation()
    {
        $response = $this->postJson('/api/newsletter', ['email' => 'invalid-email']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_newsletter_unsubscribe()
    {
        $email = $this->faker->email;
        $token = 'test-unsubscribe-token';

        DB::table('newsletter_subscribers')->insert([
            'email' => $email,
            'active' => true,
            'unsubscribe_token' => $token,
            'subscribed_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->getJson("/api/newsletter/unsubscribe?email={$email}&token={$token}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Đã hủy đăng ký newsletter thành công'
                 ]);

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => $email,
            'active' => false,
        ]);
    }

    public function test_newsletter_unsubscribe_invalid_token()
    {
        $response = $this->getJson('/api/newsletter/unsubscribe?email=test@example.com&token=invalid-token');

        $response->assertStatus(404)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Link hủy đăng ký không hợp lệ'
                 ]);
    }
}

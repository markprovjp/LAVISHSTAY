<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Mail\ContactResponse;
use App\Mail\AdminContactNotification;

class ContactTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_contact_form_submission_success()
    {
        Mail::fake();

        $contactData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'subject' => $this->faker->sentence,
            'message' => $this->faker->paragraph,
        ];

        $response = $this->postJson('/api/contact', $contactData);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Đã nhận yêu cầu. Kiểm tra email để xác nhận.'
                 ]);

        // Assert contact message was saved
        $this->assertDatabaseHas('contact_messages', [
            'name' => $contactData['name'],
            'email' => $contactData['email'],
            'subject' => $contactData['subject'],
        ]);

        // Assert emails were queued
        Mail::assertQueued(ContactResponse::class);
        Mail::assertQueued(AdminContactNotification::class);
    }

    public function test_contact_form_validation_errors()
    {
        $response = $this->postJson('/api/contact', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'email', 'phone', 'subject', 'message']);
    }

    public function test_contact_form_email_validation()
    {
        $response = $this->postJson('/api/contact', [
            'name' => 'Test User',
            'email' => 'quyenjpn@gmail.com',
            'phone' => '123456789',
            'subject' => 'Test Subject',
            'message' => 'Test Message',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }
}

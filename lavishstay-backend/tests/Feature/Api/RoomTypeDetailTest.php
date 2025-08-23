<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\RoomType;
use App\Models\RoomTypeImage;
use App\Models\Amenity;
use App\Models\Review;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class RoomTypeDetailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        $this->roomType = RoomType::factory()->create([
            'room_code' => 'deluxe',
            'name' => 'Deluxe Room',
            'description' => 'A luxurious room with great amenities',
            'total_room' => 10,
            'base_price' => 1500000,
            'is_active' => 1
        ]);

        $this->amenity = Amenity::factory()->create([
            'name' => 'WiFi',
            'is_active' => 1
        ]);

        $this->roomType->amenities()->attach($this->amenity->amenity_id);

        RoomTypeImage::factory()->create([
            'room_type_id' => $this->roomType->room_type_id,
            'image_path' => '/storage/room-types/1/1.jpg',
            'is_main' => 1
        ]);
    }

    /** @test */
    public function it_can_get_room_type_detail_by_slug()
    {
        $response = $this->getJson('/api/room-types/deluxe');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'slug' => 'deluxe',
                    'name' => 'Deluxe Room',
                    'flags' => [
                        'booking_allowed' => false
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_returns_404_for_non_existent_slug()
    {
        $response = $this->getJson('/api/room-types/non-existent');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Room type not found'
            ]);
    }

    /** @test */
    public function it_validates_query_parameters()
    {
        $response = $this->getJson('/api/room-types/deluxe?locale=invalid&currency=INVALID&reviews_per_page=100');

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed'
            ]);
    }

    /** @test */
    public function it_returns_correct_image_urls()
    {
        $response = $this->getJson('/api/room-types/deluxe');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        $this->assertStringContains('http://localhost/storage/room-types', $data['images']['main']);
        $this->assertIsArray($data['images']['gallery']);
    }

    /** @test */
    public function it_includes_amenities_information()
    {
        $response = $this->getJson('/api/room-types/deluxe');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        $this->assertIsArray($data['amenities']);
        $this->assertArrayHasKey('id', $data['amenities'][0]);
        $this->assertArrayHasKey('name', $data['amenities'][0]);
        $this->assertArrayHasKey('slug', $data['amenities'][0]);
    }

    /** @test */
    public function it_includes_availability_information()
    {
        $response = $this->getJson('/api/room-types/deluxe');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        $this->assertArrayHasKey('availability', $data);
        $this->assertArrayHasKey('total_rooms', $data['availability']);
        $this->assertArrayHasKey('available_rooms', $data['availability']);
        $this->assertEquals(10, $data['availability']['total_rooms']);
    }

    /** @test */
    public function it_includes_pricing_information()
    {
        $response = $this->getJson('/api/room-types/deluxe');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('currency', $data['price']);
        $this->assertArrayHasKey('min_price', $data['price']);
        $this->assertArrayHasKey('max_price', $data['price']);
        $this->assertArrayHasKey('base_price', $data['price']);
    }

    /** @test */
    public function it_includes_reviews_with_pagination()
    {
        // Create a user and booking with review
        $user = User::factory()->create();
        $booking = Booking::factory()->create([
            'room_type_id' => $this->roomType->room_type_id,
            'user_id' => $user->id
        ]);
        Review::factory()->create([
            'booking_id' => $booking->booking_id,
            'status' => 'approved'
        ]);

        $response = $this->getJson('/api/room-types/deluxe?reviews_page=1&reviews_per_page=5');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        $this->assertArrayHasKey('reviews', $data);
        $this->assertArrayHasKey('total', $data['reviews']);
        $this->assertArrayHasKey('page', $data['reviews']);
        $this->assertArrayHasKey('per_page', $data['reviews']);
        $this->assertArrayHasKey('items', $data['reviews']);
    }

    /** @test */
    public function it_includes_related_rooms()
    {
        // Create another room type with shared amenities
        $relatedRoom = RoomType::factory()->create([
            'room_code' => 'premium',
            'name' => 'Premium Room',
            'is_active' => 1
        ]);
        $relatedRoom->amenities()->attach($this->amenity->amenity_id);

        $response = $this->getJson('/api/room-types/deluxe?related_limit=5');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        $this->assertArrayHasKey('related_rooms', $data);
        $this->assertIsArray($data['related_rooms']);
    }

    /** @test */
    public function it_supports_include_parameter()
    {
        $response = $this->getJson('/api/room-types/deluxe?include=reviews,related');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        $this->assertArrayHasKey('reviews', $data);
        $this->assertArrayHasKey('related_rooms', $data);
    }

    /** @test */
    public function it_caches_responses()
    {
        Cache::flush();
        
        // First request
        $response1 = $this->getJson('/api/room-types/deluxe');
        $response1->assertStatus(200);
        
        // Second request should be from cache
        $response2 = $this->getJson('/api/room-types/deluxe');
        $response2->assertStatus(200);
        
        // Data should be the same
        $this->assertEquals($response1->json(), $response2->json());
    }

    /** @test */
    public function it_can_clear_cache()
    {
        // Make a request to populate cache
        $this->getJson('/api/room-types/deluxe');
        
        // Clear cache
        $response = $this->postJson('/api/room-types/clear-cache', [
            'slug' => 'deluxe'
        ]);
        
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Cache cleared successfully'
            ]);
    }

    /** @test */
    public function it_includes_policies_information()
    {
        $response = $this->getJson('/api/room-types/deluxe');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        $this->assertArrayHasKey('policies', $data);
        $this->assertIsArray($data['policies']);
        $this->assertNotEmpty($data['policies']);
        
        // Check policy structure
        $policy = $data['policies'][0];
        $this->assertArrayHasKey('type', $policy);
        $this->assertArrayHasKey('title', $policy);
        $this->assertArrayHasKey('description', $policy);
    }

    /** @test */
    public function it_includes_ratings_statistics()
    {
        $response = $this->getJson('/api/room-types/deluxe');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        $this->assertArrayHasKey('ratings', $data);
        $this->assertArrayHasKey('average', $data['ratings']);
        $this->assertArrayHasKey('count', $data['ratings']);
        $this->assertArrayHasKey('distribution', $data['ratings']);
        $this->assertIsArray($data['ratings']['distribution']);
    }

    /** @test */
    public function it_includes_meta_information()
    {
        $response = $this->getJson('/api/room-types/deluxe');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        $this->assertArrayHasKey('meta', $data);
        $this->assertArrayHasKey('last_updated', $data['meta']);
        $this->assertArrayHasKey('created_at', $data['meta']);
    }

    /** @test */
    public function it_includes_links_information()
    {
        $response = $this->getJson('/api/room-types/deluxe');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        $this->assertArrayHasKey('links', $data);
        $this->assertArrayHasKey('self', $data['links']);
        $this->assertArrayHasKey('images_base', $data['links']);
    }
}

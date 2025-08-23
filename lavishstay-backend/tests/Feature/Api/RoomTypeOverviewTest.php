<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\RoomType;
use App\Models\RoomTypeImage;
use App\Models\Amenity;
use App\Models\Room;
use App\Models\RoomPriceHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class RoomTypeOverviewTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        $this->createTestData();
    }

    public function test_room_types_overview_returns_successful_response()
    {
        $response = $this->getJson('/api/room-types/overview');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'meta' => [
                         'total',
                         'page',
                         'per_page',
                         'total_pages'
                     ],
                     'data' => [
                         '*' => [
                             'room_type_id',
                             'slug',
                             'title',
                             'short_description',
                             'thumbnail',
                             'gallery',
                             'starting_price',
                             'price_unit',
                             'max_adults',
                             'max_children',
                             'total_rooms',
                             'available_rooms',
                             'avg_rating',
                             'review_count',
                             'amenities',
                             'tags',
                             'badges',
                             'slug_url'
                         ]
                     ]
                 ]);

        // Test that success is true
        $this->assertTrue($response->json('success'));
    }

    public function test_room_types_overview_with_filters()
    {
        $response = $this->getJson('/api/room-types/overview?featured=true&limit=5&currency=USD');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        $this->assertLessThanOrEqual(5, count($data));
        
        // Check currency
        if (!empty($data)) {
            $this->assertEquals('USD', $data[0]['price_unit']);
        }
    }

    public function test_room_types_overview_with_price_range()
    {
        $response = $this->getJson('/api/room-types/overview?min_price=100000&max_price=2000000');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        foreach ($data as $roomType) {
            if ($roomType['starting_price']) {
                $this->assertGreaterThanOrEqual(100000, $roomType['starting_price']);
                $this->assertLessThanOrEqual(2000000, $roomType['starting_price']);
            }
        }
    }

    public function test_room_types_overview_pagination()
    {
        $response = $this->getJson('/api/room-types/overview?page=1&limit=2');

        $response->assertStatus(200);
        
        $meta = $response->json('meta');
        $this->assertEquals(1, $meta['page']);
        $this->assertEquals(2, $meta['per_page']);
        $this->assertLessThanOrEqual(2, count($response->json('data')));
    }

    public function test_room_types_overview_validation()
    {
        $response = $this->getJson('/api/room-types/overview?limit=100&currency=INVALID');

        $response->assertStatus(400)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'errors'
                 ]);

        $this->assertFalse($response->json('success'));
    }

    public function test_clear_cache_endpoint()
    {
        $response = $this->postJson('/api/room-types/overview/clear-cache');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true
                 ]);
    }

    public function test_room_type_data_structure()
    {
        $response = $this->getJson('/api/room-types/overview?limit=1');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        if (!empty($data)) {
            $roomType = $data[0];
            
            // Test required fields
            $this->assertArrayHasKey('room_type_id', $roomType);
            $this->assertArrayHasKey('title', $roomType);
            $this->assertArrayHasKey('thumbnail', $roomType);
            $this->assertArrayHasKey('gallery', $roomType);
            $this->assertArrayHasKey('amenities', $roomType);
            $this->assertArrayHasKey('badges', $roomType);
            
            // Test data types
            $this->assertIsInt($roomType['room_type_id']);
            $this->assertIsString($roomType['title']);
            $this->assertIsArray($roomType['gallery']);
            $this->assertIsArray($roomType['amenities']);
            $this->assertIsArray($roomType['badges']);
            
            // Test slug_url format
            $this->assertStringStartsWith('/room-types/', $roomType['slug_url']);
        }
    }

    private function createTestData()
    {
        // Create room types
        $roomType1 = RoomType::create([
            'room_code' => 'deluxe',
            'name' => 'Deluxe Room',
            'description' => 'Phòng giường đôi rộng rãi với đầy đủ tiện nghi hiện đại.',
            'total_room' => 10,
            'base_price' => 500000,
            'room_area' => 32,
            'view' => 'City View',
            'rating' => 4,
            'max_guests' => 2,
            'is_active' => 1
        ]);

        $roomType2 = RoomType::create([
            'room_code' => 'suite',
            'name' => 'Executive Suite',
            'description' => 'Suite sang trọng với phòng khách và phòng ngủ riêng biệt.',
            'total_room' => 5,
            'base_price' => 1500000,
            'room_area' => 64,
            'view' => 'Sea View',
            'rating' => 5,
            'max_guests' => 4,
            'is_active' => 1
        ]);

        // Create amenities
        $amenity1 = Amenity::create([
            'name' => 'WiFi miễn phí',
            'icon' => 'wifi',
            'category' => 'connectivity',
            'is_active' => 1
        ]);

        $amenity2 = Amenity::create([
            'name' => 'Điều hòa không khí',
            'icon' => 'snowflake',
            'category' => 'basic',
            'is_active' => 1
        ]);

        // Attach amenities to room types
        $roomType1->amenities()->attach([$amenity1->amenity_id, $amenity2->amenity_id]);
        $roomType2->amenities()->attach([$amenity1->amenity_id, $amenity2->amenity_id]);

        // Create images
        RoomTypeImage::create([
            'room_type_id' => $roomType1->room_type_id,
            'alt_text' => 'Deluxe Room Image',
            'image_path' => '/storage/room-types/1/1.jpg',
            'is_main' => 1
        ]);

        RoomTypeImage::create([
            'room_type_id' => $roomType2->room_type_id,
            'alt_text' => 'Suite Image',
            'image_path' => '/storage/room-types/2/1.jpg',
            'is_main' => 1
        ]);

        // Create rooms
        Room::create([
            'room_type_id' => $roomType1->room_type_id,
            'name' => 'D101',
            'status' => 'available',
            'floor_id' => 1
        ]);

        Room::create([
            'room_type_id' => $roomType2->room_type_id,
            'name' => 'S201',
            'status' => 'available',
            'floor_id' => 2
        ]);

        // Create price history
        RoomPriceHistory::create([
            'room_type_id' => $roomType1->room_type_id,
            'date' => now()->format('Y-m-d'),
            'base_price' => 500000,
            'adjusted_price' => 550000,
            'applied_rules' => json_encode([])
        ]);
    }
}

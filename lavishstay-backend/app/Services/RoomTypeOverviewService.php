<?php

namespace App\Services;

use App\Models\RoomType;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Review;
use App\Models\RoomPriceHistory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoomTypeOverviewService
{
    const PLACEHOLDER_IMAGE = '/storage/placeholders/room-placeholder.jpg';

    /**
     * Get room types overview with all necessary data for homepage
     * 
     * @param array $filters
     * @return array
     */
    public function getRoomTypesOverview(array $filters): array
    {
        $query = RoomType::query()
            ->where('is_active', 1)
            ->with([
                'images' => function ($q) {
                    $q->orderBy('is_main', 'desc')->limit(3);
                },
                'amenities:amenity_id,name,icon',
                'rooms:room_id,room_type_id,status'
            ]);

        // Apply filters
        $this->applyFilters($query, $filters);

        // Get total count before pagination
        $total = $query->count();

        // Apply pagination
        $offset = ($filters['page'] - 1) * $filters['limit'];
        $roomTypes = $query->offset($offset)->limit($filters['limit'])->get();

        // Transform data
        $data = $roomTypes->map(function ($roomType) use ($filters) {
            return $this->transformRoomType($roomType, $filters);
        });

        return [
            'total' => $total,
            'data' => $data
        ];
    }

    /**
     * Apply filters to the query
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     */
    protected function applyFilters($query, array $filters): void
    {
        // Price range filter
        if (!empty($filters['min_price']) || !empty($filters['max_price'])) {
            $query->where(function ($q) use ($filters) {
                if (!empty($filters['min_price'])) {
                    $q->where('base_price', '>=', $filters['min_price']);
                }
                if (!empty($filters['max_price'])) {
                    $q->where('base_price', '<=', $filters['max_price']);
                }
            });
        }

        // Featured filter (based on room type with high ratings or special designation)
        if ($filters['featured']) {
            $query->where(function ($q) {
                $q->where('rating', '>=', 4)
                  ->orWhereIn('room_code', ['premium_corner', 'the_level_suite', 'presidential_suite']);
            });
        }

        // Popular filter (based on booking frequency)
        if ($filters['popular']) {
            $query->withCount(['bookings' => function ($q) {
                $q->where('created_at', '>=', Carbon::now()->subDays(30));
            }])->orderBy('bookings_count', 'desc');
        }

        // Default ordering
        if (!$filters['popular']) {
            $query->orderBy('base_price', 'asc');
        }
    }

    /**
     * Transform room type model to API response format
     * 
     * @param RoomType $roomType
     * @param array $filters
     * @return array
     */
    protected function transformRoomType(RoomType $roomType, array $filters): array
    {
        // Get images with fallback
        $images = $this->getImages($roomType);
        
        // Calculate pricing
        $pricing = $this->calculatePricing($roomType, $filters['check_date'], $filters['currency']);
        
        // Get availability
        $availability = $this->getAvailability($roomType, $filters['check_date']);
        
        // Get ratings
        $ratings = $this->getRatings($roomType);
        
        // Get amenities
        $amenities = $this->getAmenities($roomType);
        
        // Generate badges
        $badges = $this->generateBadges($roomType, $pricing);

        $result = [
            'room_type_id' => $roomType->room_type_id,
            'slug' => $roomType->room_code,
            'title' => $roomType->name,
            'short_description' => $this->getShortDescription($roomType->description),
            'thumbnail' => $images['thumbnail'],
            'gallery' => $images['gallery'],
            'starting_price' => $pricing['starting_price'],
            'price_unit' => $pricing['currency'],
            'max_adults' => $roomType->max_guests ?: 2,
            'max_children' => max(0, ($roomType->max_guests ?: 2) - 1),
            'total_rooms' => $roomType->total_room,
            'available_rooms' => $availability['available_count'],
            'avg_rating' => $ratings['avg_rating'],
            'review_count' => $ratings['review_count'],
            'amenities' => $amenities,
            'tags' => $this->generateTags($roomType),
            'badges' => $badges,
            'slug_url' => "/room-types/{$roomType->room_code}"
        ];

        // Ensure all strings are valid UTF-8 to avoid json_encode errors
        return $this->sanitizeForJson($result);
    }

    /**
     * Get images with fallback
     * 
     * @param RoomType $roomType
     * @return array
     */
    protected function getImages(RoomType $roomType): array
    {
        $images = $roomType->images;
        $backendBase = $this->getBackendBase();

        if ($images->isEmpty()) {
            return [
                'thumbnail' => $backendBase . self::PLACEHOLDER_IMAGE,
                'gallery' => [$backendBase . self::PLACEHOLDER_IMAGE]
            ];
        }

        $processedImages = $images->map(function ($image) use ($backendBase) {
            $imagePath = $image->image_path ?: $image->image_url;
            return $this->normalizeImageUrl($imagePath, $backendBase);
        })->filter()->values();

        return [
            'thumbnail' => $processedImages->first() ?: ($backendBase . self::PLACEHOLDER_IMAGE),
            'gallery' => $processedImages->toArray()
        ];
    }

    /**
     * Calculate pricing based on date and currency
     * 
     * @param RoomType $roomType
     * @param string $checkDate
     * @param string $currency
     * @return array
     */
    protected function calculatePricing(RoomType $roomType, string $checkDate, string $currency): array
    {
        // Try to get dynamic pricing for the date
        $priceHistory = RoomPriceHistory::where('room_type_id', $roomType->room_type_id)
            ->where('date', '>=', $checkDate)
            ->orderBy('date', 'asc')
            ->first();

        $startingPrice = null;
        
        if ($priceHistory) {
            $startingPrice = (int) $priceHistory->adjusted_price;
        } else {
            // Fallback to base price
            $startingPrice = (int) $roomType->base_price;
        }

        // Currency conversion (if needed)
        if ($currency === 'USD' && $startingPrice) {
            $startingPrice = $this->convertToUSD($startingPrice);
        }

        return [
            'starting_price' => $startingPrice,
            'currency' => $currency
        ];
    }

    /**
     * Get room availability for specific date
     * 
     * @param RoomType $roomType
     * @param string $checkDate
     * @return array
     */
    protected function getAvailability(RoomType $roomType, string $checkDate): array
    {
        $totalRooms = $roomType->total_room;
        
        // Count rooms that are available (not out_of_service)
        $availableRooms = $roomType->rooms()
            ->where('status', 'available')
            ->count();

        // Count booked rooms for the date
        $bookedRooms = Booking::whereHas('rooms', function ($q) use ($roomType) {
                $q->where('room_type_id', $roomType->room_type_id);
            })
            ->where('check_in_date', '<=', $checkDate)
            ->where('check_out_date', '>', $checkDate)
            ->whereIn('status', ['confirmed', 'checked_in', 'operational'])
            ->count();

        $finalAvailable = max(0, $availableRooms - $bookedRooms);

        return [
            'total_count' => $totalRooms,
            'available_count' => $finalAvailable,
            'occupancy_rate' => $totalRooms > 0 ? (($totalRooms - $finalAvailable) / $totalRooms) * 100 : 0
        ];
    }

    /**
     * Get ratings and review data
     * 
     * @param RoomType $roomType
     * @return array
     */
    protected function getRatings(RoomType $roomType): array
    {
        $reviewStats = DB::table('reviews')
            ->join('booking', 'reviews.booking_id', '=', 'booking.booking_id')
            ->join('room', 'booking.room_id', '=', 'room.room_id')
            ->where('room.room_type_id', $roomType->room_type_id)
            ->where('reviews.status', 'approved')
            ->selectRaw('AVG(reviews.rating) as avg_rating, COUNT(*) as review_count')
            ->first();

        return [
            'avg_rating' => $reviewStats->avg_rating ? round($reviewStats->avg_rating, 1) : 0.0,
            'review_count' => (int) $reviewStats->review_count
        ];
    }

    /**
     * Get amenities data
     * 
     * @param RoomType $roomType
     * @return array
     */
    protected function getAmenities(RoomType $roomType): array
    {
        return $roomType->amenities->take(5)->map(function ($amenity) {
            return [
                'id' => $amenity->amenity_id,
                'name' => $amenity->name,
                'icon' => $amenity->icon
            ];
        })->toArray();
    }

    /**
     * Generate tags based on room type properties
     * 
     * @param RoomType $roomType
     * @return array
     */
    protected function generateTags(RoomType $roomType): array
    {
        $tags = [];

        if (str_contains(strtolower($roomType->view), 'sea') || str_contains(strtolower($roomType->description), 'biển')) {
            $tags[] = 'sea-view';
        }

        if (str_contains(strtolower($roomType->name), 'suite')) {
            $tags[] = 'suite';
        }

        if (str_contains(strtolower($roomType->name), 'premium') || str_contains(strtolower($roomType->name), 'level')) {
            $tags[] = 'premium';
        }

        if ($roomType->max_guests >= 4) {
            $tags[] = 'family-friendly';
        }

        return $tags;
    }

    /**
     * Generate badges for room type
     * 
     * @param RoomType $roomType
     * @param array $pricing
     * @return array
     */
    protected function generateBadges(RoomType $roomType, array $pricing): array
    {
        $badges = [];

        // Featured badge
        if ($roomType->rating >= 4 || in_array($roomType->room_code, ['premium_corner', 'the_level_suite', 'presidential_suite'])) {
            $badges[] = 'featured';
        }

        // Best seller badge (based on booking frequency)
        $recentBookings = DB::table('booking')
            ->join('room', 'booking.room_id', '=', 'room.room_id')
            ->where('room.room_type_id', $roomType->room_type_id)
            ->where('booking.created_at', '>=', Carbon::now()->subDays(30))
            ->count();

        if ($recentBookings >= 10) {
            $badges[] = 'best-seller';
        }

        // Price unavailable badge
        if (!$pricing['starting_price']) {
            $badges[] = 'price-unavailable';
        }

        // New badge for recently added room types
        if ($roomType->created_at && Carbon::parse($roomType->created_at)->diffInDays(now()) <= 30) {
            $badges[] = 'new';
        }

        return $badges;
    }

    /**
     * Get short description (limit to ~100 characters)
     * 
     * @param string $description
     * @return string
     */
    protected function getShortDescription(?string $description): string
    {
        if (!$description) {
            return 'Phòng nghỉ thoải mái với đầy đủ tiện nghi hiện đại.';
        }

        $cleaned = strip_tags($description);
        return strlen($cleaned) > 120 ? substr($cleaned, 0, 117) . '...' : $cleaned;
    }

    /**
     * Normalize image URL to absolute path
     * 
     * @param string|null $imagePath
     * @param string $backendBase
     * @return string|null
     */
    protected function normalizeImageUrl(?string $imagePath, string $backendBase): ?string
    {
        if (!$imagePath) {
            return null;
        }

        // If already absolute URL, return as-is
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            return $imagePath;
        }

        // If relative path, prepend backend base
        return $backendBase . (str_starts_with($imagePath, '/') ? $imagePath : '/' . $imagePath);
    }

    /**
     * Get backend base URL
     * 
     * @return string
     */
    protected function getBackendBase(): string
    {
        $apiBase = config('app.url', 'http://localhost:8888');
        return rtrim($apiBase, '/');
    }

    /**
     * Recursively sanitize data to ensure strings are valid UTF-8 and
     * remove/ignore malformed bytes that would break json_encode.
     *
     * @param mixed $data
     * @return mixed
     */
    protected function sanitizeForJson($data)
    {
        if (is_string($data)) {
            // iconv with //IGNORE strips invalid byte sequences
            $clean = @iconv('UTF-8', 'UTF-8//IGNORE', $data);
            if ($clean === false) {
                // Fallback: remove non-printable characters
                return preg_replace('/[\x00-\x1F\x7F]/u', '', $data);
            }
            return $clean;
        }

        if (is_array($data)) {
            $out = [];
            foreach ($data as $k => $v) {
                $key = is_string($k) ? $this->sanitizeForJson($k) : $k;
                $out[$key] = $this->sanitizeForJson($v);
            }
            return $out;
        }

        // For objects, convert to array then sanitize
        if (is_object($data)) {
            return $this->sanitizeForJson((array) $data);
        }

        // Scalars (int, float, bool, null)
        return $data;
    }

    /**
     * Convert VND to USD (simplified)
     * 
     * @param int $vndAmount
     * @return int
     */
    protected function convertToUSD(int $vndAmount): int
    {
        // Simplified conversion rate (in real app, use live rates)
        $exchangeRate = 24000; // 1 USD = 24,000 VND
        return (int) round($vndAmount / $exchangeRate);
    }
}

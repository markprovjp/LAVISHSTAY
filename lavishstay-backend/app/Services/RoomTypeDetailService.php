<?php

namespace App\Services;

use App\Models\RoomType;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class RoomTypeDetailService
{
    /**
     * Get room type details by slug
     *
     * @param string $slug
     * @param array $filters
     * @return array|null
     */
    public function getRoomTypeDetail(string $slug, array $filters): ?array
    {
        // Allow lookup by numeric id or by room_code (slug)
        $query = RoomType::where('is_active', 1)
            ->with([
                'images',
                'amenities' => function ($query) {
                    $query->where('is_active', 1);
                },
                'rooms'
            ]);

        // If the provided slug is a numeric id, search by primary key
        if (is_numeric($slug) && ctype_digit((string) $slug)) {
            $query->where('room_type_id', (int) $slug);
        } else {
            $query->where('room_code', $slug);
        }

        $roomType = $query->first();

        if (!$roomType) {
            return null;
        }

        $includeExpansions = $filters['include'] ?? [];

        $result = [
            'id' => $roomType->room_type_id,
            'slug' => $roomType->room_code,
            'code' => $roomType->room_code,
            'name' => $roomType->name,
            'short_description' => $this->truncateText($roomType->description, 150),
            'description_html' => '<p>' . nl2br(e($roomType->description)) . '</p>',
            'description_plain' => strip_tags($roomType->description),
            'status' => $roomType->is_active ? 'published' : 'draft',
            'capacity' => [
                'adults' => $roomType->max_guests ?? 2,
                'children' => 2, // Default value
            ],
            'size' => $roomType->room_area,
            'max_occupancy' => $roomType->max_guests,
            'images' => $this->getImages($roomType),
            'price' => $this->calculatePricing($roomType, $filters['currency']),
            'availability' => $this->getAvailability($roomType),
            'amenities' => $this->getAmenities($roomType),
            'policies' => $this->getPolicies(),
            'ratings' => $this->getRatings($roomType),
            'meta' => [
                'last_updated' => $roomType->updated_at ? $roomType->updated_at->toISOString() : null,
                'created_at' => $roomType->created_at ? $roomType->created_at->toISOString() : null,
            ],
            'flags' => [
                'booking_allowed' => false, // As per requirement
            ],
            'links' => [
                'self' => url("/api/room-types/{$slug}"),
                'images_base' => config('app.url') . '/storage/room-types/' . $roomType->room_type_id . '/',
            ]
        ];

        // Add optional expansions
        if (in_array('reviews', $includeExpansions) || empty($includeExpansions)) {
            $result['reviews'] = $this->getReviews($roomType, $filters);
        }

        if (in_array('related', $includeExpansions) || empty($includeExpansions)) {
            $result['related_rooms'] = $this->getRelatedRooms($roomType, $filters['related_limit']);
        }

        if (in_array('price_by_date', $includeExpansions)) {
            $result['price']['price_by_date'] = $this->getPriceByDate($roomType, $filters['currency']);
        }

        return $this->sanitizeForJson($result);
    }

    /**
     * Get room type images
     *
     * @param RoomType $roomType
     * @return array
     */
    protected function getImages(RoomType $roomType): array
    {
        $images = $roomType->images ?? collect();
        $baseUrl = $this->getBackendBase();

        $gallery = $images->map(function ($image) use ($baseUrl) {
            $imagePath = $image->image_path ?? $image->image_url ?? null;
            if (!$imagePath) return null;

            // If absolute URL, return as-is
            if (preg_match('#^https?://#i', $imagePath)) {
                return $imagePath;
            }

            // Normalize relative paths to include backend base and /storage prefix
            if (str_starts_with($imagePath, '/storage/')) {
                return $baseUrl . $imagePath;
            }

            return $baseUrl . '/storage/' . ltrim($imagePath, '/');
        })->filter()->values()->toArray();

        $mainImage = $images->where('is_main', 1)->first();
        if (!$mainImage && $images->isNotEmpty()) {
            $mainImage = $images->first();
        }

        $mainImageUrl = null;
        if ($mainImage) {
            $imagePath = $mainImage->image_path ?? $mainImage->image_url ?? null;
            if ($imagePath) {
                if (preg_match('#^https?://#i', $imagePath)) {
                    $mainImageUrl = $imagePath;
                } elseif (str_starts_with($imagePath, '/storage/')) {
                    $mainImageUrl = $baseUrl . $imagePath;
                } else {
                    $mainImageUrl = $baseUrl . '/storage/' . ltrim($imagePath, '/');
                }
            }
        }

        return [
            'main' => $mainImageUrl,
            'gallery' => $gallery
        ];
    }

    /**
     * Calculate pricing information
     *
     * @param RoomType $roomType
     * @param string $currency
     * @return array
     */
    protected function calculatePricing(RoomType $roomType, string $currency): array
    {
        $basePrice = $roomType->base_price;
        
        // Get recent price data from room_price_history
        $priceHistory = DB::table('room_price_history')
            ->where('room_type_id', $roomType->room_type_id)
            ->where('date', '>=', now()->subDays(30))
            ->orderBy('date', 'desc')
            ->get();

        $minPrice = $basePrice;
        $maxPrice = $basePrice;

        if ($priceHistory->isNotEmpty()) {
            $minPrice = min($basePrice, $priceHistory->min('adjusted_price') ?? $basePrice);
            $maxPrice = max($basePrice, $priceHistory->max('adjusted_price') ?? $basePrice);
        }

        return [
            'currency' => $currency,
            'min_price' => (int) $minPrice,
            'max_price' => (int) $maxPrice,
            'base_price' => (int) $basePrice,
        ];
    }

    /**
     * Get availability information
     *
     * @param RoomType $roomType
     * @return array
     */
    protected function getAvailability(RoomType $roomType): array
    {
        $totalRooms = $roomType->total_room;
        
        // Count rooms currently booked for today
        $bookedRooms = Booking::where('room_type_id', $roomType->room_type_id)
            ->where('check_in_date', '<=', now())
            ->where('check_out_date', '>', now())
            ->whereIn('status', ['Confirmed', 'Operational'])
            ->count();

        $availableRooms = max(0, $totalRooms - $bookedRooms);

        return [
            'total_rooms' => $totalRooms,
            'available_rooms' => $availableRooms,
            'next_available_date' => $availableRooms > 0 ? null : $this->getNextAvailableDate($roomType),
        ];
    }

    /**
     * Get next available date
     *
     * @param RoomType $roomType
     * @return string|null
     */
    protected function getNextAvailableDate(RoomType $roomType): ?string
    {
        // Simple implementation - find next date with available rooms
        $date = now()->addDay();
        for ($i = 0; $i < 30; $i++) {
            $bookedRooms = Booking::where('room_type_id', $roomType->room_type_id)
                ->where('check_in_date', '<=', $date)
                ->where('check_out_date', '>', $date)
                ->whereIn('status', ['Confirmed', 'Operational'])
                ->count();

            if ($bookedRooms < $roomType->total_room) {
                return $date->format('Y-m-d');
            }
            $date->addDay();
        }

        return null;
    }

    /**
     * Get amenities
     *
     * @param RoomType $roomType
     * @return array
     */
    protected function getAmenities(RoomType $roomType): array
    {
        return $roomType->amenities->map(function ($amenity) {
            return [
                'id' => $amenity->amenity_id,
                'name' => $amenity->name,
                'slug' => strtolower(str_replace(' ', '_', $amenity->name)),
                'icon' => $amenity->icon,
                'group' => $amenity->category,
            ];
        })->toArray();
    }

    /**
     * Get policies (static for now)
     *
     * @return array
     */
    protected function getPolicies(): array
    {
        return [
            [
                'type' => 'cancellation',
                'title' => 'Chính sách hủy phòng',
                'description' => 'Khách có thể hủy miễn phí trước 24 giờ check-in. Sau thời gian này sẽ bị tính phí 50% giá phòng.'
            ],
            [
                'type' => 'checkin',
                'title' => 'Thời gian nhận phòng',
                'description' => 'Check-in: 14:00 - 00:00. Check-out: 06:00 - 12:00.'
            ],
            [
                'type' => 'child',
                'title' => 'Chính sách trẻ em',
                'description' => 'Trẻ em dưới 6 tuổi được miễn phí khi ở chung giường với người lớn.'
            ],
            [
                'type' => 'pet',
                'title' => 'Chính sách thú cưng',
                'description' => 'Không cho phép mang theo thú cưng.'
            ]
        ];
    }

    /**
     * Get ratings and reviews statistics
     *
     * @param RoomType $roomType
     * @return array
     */
    protected function getRatings(RoomType $roomType): array
    {
        $reviews = Review::whereHas('booking', function ($query) use ($roomType) {
            $query->where('room_type_id', $roomType->room_type_id);
        })
        ->where('status', 'approved')
        ->get();

        if ($reviews->isEmpty()) {
            return [
                'average' => 0,
                'count' => 0,
                'distribution' => [
                    '1' => 0,
                    '2' => 0,
                    '3' => 0,
                    '4' => 0,
                    '5' => 0,
                ]
            ];
        }

        $average = $reviews->avg('rating');
        $distribution = [
            '1' => $reviews->where('rating', '>=', 1)->where('rating', '<', 2)->count(),
            '2' => $reviews->where('rating', '>=', 2)->where('rating', '<', 3)->count(),
            '3' => $reviews->where('rating', '>=', 3)->where('rating', '<', 4)->count(),
            '4' => $reviews->where('rating', '>=', 4)->where('rating', '<', 5)->count(),
            '5' => $reviews->where('rating', '5')->count(),
        ];

        return [
            'average' => round($average, 1),
            'count' => $reviews->count(),
            'distribution' => $distribution
        ];
    }

    /**
     * Get reviews with pagination
     *
     * @param RoomType $roomType
     * @param array $filters
     * @return array
     */
    protected function getReviews(RoomType $roomType, array $filters): array
    {
        $page = $filters['reviews_page'] ?? 1;
        $perPage = $filters['reviews_per_page'] ?? 10;

        $reviewsQuery = Review::whereHas('booking', function ($query) use ($roomType) {
            $query->where('room_type_id', $roomType->room_type_id);
        })
        ->where('status', 'approved')
        ->with(['booking.user', 'reviewMedia'])
        ->orderBy('created_at', 'desc');

        $total = $reviewsQuery->count();
        $reviews = $reviewsQuery->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        $baseUrl = $this->getBackendBase();

        $reviewItems = $reviews->map(function ($review) use ($baseUrl) {
            $user = $review->booking->user ?? null;

            // Normalize review media
            $media = collect($review->reviewMedia ?? [])->map(function ($m) use ($baseUrl) {
                $path = $m->file_url ?? null;
                if (!$path) return null;

                if (preg_match('#^https?://#i', $path)) {
                    $url = $path;
                } elseif (str_starts_with($path, '/storage/')) {
                    $url = $baseUrl . $path;
                } else {
                    $url = $baseUrl . '/storage/' . ltrim($path, '/');
                }

                return [
                    'url' => $url,
                    'type' => $m->file_type ?? null,
                ];
            })->filter()->values()->toArray();

            $mediaUrls = array_map(function ($m) {
                return $m['url'];
            }, $media);

            // Admin reply mapping (if any)
            $replies = [];
            if (!empty($review->admin_reply_content)) {
                $replies[] = [
                    'id' => 'admin_' . $review->review_id,
                    'userName' => $review->admin_name ?? 'Admin',
                    'isStaff' => true,
                    'content' => $review->admin_reply_content,
                    'createdAt' => $review->admin_reply_date ? $review->admin_reply_date->toISOString() : ($review->updated_at ? $review->updated_at->toISOString() : null),
                ];
            }

            return [
                'id' => $review->review_id,
                'user' => [
                    'id' => $user ? $user->id : null,
                    'name' => $user ? $user->name : $review->booking->guest_name,
                    'avatar' => $user ? $user->avatar : null,
                ],
                'rating' => (float) $review->rating,
                'title' => $review->title,
                'body' => $review->comment,
                'pros' => $review->pros ?? null,
                'cons' => $review->cons ?? null,
                'detailed_scores' => $review->detailed_scores ?? null,
                'helpful' => (int) ($review->helpful ?? 0),
                'not_helpful' => (int) ($review->not_helpful ?? 0),
                'media' => $media,
                'media_urls' => $mediaUrls,
                'replies' => $replies,
                'created_at' => $review->created_at ? $review->created_at->toISOString() : null,
            ];
        })->toArray();

        return [
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'items' => $reviewItems
        ];
    }

    /**
     * Get related room types
     *
     * @param RoomType $roomType
     * @param int $limit
     * @return array
     */
    protected function getRelatedRooms(RoomType $roomType, int $limit): array
    {
        // Get room types that share amenities
        $sharedAmenityIds = $roomType->amenities->pluck('amenity_id')->toArray();
        
        $relatedRooms = RoomType::where('room_type_id', '!=', $roomType->room_type_id)
            ->where('is_active', 1)
            ->whereHas('amenities', function ($query) use ($sharedAmenityIds) {
                // fully qualify column to avoid ambiguous column errors when joins occur
                $query->whereIn('amenities.amenity_id', $sharedAmenityIds);
            })
            ->with(['images', 'amenities'])
            ->get()
            ->map(function ($room) use ($roomType) {
                // Calculate similarity score based on shared amenities and price proximity
                $sharedAmenities = $room->amenities->pluck('amenity_id')
                    ->intersect($roomType->amenities->pluck('amenity_id'))
                    ->count();
                
                $priceDiff = abs($room->base_price - $roomType->base_price);
                $priceScore = max(0, 100 - ($priceDiff / 10000)); // Normalize price difference
                
                $room->similarity_score = $sharedAmenities * 10 + $priceScore;
                return $room;
            })
            ->sortByDesc('similarity_score')
            ->take($limit);

        return $relatedRooms->map(function ($room) {
            $images = $room->images ?? collect();
            $thumbnail = null;
            $baseUrl = $this->getBackendBase();
            if ($images->isNotEmpty()) {
                $firstImage = $images->first();
                $imagePath = $firstImage->image_path ?? $firstImage->image_url ?? null;
                if ($imagePath) {
                    if (preg_match('#^https?://#i', $imagePath)) {
                        $thumbnail = $imagePath;
                    } elseif (str_starts_with($imagePath, '/storage/')) {
                        $thumbnail = $baseUrl . $imagePath;
                    } else {
                        $thumbnail = $baseUrl . '/storage/' . ltrim($imagePath, '/');
                    }
                }
            }

            return [
                'id' => $room->room_type_id,
                'slug' => $room->room_code,
                'name' => $room->name,
                'thumbnail' => $thumbnail,
                'price' => [
                    'min' => (int) $room->base_price,
                    'max' => (int) $room->base_price * 1.3, // Estimated max
                ],
                'short_description' => $this->truncateText($room->description, 100),
                'tags' => $room->amenities->take(3)->pluck('name')->toArray(),
                'score' => round($room->similarity_score, 1),
            ];
        })->values()->toArray();
    }

    /**
     * Get price by date (next 90 days)
     *
     * @param RoomType $roomType
     * @param string $currency
     * @return array
     */
    protected function getPriceByDate(RoomType $roomType, string $currency): array
    {
        $startDate = now();
        $endDate = now()->addDays(90);

        $priceHistory = DB::table('room_price_history')
            ->where('room_type_id', $roomType->room_type_id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();

        return $priceHistory->map(function ($price) {
            return [
                'date' => $price->date,
                'price' => (int) $price->adjusted_price,
            ];
        })->toArray();
    }

    /**
     * Truncate text to specified length
     *
     * @param string|null $text
     * @param int $length
     * @return string
     */
    protected function truncateText(?string $text, int $length): string
    {
        if (!$text) return '';
        
        $text = strip_tags($text);
        if (strlen($text) <= $length) {
            return $text;
        }
        
        return substr($text, 0, $length) . '...';
    }

    /**
     * Sanitize data for JSON encoding
     *
     * @param mixed $data
     * @return mixed
     */
    protected function sanitizeForJson($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitizeForJson'], $data);
        }
        
        if (is_string($data)) {
            // Remove invalid UTF-8 characters
            return iconv('UTF-8', 'UTF-8//IGNORE', $data);
        }
        
        return $data;
    }

    /**
     * Get backend base URL (without trailing slash)
     *
     * @return string
     */
    protected function getBackendBase(): string
    {
        // Prefer the current request host (includes port) when available
        try {
            if (function_exists('request')) {
                $req = request();
                if ($req && method_exists($req, 'getSchemeAndHttpHost')) {
                    $host = $req->getSchemeAndHttpHost();
                    if ($host) {
                        return rtrim($host, '/');
                    }
                }
            }
        } catch (\Throwable $e) {
            // ignore and fallback to config
        }

        $appUrl = config('app.url') ?? env('APP_URL', 'http://localhost:8888');
        return rtrim($appUrl, '/');
    }
}

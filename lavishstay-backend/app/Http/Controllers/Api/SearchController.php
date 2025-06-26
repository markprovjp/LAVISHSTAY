<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class SearchController extends Controller
{
    /**
     * Pricing rules for guest calculations
     */
    private const PRICING_RULES = [
        'adult_price' => 670000, // Adults >= 12 years old
        'child_with_bed_price' => 335000, // Children 6-12 with extra bed
        'child_no_bed_price' => 110000, // Children 6-12 without extra bed  
        'child_free_price' => 0, // Children < 6 years old (free)
        'extra_bed_price' => 1080000, // Extra bed per night
    ];

    /**
     * Search for available rooms with pricing calculation
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchRooms(Request $request)
    {
        try {
            \Log::info('Search API called', ['request' => $request->all()]);
            
            // Validate input - simplified parameters
            $validated = $request->validate([
                'check_in' => 'required|date|after_or_equal:today',
                'check_out' => 'required|date|after:check_in',
                'adults' => 'required|integer|min:1|max:10',
                'children' => 'integer|min:0|max:8',
                'children_ages' => 'array',
                'guest_type' => 'string|in:solo,couple,family_young,group|nullable',
            ]);

            \Log::info('Validation passed', ['validated' => $validated]);

            $checkIn = Carbon::parse($validated['check_in']);
            $checkOut = Carbon::parse($validated['check_out']);
            $nights = $checkIn->diffInDays($checkOut);
            
            if ($nights <= 0) {
                throw ValidationException::withMessages([
                    'check_out' => 'Check-out date must be after check-in date'
                ]);
            }

            \Log::info('Date parsing successful', ['nights' => $nights]);

            // Get all room types with real data
            $roomTypes = DB::table('room_types')
                ->select('room_type_id as id', 'name', 'room_code', 'description', 'total_room')
                ->where('total_room', '>', 0)
                ->get();

            \Log::info('Room types fetched', ['count' => $roomTypes->count()]);

            $results = [];
            foreach ($roomTypes as $roomType) {
                // Get room type details with amenities and images
                $roomTypeDetails = $this->getRoomTypeDetails($roomType->id);

                // Get available rooms for this room type
                $availableRooms = $this->getAvailableRooms($roomType->id, $checkIn, $checkOut);
                
                // Skip if no available rooms
                if ($availableRooms->isEmpty()) {
                    continue;
                }

                // Simple pricing calculation
                $pricing = $this->calculateSimplePricing(
                    $roomType,
                    $roomTypeDetails,
                    $validated['adults'],
                    $validated['children'] ?? 0,
                    $validated['children_ages'] ?? [],
                    $validated['guest_type'] ?? 'solo',
                    $nights
                );

                $results[] = [
                    'room_type' => array_merge((array)$roomType, $roomTypeDetails, [
                        'available_rooms' => $availableRooms->count(),
                    ]),
                    'pricing' => $pricing,
                    'booking_details' => [
                        'check_in' => $checkIn->format('Y-m-d'),
                        'check_out' => $checkOut->format('Y-m-d'),
                        'nights' => $nights,
                        'guests' => [
                            'adults' => $validated['adults'],
                            'children' => $validated['children'] ?? 0,
                            'children_ages' => $validated['children_ages'] ?? [],
                            'guest_type' => $validated['guest_type'] ?? null,
                        ],
                    ],
                ];
            }

            // Sort by total price
            usort($results, function($a, $b) {
                return $a['pricing']['total_price'] <=> $b['pricing']['total_price'];
            });

            \Log::info('Search completed successfully', ['results_count' => count($results)]);

            return response()->json([
                'success' => true,
                'data' => [
                    'results' => $results,
                    'search_params' => [
                        'check_in' => $checkIn->format('Y-m-d'),
                        'check_out' => $checkOut->format('Y-m-d'),
                        'nights' => $nights,
                        'guests' => [
                            'adults' => $validated['adults'],
                            'children' => $validated['children'] ?? 0,
                            'guest_type' => $validated['guest_type'] ?? null,
                            'total' => $validated['adults'] + ($validated['children'] ?? 0),
                        ],
                    ],
                ],
            ]);

        } catch (ValidationException $e) {
            \Log::error('Validation error', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Search error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Search failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Simple pricing calculation for room search
     */
    private function calculateSimplePricing($roomType, $roomTypeDetails, int $adults, int $children, array $childrenAges, string $guestType, int $nights): array
    {
        // Get base price from room details or use default
        $basePrice = $roomTypeDetails['base_price'] ?? 1000000;
        
        // Apply seasonal multiplier
        $seasonMultiplier = $this->getSeasonalMultiplier();
        $adjustedBasePrice = $basePrice * $seasonMultiplier;
        
        // Simple guest calculation
        $totalGuests = $adults + $children;
        $guestMultiplier = 1.0;
        
        // Add extra charge for additional guests beyond standard occupancy
        if ($totalGuests > 2) {
            $extraGuests = $totalGuests - 2;
            $guestMultiplier = 1.0 + ($extraGuests * 0.15); // 15% per extra guest
        }
        
        // Children pricing based on ages
        $childrenCharge = 0;
        if (!empty($childrenAges)) {
            foreach ($childrenAges as $age) {
                $ageValue = is_array($age) ? ($age['age'] ?? 0) : $age;
                if ($ageValue >= 12) {
                    $childrenCharge += $adjustedBasePrice * 0.8; // 80% of adult price
                } elseif ($ageValue >= 6) {
                    $childrenCharge += $adjustedBasePrice * 0.5; // 50% of adult price
                } // Children under 6 are free
            }
        }
        
        // Apply guest type multiplier
        $guestTypeMultiplier = 1.0;
        switch ($guestType) {
            case 'solo':
                $guestTypeMultiplier = 0.95; // 5% discount for solo travelers
                break;
            case 'couple':
                $guestTypeMultiplier = 1.0; // Standard price
                break;
            case 'family_young':
                $guestTypeMultiplier = 0.92; // 8% family discount
                break;
            case 'group':
                $guestTypeMultiplier = 0.88; // 12% group discount
                break;
        }
        
        $finalBasePrice = $adjustedBasePrice * $guestTypeMultiplier;
        $totalPerNight = ($finalBasePrice * $guestMultiplier) + $childrenCharge;
        $totalPrice = $totalPerNight * $nights;
        
        // Children breakdown for display
        $childrenBreakdown = [];
        if (!empty($childrenAges)) {
            foreach ($childrenAges as $age) {
                $ageValue = is_array($age) ? ($age['age'] ?? 0) : $age;
                $charge = 0;
                $category = 'child_free';
                
                if ($ageValue >= 12) {
                    $charge = $adjustedBasePrice * 0.8;
                    $category = 'adult_rate';
                } elseif ($ageValue >= 6) {
                    $charge = $adjustedBasePrice * 0.5;
                    $category = 'child_with_charge';
                }
                
                $childrenBreakdown[] = [
                    'age' => $ageValue,
                    'charge' => $charge,
                    'category' => $category,
                ];
            }
        }
        
        return [
            'base_price_per_night' => $basePrice,
            'adjusted_price_per_night' => $adjustedBasePrice,
            'seasonal_multiplier' => $seasonMultiplier,
            'guest_type_multiplier' => $guestTypeMultiplier,
            'guest_multiplier' => $guestMultiplier,
            'children_charge_per_night' => $childrenCharge,
            'total_per_night' => $totalPerNight,
            'nights' => $nights,
            'total_price' => $totalPrice,
            'breakdown' => [
                'base_room_price' => $finalBasePrice,
                'guest_surcharge' => ($guestMultiplier - 1.0) * $finalBasePrice,
                'children_charge' => $childrenCharge,
                'total_guests' => $totalGuests,
                'children_breakdown' => $childrenBreakdown,
            ],
        ];
    }

    /**
     * Calculate room pricing based on guest rules
     *
     * @param object $roomType
     * @param int $adults
     * @param int $children
     * @param array $childrenAges
     * @param int $extraBeds
     * @param int $nights
     * @return array
     */
    private function calculateRoomPricing($roomType, int $adults, int $children, array $childrenAges, int $extraBeds, int $nights): array
    {
        $basePrice = $roomType->base_price;
        $guestCharges = 0;
        $extraBedCharges = $extraBeds * self::PRICING_RULES['extra_bed_price'];
        
        $breakdown = [
            'base_room_price' => $basePrice,
            'adult_charges' => 0,
            'child_charges' => 0,
            'extra_bed_charges' => $extraBedCharges,
            'children_breakdown' => [],
        ];

        // Calculate adult charges (adults >= 12 years old)
        if ($adults > 0) {
            $breakdown['adult_charges'] = $adults * self::PRICING_RULES['adult_price'];
            $guestCharges += $breakdown['adult_charges'];
        }

        // Calculate children charges based on age
        if ($children > 0 && !empty($childrenAges)) {
            foreach ($childrenAges as $index => $childData) {
                $age = $childData['age'] ?? 0;
                $childCharge = 0;
                $category = '';

                if ($age >= 12) {
                    // Children >= 12 are considered adults
                    $childCharge = self::PRICING_RULES['adult_price'];
                    $category = 'adult_rate';
                } elseif ($age >= 6) {
                    // Children 6-12 years old
                    // For simplicity, assume they need extra bed (can be customized)
                    $needsExtraBed = ($index < $extraBeds); // First N children get extra beds
                    if ($needsExtraBed) {
                        $childCharge = self::PRICING_RULES['child_with_bed_price'];
                        $category = 'child_with_bed';
                    } else {
                        $childCharge = self::PRICING_RULES['child_no_bed_price'];
                        $category = 'child_no_bed';
                    }
                } else {
                    // Children < 6 years old are free
                    $childCharge = self::PRICING_RULES['child_free_price'];
                    $category = 'child_free';
                }

                $breakdown['children_breakdown'][] = [
                    'age' => $age,
                    'charge' => $childCharge,
                    'category' => $category,
                ];

                $breakdown['child_charges'] += $childCharge;
                $guestCharges += $childCharge;
            }
        }

        // Calculate total for all nights
        $totalRoomPrice = $basePrice * $nights;
        $totalGuestCharges = $guestCharges * $nights;
        $totalExtraBedCharges = $extraBedCharges * $nights;
        $totalPrice = $totalRoomPrice + $totalGuestCharges + $totalExtraBedCharges;

        return [
            'base_price_per_night' => $basePrice,
            'guest_charges_per_night' => $guestCharges,
            'extra_bed_charges_per_night' => $extraBedCharges,
            'total_per_night' => $basePrice + $guestCharges + $extraBedCharges,
            'nights' => $nights,
            'total_room_price' => $totalRoomPrice,
            'total_guest_charges' => $totalGuestCharges,
            'total_extra_bed_charges' => $totalExtraBedCharges,
            'total_price' => $totalPrice,
            'breakdown' => $breakdown,
            'pricing_rules_applied' => self::PRICING_RULES,
        ];
    }

    /**
     * Get pricing information for a specific room type
     *
     * @param Request $request
     * @param int $roomTypeId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoomTypePricing(Request $request, int $roomTypeId)
    {
        try {
            $validated = $request->validate([
                'adults' => 'required|integer|min:1|max:10',
                'children' => 'integer|min:0|max:8',
                'children_ages' => 'array',
                'children_ages.*.age' => 'integer|min:0|max:17',
                'extra_beds' => 'integer|min:0|max:5',
                'nights' => 'integer|min:1|max:30',
            ]);

            $roomType = RoomType::findOrFail($roomTypeId);

            $pricing = $this->calculateRoomPricing(
                $roomType,
                $validated['adults'],
                $validated['children'] ?? 0,
                $validated['children_ages'] ?? [],
                $validated['extra_beds'] ?? 0,
                $validated['nights'] ?? 1
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'room_type' => [
                        'id' => $roomType->id,
                        'name' => $roomType->name,
                        'base_price' => $roomType->base_price,
                    ],
                    'pricing' => $pricing,
                ],
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate pricing: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get available pricing rules
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPricingRules()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'pricing_rules' => self::PRICING_RULES,
                'descriptions' => [
                    'adult_price' => 'Adults and children >= 12 years old',
                    'child_with_bed_price' => 'Children 6-12 years with extra bed',
                    'child_no_bed_price' => 'Children 6-12 years without extra bed',
                    'child_free_price' => 'Children < 6 years (sharing bed with parents)',
                    'extra_bed_price' => 'Extra bed per night',
                ],
            ],
        ]);
    }

    /**
     * Get available rooms for a room type within date range
     */
    private function getAvailableRooms($roomTypeId, Carbon $checkIn, Carbon $checkOut)
    {
        return DB::table('room')
            ->select('room_id', 'name', 'base_price_vnd', 'size', 'max_guests', 'view')
            ->where('room_type_id', $roomTypeId)
            ->where('status', 'available')
            ->whereNotExists(function ($query) use ($checkIn, $checkOut) {
                $query->select(DB::raw(1))
                    ->from('bookings')
                    ->whereRaw('bookings.room_id = room.room_id')
                    ->where(function ($q) use ($checkIn, $checkOut) {
                        $q->whereBetween('check_in', [$checkIn, $checkOut])
                          ->orWhereBetween('check_out', [$checkIn, $checkOut])
                          ->orWhere(function ($innerQ) use ($checkIn, $checkOut) {
                              $innerQ->where('check_in', '<=', $checkIn)
                                     ->where('check_out', '>=', $checkOut);
                          });
                    });
            })
            ->get();
    }

    /**
     * Get detailed room type information with amenities and specifications
     */
    private function getRoomTypeDetails($roomTypeId)
    {
        // Get amenities with correct column names
        $amenities = DB::table('room_type_amenity')
            ->join('amenities', 'room_type_amenity.amenity_id', '=', 'amenities.amenity_id')
            ->where('room_type_amenity.room_type_id', $roomTypeId)
            ->select('amenities.*', 'room_type_amenity.is_highlighted')
            ->get();

        $highlightedAmenities = $amenities->where('is_highlighted', 1);

        // Get images
        $images = DB::table('room_type_image')
            ->where('room_type_id', $roomTypeId)
            ->orderBy('is_main', 'desc')
            ->pluck('image_url')
            ->toArray();

        // Get sample room for basic specs
        $sampleRoom = DB::table('room')
            ->where('room_type_id', $roomTypeId)
            ->first();

        // Get room type specific details based on room_code
        $roomTypeSpecs = $this->getRoomTypeSpecifications($roomTypeId);

        return [
            'amenities' => $amenities,
            'highlighted_amenities' => $highlightedAmenities,
            'images' => $images,
            'base_price' => $sampleRoom->base_price_vnd ?? 0,
            'max_guests' => $sampleRoom->max_guests ?? 2,
            'room_size' => $sampleRoom->size ?? 32,
            'view_type' => $sampleRoom->view ?? 'City view',
            'specifications' => $roomTypeSpecs,
        ];
    }

    /**
     * Get room type specifications based on your requirements
     */
    private function getRoomTypeSpecifications($roomTypeId)
    {
        $roomType = DB::table('room_types')->where('room_type_id', $roomTypeId)->first();
        if (!$roomType) return [];

        // Define specifications for each room type
        $specifications = [
            'deluxe' => [
                'area' => 32,
                'bed_options' => ['1 giường King', '2 giường Twin'],
                'max_adults' => 2,
                'max_children' => 2,
                'child_age_limit' => 4,
                'price_range_single' => [1130000, 1290000],
                'price_range_double' => [1290000, 1480000],
            ],
            'premium_corner' => [
                'area' => 42,
                'bed_options' => ['1 giường King', '2 giường Twin'],
                'max_adults' => 2,
                'max_children' => 1,
                'child_age_limit' => 12,
                'price_range_single' => [1390000, 1590000],
                'price_range_double' => [1590000, 1820000],
            ],
            'suite' => [
                'area' => 93,
                'bed_options' => ['1 giường King'],
                'max_adults' => 2,
                'max_children' => 0,
                'child_age_limit' => 0,
                'price_range_single' => [2150000, 2450000],
                'price_range_double' => [2450000, 2800000],
            ],
            'the_level_premium' => [
                'area' => 32,
                'bed_options' => ['1 giường King', '2 giường Twin'],
                'max_adults' => 2,
                'max_children' => 1,
                'child_age_limit' => 12,
                'price_range_single' => [1920000, 2190000],
                'price_range_double' => [2190000, 2500000],
                'special_features' => ['Quyền truy cập The Level Lounge tại tầng 33'],
            ],
            'the_level_premium_corner' => [
                'area' => 42,
                'bed_options' => ['1 giường King', '2 giường Twin'],
                'max_adults' => 2,
                'max_children' => 0,
                'child_age_limit' => 0,
                'price_range_single' => [2150000, 2450000],
                'price_range_double' => [2450000, 2800000],
                'special_features' => ['Quyền truy cập The Level Lounge tại tầng 33'],
            ],
            'the_level_suite' => [
                'area' => 93,
                'bed_options' => ['1 giường King'],
                'max_adults' => 2,
                'max_children' => 0,
                'child_age_limit' => 0,
                'price_range_single' => [2970000, 3390000],
                'price_range_double' => [3390000, 3870000],
                'special_features' => ['Quyền truy cập The Level Lounge tại tầng 33'],
            ],
            'presidential_suite' => [
                'area' => 270,
                'bed_options' => ['2 phòng ngủ với mỗi phòng 1 giường King'],
                'max_adults' => 4,
                'max_children' => 0,
                'child_age_limit' => 0,
                'price_range_single' => [38990000, 48990000],
                'price_range_double' => [38990000, 48990000],
            ],
        ];

        return $specifications[$roomType->room_code] ?? [];
    }

    /**
     * Get seasonal pricing multiplier
     */
    private function getSeasonalMultiplier(): float
    {
        $currentDate = Carbon::now();
        $month = $currentDate->month;
        $day = $currentDate->day;

        // Mùa Thấp Điểm: 01/01 - 14/05 và 04/09 - 30/10
        if (($month == 1) || 
            ($month <= 5 && $day <= 14) || 
            ($month == 9 && $day >= 4) || 
            ($month == 10)) {
            return 1.0; // Base price
        }

        // Mùa Cao Điểm: 15/05 - 03/09 (8-15% increase)
        if (($month == 5 && $day >= 15) || 
            ($month >= 6 && $month <= 8) || 
            ($month == 9 && $day <= 3)) {
            return 1.12; // 12% increase
        }

        // Check for holiday periods (35-50% increase)
        if ($this->isHolidayPeriod($currentDate)) {
            return 1.42; // 42% increase
        }

        return 1.0; // Default
    }

    /**
     * Check if current date is in holiday period
     */
    private function isHolidayPeriod(Carbon $date): bool
    {
        $month = $date->month;
        $day = $date->day;

        // Tết Nguyên Đán: 21/01 - 29/01
        if ($month == 1 && $day >= 21 && $day <= 29) return true;
        
        // Lễ 30/4-1/5: 29/04 - 01/05
        if (($month == 4 && $day >= 29) || ($month == 5 && $day == 1)) return true;
        
        // Lễ Quốc Khánh: 01/10 - 03/10
        if ($month == 10 && $day >= 1 && $day <= 3) return true;
        
        // Other holidays: 23-24/10, 30/10 - 01/11
        if (($month == 10 && ($day == 23 || $day == 24 || $day >= 30)) || 
            ($month == 11 && $day == 1)) return true;

        return false;
    }
}

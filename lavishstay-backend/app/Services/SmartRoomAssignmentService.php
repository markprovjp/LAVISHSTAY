<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Booking;
use App\Models\BookingRoom;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SmartRoomAssignmentService
{
    /**
     * Thuật toán thông minh gán phòng dựa trên yêu cầu booking
     * 
     * @param array $roomsRequests Mảng rooms từ booking request
     * @param string $checkInDate 
     * @param string $checkOutDate
     * @param array $specialRequests Yêu cầu đặc biệt từ khách
     * @return array Mảng room_id được gán
     */
    public function assignRoomsSmartly(array $roomsRequests, string $checkInDate, string $checkOutDate, array $specialRequests = [], ?float $bookingTotal = null): array
    {
        Log::info('Smart room assignment started', [
            'total_rooms_requested' => count($roomsRequests),
            'check_in' => $checkInDate,
            'check_out' => $checkOutDate,
            'special_requests' => $specialRequests
        ]);

        $assignedRooms = [];
        $checkIn = Carbon::parse($checkInDate);
        $checkOut = Carbon::parse($checkOutDate);

        // 1. Lấy tất cả phòng available
        $availableRooms = $this->getAvailableRooms($checkIn, $checkOut);
        
        if ($availableRooms->isEmpty()) {
            Log::warning('No available rooms found for the requested dates');
            return [];
        }

        // 2. Phân tích yêu cầu đặc biệt
    $preferences = $this->analyzeSpecialRequests($specialRequests, $roomsRequests, $bookingTotal);
        
        // 3. Gán phòng theo chiến lược thông minh
        if ($preferences['group_together']) {
            // Gán phòng gần nhau cho nhóm đông người
            $assignedRooms = $this->assignRoomsForGroup($roomsRequests, $availableRooms, $preferences);
        } else {
            // Gán phòng bình thường
            $assignedRooms = $this->assignRoomsNormal($roomsRequests, $availableRooms, $preferences);
        }

        // Post-process: ensure we didn't assign the same room to multiple requests
        if (!empty($assignedRooms)) {
            $assignedValues = array_values($assignedRooms);
            $uniqueAssigned = array_unique($assignedValues);
            if (count($uniqueAssigned) < count($assignedValues)) {
                Log::warning('Duplicate room assignments detected, attempting to resolve', [
                    'assigned' => $assignedRooms
                ]);

                // Try to find replacements from the available pool
                $freshAvailable = $this->getAvailableRooms($checkIn, $checkOut);
                // Remove already used room ids
                $used = array_count_values($assignedValues);
                foreach ($used as $rid => $cnt) {
                    if ($cnt > 1) {
                        // keep the first occurrence, need replacements for the rest
                        $occurrences = 0;
                        foreach ($assignedRooms as $idx => $assignedId) {
                            if ($assignedId == $rid) {
                                $occurrences++;
                                if ($occurrences > 1) {
                                    // find a candidate not in uniqueAssigned
                                    $candidate = $freshAvailable->first(function($r) use ($uniqueAssigned, $rid) {
                                        $roomId = $r->room_id ?? $r->id ?? null;
                                        return $roomId && !in_array($roomId, $uniqueAssigned) && $roomId != $rid;
                                    });
                                    if ($candidate) {
                                        $newId = $candidate->room_id ?? $candidate->id;
                                        $assignedRooms[$idx] = $newId;
                                        $uniqueAssigned[] = $newId;
                                        // remove chosen from freshAvailable
                                        $freshAvailable = $freshAvailable->reject(function($r) use ($newId) {
                                            return ($r->room_id ?? $r->id) == $newId;
                                        });
                                    } else {
                                        Log::warning('Unable to find replacement room for duplicate assignment', ['room_id' => $rid, 'index' => $idx]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        Log::info('Smart room assignment completed', [
            'assigned_rooms' => $assignedRooms,
            'success_rate' => count($assignedRooms) . '/' . count($roomsRequests)
        ]);

        return $assignedRooms;
    }

    /**
     * Lấy danh sách phòng available trong khoảng thời gian
     * Sử dụng logic giống như ReceptionController::getAssignmentPreview
     */
    private function getAvailableRooms(Carbon $checkIn, Carbon $checkOut)
    {
        // Use the same logic as ReceptionController: only exclude rooms from 'confirmed' and 'operational' bookings
        $conflictingRoomIds = DB::table('booking_rooms as br')
            ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
            ->whereRaw("LOWER(b.status) IN ('confirmed','operational')")
            ->whereNotNull('br.room_id')
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where('br.check_in_date', '<', $checkOut->format('Y-m-d'))
                      ->where('br.check_out_date', '>', $checkIn->format('Y-m-d'));
            })
            ->pluck('br.room_id')
            ->unique()
            ->filter()
            ->values()
            ->toArray();

        Log::info('SmartRoomAssignmentService: conflicting room_ids for dates', [
            'check_in' => $checkIn->toDateString(),
            'check_out' => $checkOut->toDateString(),
            'conflicting_room_ids' => $conflictingRoomIds
        ]);

        // Get all available rooms (not just by status='available', because room availability is based on bookings)
        $query = Room::with(['roomType']);

        if (!empty($conflictingRoomIds)) {
            $query->whereNotIn('room_id', $conflictingRoomIds);
        }

        $available = $query->orderBy('floor_id', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        Log::info('SmartRoomAssignmentService: available rooms after filtering', [
            'available_room_ids' => $available->pluck('room_id')->toArray(),
            'available_count' => $available->count()
        ]);

        return $available;
    }

    /**
     * Phân tích yêu cầu đặc biệt từ khách
     */
    private function analyzeSpecialRequests(array $specialRequests, array $roomsRequests): array
    {
        $preferences = [
            'group_together' => false,
            'prefer_high_floor' => false,
            'prefer_low_floor' => false,
            'prefer_quiet' => false,
            'prefer_view' => false,
            'family_friendly' => false,
            'vip_treatment' => false
        ];

        // Phân tích từ special requests
        $requestText = strtolower(implode(' ', $specialRequests));
        
        // Detect group booking
        if (count($roomsRequests) > 1) {
            if (strpos($requestText, 'gần nhau') !== false || 
                strpos($requestText, 'cùng tầng') !== false ||
                strpos($requestText, 'kế tiếp') !== false ||
                strpos($requestText, 'group') !== false ||
                strpos($requestText, 'nhóm') !== false) {
                $preferences['group_together'] = true;
            }
        }

        // Detect floor preferences
        if (strpos($requestText, 'tầng cao') !== false || 
            strpos($requestText, 'view đẹp') !== false ||
            strpos($requestText, 'cao tầng') !== false) {
            $preferences['prefer_high_floor'] = true;
        }

        if (strpos($requestText, 'tầng thấp') !== false || 
            strpos($requestText, 'dễ đi lại') !== false) {
            $preferences['prefer_low_floor'] = true;
        }

        // Detect quiet preference
        if (strpos($requestText, 'yên tĩnh') !== false || 
            strpos($requestText, 'ít tiếng ồn') !== false ||
            strpos($requestText, 'quiet') !== false) {
            $preferences['prefer_quiet'] = true;
        }

        // Detect family booking
        $totalChildren = array_sum(array_column($roomsRequests, 'children'));
        if ($totalChildren > 0) {
            $preferences['family_friendly'] = true;
        }

        // Detect VIP (high spending)
        $totalSpending = array_sum(array_column($roomsRequests, 'room_price'));
        if ($totalSpending > 5000000) { // > 5M VND
            $preferences['vip_treatment'] = true;
        }

        return $preferences;
    }

    /**
     * Gán phòng cho nhóm (ưu tiên gần nhau)
     */
    private function assignRoomsForGroup(array $roomsRequests, $availableRooms, array $preferences): array
    {
        $assigned = [];
        // Keep a mutable copy of the global available rooms so we can remove assigned rooms
        $globalAvailable = $availableRooms;
        $roomsByFloor = $globalAvailable->groupBy('floor_id');

        // Ưu tiên tầng cao nếu yêu cầu
        if ($preferences['prefer_high_floor']) {
            $roomsByFloor = $roomsByFloor->sortKeysDesc();
        } else {
            $roomsByFloor = $roomsByFloor->sortKeys();
        }

        Log::info('Starting group room assignment', [
            'total_requests' => count($roomsRequests),
            'available_floors' => $roomsByFloor->keys()->toArray(),
            'rooms_per_floor' => $roomsByFloor->map(function($rooms) { return $rooms->count(); })->toArray()
        ]);

        foreach ($roomsByFloor as $floorId => $floorRooms) {
            if (count($assigned) >= count($roomsRequests)) break;
            
            $floorRooms = $floorRooms->sortBy('name'); // Sort by room number
            $remainingRequests = count($roomsRequests) - count($assigned);
            
            Log::info("Checking floor {$floorId}", [
                'rooms_on_floor' => $floorRooms->count(),
                'remaining_requests' => $remainingRequests,
                'already_assigned' => count($assigned)
            ]);

            if ($floorRooms->count() >= $remainingRequests) {
                // Tầng này có đủ phòng cho cả nhóm
                Log::info("Found floor with enough rooms for group", [
                    'floor_id' => $floorId,
                    'available_rooms' => $floorRooms->count(),
                    'needed_rooms' => $remainingRequests
                ]);
                // Try adjacency/contiguous assignment first if group requests prefer adjacency
                $preferAdjacent = false;
                foreach ($roomsRequests as $r) {
                    if (!empty($r['prefer_adjacent'])) { $preferAdjacent = true; break; }
                }

                if ($preferAdjacent) {
                    Log::info('Attempting contiguous block assignment for group (adjacency requested)', [
                        'floor_id' => $floorId,
                        'needed_rooms' => $remainingRequests
                    ]);

                    $block = $this->findContiguousBlock($floorRooms, $remainingRequests, $roomsRequests);
                    if ($block && $block->count() >= $remainingRequests) {
                        // Assign block rooms to requests. Match larger requests to larger-capacity rooms.
                        $blockRooms = $block->values();

                        // Sort requests by total guests desc but keep original index mapping
                        $reqsWithIndex = [];
                        foreach ($roomsRequests as $i => $rr) {
                            $reqsWithIndex[] = ['index' => $i, 'guests' => ($rr['adults'] ?? 1) + ($rr['children'] ?? 0), 'request' => $rr];
                        }
                        usort($reqsWithIndex, function($a,$b){ return $b['guests'] - $a['guests']; });

                        // Sort block rooms by capacity desc
                        $sortedBlock = $blockRooms->sortByDesc(function($room) {
                            return isset($room->roomType) ? ($room->roomType->max_guests ?? ($room->max_guests ?? 2)) : ($room->max_guests ?? 2);
                        })->values();

                        // Attempt assignment
                        $tempAssigned = [];
                        $usedRoomIds = [];
                        foreach ($reqsWithIndex as $idxReq => $ri) {
                            $assignedThis = false;
                            foreach ($sortedBlock as $bi => $roomCandidate) {
                                $rid = $roomCandidate->room_id ?? $roomCandidate->id;
                                if (in_array($rid, $usedRoomIds)) continue;
                                $cap = isset($roomCandidate->roomType) ? ($roomCandidate->roomType->max_guests ?? ($roomCandidate->max_guests ?? 2)) : ($roomCandidate->max_guests ?? 2);
                                if ($cap >= $ri['guests']) {
                                    $tempAssigned[$ri['index']] = $rid;
                                    $usedRoomIds[] = $rid;
                                    $assignedThis = true;
                                    break;
                                }
                            }
                            if (!$assignedThis) {
                                // contiguous block cannot satisfy capacity constraints
                                $tempAssigned = [];
                                break;
                            }
                        }

                        if (!empty($tempAssigned) && count($tempAssigned) >= $remainingRequests) {
                            // Commit the temporary assignments into $assigned and remove rooms from pools
                            foreach ($tempAssigned as $reqIndex => $rid) {
                                $assigned[$reqIndex] = $rid;
                                Log::info('Assigned contiguous room to request', ['request_index' => $reqIndex, 'room_id' => $rid, 'floor_id' => $floorId]);
                                $globalAvailable = $globalAvailable->reject(function($room) use ($rid) { return ($room->room_id ?? $room->id) == $rid; });
                                $floorRooms = $floorRooms->reject(function($room) use ($rid) { return ($room->room_id ?? $room->id) == $rid; });
                            }
                            break; // done for this floor
                        } else {
                            Log::info('Contiguous block found but could not satisfy capacities, falling back to per-request assignment on this floor', ['floor_id' => $floorId]);
                        }
                    } else {
                        Log::info('No contiguous block available on floor', ['floor_id' => $floorId]);
                    }
                }

                // If not assigned by contiguous block, fall back to per-request assignment
                if (count($assigned) < count($roomsRequests)) {
                    // Assign rooms systematically - one request at a time, removing used rooms
                    foreach ($roomsRequests as $index => $roomRequest) {
                        if (isset($assigned[$index])) continue;
                        
                        $bestRoom = $this->findBestRoomForRequest($roomRequest, $floorRooms, $preferences);
                        if ($bestRoom) {
                            $assigned[$index] = $bestRoom->room_id;
                            Log::info("Assigned room {$bestRoom->room_id} ({$bestRoom->name}) to request {$index}");
                            
                            // Remove assigned room from both the floor subset and the global available pool
                            $floorRooms = $floorRooms->reject(function($room) use ($bestRoom) {
                                return $room->room_id === $bestRoom->room_id;
                            });
                            $globalAvailable = $globalAvailable->reject(function($room) use ($bestRoom) {
                                return $room->room_id === $bestRoom->room_id;
                            });
                        } else {
                            Log::warning("Could not find suitable room for request {$index} on floor {$floorId}");
                        }
                    }
                }
                break; // Đã gán xong trên 1 tầng
            }
        }

        // Nếu không gán được hết trên 1 tầng, gán phòng còn lại
        if (count($assigned) < count($roomsRequests)) {
            Log::info("Not all rooms assigned on single floor, assigning remaining rooms", [
                'assigned_so_far' => count($assigned),
                'total_requests' => count($roomsRequests)
            ]);
            // Use the updated global available pool to avoid assigning the same room twice
            $assigned = $this->assignRemainingRooms($roomsRequests, $globalAvailable, $assigned, $preferences);
        }

        return $assigned;
    }    /**
     * Gán phòng bình thường (không nhóm)
     */
    private function assignRoomsNormal(array $roomsRequests, $availableRooms, array $preferences): array
    {
        $assigned = [];

        foreach ($roomsRequests as $index => $roomRequest) {
            $bestRoom = $this->findBestRoomForRequest($roomRequest, $availableRooms, $preferences);
            if ($bestRoom) {
                $assigned[$index] = $bestRoom->room_id;
                $availableRooms = $availableRooms->reject(function($room) use ($bestRoom) {
                    return $room->room_id === $bestRoom->room_id;
                });
            }
        }

        return $assigned;
    }

    /**
     * Tìm phòng tốt nhất cho 1 request cụ thể
     */
    private function findBestRoomForRequest($roomRequest, $availableRooms, array $preferences)
    {
        $roomTypeId = $roomRequest['room_type_id'];
        $adults = $roomRequest['adults'] ?? 1;
        $children = $roomRequest['children'] ?? 0;
        $totalGuests = $adults + $children;

        Log::debug('Finding best room for request', [
            'room_type_id' => $roomTypeId,
            'total_guests' => $totalGuests,
            'available_rooms_count' => $availableRooms->count()
        ]);

        // Defensive helper to extract room_type_id and max_guests from different shapes
        $extractRoomTypeId = function($room) {
            if (isset($room->room_type_id)) return $room->room_type_id;
            if (isset($room->roomType) && isset($room->roomType->room_type_id)) return $room->roomType->room_type_id;
            if (isset($room->roomType) && isset($room->roomType->id)) return $room->roomType->id;
            if (isset($room->roomType_id)) return $room->roomType_id;
            if (isset($room->id) && isset($room->room_type)) return $room->room_type;
            return null;
        };

        $extractMaxGuests = function($room) {
            if (isset($room->roomType) && isset($room->roomType->max_guests)) return intval($room->roomType->max_guests);
            if (isset($room->max_guests)) return intval($room->max_guests);
            if (isset($room->capacity)) return intval($room->capacity);
            return 2; // default fallback
        };

        // Log a small sample of available rooms to help diagnose shape/content issues
        try {
            $sample = $availableRooms->take(10)->map(function($r) use ($extractRoomTypeId, $extractMaxGuests) {
                return [
                    'room_id' => $r->room_id ?? $r->id ?? null,
                    'room_type_id' => $extractRoomTypeId($r),
                    'max_guests' => $extractMaxGuests($r),
                    'name' => $r->name ?? null,
                    'floor_id' => $r->floor_id ?? null,
                ];
            })->toArray();

            Log::debug('Available rooms sample for request', ['sample' => $sample]);
        } catch (\Exception $e) {
            Log::debug('Failed to build available rooms sample', ['error' => $e->getMessage()]);
        }

        // Filter phòng theo room_type - prefer exact match first
        $suitableRooms = $availableRooms->filter(function($room) use ($roomTypeId, $totalGuests, $extractRoomTypeId, $extractMaxGuests) {
            $rtId = $extractRoomTypeId($room);
            $maxGuests = $extractMaxGuests($room);

            $typeMatch = $rtId == $roomTypeId;
            $capacityMatch = $maxGuests >= $totalGuests;

            return $typeMatch && $capacityMatch;
        });

        if ($suitableRooms->isEmpty()) {
            // Relax constraint: try any available room regardless of room_type (to maximize assignment)
            Log::info("No exact room_type match found, falling back to any available room for assignment", [
                'room_type_id' => $roomTypeId,
                'total_guests' => $totalGuests
            ]);
            $suitableRooms = $availableRooms->filter(function($room) use ($totalGuests) {
                $maxGuests = isset($room->roomType) ? ($room->roomType->max_guests ?? 2) : ($room->max_guests ?? 2);
                return $maxGuests >= $totalGuests;
            });

            if ($suitableRooms->isEmpty()) {
                Log::warning("Still no suitable room after relaxation", ['total_guests' => $totalGuests]);
                return null;
            }
        }

        // Scoring system để chọn phòng tốt nhất
        $scoredRooms = $suitableRooms->map(function($room) use ($preferences, $roomTypeId) {
            $score = 0;
            
            // Bonus for exact room type match
            $rtId = isset($room->room_type_id) ? $room->room_type_id : ($room->roomType->room_type_id ?? null);
            if ($rtId == $roomTypeId) {
                $score += 100; // High priority for exact type match
            }
            
            // Floor preference scoring
            if ($preferences['prefer_high_floor']) {
                $score += ($room->floor_id ?? 1) * 10; // Cao hơn = điểm cao hơn
            } elseif ($preferences['prefer_low_floor']) {
                $score += (10 - ($room->floor_id ?? 1)) * 10; // Thấp hơn = điểm cao hơn
            }

            // Room number preference (even numbers might be quieter)
            if ($preferences['prefer_quiet']) {
                $roomNumber = (int)filter_var($room->name, FILTER_SANITIZE_NUMBER_INT);
                if ($roomNumber % 2 === 0) {
                    $score += 5; // Even room numbers
                }
            }

            // Family friendly (lower floors)
            if ($preferences['family_friendly'] && ($room->floor_id ?? 1) <= 3) {
                $score += 15;
            }

            // VIP treatment (higher floors, better rooms)
            if ($preferences['vip_treatment']) {
                $score += ($room->floor_id ?? 1) * 5;
                if (strpos(strtolower($room->name), '01') !== false || 
                    strpos(strtolower($room->name), '15') !== false) {
                    $score += 20; // Corner rooms
                }
            }

            $room->score = $score;
            return $room;
        });

        // Trả về phòng có điểm cao nhất
        $bestRoom = $scoredRooms->sortByDesc('score')->first();
        
        if ($bestRoom) {
            Log::debug('Selected best room', [
                'room_id' => $bestRoom->room_id,
                'room_name' => $bestRoom->name,
                'floor_id' => $bestRoom->floor_id,
                'score' => $bestRoom->score ?? 0
            ]);
        }
        
        return $bestRoom;
    }

    /**
     * Gán các phòng còn lại nếu chưa gán đủ
     */
    private function assignRemainingRooms(array $roomsRequests, $availableRooms, array $assigned, array $preferences): array
    {
        foreach ($roomsRequests as $index => $roomRequest) {
            if (isset($assigned[$index])) continue;
            
            $bestRoom = $this->findBestRoomForRequest($roomRequest, $availableRooms, $preferences);
            if ($bestRoom) {
                $assigned[$index] = $bestRoom->room_id;
                $availableRooms = $availableRooms->reject(function($room) use ($bestRoom) {
                    return $room->room_id === $bestRoom->room_id;
                });
            }
        }

        return $assigned;
    }

    /**
     * Tìm một block các phòng liền kề trên cùng một tầng có độ dài >= $needed
     * Trả về collection các phòng theo thứ tự liền kề nếu tìm thấy, null nếu không
     */
    private function findContiguousBlock($floorRooms, int $needed, array $roomsRequests)
    {
        // Build an ordered map by parsed room numbers
        $roomsByNumber = $floorRooms->mapWithKeys(function($room) {
            $num = $this->parseRoomNumber($room->name ?? '0');
            $key = $num !== null ? $num : intval($room->room_id ?? $room->id ?? 0);
            return [$key => $room];
        })->sortKeys();

        $numbers = $roomsByNumber->keys()->toArray();
        if (empty($numbers)) return null;

        // Sliding window to find contiguous integer sequences of length >= needed
        $count = count($numbers);
        for ($i = 0; $i <= $count - $needed; $i++) {
            $expected = $numbers[$i];
            $block = [];
            $j = $i;
            while ($j < $count && count($block) < $needed) {
                if ($numbers[$j] === $expected) {
                    $block[] = $roomsByNumber->get($numbers[$j]);
                    $expected++;
                    $j++;
                } else {
                    break; // non-contiguous
                }
            }
            if (count($block) >= $needed) {
                return collect($block);
            }
        }

        return null;
    }

    /**
     * Parse integer from room name, fall back to null if not parsable
     */
    private function parseRoomNumber(?string $name)
    {
        if (empty($name)) return null;
        // Extract the first integer sequence from the room name
        if (preg_match('/(\d+)/', $name, $m)) {
            return intval($m[1]);
        }
        return null;
    }

    /**
     * Cập nhật booking_rooms với room_id đã được gán
     */
    public function updateBookingRoomsWithAssignment(string $bookingCode, array $assignedRooms): bool
    {
        try {
            $bookingRooms = BookingRoom::where('booking_code', $bookingCode)
                ->orderBy('id', 'asc')
                ->get();

            if ($bookingRooms->count() !== count($assignedRooms)) {
                Log::warning('Mismatch between booking rooms and assigned rooms', [
                    'booking_code' => $bookingCode,
                    'booking_rooms_count' => $bookingRooms->count(),
                    'assigned_rooms_count' => count($assignedRooms)
                ]);
            }

            $updated = 0;
            foreach ($bookingRooms as $index => $bookingRoom) {
                if (isset($assignedRooms[$index])) {
                    $bookingRoom->update([
                        'room_id' => $assignedRooms[$index],
                        'assigned_by' => 'auto',
                        'auto_assigned' => 1
                    ]);
                    $updated++;
                    
                    Log::info('Updated booking room with assigned room', [
                        'booking_room_id' => $bookingRoom->id,
                        'room_id' => $assignedRooms[$index]
                    ]);
                }
            }

            Log::info('Room assignment update completed', [
                'booking_code' => $bookingCode,
                'updated_count' => $updated
            ]);

            return $updated > 0;
        } catch (\Exception $e) {
            Log::error('Error updating booking rooms with assignment', [
                'booking_code' => $bookingCode,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}

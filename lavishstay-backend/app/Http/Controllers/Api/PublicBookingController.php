<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PublicBookingController extends Controller
{
    /**
     * Tìm booking theo số điện thoại hoặc mã booking
     * Public endpoint - không cần authentication
     */
    public function searchBookings(Request $request)
    {
        try {
            // Validation
            $validator = Validator::make($request->all(), [
                'search' => 'required|string|min:3|max:50',
                'search_type' => 'sometimes|in:phone,booking_code,auto',
                'page' => 'sometimes|integer|min:1',
                'per_page' => 'sometimes|integer|min:1|max:50'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            $search = $request->input('search');
            $searchType = $request->input('search_type', 'auto');
            $perPage = $request->input('per_page', 20);
            
            Log::info('Public booking search', [
                'search' => $search,
                'search_type' => $searchType,
                'ip' => $request->ip()
            ]);

            // Chuẩn hóa số điện thoại (loại bỏ ký tự đặc biệt)
            $normalizedPhone = $this->normalizePhone($search);
            
            // Auto detect search type
            if ($searchType === 'auto') {
                $searchType = $this->detectSearchType($search);
            }

            // Build query
            $query = Booking::with([
                'bookingRooms.room.roomType',
                'representatives:id,booking_id,full_name,phone_number',
                'payments' => function($q) {
                    $q->where('status', 'completed')->select('booking_id', 'amount_vnd', 'payment_type');
                }
            ]);

            // Apply search conditions
            if ($searchType === 'booking_code') {
                $query->where('booking_code', 'LIKE', "%{$search}%");
            } elseif ($searchType === 'phone') {
                $query->where(function($q) use ($search, $normalizedPhone) {
                    $q->where('guest_phone', 'LIKE', "%{$normalizedPhone}%")
                      ->orWhere('guest_phone', 'LIKE', "%{$search}%")
                      ->orWhereHas('representatives', function($subQ) use ($search, $normalizedPhone) {
                          $subQ->where('phone_number', 'LIKE', "%{$normalizedPhone}%")
                               ->orWhere('phone_number', 'LIKE', "%{$search}%");
                      });
                });
            } else {
                // Auto search - try both
                $query->where(function($q) use ($search, $normalizedPhone) {
                    $q->where('booking_code', 'LIKE', "%{$search}%")
                      ->orWhere('guest_phone', 'LIKE', "%{$normalizedPhone}%")
                      ->orWhere('guest_phone', 'LIKE', "%{$search}%")
                      ->orWhereHas('representatives', function($subQ) use ($search, $normalizedPhone) {
                          $subQ->where('phone_number', 'LIKE', "%{$normalizedPhone}%")
                               ->orWhere('phone_number', 'LIKE', "%{$search}%");
                      });
                });
            }

            // Apply filters và ordering
            $query->whereNotIn('status', ['Cancelled', 'Unsuccessful'])
                  ->orderBy('created_at', 'desc');

            $bookings = $query->paginate($perPage);

            // Transform data for response
            $data = $bookings->getCollection()->map(function ($booking) {
                return [
                    'booking_id' => $booking->booking_id,
                    'booking_code' => $booking->booking_code,
                    'guest_name' => $booking->guest_name,
                    'guest_phone' => $booking->guest_phone,
                    'guest_email' => $booking->guest_email,
                    'check_in_date' => $booking->check_in_date,
                    'check_out_date' => $booking->check_out_date,
                    'total_price_vnd' => number_format($booking->total_price_vnd, 0, ',', '.'),
                    'total_price_raw' => $booking->total_price_vnd,
                    'status' => $booking->status,
                    'guest_count' => $booking->guest_count,
                    'nights' => $booking->check_in_date && $booking->check_out_date 
                        ? \Carbon\Carbon::parse($booking->check_in_date)->diffInDays($booking->check_out_date)
                        : 0,
                    'rooms' => $booking->bookingRooms->map(function ($bookingRoom) {
                        return [
                            'room_id' => $bookingRoom->room_id,
                            'room_name' => $bookingRoom->room->name ?? 'N/A',
                            'room_type' => $bookingRoom->room->roomType->name ?? 'N/A',
                            'option_name' => $bookingRoom->option_name,
                            'adults' => $bookingRoom->adults,
                            'children' => $bookingRoom->children,
                            'total_price' => number_format($bookingRoom->total_price, 0, ',', '.'),
                        ];
                    }),
                    'representatives' => $booking->representatives->map(function ($rep) {
                        return [
                            'name' => $rep->full_name,
                            'phone' => $rep->phone_number,
                        ];
                    }),
                    'total_paid' => $booking->payments->sum('amount_vnd'),
                    'created_at' => $booking->created_at->format('d/m/Y H:i'),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'meta' => [
                    'total' => $bookings->total(),
                    'page' => $bookings->currentPage(),
                    'per_page' => $bookings->perPage(),
                    'last_page' => $bookings->lastPage(),
                    'search_type' => $searchType,
                    'search_term' => $search
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Public booking search error', [
                'error' => $e->getMessage(),
                'search' => $request->input('search'),
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi tìm kiếm. Vui lòng thử lại sau.'
            ], 500);
        }
    }

    /**
     * Lấy chi tiết booking theo ID
     */
    public function getBookingDetail(Request $request, $bookingId)
    {
        try {
            // Validation
            $validator = Validator::make(['booking_id' => $bookingId], [
                'booking_id' => 'required|integer|exists:booking,booking_id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking không tồn tại'
                ], 404);
            }

            $booking = Booking::with([
                'bookingRooms.room.roomType',
                'bookingRooms.room.floor',
                'representatives',
                'payments' => function($q) {
                    $q->orderBy('created_at', 'desc');
                }
            ])->find($bookingId);

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy booking'
                ], 404);
            }

            // Transform detailed data
            $detailData = [
                'booking_id' => $booking->booking_id,
                'booking_code' => $booking->booking_code,
                'guest_name' => $booking->guest_name,
                'guest_phone' => $booking->guest_phone,
                'guest_email' => $booking->guest_email,
                'check_in_date' => $booking->check_in_date,
                'check_out_date' => $booking->check_out_date,
                'total_price_vnd' => $booking->total_price_vnd,
                'total_price_formatted' => number_format($booking->total_price_vnd, 0, ',', '.') . ' VNĐ',
                'status' => $booking->status,
                'guest_count' => $booking->guest_count,
                'children' => $booking->children,
                'notes' => $booking->notes,
                'nights' => $booking->check_in_date && $booking->check_out_date 
                    ? \Carbon\Carbon::parse($booking->check_in_date)->diffInDays($booking->check_out_date)
                    : 0,
                'rooms_detail' => $booking->bookingRooms->map(function ($bookingRoom) {
                    return [
                        'id' => $bookingRoom->id,
                        'room_id' => $bookingRoom->room_id,
                        'room_name' => $bookingRoom->room->name ?? 'N/A',
                        'room_type' => $bookingRoom->room->roomType->name ?? 'N/A',
                        'floor' => $bookingRoom->room->floor->floor_name ?? 'N/A',
                        'option_name' => $bookingRoom->option_name,
                        'adults' => $bookingRoom->adults,
                        'children' => $bookingRoom->children,
                        'children_age' => $bookingRoom->children_age,
                        'price_per_night' => number_format($bookingRoom->price_per_night, 0, ',', '.'),
                        'nights' => $bookingRoom->nights,
                        'total_price' => number_format($bookingRoom->total_price, 0, ',', '.'),
                        'check_in_date' => $bookingRoom->check_in_date,
                        'check_out_date' => $bookingRoom->check_out_date,
                    ];
                }),
                'representatives' => $booking->representatives->map(function ($rep) {
                    return [
                        'id' => $rep->id,
                        'full_name' => $rep->full_name,
                        'phone_number' => $rep->phone_number,
                        'email' => $rep->email,
                        'id_card' => $rep->id_card,
                    ];
                }),
                'payments' => $booking->payments->map(function ($payment) {
                    return [
                        'payment_id' => $payment->payment_id,
                        'amount_vnd' => number_format($payment->amount_vnd, 0, ',', '.') . ' VNĐ',
                        'amount_raw' => $payment->amount_vnd,
                        'payment_type' => $payment->payment_type,
                        'status' => $payment->status,
                        'transaction_id' => $payment->transaction_id,
                        'created_at' => $payment->created_at->format('d/m/Y H:i'),
                    ];
                }),
                'created_at' => $booking->created_at->format('d/m/Y H:i'),
                'updated_at' => $booking->updated_at->format('d/m/Y H:i'),
            ];

            return response()->json([
                'success' => true,
                'data' => $detailData
            ]);

        } catch (\Exception $e) {
            Log::error('Booking detail error', [
                'booking_id' => $bookingId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi lấy thông tin booking'
            ], 500);
        }
    }

    /**
     * Chuẩn hóa số điện thoại
     */
    private function normalizePhone($phone)
    {
        // Loại bỏ ký tự đặc biệt
        $normalized = preg_replace('/[^0-9+]/', '', $phone);
        
        // Chuyển đổi +84 thành 0
        if (str_starts_with($normalized, '+84')) {
            $normalized = '0' . substr($normalized, 3);
        }
        
        return $normalized;
    }

    /**
     * Tự động phát hiện kiểu tìm kiếm
     */
    private function detectSearchType($search)
    {
        // Nếu chứa LS- hoặc có định dạng booking code
        if (str_contains(strtoupper($search), 'LS-') || preg_match('/^[A-Z]{2}-\d{4}-\d+$/', strtoupper($search))) {
            return 'booking_code';
        }
        
        // Nếu là số điện thoại (chỉ chứa số, +, -, space, dots)
        if (preg_match('/^[\d\+\-\s\.]+$/', $search)) {
            return 'phone';
        }
        
        return 'auto';
    }
}

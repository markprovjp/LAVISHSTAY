<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
class BookingController extends Controller {
    /**
     * Gán booking vào user (dùng cho khách vừa đăng ký)
     * Route: POST /api/booking/assign
     */
    public function assignBookingToUser(Request $request)
    {
        $request->validate([
            'bookingCode' => 'required|string',
            'userId' => 'required|integer|exists:users,id',
        ]);

        $booking = \DB::table('booking')->where('booking_code', $request->bookingCode)->first();
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy booking với mã này.'
            ], 404);
        }
        // Chỉ cho phép gán nếu booking chưa có user_id
        if ($booking->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Booking đã được gán user.'
            ], 400);
        }
        \DB::table('booking')->where('booking_code', $request->bookingCode);
       
        return response()->json([
            'success' => true,
            'message' => 'Đã gán booking vào tài khoản thành công.'
        ]);
    }

    /**
     * Display a listing of bookings
     */
    public function index(Request $request)
    {
        $query = DB::table('booking as b')
            ->select([
                'b.*',
                DB::raw('GROUP_CONCAT(DISTINCT r.name) as room_names'),
                DB::raw('GROUP_CONCAT(DISTINCT rt.name) as room_type_names'),
                DB::raw('COUNT(DISTINCT br.room_id) as total_rooms'),
                DB::raw('SUM(CASE WHEN br.adults IS NOT NULL THEN br.adults ELSE 0 END) as total_adults_from_rooms'),
                DB::raw('SUM(CASE WHEN br.children IS NOT NULL THEN br.children ELSE 0 END) as total_children_from_rooms'),
                DB::raw('GROUP_CONCAT(DISTINCT br.option_name) as option_names')
            ])
            ->leftJoin('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
            ->leftJoin('room as r', 'br.room_id', '=', 'r.room_id')
            ->leftJoin('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
            ->groupBy('b.booking_id');

        // Apply filters
        if ($request->filled('booking_code')) {
            $query->where('b.booking_code', 'like', '%' . $request->booking_code . '%');
        }

        if ($request->filled('guest_name')) {
            $query->where('b.guest_name', 'like', '%' . $request->guest_name . '%');
        }

        if ($request->filled('status')) {
            $query->where('b.status', $request->status);
        }

        if ($request->filled('check_in_date')) {
            $query->whereDate('b.check_in_date', '>=', $request->check_in_date);
        }

        if ($request->filled('check_out_date')) {
            $query->whereDate('b.check_out_date', '<=', $request->check_out_date);
        }

        // Order by latest first
        $query->orderBy('b.created_at', 'desc');

        // Get paginated results
        $bookings = $query->paginate(20);

        // Get statistics
        $statistics = $this->getBookingStatistics();

        // Handle AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'bookings' => $bookings->items(),
                'statistics' => $statistics,
                'pagination' => [
                    'current_page' => $bookings->currentPage(),
                    'last_page' => $bookings->lastPage(),
                    'per_page' => $bookings->perPage(),
                    'total' => $bookings->total(),
                ]
            ]);
        }

        return view('admin.bookings.index', compact('bookings', 'statistics'));
    }

    // Route: GET /api/user/bookings
    public function getUserBookings(Request $request)
    {
        $userId = $request->user()->id ?? $request->get('user_id');
        if (!$userId) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
    
        $bookings = DB::table('booking as b')
            ->leftJoin('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
            ->leftJoin('room as r', 'br.room_id', '=', 'r.room_id')
            ->leftJoin('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
            ->leftJoin('room_types as rt2', 'b.room_type_id', '=', 'rt2.room_type_id')
            ->leftJoin('representatives as rep', function($join) {
                $join->on('rep.booking_id', '=', 'b.booking_id');
            })
            ->leftJoin('payment as p', 'b.booking_id', '=', 'p.booking_id')
            ->where('b.user_id', $userId)
            ->select([
                'b.booking_id',
                'b.booking_code',
                'b.check_in_date',
                'b.check_out_date',
                'b.status',
                'b.total_price_vnd',
                'b.created_at',
                'b.guest_count',
                'b.guest_name',
                'b.guest_email',
                'b.guest_phone',
                // Always get room_type name, fallback to booking.room_type_id
                DB::raw('COALESCE(rt.name, rt2.name) as room_type'),
                'r.name as room_name',
                'r.image as room_image',
                'r.room_id',
                'r.status as room_status',
                'br.option_id',
                'br.option_name',
                'br.price_per_night',
                'br.nights',
                'br.total_price',
                'br.adults',
                'br.children',
                'br.children_age',
                'rep.id as representative_id',
                'rep.full_name as representative_name',
                'rep.phone_number as representative_phone',
                'rep.email as representative_email',
                'rep.id_card as representative_id_card',
                'p.amount_vnd as payment_amount',
                'p.status as payment_status',
                'p.payment_type',
                'p.transaction_id',
                // Subquery: all images for room_type as JSON array (fix: use COALESCE to support bookings chưa gán room)
                DB::raw('(SELECT JSON_ARRAYAGG(JSON_OBJECT(
                    "image_id", rti.image_id,
                    "image_path", rti.image_path,
                    "alt_text", rti.alt_text,
                    "is_main", rti.is_main
                )) FROM room_type_image rti WHERE rti.room_type_id = COALESCE(rt.room_type_id, b.room_type_id)) as room_type_images'),
                // Subquery: all amenities for room_type as JSON array
                DB::raw('(SELECT JSON_ARRAYAGG(JSON_OBJECT(
                    "amenity_id", a.amenity_id,
                    "name", a.name,
                    "icon", a.icon,
                    "icon_lib", a.icon_lib,
                    "category", a.category,
                    "description", a.description,
                    "is_highlighted", rta.is_highlighted
                )) FROM room_type_amenity rta
                JOIN amenities a ON rta.amenity_id = a.amenity_id
                WHERE rta.room_type_id = COALESCE(rt.room_type_id, b.room_type_id)) as room_type_amenities')
            ])
            ->orderBy('b.created_at', 'desc')
            ->get();

        // Parse images JSON for each booking and prepend backend URL to image_path
        foreach ($bookings as $booking) {
            $booking->room_type_images = $booking->room_type_images ? json_decode($booking->room_type_images) : [];
            if (is_array($booking->room_type_images)) {
                foreach ($booking->room_type_images as $img) {
                    if (!empty($img->image_path)) {
                        // Only prepend if not already absolute
                        if (!preg_match('/^https?:\/\//', $img->image_path)) {
                            $img->image_path = 'http://localhost:8888/' . ltrim($img->image_path, '/');
                        }
                    }
                }
            }
            $booking->room_type_amenities = $booking->room_type_amenities ? json_decode($booking->room_type_amenities) : [];
        }

        Log::info("User bookings fetched " . json_encode($bookings) . " for user ID: " . $userId);
        return response()->json([
            'success' => true,
            'bookings' => $bookings
        ]);
    }

    /**
     * Show booking details
     */
    public function show(Request $request, $id)
    {
        $booking = DB::table('booking as b')
            ->select([
                'b.*',
                DB::raw('GROUP_CONCAT(DISTINCT r.name) as room_names'),
                DB::raw('GROUP_CONCAT(DISTINCT rt.name) as room_type_names'),
                DB::raw('COUNT(DISTINCT r.room_id) as total_rooms'),
                DB::raw('GROUP_CONCAT(DISTINCT br.option_name) as option_names'),
                'p.status as payment_status',
                'p.payment_type',
                'p.transaction_id',
                'p.amount_vnd as payment_amount'
            ])
            ->leftJoin('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
            ->leftJoin('room as r', 'br.room_id', '=', 'r.room_id')
            ->leftJoin('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
            ->leftJoin('payment as p', 'b.booking_id', '=', 'p.booking_id')
            ->where('b.booking_id', $id)
            ->groupBy('b.booking_id', 'p.payment_id', 'p.status', 'p.payment_type', 'p.transaction_id', 'p.amount_vnd')
            ->first();

        if (!$booking) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đặt phòng'
                ], 404);
            }
            abort(404);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'booking' => $booking
            ]);
        }

        return view('admin.bookings.show', compact('booking'));
    }


    /**
     * Get available rooms for a booking
     */
    public function getAvailableRooms(Request $request, $id)
    {
        $booking = DB::table('booking')->where('booking_id', $id)->first();
        
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }
        
        $availableRooms = DB::table('room as r')
            ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
            ->select([
                'r.room_id as id',
                'r.name',
                'r.room_number',
                'rt.name as room_type_name',
                'r.max_guests',
                'r.status',
                'r.price_per_night'
            ])
            ->where('r.status', 'available')
            ->whereNotExists(function ($query) use ($booking) {
                $query->select(DB::raw(1))
                    ->from('booking_rooms as br')
                    ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
                    ->whereRaw('br.room_id = r.room_id')
                    ->where('b.status', '!=', 'cancelled')
                    ->where(function ($q) use ($booking) {
                        $q->whereBetween('b.check_in_date', [$booking->check_in_date, $booking->check_out_date])
                          ->orWhereBetween('b.check_out_date', [$booking->check_in_date, $booking->check_out_date])
                          ->orWhere(function ($q2) use ($booking) {
                                $q2->where('b.check_in_date', '<=', $booking->check_in_date)
                                   ->where('b.check_out_date', '>=', $booking->check_out_date);
                          });
                    });
            })
            ->get();

        return response()->json([
            'success' => true,
            'rooms' => $availableRooms
        ]);
    }

    /**
     * Assign room to booking
     */
    public function assignRoom(Request $request, $id)
    {
        $request->validate([
            'room_id' => 'required|exists:room,room_id'
        ]);

        $booking = DB::table('booking')->where('booking_id', $id)->first();
        $room = DB::table('room')->where('room_id', $request->room_id)->first();

        if (!$booking || !$room) {
            return response()->json([
                'success' => false,
                'message' => 'Booking hoặc phòng không tồn tại'
            ], 404);
        }

        // Check if room is available for the booking period
        $conflictingBooking = DB::table('booking_rooms as br')
            ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
            ->where('br.room_id', $room->room_id)
            ->where('b.status', '!=', 'cancelled')
            ->where('b.booking_id', '!=', $booking->booking_id)
            ->where(function ($query) use ($booking) {
                $query->whereBetween('b.check_in_date', [$booking->check_in_date, $booking->check_out_date])
                      ->orWhereBetween('b.check_out_date', [$booking->check_in_date, $booking->check_out_date])
                      ->orWhere(function ($q) use ($booking) {
                          $q->where('b.check_in_date', '<=', $booking->check_in_date)
                            ->where('b.check_out_date', '>=', $booking->check_out_date);
                      });
            })
            ->exists();

        if ($conflictingBooking) {
            return response()->json([
                'success' => false,
                'message' => 'Phòng này đã được đặt trong thời gian này'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Remove existing room assignments for this booking
            DB::table('booking_rooms')->where('booking_id', $booking->booking_id)->delete();

            // Calculate nights and total price
            $checkIn = Carbon::parse($booking->check_in_date);
            $checkOut = Carbon::parse($booking->check_out_date);
            $nights = $checkIn->diffInDays($checkOut);
            $totalPrice = $room->price_per_night * $nights;

            // Assign new room
            DB::table('booking_rooms')->insert([
                'booking_id' => $booking->booking_id,
                'booking_code' => $booking->booking_code,
                'room_id' => $room->room_id,
                'adults' => $booking->guest_count,
                'children' => $booking->children ?? 0,
                'children_age' => $booking->children_age,
                'price_per_night' => $room->price_per_night,
                'nights' => $nights,
                'total_price' => $totalPrice,
                'check_in_date' => $booking->check_in_date,
                'check_out_date' => $booking->check_out_date,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Update booking status if it was pending
            if ($booking->status === 'Pending') {
                DB::table('booking')->where('booking_id', $booking->booking_id)
                    ->update(['status' => 'Confirmed']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đã gán phòng thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gán phòng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirm booking
     */
    public function confirm(Request $request, $id)
    {
        $booking = DB::table('booking')->where('booking_id', $id)->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking không tồn tại'
            ], 404);
        }

        if ($booking->status !== 'Pending') {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ có thể xác nhận đặt phòng đang chờ xử lý'
            ], 400);
        }

        try {
            DB::table('booking')->where('booking_id', $id)->update([
                'status' => 'Confirmed',
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đã xác nhận đặt phòng thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xác nhận đặt phòng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel booking
     */
    public function cancel(Request $request, $id)
    {
        $booking = DB::table('booking')->where('booking_id', $id)->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking không tồn tại'
            ], 404);
        }

        if (in_array($booking->status, ['Cancelled', 'Completed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể hủy đặt phòng này'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Update booking status
            DB::table('booking')->where('booking_id', $id)->update([
                'status' => 'Cancelled',
                'updated_at' => now()
            ]);

            // Free up the rooms
            DB::table('booking_rooms')->where('booking_id', $booking->booking_id)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đã hủy đặt phòng thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi hủy đặt phòng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get booking analytics data - FIXED với tên bảng đúng
     */
    public function analytics(Request $request)
    {
        $period = $request->get('period', '30'); // days
        $startDate = Carbon::now()->subDays($period);

        // Booking trends
        $bookingTrends = DB::table('booking')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Revenue trends - FIXED: Tính từ bảng payment với status completed
        $revenueTrends = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->selectRaw('DATE(p.created_at) as date, SUM(p.amount_vnd) as revenue')
                        ->where('p.created_at', '>=', $startDate)
            ->where('p.status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Status distribution
        $statusDistribution = DB::table('booking')
            ->selectRaw('status, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('status')
            ->get();

        // Top room types - FIXED: Tính revenue từ payment completed
        $topRoomTypes = DB::table('booking as b')
            ->join('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
            ->join('room as r', 'br.room_id', '=', 'r.room_id')
            ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
            ->leftJoin('payment as p', function($join) {
                $join->on('b.booking_id', '=', 'p.booking_id')
                     ->where('p.status', '=', 'completed');
            })
            ->selectRaw('
                rt.name, 
                COUNT(DISTINCT b.booking_id) as bookings_count, 
                COALESCE(SUM(p.amount_vnd), 0) as total_revenue
            ')
            ->where('b.created_at', '>=', $startDate)
            ->where('b.status', '!=', 'Cancelled')
            ->groupBy('rt.room_type_id', 'rt.name')
            ->orderBy('bookings_count', 'desc')
            ->limit(10)
            ->get();

        // Total statistics - FIXED: Tính từ payment completed
        $totalStats = [
            'total_bookings' => DB::table('booking')->where('created_at', '>=', $startDate)->count(),
            'total_revenue' => DB::table('payment as p')
                ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                ->where('p.status', 'completed')
                ->where('b.created_at', '>=', $startDate)
                ->sum('p.amount_vnd'),
            'pending_bookings' => DB::table('booking')
                ->where('created_at', '>=', $startDate)
                ->where('status', 'Pending')
                ->count(),
            'confirmed_bookings' => DB::table('booking')
                ->where('created_at', '>=', $startDate)
                ->where('status', 'Confirmed')
                ->count(),
            'cancelled_bookings' => DB::table('booking')
                ->where('created_at', '>=', $startDate)
                ->where('status', 'Cancelled')
                ->count(),
            'completed_bookings' => DB::table('booking')
                ->where('created_at', '>=', $startDate)
                ->where('status', 'Completed')
                ->count(),
        ];

        // Average booking value - FIXED: Tính từ payment completed
        $avgBookingValue = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->where('p.status', 'completed')
            ->where('b.created_at', '>=', $startDate)
            ->avg('p.amount_vnd');

        // Revenue by payment method
        $revenueByPaymentMethod = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->selectRaw('p.payment_type, SUM(p.amount_vnd) as revenue, COUNT(*) as count')
            ->where('p.status', 'completed')
            ->where('b.created_at', '>=', $startDate)
            ->groupBy('p.payment_type')
            ->orderBy('revenue', 'desc')
            ->get();

        // Monthly revenue comparison (so với tháng trước)
        $currentMonthRevenue = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->where('p.status', 'completed')
            ->whereMonth('p.created_at', Carbon::now()->month)
            ->whereYear('p.created_at', Carbon::now()->year)
            ->sum('p.amount_vnd');

        $lastMonthRevenue = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->where('p.status', 'completed')
            ->whereMonth('p.created_at', Carbon::now()->subMonth()->month)
            ->whereYear('p.created_at', Carbon::now()->subMonth()->year)
            ->sum('p.amount_vnd');

        $revenueGrowth = $lastMonthRevenue > 0 
            ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'booking_trends' => $bookingTrends,
                'revenue_trends' => $revenueTrends,
                'status_distribution' => $statusDistribution,
                'top_room_types' => $topRoomTypes,
                'total_stats' => $totalStats,
                'avg_booking_value' => round($avgBookingValue ?? 0, 0),
                'revenue_by_payment_method' => $revenueByPaymentMethod,
                'revenue_growth' => round($revenueGrowth, 2),
                'current_month_revenue' => $currentMonthRevenue,
                'last_month_revenue' => $lastMonthRevenue,
                'period' => $period
            ]
        ]);
    }

    /**
     * Export bookings to Excel
     */
    public function export(Request $request)
    {
        $query = DB::table('booking as b')
            ->select([
                'b.*',
                DB::raw('GROUP_CONCAT(DISTINCT r.name) as room_names'),
                DB::raw('GROUP_CONCAT(DISTINCT rt.name) as room_type_names'),
                DB::raw('COUNT(DISTINCT r.room_id) as total_rooms')
            ])
            ->leftJoin('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
            ->leftJoin('room as r', 'br.room_id', '=', 'r.room_id')
            ->leftJoin('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
            ->groupBy('b.booking_id');

        // Apply same filters as index
        if ($request->filled('booking_code')) {
            $query->where('b.booking_code', 'like', '%' . $request->booking_code . '%');
        }

        if ($request->filled('guest_name')) {
            $query->where('b.guest_name', 'like', '%' . $request->guest_name . '%');
        }

        if ($request->filled('status')) {
            $query->where('b.status', $request->status);
        }

        if ($request->filled('check_in_date')) {
            $query->whereDate('b.check_in_date', '>=', $request->check_in_date);
        }

        if ($request->filled('check_out_date')) {
            $query->whereDate('b.check_out_date', '<=', $request->check_out_date);
        }

        $bookings = $query->orderBy('b.created_at', 'desc')->get();

        // Create CSV content
        $csvContent = "Mã đặt phòng,Tên khách,Email,Điện thoại,Check-in,Check-out,Số phòng,Tên phòng,Loại phòng,Tổng tiền,Trạng thái,Ngày đặt\n";
        
        foreach ($bookings as $booking) {
            $csvContent .= sprintf(
                "%s,%s,%s,%s,%s,%s,%d,%s,%s,%s,%s,%s\n",
                $booking->booking_code,
                $booking->guest_name,
                $booking->guest_email,
                $booking->guest_phone,
                $booking->check_in_date,
                $booking->check_out_date,
                $booking->total_rooms ?? 1,
                $booking->room_names ?? 'Chưa chọn phòng',
                $booking->room_type_names ?? 'N/A',
                number_format($booking->total_price_vnd ?? 0),
                $this->getStatusText($booking->status),
                Carbon::parse($booking->created_at)->format('d/m/Y H:i')
            );
        }

        $filename = 'bookings_' . date('Y-m-d_H-i-s') . '.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Length', strlen($csvContent));
    }

    /**
     * Print booking details
     */
    public function print($id)
    {
        $booking = DB::table('booking as b')
            ->select([
                'b.*',
                DB::raw('GROUP_CONCAT(DISTINCT r.name) as room_names'),
                DB::raw('GROUP_CONCAT(DISTINCT rt.name) as room_type_names'),
                DB::raw('COUNT(DISTINCT r.room_id) as total_rooms'),
                DB::raw('GROUP_CONCAT(DISTINCT br.option_name) as option_names')
            ])
            ->leftJoin('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
            ->leftJoin('room as r', 'br.room_id', '=', 'r.room_id')
            ->leftJoin('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
            ->where('b.booking_id', $id)
            ->groupBy('b.booking_id')
            ->first();

        if (!$booking) {
            abort(404);
        }

        return view('admin.bookings.print', compact('booking'));
    }

    /**
     * Get booking statistics - FIXED với tên bảng đúng
     */
    private function getBookingStatistics()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'total_bookings' => DB::table('booking')->count(),
            'pending_bookings' => DB::table('booking')->where('status', 'Pending')->count(),
            'confirmed_bookings' => DB::table('booking')->where('status', 'Confirmed')->count(),
            'cancelled_bookings' => DB::table('booking')->where('status', 'Cancelled')->count(),
            'completed_bookings' => DB::table('booking')->where('status', 'Completed')->count(),
            'total_revenue' => DB::table('payment as p')
                ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                ->where('p.status', 'completed')
                ->sum('p.amount_vnd'),
            'today_bookings' => DB::table('booking')->whereDate('created_at', $today)->count(),
            'this_month_bookings' => DB::table('booking')->whereDate('created_at', '>=', $thisMonth)->count(),
            'this_month_revenue' => DB::table('payment as p')
                ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                ->where('p.status', 'completed')
                ->whereDate('b.created_at', '>=', $thisMonth)
                ->sum('p.amount_vnd'),
            'average_booking_value' => DB::table('payment as p')
                ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                ->where('p.status', 'completed')
                ->avg('p.amount_vnd'),
        ];
    }

    /**
     * Get status text in Vietnamese
     */
    private function getStatusText($status)
    {
        $statusMap = [
            'Pending' => 'Chờ xác nhận',
            'Confirmed' => 'Đã xác nhận',
            'Operational' => 'Đang hoạt động',
            'Completed' => 'Hoàn thành',
            'Cancelled' => 'Đã hủy',
            'Cancelled With Penalty' => 'Đã hủy có phạt',
            'Unsuccessful' => 'Không thành công'
        ];

        return $statusMap[$status] ?? $status;
    }

    /**
     * Bulk update booking status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'booking_ids' => 'required|array',
            'booking_ids.*' => 'exists:booking,booking_id',
            'status' => 'required|in:Pending,Confirmed,Cancelled,Completed'
        ]);

        try {
            DB::beginTransaction();

            $updatedCount = DB::table('booking')
                ->whereIn('booking_id', $request->booking_ids)
                ->update([
                    'status' => $request->status,
                    'updated_at' => now()
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Đã cập nhật trạng thái {$updatedCount} đặt phòng thành công"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật trạng thái: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk confirm bookings
     */
    public function bulkConfirm(Request $request)
    {
        $request->validate([
            'booking_ids' => 'required|array',
            'booking_ids.*' => 'exists:booking,booking_id'
        ]);

        try {
            DB::beginTransaction();

            $confirmedCount = DB::table('booking')
                ->whereIn('booking_id', $request->booking_ids)
                ->where('status', 'Pending')
                ->update([
                    'status' => 'Confirmed',
                    'updated_at' => now()
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'confirmed_count' => $confirmedCount,
                'message' => "Đã xác nhận thành công {$confirmedCount} đặt phòng"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                                'message' => 'Có lỗi xảy ra khi xác nhận hàng loạt: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk cancel bookings
     */
    public function bulkCancel(Request $request)
    {
        $request->validate([
            'booking_ids' => 'required|array',
            'booking_ids.*' => 'exists:booking,booking_id'
        ]);

        try {
            DB::beginTransaction();

            $cancelledCount = DB::table('booking')
                ->whereIn('booking_id', $request->booking_ids)
                ->whereIn('status', ['Pending', 'Confirmed'])
                ->update([
                    'status' => 'Cancelled',
                    'updated_at' => now()
                ]);

            // Free up the rooms for cancelled bookings
            DB::table('booking_rooms')
                ->whereIn('booking_id', $request->booking_ids)
                ->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'cancelled_count' => $cancelledCount,
                'message' => "Đã hủy thành công {$cancelledCount} đặt phòng"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi hủy hàng loạt: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get booking statistics for AJAX
     */
    public function getStats()
    {
        $statistics = $this->getBookingStatistics();
        return response()->json([
            'success' => true,
            'statistics' => $statistics
        ]);
    }

    /**
     * Send booking confirmation email
     */
    public function sendConfirmationEmail($id)
    {
        $booking = DB::table('booking as b')
            ->select([
                'b.*',
                DB::raw('GROUP_CONCAT(DISTINCT r.name) as room_names'),
                DB::raw('GROUP_CONCAT(DISTINCT rt.name) as room_type_names')
            ])
            ->leftJoin('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
            ->leftJoin('room as r', 'br.room_id', '=', 'r.room_id')
            ->leftJoin('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
            ->where('b.booking_id', $id)
            ->groupBy('b.booking_id')
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking không tồn tại'
            ], 404);
        }

        try {
            // Send confirmation email
            // Mail::to($booking->guest_email)->send(new BookingConfirmationMail($booking));

            return response()->json([
                'success' => true,
                'message' => 'Đã gửi email xác nhận thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gửi email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create new booking modal
     */
    public function showNewBookingModal()
    {
        // Get available rooms
        $rooms = DB::table('room as r')
            ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
            ->select([
                'r.room_id',
                'r.name',
                'r.room_number',
                'rt.name as room_type_name',
                'r.max_guests',
                'r.price_per_night'
            ])
            ->where('r.status', 'available')
            ->get();

        return response()->json([
            'success' => true,
            'rooms' => $rooms
        ]);
    }

    /**
     * Store new booking
     */
    public function store(Request $request)
    {
        $request->validate([
            'guest_name' => 'required|string|max:100',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:20',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'guest_count' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'room_id' => 'required|exists:room,room_id'
        ]);

        try {
            DB::beginTransaction();

            // Generate booking code
            $bookingCode = 'BK' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            // Get room details
            $room = DB::table('room')->where('room_id', $request->room_id)->first();
            
            // Calculate nights and total price
            $checkIn = Carbon::parse($request->check_in_date);
            $checkOut = Carbon::parse($request->check_out_date);
            $nights = $checkIn->diffInDays($checkOut);
            $totalPrice = $room->price_per_night * $nights;

            // Create booking
            $bookingId = DB::table('booking')->insertGetId([
                'booking_code' => $bookingCode,
                'check_in_date' => $request->check_in_date,
                'check_out_date' => $request->check_out_date,
                'total_price_vnd' => $totalPrice,
                'guest_count' => $request->guest_count,
                'status' => 'Pending',
                'guest_name' => $request->guest_name,
                'guest_email' => $request->guest_email,
                'guest_phone' => $request->guest_phone,
                'children' => $request->children ?? 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Create booking room
            DB::table('booking_rooms')->insert([
                'booking_id' => $bookingId,
                'booking_code' => $bookingCode,
                'room_id' => $request->room_id,
                'adults' => $request->guest_count,
                'children' => $request->children ?? 0,
                'price_per_night' => $room->price_per_night,
                'nights' => $nights,
                'total_price' => $totalPrice,
                'check_in_date' => $request->check_in_date,
                'check_out_date' => $request->check_out_date,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đã tạo đặt phòng thành công',
                'booking_code' => $bookingCode
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo đặt phòng: ' . $e->getMessage()
            ], 500);
        }
    }
}


<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Http\Requests\Coupon\StoreCouponRequest;
use App\Http\Requests\Coupon\UpdateCouponRequest;
use App\Http\Resources\Coupon\CouponResource;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AdminCouponController extends Controller
{
    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
        $this->middleware('auth:sanctum');
        // $this->middleware('role:admin'); // Uncomment nếu có middleware role
    }

    /**
     * Display a listing of coupons.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Coupon::with('creator');

        // Filter by status
        if ($request->has('active')) {
            $query->where('active', $request->boolean('active'));
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Search by code
        if ($request->filled('search')) {
            $query->where('code', 'like', '%' . strtoupper($request->search) . '%');
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $coupons = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => CouponResource::collection($coupons),
            'meta' => [
                'current_page' => $coupons->currentPage(),
                'last_page' => $coupons->lastPage(),
                'per_page' => $coupons->perPage(),
                'total' => $coupons->total(),
            ]
        ]);
    }

    /**
     * Store a newly created coupon.
     */
    public function store(StoreCouponRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['created_by'] = Auth::id();

        $coupon = Coupon::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Tạo mã giảm giá thành công',
            'data' => new CouponResource($coupon->load('creator'))
        ], 201);
    }

    /**
     * Display the specified coupon.
     */
    public function show(Coupon $coupon): JsonResponse
    {
        $coupon->load(['creator', 'redemptions.user', 'redemptions.booking']);
        
        $stats = $this->couponService->getCouponUsageStats($coupon);

        return response()->json([
            'success' => true,
            'data' => new CouponResource($coupon),
            'usage_stats' => $stats
        ]);
    }

    /**
     * Update the specified coupon.
     */
    public function update(UpdateCouponRequest $request, Coupon $coupon): JsonResponse
    {
        $data = $request->validated();
        
        $coupon->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật mã giảm giá thành công',
            'data' => new CouponResource($coupon->fresh(['creator']))
        ]);
    }

    /**
     * Remove the specified coupon (soft delete).
     */
    public function destroy(Coupon $coupon): JsonResponse
    {
        // Kiểm tra xem mã có đang được sử dụng không
        $activeRedemptions = $coupon->redemptions()
            ->whereHas('booking', function ($query) {
                $query->whereIn('status', ['Pending', 'Confirmed', 'Operational']);
            })
            ->count();

        if ($activeRedemptions > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa mã giảm giá đang được sử dụng trong booking active'
            ], 400);
        }

        $coupon->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa mã giảm giá thành công'
        ]);
    }

    /**
     * Get coupon statistics
     */
    public function statistics(): JsonResponse
    {
        $stats = [
            'total_coupons' => Coupon::count(),
            'active_coupons' => Coupon::active()->count(),
            'expired_coupons' => Coupon::where('end_at', '<', now())->count(),
            'total_redemptions' => \App\Models\CouponRedemption::count(),
            'total_amount_saved' => \App\Models\CouponRedemption::sum('amount_saved_vnd'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}

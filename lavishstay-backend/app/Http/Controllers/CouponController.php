<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponRedemption;
use App\Http\Requests\Coupon\StoreCouponRequest;
use App\Http\Requests\Coupon\UpdateCouponRequest;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CouponController extends Controller
{
    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
        $this->middleware('auth');
        // Add admin middleware if needed
        // $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        $query = Coupon::with('creator');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('active', false);
            }
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Expiry filter
        if ($request->filled('expiry')) {
            $now = now();
            if ($request->expiry === 'active') {
                $query->where(function ($q) use ($now) {
                    $q->where('end_at', '>', $now)
                      ->orWhereNull('end_at');
                });
            } elseif ($request->expiry === 'expired') {
                $query->where('end_at', '<=', $now);
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSorts = ['created_at', 'code', 'value', 'end_at', 'usage_limit'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $coupons = $query->paginate(15)->withQueryString();

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(StoreCouponRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = Auth::id();

        Coupon::create($data);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon đã được tạo thành công!');
    }

    public function show(Coupon $coupon)
    {
        $coupon->load(['creator', 'redemptions.user', 'redemptions.booking']);
        $stats = $this->couponService->getCouponUsageStats($coupon);
        
        return view('admin.coupons.show', compact('coupon', 'stats'));
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        $data = $request->validated();
        $coupon->update($data);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon đã được cập nhật thành công!');
    }

    public function destroy(Coupon $coupon)
    {
        // Check if coupon has active redemptions
        $activeRedemptions = $coupon->redemptions()
            ->whereHas('booking', function ($query) {
                $query->whereIn('status', ['Pending', 'Confirmed', 'Operational']);
            })
            ->count();

        if ($activeRedemptions > 0) {
            return redirect()->route('admin.coupons.index')
                ->with('error', 'Không thể xóa coupon đang được sử dụng trong booking active!');
        }

        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon đã được xóa thành công!');
    }

    public function toggleStatus(Coupon $coupon)
    {
        $coupon->update(['active' => !$coupon->active]);

        return response()->json([
            'success' => true,
            'message' => 'Trạng thái coupon đã được cập nhật!',
            'active' => $coupon->active
        ]);
    }

    public function redemptions(Coupon $coupon, Request $request)
    {
        $query = CouponRedemption::with(['booking', 'user'])
            ->where('coupon_id', $coupon->id);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('booking', function ($bookingQuery) use ($search) {
                    $bookingQuery->where('booking_code', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $redemptions = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.coupons.redemptions', compact('coupon', 'redemptions'));
    }

    public function allRedemptions(Request $request)
    {
        // Handle export request
        if ($request->has('export') && $request->export === 'excel') {
            return $this->exportRedemptions($request);
        }

        // Build query for redemptions
        $query = CouponRedemption::with(['coupon', 'user', 'booking']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('coupon', function ($couponQuery) use ($search) {
                    $couponQuery->where('code', 'like', "%{$search}%");
                })
                ->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('booking', function ($bookingQuery) use ($search) {
                    $bookingQuery->where('booking_code', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('coupon_id')) {
            $query->where('coupon_id', $request->coupon_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Get paginated results
        $redemptions = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // Calculate statistics
        $stats = $this->calculateRedemptionStats($request);

        // Get all coupons for filter dropdown
        $coupons = Coupon::select('id', 'code')->orderBy('code')->get();

        return view('admin.coupons.all-history-coupon', compact('redemptions', 'stats', 'coupons'));
    }

    private function calculateRedemptionStats(Request $request)
    {
        // Base query for stats (same filters as main query)
        $statsQuery = CouponRedemption::query();

        // Apply same filters for consistent stats
        if ($request->filled('search')) {
            $search = $request->search;
            $statsQuery->where(function ($q) use ($search) {
                $q->whereHas('coupon', function ($couponQuery) use ($search) {
                    $couponQuery->where('code', 'like', "%{$search}%");
                })
                ->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('booking', function ($bookingQuery) use ($search) {
                    $bookingQuery->where('booking_code', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('coupon_id')) {
            $statsQuery->where('coupon_id', $request->coupon_id);
        }

        if ($request->filled('date_from')) {
            $statsQuery->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $statsQuery->whereDate('created_at', '<=', $request->date_to);
        }

        // Calculate stats
        $totalRedemptions = $statsQuery->count();
        $totalSavings = $statsQuery->sum('amount_saved_vnd');
        $uniqueUsers = $statsQuery->distinct('user_id')->count('user_id');

        // Active coupons count (not filtered by date)
        $activeCoupons = Coupon::where('active', true)
            ->where(function ($query) {
                $query->where('end_at', '>', now())
                      ->orWhereNull('end_at');
            })
            ->count();

        return [
            'total_redemptions' => $totalRedemptions,
            'total_savings' => $totalSavings,
            'active_coupons' => $activeCoupons,
            'unique_users' => $uniqueUsers,
        ];
    }

    private function exportRedemptions(Request $request)
    {
        // Build query for export (same as main query but without pagination)
        $query = CouponRedemption::with(['coupon', 'user', 'booking']);

        // Apply same filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('coupon', function ($couponQuery) use ($search) {
                    $couponQuery->where('code', 'like', "%{$search}%");
                })
                ->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('booking', function ($bookingQuery) use ($search) {
                    $bookingQuery->where('booking_code', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('coupon_id')) {
            $query->where('coupon_id', $request->coupon_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $redemptions = $query->orderBy('created_at', 'desc')->get();

        // Create CSV content
        $csvContent = "ID,Mã Coupon,Loại Coupon,Giá trị Coupon,Tên Khách hàng,Email,Booking Code,Booking Status,Số tiền áp dụng (VND),Số tiền tiết kiệm (VND),Ngày sử dụng\n";

        foreach ($redemptions as $redemption) {
            $csvContent .= sprintf(
                "%d,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
                $redemption->id,
                $redemption->coupon ? $redemption->coupon->code : 'N/A',
                $redemption->coupon ? ($redemption->coupon->type === 'percent' ? 'Phần trăm' : 'Cố định') : 'N/A',
                $redemption->coupon ? ($redemption->coupon->type === 'percent' ? $redemption->coupon->value . '%' : number_format($redemption->coupon->value, 0, ',', '.') . ' VND') : 'N/A',
                $redemption->user ? $redemption->user->name : 'N/A',
                $redemption->user ? $redemption->user->email : 'N/A',
                $redemption->booking ? ($redemption->booking->booking_code ?? '#' . $redemption->booking_id) : 'N/A',
                $redemption->booking ? $redemption->booking->status : 'N/A',
                number_format($redemption->applied_amount_vnd, 0, ',', '.'),
                number_format($redemption->amount_saved_vnd, 0, ',', '.'),
                $redemption->created_at->format('d/m/Y H:i:s')
            );
        }

        // Return CSV download
        $filename = 'coupon-redemptions-' . now()->format('Y-m-d-H-i-s') . '.csv';
        
        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Length', strlen($csvContent));
    }

    public function statistics()
    {
        $stats = [
            'total_coupons' => Coupon::count(),
            'active_coupons' => Coupon::where('active', true)->count(),
            'expired_coupons' => Coupon::where('end_at', '<', now())->count(),
            'total_redemptions' => CouponRedemption::count(),
            'total_amount_saved' => CouponRedemption::sum('amount_saved_vnd'),
        ];

        return view('admin.coupons.statistics', compact('stats'));
    }
}
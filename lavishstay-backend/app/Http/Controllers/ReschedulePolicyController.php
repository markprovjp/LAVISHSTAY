<?php

namespace App\Http\Controllers;

use App\Models\ReschedulePolicy;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ReschedulePolicyController extends Controller
{
    /**
     * Display a listing of reschedule policies with filtering and pagination
     */
    public function index(Request $request)
    {
        $query = ReschedulePolicy::with('roomType');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Room type filter
        if ($request->filled('room_type')) {
            $roomTypeId = $request->get('room_type');
            $query->where('room_type_id', $roomTypeId);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortFields = ['created_at', 'name', 'reschedule_fee_vnd', 'min_days_before_checkin'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $policies = $query->paginate(15)->withQueryString();
        $roomTypes = RoomType::where('is_active', true)->orderBy('name')->get();

        return view('admin.policy.reschedule-policies.index', compact('policies', 'roomTypes'));
    }

    /**
     * Show the form for creating a new reschedule policy
     */
    public function create()
    {
        $roomTypes = RoomType::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.policy.reschedule-policies.create', compact('roomTypes'));
    }

    /**
     * Store a newly created reschedule policy in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:reschedule_policies,name',
            'description' => 'nullable|string|max:1000',
            'reschedule_fee_vnd' => 'nullable|numeric|min:0',
            'reschedule_fee_percentage' => 'nullable|numeric|min:0|max:100',
            'min_days_before_checkin' => 'nullable|integer|min:0|max:30',
            'room_type_id' => 'nullable|exists:room_types,id',
            'applies_to_holiday' => 'boolean',
            'applies_to_weekend' => 'boolean',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Tên chính sách là bắt buộc.',
            'name.unique' => 'Tên chính sách đã tồn tại.',
            'name.max' => 'Tên chính sách không được vượt quá 255 ký tự.',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
            'reschedule_fee_vnd.min' => 'Phí dời lịch không được âm.',
            'reschedule_fee_percentage.min' => 'Phần trăm phí dời lịch không được âm.',
            'reschedule_fee_percentage.max' => 'Phần trăm phí dời lịch không được vượt quá 100%.',
            'min_days_before_checkin.min' => 'Số ngày tối thiểu trước check-in không được âm.',
            'min_days_before_checkin.max' => 'Số ngày tối thiểu trước check-in không được vượt quá 30.',
            'room_type_id.exists' => 'Loại phòng được chọn không hợp lệ.',
        ]);

        // Convert checkbox values
        $validated['applies_to_holiday'] = $request->has('applies_to_holiday');
        $validated['applies_to_weekend'] = $request->has('applies_to_weekend');
        $validated['is_active'] = $request->has('is_active');

        // Validate that at least one fee type is provided
        if (empty($validated['reschedule_fee_vnd']) && empty($validated['reschedule_fee_percentage'])) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['reschedule_fee_vnd' => 'Phải có ít nhất một loại phí (VND hoặc phần trăm).']);
        }

        try {
            DB::beginTransaction();

            $policy = ReschedulePolicy::create($validated);

            DB::commit();

            return redirect()
                ->route('admin.reschedule-policies')
                ->with('success', 'Chính sách dời lịch đã được tạo thành công.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo chính sách dời lịch. Vui lòng thử lại.');
        }
    }

    /**
     * Show the form for editing the specified reschedule policy
     */
    public function edit(ReschedulePolicy $reschedulePolicy)
    {
        $policy = $reschedulePolicy;
        $roomTypes = RoomType::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.policy.reschedule-policies.edit', compact('policy', 'roomTypes'));
    }

    /**
     * Update the specified reschedule policy in storage
     */
    public function update(Request $request, ReschedulePolicy $reschedulePolicy)
    {
        $policy = $reschedulePolicy;

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('reschedule_policies', 'name')->ignore($policy->id)
            ],
            'description' => 'nullable|string|max:1000',
            'reschedule_fee_vnd' => 'nullable|numeric|min:0',
            'reschedule_fee_percentage' => 'nullable|numeric|min:0|max:100',
            'min_days_before_checkin' => 'nullable|integer|min:0|max:30',
            'room_type_id' => 'nullable|exists:room_types,id',
            'applies_to_holiday' => 'boolean',
            'applies_to_weekend' => 'boolean',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Tên chính sách là bắt buộc.',
            'name.unique' => 'Tên chính sách đã tồn tại.',
            'name.max' => 'Tên chính sách không được vượt quá 255 ký tự.',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
            'reschedule_fee_vnd.min' => 'Phí dời lịch không được âm.',
            'reschedule_fee_percentage.min' => 'Phần trăm phí dời lịch không được âm.',
            'reschedule_fee_percentage.max' => 'Phần trăm phí dời lịch không được vượt quá 100%.',
            'min_days_before_checkin.min' => 'Số ngày tối thiểu trước check-in không được âm.',
            'min_days_before_checkin.max' => 'Số ngày tối thiểu trước check-in không được vượt quá 30.',
            'room_type_id.exists' => 'Loại phòng được chọn không hợp lệ.',
        ]);

        // Convert checkbox values
        $validated['applies_to_holiday'] = $request->has('applies_to_holiday');
        $validated['applies_to_weekend'] = $request->has('applies_to_weekend');
        $validated['is_active'] = $request->has('is_active');

        // Validate that at least one fee type is provided
        if (empty($validated['reschedule_fee_vnd']) && empty($validated['reschedule_fee_percentage'])) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['reschedule_fee_vnd' => 'Phải có ít nhất một loại phí (VND hoặc phần trăm).']);
        }

        try {
            DB::beginTransaction();

            $policy->update($validated);

            DB::commit();

            return redirect()
                ->route('admin.reschedule-policies')
                ->with('success', 'Chính sách dời lịch đã được cập nhật thành công.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật chính sách dời lịch. Vui lòng thử lại.');
        }
    }

    /**
     * Remove the specified reschedule policy from storage
     */
    public function destroy(ReschedulePolicy $reschedulePolicy)
    {
        try {
            $policy = $reschedulePolicy;
            
            // Check if policy is being used in any bookings or reservations
            // You might want to add this check based on your business logic
            // if ($policy->bookings()->exists()) {
            //     return redirect()
            //         ->back()
            //         ->with('error', 'Không thể xóa chính sách này vì đang được sử dụng trong các đặt phòng.');
            // }

            DB::beginTransaction();

            $policyName = $policy->name;
            $policy->delete();

            DB::commit();

            return redirect()
                ->route('admin.reschedule-policies')
                ->with('success', "Chính sách dời lịch '{$policyName}' đã được xóa thành công.");

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra khi xóa chính sách dời lịch. Vui lòng thử lại.');
        }
    }

    /**
     * Toggle the active status of the specified reschedule policy
     */
    public function toggleStatus(ReschedulePolicy $reschedulePolicy)
    {
        try {
            $policy = $reschedulePolicy;
            
            DB::beginTransaction();

            $policy->update([
                'is_active' => !$policy->is_active
            ]);

            DB::commit();

            $status = $policy->is_active ? 'kích hoạt' : 'vô hiệu hóa';
            
            return response()->json([
                'success' => true,
                'message' => "Chính sách đã được {$status} thành công.",
                'is_active' => $policy->is_active
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thay đổi trạng thái chính sách.'
            ], 500);
        }
    }

    /**
     * Show the specified reschedule policy details
     */
    public function show(ReschedulePolicy $reschedulePolicy)
    {
        $policy = $reschedulePolicy->load('roomType');
        
        return view('admin.policy.reschedule-policies.show', compact('policy'));
    }

    /**
     * Get reschedule policies for API or AJAX requests
     */
    public function getActivePolicies(Request $request)
    {
        $query = ReschedulePolicy::where('is_active', true);

        // Filter by room type if provided
        if ($request->filled('room_type_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('room_type_id', $request->room_type_id)
                  ->orWhereNull('room_type_id'); // Include policies that apply to all room types
            });
        }

        $policies = $query->orderBy('name')
            ->get(['id', 'name', 'reschedule_fee_vnd', 'reschedule_fee_percentage', 'min_days_before_checkin']);

        return response()->json([
            'success' => true,
            'data' => $policies
        ]);
    }

    /**
     * Get policies by room type for specific room reschedule scenarios
     */
    public function getPoliciesByRoomType($roomTypeId)
    {
        $policies = ReschedulePolicy::where('is_active', true)
            ->where(function ($query) use ($roomTypeId) {
                $query->where('room_type_id', $roomTypeId)
                      ->orWhereNull('room_type_id'); // Include policies that apply to all room types
            })
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $policies
        ]);
    }

    /**
     * Calculate reschedule fee for a specific policy and booking amount
     */
    public function calculateRescheduleFee(Request $request)
    {
        $request->validate([
            'policy_id' => 'required|exists:reschedule_policies,id',
            'booking_amount' => 'required|numeric|min:0',
        ]);

        $policy = ReschedulePolicy::findOrFail($request->policy_id);
        $bookingAmount = $request->booking_amount;

        $rescheduleFee = 0;

        // Calculate fee based on policy settings
        if ($policy->reschedule_fee_vnd) {
            $rescheduleFee += $policy->reschedule_fee_vnd;
        }

        if ($policy->reschedule_fee_percentage) {
            $rescheduleFee += ($bookingAmount * $policy->reschedule_fee_percentage / 100);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'reschedule_fee' => $rescheduleFee,
                'policy_name' => $policy->name,
                'min_days_before_checkin' => $policy->min_days_before_checkin,
            ]
        ]);
    }

    /**
     * Check if reschedule is allowed based on policy and check-in date
     */
    public function checkRescheduleEligibility(Request $request)
    {
        $request->validate([
            'policy_id' => 'required|exists:reschedule_policies,id',
            'checkin_date' => 'required|date',
        ]);

        $policy = ReschedulePolicy::findOrFail($request->policy_id);
        $checkinDate = \Carbon\Carbon::parse($request->checkin_date);
        $today = \Carbon\Carbon::today();

        $daysUntilCheckin = $today->diffInDays($checkinDate, false);
        $isEligible = true;
        $reason = '';

        // Check minimum days requirement
        if ($policy->min_days_before_checkin && $daysUntilCheckin < $policy->min_days_before_checkin) {
            $isEligible = false;
            $reason = "Phải dời lịch ít nhất {$policy->min_days_before_checkin} ngày trước ngày check-in.";
        }

        // Check holiday restriction
        if ($policy->applies_to_holiday && $checkinDate->isHoliday()) {
            $isEligible = false;
            $reason = 'Không thể dời lịch cho ngày lễ theo chính sách này.';
        }

        // Check weekend restriction
        if ($policy->applies_to_weekend && $checkinDate->isWeekend()) {
            $isEligible = false;
            $reason = 'Không thể dời lịch cho cuối tuần theo chính sách này.';
        }

        return response()->json([
            'success' => true,
            'data' => [
                'is_eligible' => $isEligible,
                'reason' => $reason,
                'days_until_checkin' => $daysUntilCheckin,
                'policy_name' => $policy->name,
            ]
        ]);
    }
}
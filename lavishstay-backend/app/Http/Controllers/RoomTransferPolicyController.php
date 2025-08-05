<?php

namespace App\Http\Controllers;

use App\Models\RoomTransferPolicy;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RoomTransferPolicyController extends Controller
{
    /**
     * Display a listing of transfer policies with filtering and pagination
     */
    public function index(Request $request)
    {
        $query = RoomTransferPolicy::with('roomType');

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
        
        $allowedSortFields = ['created_at', 'name', 'transfer_fee_vnd', 'min_days_before_check_in'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $policies = $query->paginate(15)->withQueryString();
        $roomTypes = RoomType::where('is_active', true)->orderBy('name')->get();

        return view('admin.policy.transfer-policies.index', compact('policies', 'roomTypes'));
    }

    /**
     * Show the form for creating a new transfer policy
     */
    public function create()
    {
        $roomTypes = RoomType::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.policy.transfer-policies.create', compact('roomTypes'));
    }

    /**
     * Store a newly created transfer policy in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:transfer_policies,name',
            'description' => 'nullable|string|max:1000',
            'transfer_fee_vnd' => 'nullable|numeric|min:0',
            'transfer_fee_percentage' => 'nullable|numeric|min:0|max:100',
            'min_days_before_check_in' => 'nullable|integer|min:0|max:30',
            'room_type_id' => 'nullable|exists:room_types,id',
            'applies_to_holiday' => 'boolean',
            'applies_to_weekend' => 'boolean',
            'requires_guest_confirmation' => 'boolean',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Tên chính sách là bắt buộc.',
            'name.unique' => 'Tên chính sách đã tồn tại.',
            'name.max' => 'Tên chính sách không được vượt quá 255 ký tự.',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
            'transfer_fee_vnd.min' => 'Phí chuyển phòng không được âm.',
            'transfer_fee_percentage.min' => 'Phần trăm phí chuyển phòng không được âm.',
            'transfer_fee_percentage.max' => 'Phần trăm phí chuyển phòng không được vượt quá 100%.',
            'min_days_before_check_in.min' => 'Số ngày tối thiểu trước check-in không được âm.',
            'min_days_before_check_in.max' => 'Số ngày tối thiểu trước check-in không được vượt quá 30.',
            'room_type_id.exists' => 'Loại phòng được chọn không hợp lệ.',
        ]);

        // Convert checkbox values
        $validated['applies_to_holiday'] = $request->has('applies_to_holiday');
        $validated['applies_to_weekend'] = $request->has('applies_to_weekend');
        $validated['requires_guest_confirmation'] = $request->has('requires_guest_confirmation');
        $validated['is_active'] = $request->has('is_active');

        // Validate that at least one fee type is provided
        if (empty($validated['transfer_fee_vnd']) && empty($validated['transfer_fee_percentage'])) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['transfer_fee_vnd' => 'Phải có ít nhất một loại phí (VND hoặc phần trăm).']);
        }

        try {
            DB::beginTransaction();

            $policy = RoomTransferPolicy::create($validated);

            DB::commit();

            return redirect()
                ->route('admin.transfer-policies')
                ->with('success', 'Chính sách chuyển phòng đã được tạo thành công.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo chính sách chuyển phòng. Vui lòng thử lại.');
        }
    }

    /**
     * Show the form for editing the specified transfer policy
     */
    public function edit(RoomTransferPolicy $transferPolicy)
    {
        $roomTypes = RoomType::where('is_active', true)->orderBy('name')->get();
        return view('admin.policy.transfer-policies.edit', compact('transferPolicy', 'roomTypes'));
    }


    /**
     * Update the specified transfer policy in storage
     */
    public function update(Request $request, $id)
    {
        $policy = RoomTransferPolicy::findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('transfer_policies', 'name')->ignore($policy->id)
            ],
            'description' => 'nullable|string|max:1000',
            'transfer_fee_vnd' => 'nullable|numeric|min:0',
            'transfer_fee_percentage' => 'nullable|numeric|min:0|max:100',
            'min_days_before_check_in' => 'nullable|integer|min:0|max:30',
            'room_type_id' => 'nullable|exists:room_types,id',
            'applies_to_holiday' => 'boolean',
            'applies_to_weekend' => 'boolean',
            'requires_guest_confirmation' => 'boolean',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Tên chính sách là bắt buộc.',
            'name.unique' => 'Tên chính sách đã tồn tại.',
            'name.max' => 'Tên chính sách không được vượt quá 255 ký tự.',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
            'transfer_fee_vnd.min' => 'Phí chuyển phòng không được âm.',
            'transfer_fee_percentage.min' => 'Phần trăm phí chuyển phòng không được âm.',
            'transfer_fee_percentage.max' => 'Phần trăm phí chuyển phòng không được vượt quá 100%.',
            'min_days_before_check_in.min' => 'Số ngày tối thiểu trước check-in không được âm.',
            'min_days_before_check_in.max' => 'Số ngày tối thiểu trước check-in không được vượt quá 30.',
            'room_type_id.exists' => 'Loại phòng được chọn không hợp lệ.',
        ]);

        // Convert checkbox values
        $validated['applies_to_holiday'] = $request->has('applies_to_holiday');
        $validated['applies_to_weekend'] = $request->has('applies_to_weekend');
        $validated['requires_guest_confirmation'] = $request->has('requires_guest_confirmation');
        $validated['is_active'] = $request->has('is_active');

        // Validate that at least one fee type is provided
        if (empty($validated['transfer_fee_vnd']) && empty($validated['transfer_fee_percentage'])) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['transfer_fee_vnd' => 'Phải có ít nhất một loại phí (VND hoặc phần trăm).']);
        }

        try {
            DB::beginTransaction();

            $policy->update($validated);

            DB::commit();

            return redirect()
                ->route('admin.transfer-policies')
                ->with('success', 'Chính sách chuyển phòng đã được cập nhật thành công.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật chính sách chuyển phòng. Vui lòng thử lại.');
        }
    }

    /**
     * Remove the specified transfer policy from storage
     */
    public function destroy($id)
    {
        try {
            $policy = RoomTransferPolicy::findOrFail($id);
            
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
                ->route('admin.transfer-policies')
                ->with('success', "Chính sách chuyển phòng '{$policyName}' đã được xóa thành công.");

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra khi xóa chính sách chuyển phòng. Vui lòng thử lại.');
        }
    }

    /**
     * Toggle the active status of the specified transfer policy
     */
    public function toggleStatus($id)
    {
        try {
            $policy = RoomTransferPolicy::findOrFail($id);
            
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
     * Show the specified transfer policy details
     */
    public function show($id)
    {
        $policy = RoomTransferPolicy::with('roomType')->findOrFail($id);
        
        return view('admin.policy.transfer-policies.show', compact('policy'));
    }

    /**
     * Get transfer policies for API or AJAX requests
     */
    public function getActivePolicies(Request $request)
    {
        $query = RoomTransferPolicy::where('is_active', true);

        // Filter by room type if provided
        if ($request->filled('room_type_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('room_type_id', $request->room_type_id)
                  ->orWhereNull('room_type_id'); // Include policies that apply to all room types
            });
        }

        $policies = $query->orderBy('name')
            ->get(['id', 'name', 'transfer_fee_vnd', 'transfer_fee_percentage', 'min_days_before_check_in', 'requires_guest_confirmation']);

        return response()->json([
            'success' => true,
            'data' => $policies
        ]);
    }

    /**
     * Get policies by room type for specific room transfer scenarios
     */
    public function getPoliciesByRoomType($roomTypeId)
    {
        $policies = RoomTransferPolicy::where('is_active', true)
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
     * Calculate transfer fee for a specific policy and booking amount
     */
    public function calculateTransferFee(Request $request)
    {
        $request->validate([
            'policy_id' => 'required|exists:transfer_policies,id',
            'booking_amount' => 'required|numeric|min:0',
        ]);

        $policy = RoomTransferPolicy::findOrFail($request->policy_id);
        $bookingAmount = $request->booking_amount;

        $transferFee = 0;

        // Calculate fee based on policy settings
        if ($policy->transfer_fee_vnd) {
            $transferFee += $policy->transfer_fee_vnd;
        }

        if ($policy->transfer_fee_percentage) {
            $transferFee += ($bookingAmount * $policy->transfer_fee_percentage / 100);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'transfer_fee' => $transferFee,
                'policy_name' => $policy->name,
                'requires_guest_confirmation' => $policy->requires_guest_confirmation,
            ]
        ]);
    }
}
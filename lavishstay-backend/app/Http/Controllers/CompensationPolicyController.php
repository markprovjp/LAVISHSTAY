<?php

namespace App\Http\Controllers;

use App\Models\CompensationPolicy;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CompensationPolicyController extends Controller
{
    /**
     * Display a listing of compensation policies with filtering and pagination
     */
    public function index(Request $request)
    {
        $query = CompensationPolicy::with('roomType');

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

        // Condition type filter
        if ($request->filled('condition_type')) {
            $query->where('condition_type', $request->get('condition_type'));
        }

        // Room type filter
        if ($request->filled('room_type_id')) {
            $query->where('applies_to_room_type_id', $request->get('room_type_id'));
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSortFields = ['compensation_policy_id', 'name', 'discount_value', 'max_compensation_amount', 'created_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $policies = $query->paginate(15)->withQueryString();
        $roomTypes = RoomType::all();

        return view('admin.policy.compensation-policies.index', compact('policies', 'roomTypes'));
    }

    /**
     * Show the form for creating a new compensation policy
     */
    public function create()
    {
        $roomTypes = RoomType::all();
        return view('admin.policy.compensation-policies.create', compact('roomTypes'));
    }

    /**
     * Store a newly created compensation policy in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:compensation_policies,name',
            'description' => 'nullable|string',
            'applies_to_room_type_id' => 'nullable|exists:room_types,room_type_id',
            'condition_type' => 'required|in:room_damage,service_failure,overbooking,other',
            'discount_type' => 'required|in:percentage,fixed_amount',
            'discount_value' => 'required|numeric|min:0',
            'max_compensation_amount' => 'nullable|numeric|min:0',
            'is_active' => 'required|boolean', // Bắt buộc và kiểm tra boolean
        ], [
            'name.required' => 'Tên chính sách là bắt buộc.',
            'name.unique' => 'Tên chính sách đã tồn tại.',
            'name.max' => 'Tên chính sách không được vượt quá 255 ký tự.',
            'condition_type.required' => 'Loại sự cố là bắt buộc.',
            'condition_type.in' => 'Loại sự cố không hợp lệ.',
            'discount_type.required' => 'Loại giảm giá là bắt buộc.',
            'discount_type.in' => 'Loại giảm giá không hợp lệ.',
            'discount_value.required' => 'Giá trị giảm giá là bắt buộc.',
            'discount_value.min' => 'Giá trị giảm giá không được âm.',
            'max_compensation_amount.min' => 'Mức bồi thường tối đa không được âm.',
            'is_active.required' => 'Trạng thái là bắt buộc.',
        ]);

        // Ép kiểu thành 0/1 rõ ràng
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        try {
            DB::beginTransaction();
            CompensationPolicy::create($validated);
            DB::commit();
            return redirect()
                ->route('admin.compensation-policies')
                ->with('success', 'Chính sách bồi thường đã được tạo thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo chính sách bồi thường. Vui lòng thử lại.');
        }
    }

    /**
     * Display the specified compensation policy
     */
    public function show(string $id)
    {
        $policy = CompensationPolicy::with('roomType', 'compensationRequests')->findOrFail($id);
        return view('admin.policy.compensation-policies.show', compact('policy'));
    }

    /**
     * Show the form for editing the specified compensation policy
     */
    public function edit(string $id)
    {
        $policy = CompensationPolicy::findOrFail($id);
        $roomTypes = RoomType::all();
        return view('admin.policy.compensation-policies.edit', compact('policy', 'roomTypes'));
    }

    /**
     * Update the specified compensation policy in storage
     */
    public function update(Request $request, string $id)
    {
        $policy = CompensationPolicy::findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('compensation_policies', 'name')->ignore($policy->compensation_policy_id, 'compensation_policy_id')
            ],
            'description' => 'nullable|string',
            'applies_to_room_type_id' => 'nullable|exists:room_types,room_type_id',
            'condition_type' => 'required|in:room_damage,service_failure,overbooking,other',
            'discount_type' => 'required|in:percentage,fixed_amount',
            'discount_value' => 'required|numeric|min:0',
            'max_compensation_amount' => 'nullable|numeric|min:0',
            'is_active' => 'required|boolean', // Bắt buộc và kiểm tra boolean
        ], [
            'name.required' => 'Tên chính sách là bắt buộc.',
            'name.unique' => 'Tên chính sách đã tồn tại.',
            'name.max' => 'Tên chính sách không được vượt quá 255 ký tự.',
            'condition_type.required' => 'Loại sự cố là bắt buộc.',
            'condition_type.in' => 'Loại sự cố không hợp lệ.',
            'discount_type.required' => 'Loại giảm giá là bắt buộc.',
            'discount_type.in' => 'Loại giảm giá không hợp lệ.',
            'discount_value.required' => 'Giá trị giảm giá là bắt buộc.',
            'discount_value.min' => 'Giá trị giảm giá không được âm.',
            'max_compensation_amount.min' => 'Mức bồi thường tối đa không được âm.',
            'is_active.required' => 'Trạng thái là bắt buộc.',
        ]);

        // Lấy giá trị is_active trực tiếp từ input, đảm bảo 0 hoặc 1
        $validated['is_active'] = $request->input('is_active') == '1' ? 1 : 0;

        try {
            DB::beginTransaction();
            $policy->update($validated);
            DB::commit();
            return redirect()
                ->route('admin.compensation-policies')
                ->with('success', 'Chính sách bồi thường đã được cập nhật thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật chính sách bồi thường. Vui lòng thử lại.');
        }
    }

    /**
     * Remove the specified compensation policy from storage
     */
    public function destroy(string $id)
    {
        try {
            $policy = CompensationPolicy::findOrFail($id);

            DB::beginTransaction();

            $policyName = $policy->name;
            $policy->delete();

            DB::commit();

            return redirect()
                ->route('admin.compensation-policies')
                ->with('success', "Chính sách bồi thường '{$policyName}' đã được xóa thành công.");
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra khi xóa chính sách bồi thường. Vui lòng thử lại.');
        }
    }

    /**
     * Toggle the active status of the specified compensation policy
     */
    public function toggleStatus(string $id)
    {
        try {
            $policy = CompensationPolicy::findOrFail($id);

            DB::beginTransaction();

            $newStatus = $policy->is_active == 1 ? 0 : 1; // Toggle rõ ràng giữa 0 và 1
            $policy->update(['is_active' => $newStatus]);

            DB::commit();

            $status = $newStatus == 1 ? 'kích hoạt' : 'vô hiệu hóa';

            return response()->json([
                'success' => true,
                'message' => "Chính sách đã được {$status} thành công.",
                'is_active' => $newStatus
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
     * Get active compensation policies for API or AJAX requests
     */
    public function getActivePolicies(Request $request)
    {
        $policies = CompensationPolicy::with('roomType')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get([
                'compensation_policy_id',
                'name',
                'condition_type',
                'discount_type',
                'discount_value',
                'max_compensation_amount',
                'applies_to_room_type_id'
            ]);

        return response()->json([
            'success' => true,
            'data' => $policies
        ]);
    }

    /**
     * Calculate compensation amount for a specific policy
     */
    public function calculateCompensation(Request $request)
    {
        $request->validate([
            'policy_id' => 'required|exists:compensation_policies,compensation_policy_id',
            'amount' => 'required|numeric|min:0',
        ]);

        $policy = CompensationPolicy::findOrFail($request->policy_id);
        $amount = $policy->calculateCompensation($request->amount);

        return response()->json([
            'success' => true,
            'data' => [
                'compensation_amount' => $amount,
                'policy_name' => $policy->name,
                'discount_type' => $policy->discount_type_label,
                'discount_value' => $policy->formatted_discount_value,
                'max_compensation_amount' => $policy->max_compensation_amount,
            ]
        ]);
    }
}

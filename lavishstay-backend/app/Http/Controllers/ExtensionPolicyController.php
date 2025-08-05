<?php

namespace App\Http\Controllers;

use App\Models\ExtensionPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ExtensionPolicyController extends Controller
{
    /**
     * Display a listing of extend policies with filtering and pagination
     */
    public function index(Request $request)
    {
        $query = ExtensionPolicy::query();

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

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortFields = ['created_at', 'name', 'max_extension_days', 'extension_fee_vnd'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $policies = $query->paginate(15)->withQueryString();

        return view('admin.policy.extend-policies.index', compact('policies'));
    }

    /**
     * Show the form for creating a new extend policy
     */
    public function create()
    {
        return view('admin.policy.extend-policies.create');
    }

    /**
     * Store a newly created extend policy in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:extend_policies,name',
            'description' => 'nullable|string|max:1000',
            'max_extension_days' => 'required|integer|min:1|max:365',
            'extension_fee_vnd' => 'nullable|numeric|min:0',
            'extension_percentage' => 'nullable|numeric|min:0|max:100',
            'min_days_before_checkout' => 'nullable|integer|min:0|max:30',
            'applies_to_holiday' => 'boolean',
            'applies_to_weekend' => 'boolean',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Tên chính sách là bắt buộc.',
            'name.unique' => 'Tên chính sách đã tồn tại.',
            'name.max' => 'Tên chính sách không được vượt quá 255 ký tự.',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
            'max_extension_days.required' => 'Số ngày gia hạn tối đa là bắt buộc.',
            'max_extension_days.min' => 'Số ngày gia hạn tối đa phải ít nhất là 1.',
            'max_extension_days.max' => 'Số ngày gia hạn tối đa không được vượt quá 365.',
            'extension_fee_vnd.min' => 'Phí gia hạn không được âm.',
            'extension_percentage.min' => 'Phần trăm gia hạn không được âm.',
            'extension_percentage.max' => 'Phần trăm gia hạn không được vượt quá 100%.',
            'min_days_before_checkout.min' => 'Số ngày tối thiểu trước checkout không được âm.',
            'min_days_before_checkout.max' => 'Số ngày tối thiểu trước checkout không được vượt quá 30.',
        ]);

        // Convert checkbox values
        $validated['applies_to_holiday'] = $request->has('applies_to_holiday');
        $validated['applies_to_weekend'] = $request->has('applies_to_weekend');
        $validated['is_active'] = $request->has('is_active');

        try {
            DB::beginTransaction();

            $policy = ExtensionPolicy::create($validated);

            DB::commit();

            return redirect()
                ->route('admin.extend-policies')
                ->with('success', 'Chính sách gia hạn đã được tạo thành công.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo chính sách gia hạn. Vui lòng thử lại.');
        }
    }

    /**
     * Show the form for editing the specified extend policy
     */
    public function edit($id)
    {
        $policy = ExtensionPolicy::findOrFail($id);
        
        return view('admin.policy.extend-policies.edit', compact('policy'));
    }

    /**
     * Update the specified extend policy in storage
     */
    public function update(Request $request, $id)
    {
        $policy = ExtensionPolicy::findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('extend_policies', 'name')->ignore($policy->policy_id, 'policy_id')
            ],
            'description' => 'nullable|string|max:1000',
            'max_extension_days' => 'required|integer|min:1|max:365',
            'extension_fee_vnd' => 'nullable|numeric|min:0',
            'extension_percentage' => 'nullable|numeric|min:0|max:100',
            'min_days_before_checkout' => 'nullable|integer|min:0|max:30',
            'applies_to_holiday' => 'boolean',
            'applies_to_weekend' => 'boolean',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Tên chính sách là bắt buộc.',
            'name.unique' => 'Tên chính sách đã tồn tại.',
            'name.max' => 'Tên chính sách không được vượt quá 255 ký tự.',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
            'max_extension_days.required' => 'Số ngày gia hạn tối đa là bắt buộc.',
            'max_extension_days.min' => 'Số ngày gia hạn tối đa phải ít nhất là 1.',
            'max_extension_days.max' => 'Số ngày gia hạn tối đa không được vượt quá 365.',
            'extension_fee_vnd.min' => 'Phí gia hạn không được âm.',
            'extension_percentage.min' => 'Phần trăm gia hạn không được âm.',
            'extension_percentage.max' => 'Phần trăm gia hạn không được vượt quá 100%.',
            'min_days_before_checkout.min' => 'Số ngày tối thiểu trước checkout không được âm.',
            'min_days_before_checkout.max' => 'Số ngày tối thiểu trước checkout không được vượt quá 30.',
        ]);

        // Convert checkbox values
        $validated['applies_to_holiday'] = $request->has('applies_to_holiday');
        $validated['applies_to_weekend'] = $request->has('applies_to_weekend');
        $validated['is_active'] = $request->has('is_active');

        try {
            DB::beginTransaction();

            $policy->update($validated);

            DB::commit();

            return redirect()
                ->route('admin.extend-policies')
                ->with('success', 'Chính sách gia hạn đã được cập nhật thành công.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật chính sách gia hạn. Vui lòng thử lại.');
        }
    }

    /**
     * Remove the specified extend policy from storage
     */
    public function destroy($id)
    {
        try {
            $policy = ExtensionPolicy::findOrFail($id);
            
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
                ->route('admin.extend-policies')
                ->with('success', "Chính sách gia hạn '{$policyName}' đã được xóa thành công.");

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra khi xóa chính sách gia hạn. Vui lòng thử lại.');
        }
    }

    /**
     * Toggle the active status of the specified extend policy
     */
    public function toggleStatus($id)
    {
        try {
            $policy = ExtensionPolicy::findOrFail($id);
            
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
     * Show the specified extend policy details
     */
    public function show($id)
    {
        $policy = ExtensionPolicy::findOrFail($id);
        
        return view('admin.policy.extend-policies.show', compact('policy'));
    }

    /**
     * Get extend policies for API or AJAX requests
     */
    public function getActivePolicies()
    {
        $policies = ExtensionPolicy::where('is_active', true)
            ->orderBy('name')
            ->get(['policy_id', 'name', 'max_extension_days', 'extension_fee_vnd', 'extension_percentage']);

        return response()->json([
            'success' => true,
            'data' => $policies
        ]);
    }
}
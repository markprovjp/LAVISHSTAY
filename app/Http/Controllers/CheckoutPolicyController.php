<?php

namespace App\Http\Controllers;

use App\Models\CheckoutPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CheckoutPolicyController extends Controller
{
    /**
     * Display a listing of check-out policies with filtering and pagination
     */
    public function index(Request $request)
    {
        $query = CheckoutPolicy::query();

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
        $sortBy = $request->get('sort_by', 'priority');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortFields = ['created_at', 'name', 'standard_check_out_time', 'late_check_out_fee_vnd', 'priority'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Secondary sort by priority if not already sorting by priority
        if ($sortBy !== 'priority') {
            $query->orderBy('priority', 'desc');
        }

        $policies = $query->paginate(15)->withQueryString();

        return view('admin.policy.checkout-policies.index', compact('policies'));
    }

    public function store(Request $request)
        \Log::info("Start store checkout policy");
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100|unique:check_out_policies,name',
                'early_check_out_fee_vnd' => 'nullable|numeric|min:0|max:999999999999.99',
                'late_check_out_fee_vnd' => 'nullable|numeric|min:0|max:999999999999.99',
                'late_check_out_max_hours' => 'nullable|integer|min:0',
                'early_check_out_max_hours' => 'nullable|integer|min:0',
                'description' => 'nullable|string|max:1000',
            ], [
                'name.required' => 'Tên chính sách là bắt buộc.',
                'name.unique' => 'Tên chính sách đã tồn tại.',
                'name.max' => 'Tên chính sách không được vượt quá 100 ký tự.',
                'early_check_out_fee_vnd.numeric' => 'Phí trả sớm phải là số.',
                'early_check_out_fee_vnd.min' => 'Phí trả sớm không được âm.',
                'late_check_out_fee_vnd.numeric' => 'Phí trả muộn phải là số.',
                'late_check_out_fee_vnd.min' => 'Phí trả muộn không được âm.',
                'late_check_out_max_hours.integer' => 'Giờ tối đa muộn phải là số nguyên.',
                'late_check_out_max_hours.min' => 'Giờ tối đa muộn không được âm.',
                'early_check_out_max_hours.integer' => 'Giờ tối đa sớm phải là số nguyên.',
                'early_check_out_max_hours.min' => 'Giờ tối đa sớm không được âm.',
            ]);

            \Log::info("Validated data: " . json_encode($validated));

            if (empty($validated['early_check_out_fee_vnd']) && empty($validated['late_check_out_fee_vnd'])) {
                \Log::warning("No fee method provided");
                return back()->withErrors([
                    'fee_method' => 'Vui lòng nhập ít nhất một trong hai: phí trả sớm hoặc phí trả muộn.'
                ])->withInput();
            }

            $validated['is_active'] = $request->has('is_active');
            \Log::info("Before creating checkout policy: " . json_encode($validated));

            $checkoutPolicy = CheckoutPolicy::create($validated);
            \Log::info("Checkout policy created successfully with ID: " . $checkoutPolicy->policy_id);

            return redirect()->route('admin.checkout-policies')
                            ->with('success', 'Chính sách trả phòng đã được tạo thành công!');
        } catch (\Exception $e) {
            \Log::error("Error creating checkout policy: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }

    public function show(CheckoutPolicy $checkoutPolicy)
    {
        return view('admin.policy.checkout-policies.show', compact('checkoutPolicy'));
    }

    public function edit(CheckoutPolicy $checkoutPolicy)
    {
        return view('admin.policy.checkout-policies.edit', compact('checkoutPolicy'));
    }

    public function update(Request $request, CheckoutPolicy $checkoutPolicy)
    {
        \Log::info('Checkout Policy Update method called', [
            'id' => $checkoutPolicy->policy_id,
            'request_data' => $request->all()
        ]);
=======
    /**
     * Store a newly created check-out policy in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:check_out_policies,name',
            'description' => 'nullable|string',
            'standard_check_out_time' => 'nullable|date_format:H:i',
            'late_check_out_fee_vnd' => 'nullable|numeric|min:0',
            'conditions' => 'nullable|string',
            'action' => 'nullable|string',
            'priority' => 'nullable|integer|min:0|max:999',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Tên chính sách là bắt buộc.',
            'name.unique' => 'Tên chính sách đã tồn tại.',
            'name.max' => 'Tên chính sách không được vượt quá 100 ký tự.',
            'standard_check_out_time.date_format' => 'Giờ check-out tiêu chuẩn phải có định dạng HH:MM.',
            'late_check_out_fee_vnd.min' => 'Phí check-out muộn không được âm.',
            'priority.min' => 'Mức độ ưu tiên không được âm.',
            'priority.max' => 'Mức độ ưu tiên không được vượt quá 999.',
        ]);

        // Convert checkbox values
        $validated['is_active'] = $request->has('is_active');

        // Set default priority if not provided
        if (!isset($validated['priority'])) {
            $validated['priority'] = 0;
        }

        try {
            DB::beginTransaction();

            $policy = CheckoutPolicy::create($validated);

            DB::commit();

            return redirect()
                ->route('admin.checkout-policies')
                ->with('success', 'Chính sách check-out đã được tạo thành công.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo chính sách check-out. Vui lòng thử lại.');
        }
    }

    /**
     * Show the form for editing the specified check-out policy
     */
    public function edit($id)
    {
        $policy = CheckoutPolicy::findOrFail($id);
        
        return view('admin.policy.checkout-policies.edit', compact('policy'));
    }

    /**
     * Update the specified check-out policy in storage
     */
    public function update(Request $request, $id)
    {
        $policy = CheckoutPolicy::findOrFail($id);


        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('check_out_policies', 'name')->ignore($policy->policy_id, 'policy_id')
            ],
            'description' => 'nullable|string',
            'standard_check_out_time' => 'nullable|date_format:H:i',
            'late_check_out_fee_vnd' => 'nullable|numeric|min:0',
            'conditions' => 'nullable|string',
            'action' => 'nullable|string',
            'priority' => 'nullable|integer|min:0|max:999',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Tên chính sách là bắt buộc.',
            'name.unique' => 'Tên chính sách đã tồn tại.',
            'name.max' => 'Tên chính sách không được vượt quá 100 ký tự.',
            'early_check_out_fee_vnd.numeric' => 'Phí trả sớm phải là số.',
            'early_check_out_fee_vnd.min' => 'Phí trả sớm không được âm.',
            'late_check_out_fee_vnd.numeric' => 'Phí trả muộn phải là số.',
            'late_check_out_fee_vnd.min' => 'Phí trả muộn không được âm.',
            'late_check_out_max_hours.integer' => 'Giờ tối đa muộn phải là số nguyên.',
            'late_check_out_max_hours.min' => 'Giờ tối đa muộn không được âm.',
            'early_check_out_max_hours.integer' => 'Giờ tối đa sớm phải là số nguyên.',
            'early_check_out_max_hours.min' => 'Giờ tối đa sớm không được âm.',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
        ]);
        if (!isset($validated['priority'])) {
            $validated['priority'] = 0;
        }

        try {
            DB::beginTransaction();

            $policy->update($validated);

            DB::commit();

            return redirect()
                ->route('admin.checkout-policies')
                ->with('success', 'Chính sách check-out đã được cập nhật thành công.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật chính sách check-out. Vui lòng thử lại.');
        }
    }

    /**
     * Remove the specified check-out policy from storage
     */
    public function destroy($id)
    {
        try {
            $policy = CheckoutPolicy::findOrFail($id);
            
            DB::beginTransaction();

            $policyName = $policy->name;
            $policy->delete();

            DB::commit();

            return redirect()
                ->route('admin.checkout-policies')
                ->with('success', "Chính sách check-out '{$policyName}' đã được xóa thành công.");

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra khi xóa chính sách check-out. Vui lòng thử lại.');
        }
    }

    /**
     * Toggle the active status of the specified check-out policy
     */
    public function toggleStatus($id)
    {
        try {
            $policy = CheckoutPolicy::findOrFail($id);
            
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
     * Show the specified check-out policy details
     */
    public function show($id)
    {
        $policy = CheckoutPolicy::findOrFail($id);
        
        return view('admin.policy.checkout-policies.show', compact('policy'));
    }

    /**
     * Get check-out policies for API or AJAX requests
     */
    public function getActivePolicies(Request $request)
    {
        $policies = CheckoutPolicy::where('is_active', true)
            ->orderBy('priority', 'desc')
            ->orderBy('name')
            ->get(['policy_id', 'name', 'standard_check_out_time', 'late_check_out_fee_vnd', 'conditions', 'action', 'priority']);

        return response()->json([
            'success' => true,
            'data' => $policies
        ]);
    }

    /**
     * Calculate check-out fee for a specific policy and check-out time
     */
    public function calculateCheckoutFee(Request $request)
    {
        $request->validate([
            'policy_id' => 'required|exists:check_out_policies,policy_id',
            'actual_checkout_time' => 'required|date_format:H:i',
        ]);

        $policy = CheckoutPolicy::findOrFail($request->policy_id);
        $actualCheckoutTime = \Carbon\Carbon::createFromFormat('H:i', $request->actual_checkout_time);
        
        $fee = 0;
        $feeType = 'none';
        $hoursDifference = 0;

        if ($policy->standard_check_out_time) {
            $standardCheckoutTime = \Carbon\Carbon::createFromFormat('H:i', $policy->standard_check_out_time);

            if ($actualCheckoutTime->gt($standardCheckoutTime)) {
                // Late check-out
                $hoursDifference = $actualCheckoutTime->diffInHours($standardCheckoutTime);
                
                if ($policy->late_check_out_fee_vnd) {
                    $fee = $policy->late_check_out_fee_vnd;
                    $feeType = 'late';
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'fee' => $fee,
                'fee_type' => $feeType,
                'hours_difference' => $hoursDifference,
                'policy_name' => $policy->name,
                'standard_time' => $policy->standard_check_out_time,
                'actual_time' => $request->actual_checkout_time,
                'conditions' => $policy->conditions,
                'action' => $policy->action,
            ]
        ]);
    }

    /**
     * Get applicable policies based on conditions
     */
    public function getApplicablePolicies(Request $request)
    {
        $request->validate([
            'checkout_time' => 'required|date_format:H:i',
            'checkout_date' => 'required|date',
        ]);

        $policies = CheckoutPolicy::where('is_active', true)
            ->orderBy('priority', 'desc')
            ->get();

        $applicablePolicies = [];

        foreach ($policies as $policy) {
            // Basic logic - you can expand this based on your conditions format
            $isApplicable = true;
            
            // You can add more complex condition checking here
            // For example, parse JSON conditions and check against request parameters
            
            if ($isApplicable) {
                $applicablePolicies[] = [
                    'policy_id' => $policy->policy_id,
                    'name' => $policy->name,
                    'priority' => $policy->priority,
                    'conditions' => $policy->conditions,
                    'action' => $policy->action,
                    'late_check_out_fee_vnd' => $policy->late_check_out_fee_vnd,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $applicablePolicies
>>>>>>> cdb33a3 (Up code vào nhánh phuoc)
        ]);
    }
}
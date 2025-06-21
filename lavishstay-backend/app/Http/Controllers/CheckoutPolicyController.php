<?php

namespace App\Http\Controllers;

use App\Models\CheckoutPolicy;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CheckoutPolicyController extends Controller
{
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

        // Filter by status
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'active') {
                $query->active();
            } elseif ($status === 'inactive') {
                $query->inactive();
            }
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortFields = ['policy_id', 'name', 'early_check_out_fee_vnd', 'late_check_out_fee_vnd', 'is_active', 'created_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $policies = $query->paginate(10)->appends($request->query());

        return view('admin.policy.checkout-policies.index', compact('policies'));
    }

    public function create()
    {
        return view('admin.policy.checkout-policies.create');
    }

    public function store(Request $request)
    {
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

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('check_out_policies', 'name')->ignore($checkoutPolicy->policy_id, 'policy_id')
            ],
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
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
        ]);

        if (empty($validated['early_check_out_fee_vnd']) && empty($validated['late_check_out_fee_vnd'])) {
            return back()->withErrors([
                'fee_method' => 'Vui lòng nhập ít nhất một trong hai: phí trả sớm hoặc phí trả muộn.'
            ])->withInput();
        }

        $validated['is_active'] = $request->has('is_active') && $request->input('is_active') === 'on';

        $validated['early_check_out_fee_vnd'] = $validated['early_check_out_fee_vnd'] ?: null;
        $validated['late_check_out_fee_vnd'] = $validated['late_check_out_fee_vnd'] ?: null;
        $validated['late_check_out_max_hours'] = $validated['late_check_out_max_hours'] ?: null;
        $validated['description'] = $validated['description'] ?: null;

        \Log::info('Updating checkout policy', [
            'policy_id' => $checkoutPolicy->policy_id,
            'data' => $validated
        ]);

        try {
            $checkoutPolicy->update($validated);
            
            \Log::info('Checkout policy updated successfully', [
                'policy_id' => $checkoutPolicy->policy_id
            ]);

            return redirect()->route('admin.checkout-policies')
                            ->with('success', 'Chính sách trả phòng đã được cập nhật thành công!');
        } catch (\Exception $e) {
            \Log::error('Error updating checkout policy', [
                'policy_id' => $checkoutPolicy->policy_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors([
                'error' => 'Có lỗi xảy ra khi cập nhật chính sách: ' . $e->getMessage()
            ])->withInput();
        }
    }

    public function destroy(CheckoutPolicy $checkoutPolicy)
    {
        try {
            $checkoutPolicy->delete();
            return redirect()->route('admin.checkout-policies')
                            ->with('success', 'Chính sách trả phòng đã được xóa thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.checkout-policies')
                            ->with('error', 'Không thể xóa chính sách trả phòng. Vui lòng thử lại.');
        }
    }

    public function toggleStatus(CheckoutPolicy $checkoutPolicy)
    {
        $checkoutPolicy->update([
            'is_active' => !$checkoutPolicy->is_active
        ]);

        $status = $checkoutPolicy->is_active ? 'kích hoạt' : 'vô hiệu hóa';
        
        return response()->json([
            'success' => true,
            'message' => "Chính sách trả phòng đã được {$status} thành công!",
            'is_active' => $checkoutPolicy->is_active
        ]);
    }
}
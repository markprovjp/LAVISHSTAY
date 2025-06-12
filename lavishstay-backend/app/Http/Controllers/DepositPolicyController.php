<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DepositPolicy;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepositPolicyController extends Controller
{
    public function index(Request $request)
    {
        $query = DepositPolicy::query();

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
        
        $allowedSortFields = ['policy_id', 'name', 'deposit_percentage', 'deposit_fixed_amount_vnd', 'is_active', 'created_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $policies = $query->paginate(10)->appends($request->query());

        return view('admin.policy.deposit-policies.index', compact('policies'));
    }

    public function create()
    {
        return view('admin.policy.deposit-policies.create');
    }

    public function store(Request $request)
    {
        \Log::info("Start store deposit policy");
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100|unique:deposit_policies,name',
                'deposit_percentage' => 'nullable|numeric|min:0|max:100',
                'deposit_fixed_amount_vnd' => 'nullable|numeric|min:0|max:999999999999.99',
                'description' => 'nullable|string|max:1000',
                // 'is_active' => 'boolean',
            ], [
                'name.required' => 'Tên chính sách là bắt buộc.',
                'name.unique' => 'Tên chính sách đã tồn tại.',
                'name.max' => 'Tên chính sách không được vượt quá 100 ký tự.',
                'deposit_percentage.numeric' => 'Phần trăm đặt cọc phải là số.',
                'deposit_percentage.min' => 'Phần trăm đặt cọc không được âm.',
                'deposit_percentage.max' => 'Phần trăm đặt cọc không được vượt quá 100%.',
                'deposit_fixed_amount_vnd.numeric' => 'Số tiền đặt cọc phải là số.',
                'deposit_fixed_amount_vnd.min' => 'Số tiền đặt cọc không được âm.',
            ]);

            \Log::info("Validated data: " . json_encode($validated));

            if (empty($validated['deposit_percentage']) && empty($validated['deposit_fixed_amount_vnd'])) {
                \Log::warning("No deposit method provided");
                return back()->withErrors([
                    'deposit_method' => 'Vui lòng nhập ít nhất một trong hai: phần trăm đặt cọc hoặc số tiền cố định.'
                ])->withInput();
            }

            $validated['is_active'] = $request->has('is_active');
            \Log::info("Before creating deposit policy: " . json_encode($validated));

            $depositPolicy = DepositPolicy::create($validated);
            \Log::info("Deposit policy created successfully with ID: " . $depositPolicy->policy_id);

            return redirect()->route('admin.deposit-policies')
                            ->with('success', 'Chính sách đặt cọc đã được tạo thành công!');
        } catch (\Exception $e) {
            \Log::error("Error creating deposit policy: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }
    public function show(DepositPolicy $depositPolicy)
    {
        return view('admin.policy.deposit-policies.show', compact('depositPolicy'));
    }

    public function edit(DepositPolicy $depositPolicy)
    {
        return view('admin.policy.deposit-policies.edit', compact('depositPolicy'));
    }

    public function update(Request $request, DepositPolicy $depositPolicy)
    {
        // Log để debug
        \Log::info('Deposit Policy Update method called', [
            'id' => $depositPolicy->policy_id,
            'request_data' => $request->all()
        ]);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('deposit_policies', 'name')->ignore($depositPolicy->policy_id, 'policy_id')
            ],
            'deposit_percentage' => 'nullable|numeric|min:0|max:100',
            'deposit_fixed_amount_vnd' => 'nullable|numeric|min:0|max:999999999999.99',
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Tên chính sách là bắt buộc.',
            'name.unique' => 'Tên chính sách đã tồn tại.',
            'name.max' => 'Tên chính sách không được vượt quá 100 ký tự.',
            'deposit_percentage.numeric' => 'Phần trăm đặt cọc phải là số.',
            'deposit_percentage.min' => 'Phần trăm đặt cọc không được âm.',
            'deposit_percentage.max' => 'Phần trăm đặt cọc không được vượt quá 100%.',
            'deposit_fixed_amount_vnd.numeric' => 'Số tiền đặt cọc phải là số.',
            'deposit_fixed_amount_vnd.min' => 'Số tiền đặt cọc không được âm.',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
        ]);

        // Validate that at least one deposit method is provided
        if (empty($validated['deposit_percentage']) && empty($validated['deposit_fixed_amount_vnd'])) {
            return back()->withErrors([
                'deposit_method' => 'Vui lòng nhập ít nhất một trong hai: phần trăm đặt cọc hoặc số tiền cố định.'
            ])->withInput();
        }

        // Handle checkbox - convert "on" to boolean
        $validated['is_active'] = $request->has('is_active') && $request->input('is_active') === 'on';

        // Convert empty strings to null for nullable fields
        $validated['deposit_percentage'] = $validated['deposit_percentage'] ?: null;
        $validated['deposit_fixed_amount_vnd'] = $validated['deposit_fixed_amount_vnd'] ?: null;
        $validated['description'] = $validated['description'] ?: null;

        \Log::info('Updating deposit policy', [
            'policy_id' => $depositPolicy->policy_id,
            'data' => $validated
        ]);

        try {
            $depositPolicy->update($validated);
            
            \Log::info('Deposit policy updated successfully', [
                'policy_id' => $depositPolicy->policy_id
            ]);

            return redirect()->route('admin.deposit-policies')
                            ->with('success', 'Chính sách đặt cọc đã được cập nhật thành công!');
        } catch (\Exception $e) {
            \Log::error('Error updating deposit policy', [
                'policy_id' => $depositPolicy->policy_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors([
                'error' => 'Có lỗi xảy ra khi cập nhật chính sách: ' . $e->getMessage()
            ])->withInput();
        }
    }



    public function destroy(DepositPolicy $depositPolicy)
    {
        try {
            $depositPolicy->delete();
            return redirect()->route('admin.deposit-policies')
                            ->with('success', 'Chính sách đặt cọc đã được xóa thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.deposit-policies')
                            ->with('error', 'Không thể xóa chính sách đặt cọc. Vui lòng thử lại.');
        }
    }

    public function toggleStatus(DepositPolicy $depositPolicy)
    {
        $depositPolicy->update([
            'is_active' => !$depositPolicy->is_active
        ]);

        $status = $depositPolicy->is_active ? 'kích hoạt' : 'vô hiệu hóa';
        
        return response()->json([
            'success' => true,
            'message' => "Chính sách đặt cọc đã được {$status} thành công!",
            'is_active' => $depositPolicy->is_active
        ]);
    }
}

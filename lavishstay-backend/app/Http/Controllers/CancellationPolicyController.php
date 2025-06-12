<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CancellationPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CancellationPolicyController extends Controller
{
    public function index()
    {
        $policies = CancellationPolicy::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.policy.cancellation-policies.index', compact('policies'));
    }

    public function create()
    {
        return view('admin.policy.cancellation-policies.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'free_cancellation_days' => 'nullable|integer|min:0',
            'penalty_percentage' => 'nullable|numeric|min:0|max:100',
            'penalty_fixed_amount_vnd' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        CancellationPolicy::create($request->all());

        return redirect()->route('admin.cancellation-policies')
            ->with('success', 'Chính sách hủy đã được tạo thành công!');
    }

    

    public function edit($id)
    {
        try {
            $cancellationPolicy = CancellationPolicy::findOrFail($id);
            return view('admin.policy.cancellation-policies.edit', compact('cancellationPolicy'));
        } catch (\Exception $e) {
            return redirect()->route('admin.cancellation-policies')
                ->with('error', 'Không tìm thấy chính sách hủy.');
        }
    }

    public function update(Request $request, $id)
    {
        // Debug
        \Log::info('Update method called', [
            'id' => $id,
            'request_data' => $request->all()
        ]);

        try {
            $cancellationPolicy = CancellationPolicy::findOrFail($id);
        } catch (\Exception $e) {
            \Log::error('Policy not found', ['id' => $id, 'error' => $e->getMessage()]);
            return redirect()->route('admin.cancellation-policies')
                ->with('error', 'Không tìm thấy chính sách hủy.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:cancellation_policies,name,' . $id . ',policy_id',
            'free_cancellation_days' => 'nullable|integer|min:0|max:365',
            'penalty_percentage' => 'nullable|numeric|min:0|max:100',
            'penalty_fixed_amount_vnd' => 'nullable|numeric|min:0|max:999999999999.99',
            'description' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            \Log::error('Validation failed', ['errors' => $validator->errors()]);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->only([
                'name',
                'free_cancellation_days',
                'penalty_percentage',
                'penalty_fixed_amount_vnd',
                'description'
            ]);
            
            $data['is_active'] = $request->has('is_active') ? 1 : 0;
            
            \Log::info('Updating policy', ['data' => $data]);
            
            $cancellationPolicy->update($data);

            return redirect()->route('admin.cancellation-policies')
                ->with('success', 'Chính sách hủy đã được cập nhật thành công!');
        } catch (\Exception $e) {
            \Log::error('Update failed', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi cập nhật chính sách hủy: ' . $e->getMessage())
                ->withInput();
        }
    }


    public function destroy($id)
    {
        try {
            $policy = CancellationPolicy::findOrFail($id);
            $policy->delete();

            return redirect()->route('admin.cancellation-policies')
                ->with('success', 'Chính sách hủy đã được xóa thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa chính sách hủy.');
        }
    }

    public function toggleStatus($id)
    {
        try {
            $policy = CancellationPolicy::findOrFail($id);
            $policy->update([
                'is_active' => !$policy->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Trạng thái đã được cập nhật thành công!',
                'is_active' => $policy->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thay đổi trạng thái.'
            ], 500);
        }
    }
}

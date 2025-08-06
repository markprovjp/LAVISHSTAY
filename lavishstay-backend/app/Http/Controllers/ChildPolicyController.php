<?php

namespace App\Http\Controllers;

use App\Models\ChildrenSurcharge;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChildPolicyController extends Controller
{
    public function index(Request $request)
    {
        $query = ChildrenSurcharge::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('min_age', 'like', "%{$search}%")
                  ->orWhere('max_age', 'like', "%{$search}%")
                  ->orWhere('surcharge_amount_vnd', 'like', "%{$search}%");
            });
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortFields = ['created_at', 'min_age', 'max_age', 'surcharge_amount_vnd', 'requires_extra_bed'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $childPolicies = $query->paginate(10);

        return view('admin.policy.children-surcharge.index', compact('childPolicies'));
    }

    public function create()
    {
        return view('admin.policy.children-surcharge.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'min_age' => 'required|integer|min:0|max:255',
            'max_age' => 'required|integer|min:0|max:255|gte:min_age',
            'is_free' => 'boolean',
            'count_as_adult' => 'boolean',
            'requires_extra_bed' => 'boolean',
            'surcharge_amount_vnd' => 'nullable|integer|min:0',
        ], [
            'min_age.required' => 'Tuổi tối thiểu là bắt buộc',
            'min_age.integer' => 'Tuổi tối thiểu phải là số nguyên',
            'min_age.min' => 'Tuổi tối thiểu không được nhỏ hơn 0',
            'max_age.required' => 'Tuổi tối đa là bắt buộc',
            'max_age.integer' => 'Tuổi tối đa phải là số nguyên',
            'max_age.gte' => 'Tuổi tối đa phải lớn hơn hoặc bằng tuổi tối thiểu',
            'surcharge_amount_vnd.integer' => 'Mức phụ thu phải là số nguyên',
            'surcharge_amount_vnd.min' => 'Mức phụ thu không được âm',
        ]);

        // Validate logic: if not free and not count as adult, must have surcharge amount
        if (!$request->boolean('is_free') && !$request->boolean('count_as_adult') && !$request->filled('surcharge_amount_vnd')) {
            return back()->withErrors(['surcharge_amount_vnd' => 'Mức phụ thu là bắt buộc khi không miễn phí và không tính như người lớn'])->withInput();
        }

        // Check for overlapping age ranges
        $overlapping = ChildrenSurcharge::where(function($query) use ($request) {
            $query->whereBetween('min_age', [$request->min_age, $request->max_age])
                  ->orWhereBetween('max_age', [$request->min_age, $request->max_age])
                  ->orWhere(function($q) use ($request) {
                      $q->where('min_age', '<=', $request->min_age)
                        ->where('max_age', '>=', $request->max_age);
                  });
        })->exists();

        if ($overlapping) {
            return back()->withErrors(['age_range' => 'Khoảng tuổi này đã tồn tại trong hệ thống'])->withInput();
        }

        ChildrenSurcharge::create($request->all());

        return redirect()->route('admin.children-surcharge')
                        ->with('success', 'Chính sách phụ thu trẻ em đã được tạo thành công!');
    }

    public function show(ChildrenSurcharge $childrenSurcharge)
    {
        return view('admin.policy.children-surcharge.show', compact('childrenSurcharge'));
    }

    public function edit(ChildrenSurcharge $childrenSurcharge)
    {
        return view('admin.policy.children-surcharge.edit', compact('childrenSurcharge'));
    }

    public function update(Request $request, ChildrenSurcharge $childrenSurcharge)
    {
        $request->validate([
            'min_age' => 'required|integer|min:0|max:255',
            'max_age' => 'required|integer|min:0|max:255|gte:min_age',
            'is_free' => 'boolean',
            'count_as_adult' => 'boolean',
            'requires_extra_bed' => 'boolean',
            'surcharge_amount_vnd' => 'nullable|integer|min:0',
        ], [
            'min_age.required' => 'Tuổi tối thiểu là bắt buộc',
            'min_age.integer' => 'Tuổi tối thiểu phải là số nguyên',
            'min_age.min' => 'Tuổi tối thiểu không được nhỏ hơn 0',
            'max_age.required' => 'Tuổi tối đa là bắt buộc',
            'max_age.integer' => 'Tuổi tối đa phải là số nguyên',
            'max_age.gte' => 'Tuổi tối đa phải lớn hơn hoặc bằng tuổi tối thiểu',
            'surcharge_amount_vnd.integer' => 'Mức phụ thu phải là số nguyên',
            'surcharge_amount_vnd.min' => 'Mức phụ thu không được âm',
        ]);

        // Validate logic
        if (!$request->boolean('is_free') && !$request->boolean('count_as_adult') && !$request->boolean('requires_extra_bed') && !$request->filled('surcharge_amount_vnd')) {
            return back()->withErrors(['surcharge_amount_vnd' => 'Mức phụ thu là bắt buộc khi không miễn phí và không tính như người lớn'])->withInput();
        }

        // Check for overlapping age ranges (exclude current record)
        $overlapping = ChildrenSurcharge::where('id', '!=', $childrenSurcharge->id)
            ->where(function($query) use ($request) {
                $query->whereBetween('min_age', [$request->min_age, $request->max_age])
                      ->orWhereBetween('max_age', [$request->min_age, $request->max_age])
                      ->orWhere(function($q) use ($request) {
                          $q->where('min_age', '<=', $request->min_age)
                            ->where('max_age', '>=', $request->max_age);
                      });
            })->exists();

        if ($overlapping) {
            return back()->withErrors(['age_range' => 'Khoảng tuổi này đã tồn tại trong hệ thống'])->withInput();
        }

        $childrenSurcharge->update($request->all());

        return redirect()->route('admin.children-surcharge')
                        ->with('success', 'Chính sách phụ thu trẻ em đã được cập nhật thành công!');
    }

    public function destroy(ChildrenSurcharge $childrenSurcharge)
    {
        $childrenSurcharge->delete();

        return redirect()->route('admin.children-surcharge')
                        ->with('success', 'Chính sách phụ thu trẻ em đã được xóa thành công!');
    }
}

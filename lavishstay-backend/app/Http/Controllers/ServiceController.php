<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function index(Request $request){
        $query = Service::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Chỉ áp dụng bộ lọc is_active nếu được gửi rõ ràng
        if ($request->has('is_active') && $request->is_active !== null) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->filled('min_price')) {
            $query->where('price_vnd', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price_vnd', '<=', $request->max_price);
        }

        $services = $query->orderBy('name')->paginate(15);

        return view('admin.services.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.services.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:100|unique:services,name',
            'description' => 'nullable|string|max:500',
            'price_vnd' => 'required|numeric|min:0|max:999999999',
            'unit' => 'nullable|string|max:50',
            'is_active' => 'boolean'
        ];

        $messages = [
            'name.required' => 'Tên dịch vụ là bắt buộc.',
            'name.unique' => 'Tên dịch vụ đã tồn tại, vui lòng chọn tên khác.',
            'price_vnd.required' => 'Giá dịch vụ là bắt buộc.',
            'price_vnd.numeric' => 'Giá phải là số.',
            'price_vnd.min' => 'Giá không được nhỏ hơn 0.',
            'price_vnd.max' => 'Giá không được vượt quá 999,999,999 VND.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            Service::create([
                'name' => $request->name,
                'description' => $request->description,
                'price_vnd' => $request->price_vnd,
                'unit' => $request->unit,
                'is_active' => $request->has('is_active') ? 1 : 0
            ]);
            DB::commit();

            return redirect()->route('admin.services.services')
                ->with('success', 'Dịch vụ đã được tạo thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating service: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi tạo dịch vụ!')
                ->withInput();
        }
    }

    public function show($id)
    {
        $service = Service::with(['roomTypes'])->findOrFail($id);
        return view('admin.services.show', compact('service'));
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services.services.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:100|unique:services,name,' . $id . ',service_id',
            'description' => 'nullable|string|max:500',
            'price_vnd' => 'required|numeric|min:0|max:999999999',
            'unit' => 'nullable|string|max:50',
            'is_active' => 'boolean'
        ];

        $messages = [
            'name.required' => 'Tên dịch vụ là bắt buộc.',
            'name.unique' => 'Tên dịch vụ đã tồn tại, vui lòng chọn tên khác.',
            'price_vnd.required' => 'Giá dịch vụ là bắt buộc.',
            'price_vnd.numeric' => 'Giá phải là số.',
            'price_vnd.min' => 'Giá không được nhỏ hơn 0.',
            'price_vnd.max' => 'Giá không được vượt quá 999,999,999 VND.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            $service->update([
                'name' => $request->name,
                'description' => $request->description,
                'price_vnd' => $request->price_vnd,
                'unit' => $request->unit,
                'is_active' => $request->has('is_active') ? 1 : 0
            ]);
            DB::commit();

            return redirect()->route('admin.services.services')
                ->with('success', 'Dịch vụ đã được cập nhật thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating service: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi cập nhật dịch vụ!')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $service = Service::findOrFail($id);

            if ($service->roomTypes()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Không thể xóa dịch vụ đang được sử dụng bởi các loại phòng!');
            }

            $service->delete();

            return redirect()->route('admin.services.services')
                ->with('success', 'Dịch vụ đã được xóa thành công!');
        } catch (\Exception $e) {
            \Log::error('Error deleting service: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa dịch vụ!');
        }
    }

    public function toggleStatus($id)
    {
        try {
            $service = Service::findOrFail($id);
            $service->update(['is_active' => !$service->is_active]);

            return response()->json([
                'success' => true,
                'message' => 'Trạng thái dịch vụ đã được cập nhật!',
                'is_active' => $service->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra!'
            ], 500);
        }
    }

    public function getServices(Request $request)
    {
        $query = Service::active();

        if ($request->filled('room_type_id')) {
            $query->whereHas('roomTypes', function($q) use ($request) {
                $q->where('room_type_id', $request->room_type_id);
            });
        }

        $services = $query->select('service_id', 'name', 'price_vnd', 'unit')
                         ->get();

        return response()->json($services);
    }
}
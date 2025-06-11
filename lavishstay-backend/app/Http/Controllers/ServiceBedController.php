<?php

namespace App\Http\Controllers;

use App\Models\BedType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceBedController extends Controller
{
    public function index()
    {
        $bedTypes = BedType::paginate(10);
        return view('admin.services.beds.index', compact('bedTypes'));
    }

    public function create()
    {
        return view('admin.services.beds.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_name' => 'required|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        BedType::create([
            'type_name' => $request->type_name,
            'description' => $request->description,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.services.beds')
            ->with('success', 'Bed Type đã được tạo thành công!');
    }

    public function edit($id)
    {
        $bedType = BedType::findOrFail($id);
        if (!$bedType) {
            return redirect()->route('admin.services.beds')
                ->with('error', 'Bed Type không tồn tại!');
        }
        return view('admin.services.beds.edit', compact('bedType'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'type_name' => 'required|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $bedType = BedType::findOrFail($id);
        $bedType->update([
            'type_name' => $request->type_name,
            'description' => $request->description,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.services.beds')
            ->with('success', 'Bed Type đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        \Log::info('Deleting Bed Type with ID: ' . $id);
        
        $bedType = BedType::find($id);
        
        if (!$bedType) {
            return redirect()->route('admin.services.beds')
                ->with('error', 'Bed Type không tồn tại!');
        }
        
        $bedType->delete();
        
        \Log::info('Bed Type deleted successfully');
        
        return redirect()->route('admin.services.beds')
            ->with('success', 'Bed Type đã được xóa thành công!');
    }

    public function toggleStatus(BedType $bedType)
    {
        $bedType->update(['is_active' => !$bedType->is_active]);
        
        return response()->json([
            'success' => true,
            'message' => 'Trạng thái Bed Type đã được cập nhật!',
            'is_active' => $bedType->is_active
        ]);
    }
}

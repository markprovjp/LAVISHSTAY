<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceAmenityController extends Controller
{
    public function index()
    {
        $amenities = Amenity::paginate(10); // Sắp xếp mặc định theo created_at
        return view('admin.services.amenities.index', compact('amenities'));
    }

    public function create()
    {
        return view('admin.services.amenities.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'icon' => 'nullable|string',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Amenity::create([
            'name' => $request->name,
            'icon' => $request->icon,
            'category' => $request->category,
            'description' => $request->description,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.services.amenities')
            ->with('success', 'Amenity đã được tạo thành công!');
    }

    public function edit($amenityId)
    {
        $amenity = Amenity::findOrFail($amenityId);
        if (!$amenity) {
            return redirect()->route('admin.services.amenities')
                ->with('error', 'Amenity không tồn tại!');
        }
        return view('admin.services.amenities.edit', compact('amenity'));
    }

    public function update(Request $request, $amenityId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'icon' => 'nullable|string',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $amenity = Amenity::findOrFail($amenityId);
        $amenity->update([
            'name' => $request->name,
            'icon' => $request->icon,
            'category' => $request->category,
            'description' => $request->description,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.services.amenities')
            ->with('success', 'Amenity đã được cập nhật thành công!');
    }

    public function destroy($amenityId)
    {
        \Log::info('Deleting Amenity with ID: ' . $amenityId);
        
        $amenity = Amenity::find($amenityId);
        
        if (!$amenity) {
            return redirect()->route('admin.services.amenities')
                ->with('error', 'Amenity không tồn tại!');
        }
        
        $amenity->delete();
        
        \Log::info('Amenity deleted successfully');
        
        return redirect()->route('admin.services.amenities')
            ->with('success', 'Amenity đã được xóa thành công!');
    }

    public function toggleStatus(Amenity $amenity)
    {
        $amenity->update(['is_active' => !$amenity->is_active]);
        
        return response()->json([
            'success' => true,
            'message' => 'Trạng thái Amenity đã được cập nhật!',
            'is_active' => $amenity->is_active
        ]);
    }
}
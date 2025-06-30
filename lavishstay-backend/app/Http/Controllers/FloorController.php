<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FloorController extends Controller
{
    public function index(Request $request)
    {
        $query = Floor::with(['rooms']);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('floor_name', 'like', '%' . $request->search . '%')
                  ->orWhere('floor_number', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('floor_number')) {
            $query->where('floor_number', $request->floor_number);
        }

        if ($request->filled('floor_type')) {
            $query->where('floor_type', $request->floor_type);
        }

        $floors = $query->paginate(12);

        return view('admin.floors.index', compact('floors'));
    }

    public function create()
    {
        return view('admin.floors.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'floor_number' => 'required|integer|unique:floors,floor_number',
            'floor_name' => 'required|string|max:100',
            'floor_type' => 'required|in:residential,service,special',
            'description' => 'nullable|string',
            'facilities' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['floor_number', 'floor_name', 'floor_type', 'description', 'facilities', 'is_active']);
        Floor::create($data);

        return redirect()->route('admin.floors')
            ->with('success', 'Tầng đã được tạo thành công!');
    }

    public function show($floorId)
    {
        $floor = Floor::with(['rooms'])->findOrFail($floorId);
        return view('admin.floors.show', compact('floor'));
    }

    public function edit($floorId)
    {
        $floor = Floor::findOrFail($floorId);
        return view('admin.floors.edit', compact('floor'));
    }

    public function update(Request $request, $floorId)
    {
        $validator = Validator::make($request->all(), [
            'floor_number' => 'required|integer|unique:floors,floor_number,' . $floorId . ',floor_id',
            'floor_name' => 'required|string|max:100',
            'floor_type' => 'required|in:residential,service,special',
            'description' => 'nullable|string',
            'facilities' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $floor = Floor::findOrFail($floorId);
        $floor->update($request->only(['floor_number', 'floor_name', 'floor_type', 'description', 'facilities', 'is_active']));

        return redirect()->route('admin.floors')
            ->with('success', 'Tầng đã được cập nhật thành công!');
    }

    public function destroy($floorId)
    {
        $floor = Floor::findOrFail($floorId);

        if ($floor->rooms()->exists()) {
            return response()->json([
                'success' => false,
                'error' => 'Không thể xóa tầng này vì có phòng đang sử dụng nó!'
            ], 400);
        }

        $floor->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tầng đã được xóa thành công!'
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomTypeController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::with(['amenities', 'rooms'])->paginate(7);
        return view('admin.room-types.index', compact('roomTypes'));
    }

    public function create()
    {
        return view('admin.room-types.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_code' => 'required|string|max:255|unique:room_types,room_code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_room' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['room_code', 'name', 'description', 'total_room']);
        RoomType::create($data);

        return redirect()->route('admin.room-types')
            ->with('success', 'Loại phòng đã được tạo thành công!');
    }

    public function show($roomTypeId)
    {
        $roomType = RoomType::with(['amenities', 'rooms'])
            ->where('room_type_id', $roomTypeId)
            ->firstOrFail();

        return view('admin.room-types.show', compact('roomType'));
    }

    public function edit($roomTypeId)
    {
        $roomType = RoomType::findOrFail($roomTypeId);
        return view('admin.room-types.edit', compact('roomType'));
    }

    public function update(Request $request, $roomTypeId)
    {
        $validator = Validator::make($request->all(), [
            'room_code' => 'required|string|max:255|unique:room_types,room_code,' . $roomTypeId . ',room_type_id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_room' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $roomType = RoomType::findOrFail($roomTypeId);
        $roomType->update($request->only(['room_code', 'name', 'description', 'total_room']));

        return redirect()->route('admin.room-types')
            ->with('success', 'Loại phòng đã được cập nhật thành công!');
    }

    public function destroy($roomTypeId)
    {
        $roomType = RoomType::findOrFail($roomTypeId);

        if ($roomType->rooms()->exists()) {
            return response()->json([
                'success' => false,
                'error' => 'Không thể xóa loại phòng này vì có phòng đang sử dụng nó!'
            ], 400);
        }

        $roomType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Loại phòng đã được xóa thành công!'
        ]);
    }
}
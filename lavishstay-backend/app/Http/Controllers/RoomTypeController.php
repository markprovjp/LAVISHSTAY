<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    
    public function index()
    {
        $roomTypes = RoomType::with(['amenities', 'rooms'])
            ->paginate(7);

        return view('admin.room-types.index', compact('roomTypes'));
    }

  
    public function create()
    {
        // TODO: Implement create form
        return redirect()->route('admin.room-types')
            ->with('info', 'Chức năng thêm loại phòng đang được phát triển.');
    }

    
    public function store(Request $request)
    {
        // Code tiếp theo sẽ xử lý việc lưu loại phòng mới
        return redirect()->route('admin.room-types.index')
            ->with('info', 'Chức năng thêm loại phòng đang được phát triển.');
    }

    
    public function show($roomTypeId)
    {
       $roomType = RoomType::with(['amenities', 'rooms'])
            ->where('room_type_id', $roomTypeId)
            ->firstOrFail();
        
        return view('admin.room-types.show', compact('roomType'));
    }

   
    public function edit(RoomType $roomType)
    {
        // Code tiếp
        return redirect()->route('admin.room-types.index')
            ->with('info', 'Chức năng sửa loại phòng đang được phát triển.');
    }

   
    public function update(Request $request, RoomType $roomType)
    {
        // Code tiếp
        return redirect()->route('admin.room-types.index')
            ->with('info', 'Chức năng sửa loại phòng đang được phát triển.');
    }

   
    public function destroy(RoomType $roomType)
    {
        // Code tiếp
        return redirect()->route('admin.room-types.index')
            ->with('info', 'Chức năng xóa loại phòng đang được phát triển.');
    }
}

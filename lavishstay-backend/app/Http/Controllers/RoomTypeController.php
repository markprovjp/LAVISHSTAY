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
        return redirect()->route('admin.room-types.index')
            ->with('info', 'Chức năng thêm loại phòng đang được phát triển.');
    }

    
    public function store(Request $request)
    {
        // TODO: Implement store logic
        return redirect()->route('admin.room-types.index')
            ->with('info', 'Chức năng thêm loại phòng đang được phát triển.');
    }

    
    public function show(RoomType $roomType)
    {
        $roomType->load(['amenities', 'rooms']);
        
        return view('admin.room-types.show', compact('roomType'));
    }

   
    public function edit(RoomType $roomType)
    {
        // TODO: Implement edit form
        return redirect()->route('admin.room-types.index')
            ->with('info', 'Chức năng sửa loại phòng đang được phát triển.');
    }

   
    public function update(Request $request, RoomType $roomType)
    {
        // TODO: Implement update logic
        return redirect()->route('admin.room-types.index')
            ->with('info', 'Chức năng sửa loại phòng đang được phát triển.');
    }

   
    public function destroy(RoomType $roomType)
    {
        // TODO: Implement delete logic
        return redirect()->route('admin.room-types.index')
            ->with('info', 'Chức năng xóa loại phòng đang được phát triển.');
    }
}

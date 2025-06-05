<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    /**
     * Display a listing of room types
     */
    public function index()
    {
        $roomTypes = RoomType::with(['amenities', 'rooms'])
            ->paginate(7);
        
        return view('admin.room-types.index', compact('roomTypes'));
    }

    /**
     * Show the form for creating a new room type
     */
    public function create()
    {
        // TODO: Implement create form
        return redirect()->route('admin.room-types.index')
            ->with('info', 'Chức năng thêm loại phòng đang được phát triển.');
    }

    /**
     * Store a newly created room type
     */
    public function store(Request $request)
    {
        // TODO: Implement store logic
        return redirect()->route('admin.room-types.index')
            ->with('info', 'Chức năng thêm loại phòng đang được phát triển.');
    }

    /**
     * Display the specified room type
     */
    public function show(RoomType $roomType)
    {
        $roomType->load(['amenities', 'rooms']);
        
        return view('admin.room-types.show', compact('roomType'));
    }

    /**
     * Show the form for editing the specified room type
     */
    public function edit(RoomType $roomType)
    {
        // TODO: Implement edit form
        return redirect()->route('admin.room-types.index')
            ->with('info', 'Chức năng sửa loại phòng đang được phát triển.');
    }

    /**
     * Update the specified room type
     */
    public function update(Request $request, RoomType $roomType)
    {
        // TODO: Implement update logic
        return redirect()->route('admin.room-types.index')
            ->with('info', 'Chức năng sửa loại phòng đang được phát triển.');
    }

    /**
     * Remove the specified room type
     */
    public function destroy(RoomType $roomType)
    {
        // TODO: Implement delete logic
        return redirect()->route('admin.room-types.index')
            ->with('info', 'Chức năng xóa loại phòng đang được phát triển.');
    }
}

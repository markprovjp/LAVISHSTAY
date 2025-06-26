<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomTypeServiceController extends Controller
{
    public function index(RoomType $roomType){
        $availableServices = Service::whereDoesntHave('roomTypes', function ($query) use ($roomType) {
            $query->where('room_type_service.room_type_id', $roomType->room_type_id);
        })->get()->groupBy('unit')->filter(function ($group) {
            return $group->isNotEmpty() && $group->every(function ($item) {
                return $item instanceof \App\Models\Service;
            });
        });

        $currentServices = $roomType->services;

        return view('admin.room-types.services', compact('roomType', 'availableServices', 'currentServices'));
    }

    public function store(Request $request, RoomType $roomType){
        $validator = Validator::make($request->all(), [
            'service_ids' => 'required|array',
            'service_ids.*' => 'exists:services,service_id'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed'], 422);
        }

        $roomType->services()->syncWithoutDetaching($request->service_ids);
        if ($request->has('active_ids')) {
            $roomType->services()->whereIn('service_id', $request->active_ids)->update(['is_active' => true]);
        }
        return response()->json(['success' => true, 'message' => 'Dịch vụ đã được thêm thành công']);
    }

    public function destroy(RoomType $roomType, Service $service)
    {
        $roomType->services()->detach($service->service_id);
        return response()->json(['success' => true, 'message' => 'Dịch vụ đã được xóa thành công']);
    }

    public function toggleStatus(RoomType $roomType, Service $service)
    {
        $service->update(['is_active' => !$service->is_active]);
        return response()->json(['success' => true, 'message' => 'Trạng thái đã được cập nhật']);
    }

    public function toggleAllStatus(Request $request, RoomType $roomType)
    {
        $status = $request->input('status', true);
        $roomType->services()->update(['is_active' => $status]);
        return response()->json(['success' => true, 'message' => 'Trạng thái của tất cả dịch vụ đã được cập nhật']);
    }
}

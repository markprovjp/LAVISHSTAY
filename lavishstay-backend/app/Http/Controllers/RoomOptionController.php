<?php
namespace App\Http\Controllers;

use App\Http\Resources\RoomOptionResource;
use App\Models\RoomOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomOptionController extends Controller
{
    public function index(Request $request)
    {
        $options = RoomOption::with(['room', 'meal'])
            ->when($request->room_id, fn($query) => $query->where('room_id', $request->room_id))
            ->get();
        return RoomOptionResource::collection($options);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'option_id' => 'required|string|unique:room_option',
            'room_id' => 'required|exists:room,room_id',
            'name' => 'required|string|max:100',
            'price_per_night_vnd' => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1',
            'min_guests' => 'required|integer|min:1',
            'meal_id' => 'nullable|exists:room_meal_option,meal_id',
            'bed_option_id' => 'nullable|exists:room_bed_option,bed_option_id',
            'cancellation_policy_type' => 'required|in:free,non_refundable,partial_refunded',
            'deposit_percentage' => 'nullable|numeric|min:0|max:100',
            'payment_policy_type' => 'required|in:pay_now,pay_at_hotel,pay_partial'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $option = RoomOption::create($request->all());
        return new RoomOptionResource($option);
    }

        /**
     * Get all room options/packages for a given room or room type
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * Accepts: room_id (int), room_type_id (int)
     * Returns: all room_option (gói phòng) for the room or room type, joined with room_type_package
     */
    public function getRoomOptions(Request $request)
    {
        $roomId = $request->query('room_id');
        $roomTypeId = $request->query('room_type_id');

        \Log::info('[getRoomOptions] room_id:', ['room_id' => $roomId]);
        \Log::info('[getRoomOptions] room_type_id:', ['room_type_id' => $roomTypeId]);

        // If room_id is provided, get its room_type_id
        if ($roomId) {
            $room = \DB::table('room')->where('room_id', $roomId)->first();
            if (!$room) {
                \Log::warning('[getRoomOptions] Không tìm thấy phòng', ['room_id' => $roomId]);
                return response()->json(['error' => 'Không tìm thấy phòng'], 404);
            }
            $roomTypeId = $room->room_type_id;
            \Log::info('[getRoomOptions] room_type_id from room:', ['room_type_id' => $roomTypeId]);
        }

        if (!$roomTypeId) {
            \Log::warning('[getRoomOptions] Thiếu room_type_id');
            return response()->json(['error' => 'Thiếu room_type_id'], 400);
        }

        // Get all packages for this room type
        $packages = \DB::table('room_type_package')
            ->where('room_type_id', $roomTypeId)
            ->where('is_active', 1)
            ->get();
        \Log::info('[getRoomOptions] packages:', ['count' => $packages->count(), 'ids' => $packages->pluck('package_id')]);

        // Get all room_option for this room_type (by package)
        $options = \DB::table('room_option')
            ->whereIn('package_id', $packages->pluck('package_id'))
            ->get();
        \Log::info('[getRoomOptions] options:', ['count' => $options->count(), 'ids' => $options->pluck('option_id')]);

        // Build result: always return all packages, even if no room_option exists for that package
        $result = $packages->map(function ($pkg) use ($options) {
            $option = $options->firstWhere('package_id', $pkg->package_id);
            return [
                'package_id' => $pkg->package_id,
                'package_name' => $pkg->name,
                'package_description' => $pkg->description,
                'package_price_modifier_vnd' => $pkg->price_modifier_vnd,
                'option_id' => $option ? $option->option_id : null,
                'name' => $option ? $option->name : $pkg->name,
                'price_per_night_vnd' => $option ? $option->price_per_night_vnd : null,
                'max_guests' => $option ? $option->max_guests : null,
                'min_guests' => $option ? $option->min_guests : null,
                // Add more fields from room_option if needed
            ];
        });

        \Log::info('[getRoomOptions] result:', ['result' => $result]);
        return response()->json($result);
    }
}
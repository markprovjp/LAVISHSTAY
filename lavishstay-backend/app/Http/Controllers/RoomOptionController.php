<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use Illuminate\Http\Request;

class RoomOptionController extends Controller
{
    //
}
=======
use App\Http\Resources\RoomOptionResource;
use App\Models\RoomOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomOptionController extends Controller
{
    public function index(Request $request)
    {
        $options = RoomOption::with(['room', 'meal', 'bedOption', 'promotions', 'availability'])
            ->when($request->room_id, fn($query) => $query->where('room_id', $request->room_id))
            ->when($request->check_in && $request->check_out, function ($query) use ($request) {
                $query->whereHas('availability', fn($q) => $q->whereBetween('date', [$request->check_in, $request->check_out])
                    ->where('available_rooms', '>', 0));
            })
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
}
>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc

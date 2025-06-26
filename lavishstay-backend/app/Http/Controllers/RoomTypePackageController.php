<?php

namespace App\Http\Controllers;

use App\Models\RoomTypePackage;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RoomTypePackageController extends Controller
{
    public function index(Request $request)
    {
        $query = RoomTypePackage::with(['roomType']);

        // Filter by room type
        if ($request->filled('room_type_id')) {
            $query->where('room_type_id', $request->room_type_id);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by package type
        if ($request->filled('package_type')) {
            if ($request->package_type === 'vip') {
                $query->vip();
            } elseif ($request->package_type === 'standard') {
                $query->standard();
            }
        }

        $packages = $query->orderBy('room_type_id')->orderBy('name')->paginate(15);
        $roomTypes = RoomType::all();

        return view('admin.room-type-packages.index', compact('packages', 'roomTypes'));
    }

    public function create()
    {
        $roomTypes = RoomType::all();
        return view('admin.room-type-packages.create', compact('roomTypes'));
    }

    public function store(Request $request)
    {
        $rules = [
            'room_type_id' => 'required|exists:room_types,room_type_id',
            'name' => 'required|string|max:50',
            'price_modifier_vnd' => 'required|numeric',
            'include_all_services' => 'boolean',
            'description' => 'nullable|string'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            RoomTypePackage::create([
                'room_type_id' => $request->room_type_id,
                'name' => $request->name,
                'price_modifier_vnd' => $request->price_modifier_vnd,
                'include_all_services' => $request->has('include_all_services') ? 1 : 0,
                'description' => $request->description
            ]);

            return redirect()->route('admin.room-type-packages.index')
                ->with('success', 'Gói dịch vụ đã được tạo thành công!');

        } catch (\Exception $e) {
            \Log::error('Error creating room type package: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi tạo gói dịch vụ!')
                ->withInput();
        }
    }

    public function show($id)
    {
        $package = RoomTypePackage::with(['roomType', 'roomType.services'])->findOrFail($id);
        return view('admin.room-type-packages.show', compact('package'));
    }

    public function edit($id)
    {
        $package = RoomTypePackage::findOrFail($id);
        $roomTypes = RoomType::all();
        return view('admin.room-type-packages.edit', compact('package', 'roomTypes'));
    }

    public function update(Request $request, $id)
    {
        $package = RoomTypePackage::findOrFail($id);

        $rules = [
            'room_type_id' => 'required|exists:room_types,room_type_id',
            'name' => 'required|string|max:50',
            'price_modifier_vnd' => 'required|numeric',
            'include_all_services' => 'boolean',
            'description' => 'nullable|string'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $package->update([
                'room_type_id' => $request->room_type_id,
                'name' => $request->name,
                'price_modifier_vnd' => $request->price_modifier_vnd,
                'include_all_services' => $request->has('include_all_services') ? 1 : 0,
                'description' => $request->description
            ]);

            return redirect()->route('admin.room-type-packages.index')
                ->with('success', 'Gói dịch vụ đã được cập nhật thành công!');

        } catch (\Exception $e) {
            \Log::error('Error updating room type package: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi cập nhật gói dịch vụ!')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $package = RoomTypePackage::findOrFail($id);

            // Check if package is being used by any room options
            if ($package->roomOptions()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Không thể xóa gói dịch vụ đang được sử dụng!');
            }

            $package->delete();

            return redirect()->route('admin.room-type-packages.index')
                ->with('success', 'Gói dịch vụ đã được xóa thành công!');

        } catch (\Exception $e) {
            \Log::error('Error deleting room type package: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa gói dịch vụ!');
        }
    }

    /**
     * Get packages by room type (API endpoint)
     */
    public function getPackagesByRoomType($roomTypeId)
    {
        $packages = RoomTypePackage::where('room_type_id', $roomTypeId)
            ->select('package_id', 'name', 'price_modifier_vnd', 'include_all_services', 'description')
            ->get();

        return response()->json($packages);
    }

    /**
     * Create default packages for room type
     */
    public function createDefaultPackages($roomTypeId)
    {
        try {
            $roomType = RoomType::findOrFail($roomTypeId);

            DB::beginTransaction();

            // Create standard package
            $standardPackage = RoomTypePackage::create([
                'room_type_id' => $roomTypeId,
                'name' => 'Gói Tiêu chuẩn',
                'price_modifier_vnd' => 0,
                'include_all_services' => false,
                'description' => 'Gói tiêu chuẩn không bao gồm dịch vụ bổ sung'
            ]);

            // Create VIP package (30% increase from base price)
            $vipPriceModifier = $roomType->base_price * 0.3;
            $vipPackage = RoomTypePackage::create([
                'room_type_id' => $roomTypeId,
                'name' => 'Gói VIP',
                'price_modifier_vnd' => $vipPriceModifier,
                'include_all_services' => true,
                'description' => 'Gói VIP bao gồm tất cả dịch vụ của loại phòng này'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đã tạo gói dịch vụ mặc định thành công!',
                'packages' => [
                    'standard' => $standardPackage,
                    'vip' => $vipPackage
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error creating default packages: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo gói dịch vụ mặc định!'
            ], 500);
        }
    }

        /**
     * Calculate package pricing
     */
    public function calculatePackagePricing(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,room_type_id',
            'package_id' => 'required|exists:room_type_package,package_id',
            'nights' => 'integer|min:1',
            'guests' => 'integer|min:1'
        ]);

        try {
            $roomType = RoomType::findOrFail($request->room_type_id);
            $package = RoomTypePackage::findOrFail($request->package_id);
            
            $nights = $request->nights ?? 1;
            $basePrice = $roomType->base_price;
            $totalPrice = $package->calculateTotalPrice($basePrice) * $nights;
            
            $includedServices = [];
            if ($package->include_all_services) {
                $includedServices = $roomType->activeServices->map(function($service) {
                    return [
                        'service_id' => $service->service_id,
                        'name' => $service->name,
                        'price_vnd' => $service->price_vnd,
                        'unit' => $service->unit,
                        'formatted_price' => $service->formatted_price
                    ];
                });
            }

            return response()->json([
                'success' => true,
                'pricing' => [
                    'base_price' => $basePrice,
                    'package_modifier' => $package->price_modifier_vnd,
                    'price_per_night' => $package->calculateTotalPrice($basePrice),
                    'total_price' => $totalPrice,
                    'nights' => $nights,
                    'package_type' => $package->package_type,
                    'included_services' => $includedServices,
                    'services_value' => $includedServices->sum('price_vnd') ?? 0
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error calculating package pricing: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tính toán giá!'
            ], 500);
        }
    }
}


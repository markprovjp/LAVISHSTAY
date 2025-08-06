<?php

namespace App\Http\Controllers;

use App\Models\Amenity; 
use App\Models\RoomType;
use App\Models\RoomTypeAmenity;
use App\Models\RoomTypeImage;
use App\Models\RoomTypePackage;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


use function Psy\debug;

class RoomTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = RoomType::with(['rooms', 'mainImage', 'amenities']);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('room_code', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->max_price);
        }

        if ($request->filled('max_guests')) {
            $query->where('max_guests', '>=', $request->max_guests);
        }

        $roomTypes = $query->paginate(12);

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
            'room_area' => 'nullable|integer|min:0',
            'view' => 'nullable|string|max:255',
            'rating' => 'nullable|integer|min:0|max:5',
            'max_guests' => 'nullable|integer|min:1',
            'base_price' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['room_code', 'name', 'description', 'total_room', 'room_area', 'view', 'rating', 'max_guests', 'base_price']);
        RoomType::create($data);

        return redirect()->route('admin.room-types')
            ->with('success', 'Loại phòng đã được tạo thành công!');
    }

    public function show($id)
    {
        $roomType = RoomType::with([
            'images' => function($query) {
                $query->orderBy('is_main', 'desc')->orderBy('image_id', 'asc');
            },
            'amenities' => function($query) {
                $query->orderBy('is_highlighted', 'desc')->orderBy('name', 'asc');
            },
            'services' => function($query) {
                $query->orderBy('is_active', 'desc')->orderBy('name', 'asc');
            },
            'rooms'
        ])->findOrFail($id);

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
            'room_area' => 'nullable|integer|min:0',
            'view' => 'nullable|string|max:255',
            'rating' => 'nullable|integer|min:0|max:5',
            'max_guests' => 'nullable|integer|min:1',
            'base_price' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $roomType = RoomType::findOrFail($roomTypeId);
        $roomType->update($request->only(['room_code', 'name', 'description', 'total_room', 'room_area', 'view', 'rating', 'max_guests', 'base_price']));

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

    // //////////////////////////// Quản lý ảnh ////////////////////////////
    public function images(RoomType $roomType)
    {
        $images = $roomType->images()->orderBy('is_main', 'desc')->orderBy('created_at', 'desc')->get();

        return view('admin.room-types.images', compact('roomType', 'images'));
    }

    /**
     * Upload multiple images
     */
    public function uploadImages(Request $request, RoomType $roomType)
    {
        $validator = Validator::make($request->all(), [
            'images' => 'required|array|max:10',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $uploadedImages = [];
            $hasMainImage = $roomType->images()->where('is_main', true)->exists();

            foreach ($request->file('images') as $index => $image) {
                $filename = time() . '_' . $index . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                $path = $image->storeAs('room-types/' . $roomType->room_type_id, $filename, 'public');
                $imagePath = Storage::url($path);
                $imageUrl = asset($imagePath);
                // Create database record
                $roomTypeImage = RoomTypeImage::create([
                    'room_type_id' => $roomType->room_type_id,
                    'image_path' => $imagePath,
                    'image_url' => $imageUrl,
                    'alt_text' => $roomType->name . ' - Ảnh ' . ($index + 1),
                    'is_main' => !$hasMainImage && $index === 0
                ]);

                $uploadedImages[] = $roomTypeImage;

                if (!$hasMainImage && $index === 0) {
                    $hasMainImage = true;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Đã tải lên ' . count($uploadedImages) . ' ảnh thành công!',
                'data' => $uploadedImages
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tải ảnh lên: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update image details
     */
    public function updateImage(Request $request, RoomType $roomType, $imageId)
    {
        $validator = Validator::make($request->all(), [
            'alt_text' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Mô tả ảnh không được để trống'
            ], 422);
        }

        try {
            $image = RoomTypeImage::where('image_id', $imageId)
                                  ->where('room_type_id', $roomType->room_type_id)
                                  ->firstOrFail();

            $image->update([
                'alt_text' => $request->alt_text
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật mô tả ảnh thành công!',
                'data' => $image
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy ảnh hoặc có lỗi xảy ra'
            ], 404);
        }
    }

    /**
     * Set main image
     */
    public function setMainImage(Request $request, RoomType $roomType, $imageId)
    {
        try {
            RoomTypeImage::where('room_type_id', $roomType->room_type_id)
                         ->update(['is_main' => false]);

            $image = RoomTypeImage::where('image_id', $imageId)
                                  ->where('room_type_id', $roomType->room_type_id)
                                  ->firstOrFail();

            $image->update(['is_main' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Đã đặt ảnh chính thành công!',
                'data' => $image
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy ảnh hoặc có lỗi xảy ra'
            ], 404);
        }
    }

    /**
     * Delete image
     */
    public function deleteImage(Request $request, RoomType $roomType, $imageId)
    {
        try {
            $image = RoomTypeImage::where('image_id', $imageId)
                                  ->where('room_type_id', $roomType->room_type_id)
                                  ->firstOrFail();

            if ($image->is_main && $roomType->images()->count() === 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa ảnh chính duy nhất'
                ], 400);
            }

            // Delete file from storage
            if ($image->image_path) {
                $path = str_replace('/storage/', '', $image->image_path);
                Storage::disk('public')->delete($path);
            }

            $image->delete();

            if ($image->is_main) {
                $nextImage = $roomType->images()->first();
                if ($nextImage) {
                    $nextImage->update(['is_main' => true]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa ảnh thành công!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy ảnh hoặc có lỗi xảy ra'
            ], 404);
        }
    }

    public function storePackage(Request $request, $roomTypeId){
        \Log::info('Dữ liệu nhận được trong storePackage:', $request->all());

        $request->validate([
            'name' => 'required|string|max:255',
            'price_modifier_vnd' => 'required|numeric|min:0',
            'include_all_services' => 'required|boolean',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $roomType = RoomType::findOrFail($roomTypeId);
        $package = $roomType->packages()->create([
            'name' => $request->name,
            'price_modifier_vnd' => $request->price_modifier_vnd,
            'include_all_services' => $request->include_all_services === '1',
            'description' => $request->description,
            'is_active' => $request->is_active === '1',
            'created_by' => Auth::id(),
        ]);

        return response()->json(['success' => true, 'message' => 'Gói dịch vụ đã được tạo thành công.']);
    }

    public function editPackage($packageId){
        $package = RoomTypePackage::findOrFail($packageId);
        return response()->json([
            'package_id' => $package->package_id,
            'name' => $package->name,
            'price_modifier_vnd' => $package->price_modifier_vnd,
            'include_all_services' => $package->include_all_services,
            'description' => $package->description,
            'is_active' => $package->is_active,
        ]);
    }

    public function updatePackage(Request $request, $packageId){
        \Log::info('Dữ liệu nhận được trong updatePackage:', $request->all());

        $request->validate([
            'name' => 'required|string|max:255',
            'price_modifier_vnd' => 'required|numeric|min:0',
            'include_all_services' => 'required|boolean',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $package = RoomTypePackage::findOrFail($packageId);
        $updated = $package->update([
            'name' => $request->name,
            'price_modifier_vnd' => $request->price_modifier_vnd,
            'include_all_services' => $request->include_all_services === '1',
            'description' => $request->description,
            'is_active' => $request->is_active === '1',
        ]);

        return response()->json(['success' => $updated, 'message' => $updated ? 'Cập nhật thành công' : 'Cập nhật thất bại']);
    }

   public function managePackageServices($roomTypeId, $packageId)
    {
        $roomType = RoomType::with(['packages.services' => function ($query) use ($packageId) {
            $query->where('room_type_package_services.package_id', $packageId);
        }])->findOrFail($roomTypeId);

        $package = $roomType->packages()->findOrFail($packageId);

        // Lấy danh sách dịch vụ chưa được liên kết với gói này
        $availableServices = Service::whereDoesntHave('packages', function ($query) use ($packageId) {
            $query->where('room_type_package_services.package_id', $packageId);
        })->get()->groupBy('unit');

        // Lấy danh sách dịch vụ hiện tại của gói
        $currentServices = $package->services;

        return view('admin.room-types.manage-package-services', [
            'roomType' => $roomType,
            'package' => $package,
            'availableServices' => $availableServices,
            'currentServices' => $currentServices,
        ]);
    }

    public function togglePackageServiceStatus(Request $request, $packageId, $serviceId)
    {
        return response()->json(['success' => false, 'message' => 'Tính năng này không được hỗ trợ.'], 400);
    }

    public function storePackageServices(Request $request, $packageId)
    {
        $request->validate(['service_ids' => 'required|array', 'service_ids.*' => 'exists:services,service_id']);
        $package = RoomTypePackage::findOrFail($packageId);

        // Sử dụng select để chỉ định rõ cột service_id từ bảng services
        $existingServiceIds = $package->services()->select('services.service_id')->pluck('service_id')->toArray();
        $newServiceIds = array_diff($request->service_ids, $existingServiceIds);

        if (empty($newServiceIds)) {
            return response()->json(['success' => false, 'message' => 'Tất cả dịch vụ đã được thêm trước đó.'], 400);
        }

        $package->services()->attach($newServiceIds);
        return response()->json(['success' => true, 'message' => 'Dịch vụ đã được thêm.']);
    }

    public function destroyPackageService($packageId, $serviceId)
    {
        $package = RoomTypePackage::findOrFail($packageId);
        $package->services()->detach($serviceId);
        return response()->json(['success' => true, 'message' => 'Dịch vụ đã được xóa.']);
    }

    private function allServicesAdded(RoomTypePackage $package, $availableServices)
    {
        $packageServiceIds = $package->services()->pluck('service_id')->toArray();
        $availableServiceIds = $availableServices->pluck('service_id')->toArray();
        return empty(array_diff($availableServiceIds, $packageServiceIds));
    }

    public function togglePackageStatus($packageId)
    {
        $package = RoomTypePackage::findOrFail($packageId);
        $package->is_active = !$package->is_active;
        $package->save();

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật trạng thái gói dịch vụ!'
        ]);
    }
    
}
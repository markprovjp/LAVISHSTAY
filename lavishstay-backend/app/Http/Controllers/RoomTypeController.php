<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\RoomType;
use App\Models\RoomTypeAmenity;
use App\Models\RoomTypeImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use function Psy\debug;

class RoomTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = RoomType::with(['rooms', 'mainImage', 'amenities']);

        // Search by name or code
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('room_code', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->max_price);
        }

        // Filter by guest capacity
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







    // //////////////////////////// Quản lý ảnh ////////////////////////////
    public function images(RoomType $roomType)
    {
        $images = $roomType->images()->orderBy('is_main', 'desc')->orderBy('created_at', 'desc')->get();
        // dd($images);
        
        return view('admin.room-types.images', compact('roomType', 'images'));
    }

    /**
     * Upload multiple images
     */
    public function uploadImages(Request $request, RoomType $roomType)
    {
        $validator = Validator::make($request->all(), [
            'images' => 'required|array|max:10',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120' // 5MB
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
                // Generate unique filename
                $filename = time() . '_' . $index . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                
                // Store image
                $path = $image->storeAs('room-types/' . $roomType->room_type_id, $filename, 'public');
                $imageUrl = Storage::url($path);

                // Create database record
                $roomTypeImage = RoomTypeImage::create([
                    'room_type_id' => $roomType->room_type_id,
                    'image_url' => $imageUrl,
                    'alt_text' => $roomType->name . ' - Ảnh ' . ($index + 1),
                    'is_main' => !$hasMainImage && $index === 0 // First image becomes main if no main image exists
                ]);

                $uploadedImages[] = $roomTypeImage;
                
                // Only first image can be main
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
            // Remove main status from all images of this room type
            RoomTypeImage::where('room_type_id', $roomType->room_type_id)
                         ->update(['is_main' => false]);

            // Set the selected image as main
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

            // Don't allow deleting the main image if it's the only image
            if ($image->is_main && $roomType->images()->count() === 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa ảnh chính duy nhất'
                ], 400);
            }

            // Delete file from storage
            if ($image->image_url) {
                $path = str_replace('/storage/', '', $image->image_url);
                Storage::disk('public')->delete($path);
            }

            // Delete database record
            $image->delete();

            // If deleted image was main, set another image as main
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

    
}
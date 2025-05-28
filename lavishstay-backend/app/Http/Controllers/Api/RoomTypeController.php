<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class RoomTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = RoomType::query();

        // Filters
        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        if ($request->has('on_sale')) {
            $query->onSale();
        }

        if ($request->has('view_type')) {
            $query->where('view_type', $request->view_type);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('type_name_en', 'like', "%{$search}%")
                  ->orWhere('type_name_vi', 'like', "%{$search}%")
                  ->orWhere('description_en', 'like', "%{$search}%")
                  ->orWhere('description_vi', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'sort_order');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $roomTypes = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $roomTypes,
            'message' => 'Room types retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type_name_en' => 'required|string|max:100',
            'type_name_vi' => 'required|string|max:100',
            'category' => ['required', Rule::in(['standard', 'presidential', 'the_level'])],
            'description_en' => 'nullable|string',
            'description_vi' => 'nullable|string',
            'size_sqm' => 'nullable|integer|min:1',
            'max_adults' => 'nullable|integer|min:1|max:10',
            'max_children' => 'nullable|integer|min:0|max:10',
            'max_occupancy' => 'nullable|integer|min:1|max:20',
            'base_price_usd' => 'required|numeric|min:0',
            'weekend_price_usd' => 'nullable|numeric|min:0',
            'is_sale' => 'boolean',
            'sale_percentage' => 'nullable|numeric|min:0|max:100',
            'sale_price_usd' => 'nullable|numeric|min:0',
            'sale_start_date' => 'nullable|date',
            'sale_end_date' => 'nullable|date|after_or_equal:sale_start_date',
            'view_type' => ['nullable', Rule::in(['city', 'ocean', 'garden', 'mountain'])],
            'bed_type' => 'nullable|string|max:100',
            'room_features_en' => 'nullable|array',
            'room_features_vi' => 'nullable|array',
            'policies_en' => 'nullable|string',
            'policies_vi' => 'nullable|string',
            'cancellation_policy_en' => 'nullable|string',
            'cancellation_policy_vi' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $roomType = RoomType::create($validated);

        return response()->json([
            'success' => true,
            'data' => $roomType,
            'message' => 'Room type created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomType $roomType): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $roomType,
            'message' => 'Room type retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoomType $roomType): JsonResponse
    {
        $validated = $request->validate([
            'type_name_en' => 'sometimes|required|string|max:100',
            'type_name_vi' => 'sometimes|required|string|max:100',
            'category' => ['sometimes', 'required', Rule::in(['standard', 'presidential', 'the_level'])],
            'description_en' => 'nullable|string',
            'description_vi' => 'nullable|string',
            'size_sqm' => 'nullable|integer|min:1',
            'max_adults' => 'nullable|integer|min:1|max:10',
            'max_children' => 'nullable|integer|min:0|max:10',
            'max_occupancy' => 'nullable|integer|min:1|max:20',
            'base_price_usd' => 'sometimes|required|numeric|min:0',
            'weekend_price_usd' => 'nullable|numeric|min:0',
            'is_sale' => 'boolean',
            'sale_percentage' => 'nullable|numeric|min:0|max:100',
            'sale_price_usd' => 'nullable|numeric|min:0',
            'sale_start_date' => 'nullable|date',
            'sale_end_date' => 'nullable|date|after_or_equal:sale_start_date',
            'view_type' => ['nullable', Rule::in(['city', 'ocean', 'garden', 'mountain'])],
            'bed_type' => 'nullable|string|max:100',
            'room_features_en' => 'nullable|array',
            'room_features_vi' => 'nullable|array',
            'policies_en' => 'nullable|string',
            'policies_vi' => 'nullable|string',
            'cancellation_policy_en' => 'nullable|string',
            'cancellation_policy_vi' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $roomType->update($validated);

        return response()->json([
            'success' => true,
            'data' => $roomType->fresh(),
            'message' => 'Room type updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomType $roomType): JsonResponse
    {
        $roomType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Room type deleted successfully'
        ]);
    }

    /**
     * Toggle active status
     */
    public function toggleActive(RoomType $roomType): JsonResponse
    {
        $roomType->update(['is_active' => !$roomType->is_active]);

        return response()->json([
            'success' => true,
            'data' => $roomType->fresh(),
            'message' => 'Room type status updated successfully'
        ]);
    }

    /**
     * Get room types by category
     */
    public function getByCategory(string $category): JsonResponse
    {
        $roomTypes = RoomType::byCategory($category)->active()->get();

        return response()->json([
            'success' => true,
            'data' => $roomTypes,
            'message' => 'Room types retrieved successfully'
        ]);
    }







    // Tôi là Kiệt đang sửa ở chỗ này, Đức
}

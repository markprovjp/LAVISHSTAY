<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypesController extends Controller
{
    public function index(Request $request)
    {
        $query = RoomType::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('type_name_en', 'like', "%{$search}%")
                  ->orWhere('type_name_vi', 'like', "%{$search}%")
                  ->orWhere('description_en', 'like', "%{$search}%")
                  ->orWhere('description_vi', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        // Filter by sale status
        if ($request->has('on_sale') && $request->on_sale !== '') {
            $query->where('is_sale', $request->on_sale);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'sort_order');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $roomTypes = $query->paginate(10)->withQueryString();

        return view('admin.room-types.index', compact('roomTypes'));
    }

    public function create()
    {
        return view('admin.room-types.create');
    }



public function store(Request $request)
{
    $request->validate([
        'type_name_en' => 'required|string|max:255',
        'type_name_vi' => 'required|string|max:255',
        'category' => 'required|in:standard,presidential,the_level',
        'max_adults' => 'required|integer|min:1|max:10',
        'max_occupancy' => 'required|integer|min:1|max:15',
        'base_price_usd' => 'required|numeric|min:0',
        'weekend_price_usd' => 'nullable|numeric|min:0',
        'sale_percentage' => 'nullable|numeric|min:0|max:100',
        'sale_price_usd' => 'nullable|numeric|min:0',
        'sale_start_date' => 'nullable|date',
        'sale_end_date' => 'nullable|date|after_or_equal:sale_start_date',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
    ]);

    try {
        $roomType = new RoomType();
        
        // Basic information
        $roomType->type_name_en = $request->type_name_en;
        $roomType->type_name_vi = $request->type_name_vi;
        $roomType->category = $request->category;
        $roomType->view_type = $request->view_type ?? 'city';
        
        // Specifications
        $roomType->size_sqm = $request->size_sqm;
        $roomType->bed_type = $request->bed_type;
        $roomType->max_adults = $request->max_adults;
        $roomType->max_children = $request->max_children ?? 0;
        $roomType->max_occupancy = $request->max_occupancy;
        
        // Content
        $roomType->description_en = $request->description_en;
        $roomType->description_vi = $request->description_vi;
        $roomType->room_features_en = $request->room_features_en ? json_decode($request->room_features_en) : null;
        $roomType->room_features_vi = $request->room_features_vi ? json_decode($request->room_features_vi) : null;
        
        // Pricing
        $roomType->base_price_usd = $request->base_price_usd;
        $roomType->weekend_price_usd = $request->weekend_price_usd;
        
        // Sale settings
                // Sale settings
        $roomType->is_sale = $request->has('is_sale');
        if ($roomType->is_sale) {
            $roomType->sale_percentage = $request->sale_percentage;
            $roomType->sale_price_usd = $request->sale_price_usd;
            $roomType->sale_start_date = $request->sale_start_date;
            $roomType->sale_end_date = $request->sale_end_date;
        }
        
        // Policies
        $roomType->policies_en = $request->policies_en;
        $roomType->policies_vi = $request->policies_vi;
        $roomType->cancellation_policy_en = $request->cancellation_policy_en;
        $roomType->cancellation_policy_vi = $request->cancellation_policy_vi;
        
        // Settings
        $roomType->sort_order = $request->sort_order ?? 0;
        $roomType->is_active = $request->has('is_active') && !$request->has('is_draft');
        
        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('room-types', 'public');
                $imagePaths[] = $path;
            }
            $roomType->images = json_encode($imagePaths);
        }
        
        $roomType->save();
        
        $message = $request->has('is_draft') ? 'Room type saved as draft successfully!' : 'Room type created successfully!';
        
        return redirect()->route('admin.room-types')->with('success', $message);
        
    } catch (\Exception $e) {
        return back()->withInput()->with('error', 'Error creating room type: ' . $e->getMessage());
    }
}



    public function autoSave(Request $request)
    {
        try {
            // Save form data to session or temporary storage
            $formData = $request->except(['_token', 'auto_save']);
            session(['room_type_draft' => $formData]);
            
            return response()->json(['success' => true, 'message' => 'Draft saved']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Auto-save failed']);
        }
    }
}

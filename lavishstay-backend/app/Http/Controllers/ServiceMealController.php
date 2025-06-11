<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use Illuminate\Http\Request;

class ServiceMealController extends Controller
{
    public function index(){
        return view('admin.services.meals.index');
    }
}
=======
use App\Models\MealType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceMealController extends Controller
{
    public function index()
    {
        $mealTypes = MealType::paginate(10);
        return view('admin.services.meals.index', compact('mealTypes'));
    }

    public function create()
    {
        return view('admin.services.meals.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_name' => 'required|string',
            'description' => 'nullable|string',
            'base_price_vnd' => 'required|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        MealType::create([
            'type_name' => $request->type_name,
            'description' => $request->description,
            'base_price_vnd' => $request->base_price_vnd,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.services.meals')
            ->with('success', 'Bữa ăn đã được tạo thành công!');
    }

    public function edit($id)
    {
        $mealType = MealType::findOrFail($id);
        if (!$mealType) {
            return redirect()->route('admin.services.meals')
                ->with('error', 'Bữa ăn không tồn tại!');
        }
        return view('admin.services.meals.edit', compact('mealType'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'type_name' => 'required|string',
            'description' => 'nullable|string',
            'base_price_vnd' => 'required|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $mealType = MealType::findOrFail($id);
        $mealType->update([
            'type_name' => $request->type_name,
            'description' => $request->description,
            'base_price_vnd' => $request->base_price_vnd,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.services.meals')
            ->with('success', 'Bữa ăn đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        \Log::info('Deleting Meal Type with ID: ' . $id);
        
        $mealType = MealType::find($id);
        
        if (!$mealType) {
            return redirect()->route('admin.services.meals')
                ->with('error', 'Bữa ăn không tồn tại!');
        }
        
        $mealType->delete();
        
        \Log::info('Meal Type deleted successfully');
        
        return redirect()->route('admin.services.meals')
            ->with('success', 'Bữa ăn đã được xóa thành công!');
    }

    public function toggleStatus(MealType $mealType)
    {
        $mealType->update(['is_active' => !$mealType->is_active]);
        
        return response()->json([
            'success' => true,
            'message' => 'Trạng thái bữa ăn đã được cập nhật!',
            'is_active' => $mealType->is_active
        ]);
    }
}
>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc

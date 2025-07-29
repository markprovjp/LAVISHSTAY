<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewsCategoryController extends Controller
{
    public function index()
    {
        $categories = NewsCategory::all(['id', 'name', 'slug', 'description', 'created_at', 'updated_at']);

        return response()->json($categories, 200);
    }
}
<?php

namespace App\Http\Controllers\NewsController;

use App\Http\Controllers\Controller;
use App\Models\News\NewsCategory;
use App\Models\News\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsCategoryController extends Controller
{
    /**
     * Hiển thị danh sách danh mục.
     */
    public function index()
    {
        $categories = NewsCategory::orderByDesc('id')->paginate(10);
        return view('admin.news.categories.index', compact('categories'));
    }

    /**
     * Trả về view tạo mới (hiện không dùng do bạn dùng modal).
     */
    public function create()
    {
        return view('admin.news.categories.create');
    }

    /**
     * Lưu danh mục mới.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255|unique:news_categories,name',
            'description' => 'nullable|string|max:1000',
        ]);

        NewsCategory::create([
            'name'        => $data['name'],
            'slug'        => $this->uniqueSlug($data['name']),
            'description' => $data['description'] ?? null,
        ]);

        return redirect()->route('admin.news.categories.index')->with('success', 'Đã thêm danh mục thành công!');
    }

    /**
     * Hiển thị form sửa danh mục.
     * (không cần với AlpineJS vì dữ liệu truyền qua JSON rồi).
     */
    public function edit(NewsCategory $category)
    {
        return view('admin.news.categories.edit', compact('category'));
    }

    /**
     * Cập nhật danh mục.
     */
    public function update(Request $request, NewsCategory $category)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255|unique:news_categories,name,' . $category->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $category->update([
            'name'        => $data['name'],
            'slug'        => $this->uniqueSlug($data['name'], $category->id),
            'description' => $data['description'] ?? null,
        ]);

        return redirect()->route('admin.news.categories.index')->with('success', 'Đã cập nhật danh mục thành công!');
    }

    /**
     * Xoá danh mục.
     */
   public function destroy(NewsCategory $category)
    {
        // Kiểm tra xem danh mục có bài viết liên kết hay không
        if (News::where('category_id', $category->id)->exists()) {
            return redirect()->route('admin.news.categories.index')
                ->with('error', 'Không thể xóa danh mục "' . $category->name . '" vì vẫn còn bài viết liên kết.');
        }

        // Nếu không có bài viết liên kết, tiến hành xóa danh mục
        $category->delete();
        return redirect()->route('admin.news.categories.index')->with('success', 'Đã xóa danh mục "' . $category->name . '" thành công!');
    }

    /**
     * Hàm hỗ trợ tạo slug duy nhất.
     */
    private function uniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug     = Str::slug($name);
        $original = $slug;
        $counter  = 1;

        while (
            NewsCategory::where('slug', $slug)
                ->when($excludeId, fn ($query) => $query->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }
}
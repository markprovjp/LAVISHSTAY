<?php

namespace App\Http\Controllers\NewsController;

use App\Http\Controllers\Controller;
use App\Models\News\News;
use App\Models\News\NewsCategory;
use App\Models\News\MediaFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    /*--------------------------------------------------------------------------
     | 1. Danh sách
     *------------------------------------------------------------------------*/
    public function index()
    {
        
        $news = News::with(['author', 'categories'])
                    ->latest('published_at')
                    ->paginate(10);

        return view('admin.news.index', compact('news'));
    }

    /*--------------------------------------------------------------------------
     | 2. Form tạo mới
     *------------------------------------------------------------------------*/
    public function create()
    {
        $categories = NewsCategory::orderBy('name')->get();
        $mediaFiles = MediaFile::latest()->get();
        return view('admin.news.create', compact('categories', 'mediaFiles'));
    }

    /*--------------------------------------------------------------------------
     | 3. Lưu bài viết mới
     *------------------------------------------------------------------------*/
   public function store(Request $request)
{
    $data = $request->validate([
        'title'             => 'required|string|max:255',
        'slug'              => 'nullable|string|max:255',
        'content'           => 'required|string',
        'meta_title'        => 'nullable|string|max:255',
        'meta_description'  => 'nullable|string|max:255',
        'meta_keywords'     => 'nullable|string|max:255',
        'thumbnail_id'      => 'nullable|integer|exists:media_files,id',
        'category_id'       => 'required|integer|exists:news_categories,id',
        'published_at'      => 'nullable|date',
        'status'            => 'nullable|boolean',
    ]);

    $slug = $data['slug'] ?? $this->makeUniqueSlug($data['title']);

    $news = News::create([
        'title'            => $data['title'],
        'slug'             => $slug,
        'content'          => $data['content'],
        'meta_title'       => $data['meta_title'] ?? $data['title'],
        'meta_description' => $data['meta_description'] ?? null,
        'meta_keywords'    => $data['meta_keywords'] ?? null,
        'canonical_url'    => url('/tin-tuc/' . $slug),
        'thumbnail_id'     => $data['thumbnail_id'] ?? null,
        'author_id'        => auth()->id(),
        'published_at'     => $data['published_at'] ?? now(),
        'status'           => $request->has('status') ? 1 : 0,
        'views'            => 0,
    ]);

    // Gán chuyên mục (quan hệ belongsToMany)
    $news->categories()->sync([$data['category_id']]);

    // dd($news);

    return to_route('admin.news.index')->with('success', 'Đã tạo bài viết thành công!');
}

    /*--------------------------------------------------------------------------
     | 4. Form chỉnh sửa
     *------------------------------------------------------------------------*/
    public function edit(News $news)
    {
        $categories         = NewsCategory::all();
        $selectedCategories = $news->categories()->pluck('id')->toArray();

        return view('admin.news.edit', compact('news', 'categories', 'selectedCategories'));
    }

    /*--------------------------------------------------------------------------
     | 5. Cập nhật bài viết
     *------------------------------------------------------------------------*/
    public function update(Request $request, News $news)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords'    => 'nullable|string|max:255',
            'canonical_url'    => 'nullable|string|max:255',
            'content'          => 'required|string',
            'thumbnail_id'     => 'nullable|integer|exists:media_files,id',
            'published_at'     => 'nullable|date',
            'categories'       => 'nullable|array',
            'categories.*'     => 'integer|exists:news_categories,id',
            'status'           => 'nullable|boolean',
        ]);

        $slug = $data['slug'] ?? $this->makeUniqueSlug($data['title'], $news->id);

        $news->fill([
            'title'            => $data['title'],
            'slug'             => $slug,
            'meta_title'       => $data['meta_title'] ?? $data['title'],
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords'    => $data['meta_keywords'] ?? null,
            'canonical_url'    => $data['canonical_url'] ?? url('/tin-tuc/' . $slug),
            'content'          => $data['content'],
            'thumbnail_id'     => $data['thumbnail_id'] ?? null,
            'published_at'     => $data['published_at'] ?? now(),
            'status'           => $request->boolean('status'),
        ])->save();

        $news->categories()->sync($data['categories'] ?? []);

        return to_route('admin.news.index')->with('success', 'Đã cập nhật bài viết!');
    }

    /*--------------------------------------------------------------------------
     | 6. Xoá bài viết
     *------------------------------------------------------------------------*/
    public function destroy(News $news)
    {
        $news->categories()->detach();
        $news->delete();

        return to_route('admin.news.index')->with('success', 'Đã xoá bài viết.');
    }

    /*--------------------------------------------------------------------------
     | 7. Xem chi tiết (tuỳ chọn)
     *------------------------------------------------------------------------*/
    public function show(News $news)
    {
        return view('admin.news.show', compact('news'));
    }

    /*--------------------------------------------------------------------------
     | 8. Bulk-action (ví dụ: xoá hàng loạt)
     *------------------------------------------------------------------------*/
    public function bulkAction(Request $request)
    {
        $ids = $request->input('ids', []);
        if ($request->action === 'delete') {
            News::whereIn('id', $ids)->each(fn ($n) => $n->categories()->detach());
            News::whereIn('id', $ids)->delete();
            return back()->with('success', 'Đã xoá các bài đã chọn.');
        }
        return back()->with('error', 'Thao tác không hợp lệ.');
    }

    /*--------------------------------------------------------------------------
     | 9. Upload ảnh từ CKEditor (ví dụ đơn giản)
     *------------------------------------------------------------------------*/
    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $path = $request->file('upload')->store('public/ckeditor');
            $url  = Storage::url($path);
            return response()->json([
                'uploaded' => 1,
                'fileName' => basename($path),
                'url'      => $url,
            ]);
        }
        return response()->json(['uploaded' => 0]);
    }

    /*--------------------------------------------------------------------------
     | 10. Hàm trợ giúp: tạo slug duy nhất
     *------------------------------------------------------------------------*/
    private function makeUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug     = Str::slug($title);
        $original = $slug;
        $counter  = 1;

        while (
            News::where('slug', $slug)
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = "{$original}-{$counter}";
            $counter++;
        }
        return $slug;
    }
}
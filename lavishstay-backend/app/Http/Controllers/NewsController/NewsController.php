<?php

namespace App\Http\Controllers\NewsController;

use App\Models\News\News;
use App\Models\User;
use App\Models\News\NewsCategory;
use App\Models\News\SeoScore;
use App\Models\News\MediaFile;
use App\Models\News\NewsMediaFile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::select('id', 'meta_title', 'category_id', 'author_id', 'thumbnail_id', 'status', 'published_at', 'is_featured', 'views')
            ->with(['category:id,name', 'thumbnail:id,filepath,alt_text', 'author:id,name']);

        if ($request->search_title) {
            $query->where('meta_title', 'like', '%' . $request->search_title . '%');
        }
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->author_id) {
            $query->where('author_id', $request->author_id);
        }
        if ($request->status !== null && in_array($request->status, ['0', '1'])) {
            $query->where('status', (int) $request->status);
        }
        if ($request->search_date) {
            $query->whereDate('published_at', $request->search_date);
        }
        if ($request->is_featured !== null && in_array($request->is_featured, ['0', '1'])) {
            $query->where('is_featured', (int) $request->is_featured);
        }

        $news = $query->paginate(10);

        $categories = NewsCategory::select('id', 'name')
            ->whereIn('id', News::select('category_id')->distinct())
            ->get();

        $authors = User::select('id', 'name')
            ->whereIn('id', News::select('author_id')->distinct())
            ->get();

        return view('admin.news.index', compact('news', 'categories', 'authors'));
    }

    public function updateFeatured(Request $request, $id)
    {
        $request->validate(['is_featured' => 'required|in:0,1']);
        $news = News::find($id);

        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy bài viết.'
            ], 404);
        }

        $news->is_featured = $request->is_featured;
        $news->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái nổi bật thành công!'
        ], 200);
    }

    public function create()
    {
        // Lấy danh mục và media files
        $categories = NewsCategory::select('id', 'name')->get();
        $mediaFiles = MediaFile::select('id', 'filename', 'filepath', 'alt_text', 'title')->get();

        // Chuyển dữ liệu thành dạng JSON để AlpineJS sử dụng
        $mediaJson = $mediaFiles->map(function ($file) {
            return [
                'id' => $file->id,
                'filename' => $file->filename,
                'filepath' => $file->filepath,
                'alt_text' => $file->alt_text,
                'title' => $file->title,
            ];
        })->values();

        return view('admin.news.create', compact('categories', 'mediaFiles', 'mediaJson'));
    }

  public function store(Request $request)
{
    // Xác thực dữ liệu đầu vào
    $validated = $request->validate([
        'meta_title' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255',
        'meta_description' => 'required|string|max:160',
        'meta_keywords' => 'required|string|max:100',
        'content' => 'required|string',
        'summary' => 'nullable|string|max:500',
        'tags' => 'nullable|string',
        'is_featured' => 'nullable|boolean', // Xác thực is_featured là boolean
        'canonical_url' => 'nullable|url|max:255',
        'schema_json' => 'nullable|json',
        'category_id' => 'required|exists:news_categories,id',
        'status' => 'required|boolean',
        'publish_date' => 'required|date_format:Y-m-d\TH:i',
        'thumbnail_id' => 'nullable|exists:media_files,id',
        'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
    ], [
        'meta_description.required' => 'Mô tả ngắn không được để trống.',
        'meta_keywords.required' => 'Từ khóa SEO không được để trống.',
        'publish_date.required' => 'Ngày đăng bài không được để trống.',
        'canonical_url.url' => 'Canonical URL phải là một URL hợp lệ.',
        'schema_json.json' => 'Schema JSON phải là định dạng JSON hợp lệ.',
    ]);

    // Kiểm tra ảnh đại diện
    if (!$request->hasFile('thumbnail') && !$request->input('thumbnail_id')) {
        return back()->withErrors(['thumbnail' => 'Bạn phải chọn hoặc tải lên một ảnh đại diện.'])->withInput();
    }

    // Tạo slug duy nhất
    $slug = $request->input('slug') ? Str::slug($request->slug) : Str::slug($validated['meta_title']);
    $originalSlug = $slug;
    $i = 1;
    while (News::where('slug', $slug)->exists()) {
        $slug = "$originalSlug-$i";
        $i++;
    }

    // Xử lý tags: Lưu trực tiếp chuỗi tags từ input
    $tags = $request->tags ? trim($request->tags) : 'promotion,summer,discount,offer';
    // - Dòng trên: Lấy chuỗi tags từ request, loại bỏ khoảng trắng thừa.
    // + Lợi ích: Lưu tags dưới dạng chuỗi thô, đúng yêu cầu không mã hóa.
    // + Lý do: Cột tags là text, không cần JSON.

    // Xử lý schema_json
    $schema_json = $request->schema_json ?: json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $validated['meta_title'],
        'description' => $validated['meta_description'],
        'author' => ['@type' => 'Organization', 'name' => 'LavishStay Resort'],
        'publisher' => ['@type' => 'Organization', 'name' => 'LavishStay Resort'],
    ], JSON_UNESCAPED_UNICODE);

    // Xử lý ảnh đại diện
    if ($request->hasFile('thumbnail')) {
        $file = $request->file('thumbnail');
        if ($file->isValid()) {
            $filename = time() . '_' . Str::slug($file->getClientOriginalName()) . '.' . $file->extension();
            $path = $file->storeAs('uploads/ckeditor', $filename, 'public');
            $url = asset("storage/$path");

            $mediaFile = MediaFile::create([
                'filename' => $filename,
                'filepath' => $url,
                'alt_text' => $validated['meta_description'],
                'title' => $validated['meta_title'],
                'type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'used_in' => 'news',
            ]);
            $thumbnail_id = $mediaFile->id;
        } else {
            return back()->withErrors(['thumbnail' => 'Ảnh tải lên không hợp lệ.'])->withInput();
        }
    } else {
        $thumbnail_id = $validated['thumbnail_id'];
    }

    // Xử lý is_featured: Kiểm tra và gán giá trị boolean
    $isFeatured = $request->has('is_featured') ? 1 : 0;
    // - Dòng trên: Kiểm tra xem is_featured có trong request không, gán 1 nếu có, 0 nếu không.
    // + Lợi ích: Đảm bảo is_featured được lưu đúng giá trị (1 nếu chọn checkbox, 0 nếu không).
    // + Lý do: Checkbox chỉ gửi giá trị khi được chọn, dùng $request->has để kiểm tra rõ ràng.

    // Tạo bài viết
    News::create([
        'title' => $validated['meta_title'],
        'slug' => $slug,
        'meta_title' => $validated['meta_title'],
        'meta_description' => $validated['meta_description'],
        'meta_keywords' => $validated['meta_keywords'],
        'content' => $validated['content'],
        'summary' => $validated['summary'],
        'tags' => $tags,
        'is_featured' => $isFeatured, // Sử dụng biến $isFeatured
        'canonical_url' => $request->canonical_url ?: (config('app.url') . "/news/$slug"),
        'schema_json' => $schema_json,
        'category_id' => $validated['category_id'],
        'author_id' => Auth::id(),
        'thumbnail_id' => $thumbnail_id,
        'status' => $validated['status'],
        'published_at' => Carbon::parse($validated['publish_date']),
        'views' => 0,
    ]);
    // - Dòng News::create: Lưu bài viết với is_featured đúng giá trị từ checkbox.
    // + Lợi ích: Đảm bảo bài viết được đánh dấu nổi bật nếu người dùng chọn checkbox.
    // + Lý do: Sử dụng biến $isFeatured để rõ ràng và tránh lỗi từ request.

    return redirect()->route('admin.news.index')->with('success', 'Bài viết đã được tạo thành công!');
}
    public function uploadImage(Request $request)
    {
        try {
            // Xác thực file upload
            $request->validate([
                'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Kiểm tra file upload
            if ($request->hasFile('upload')) {
                $originName = $request->file('upload')->getClientOriginalName();
                $extension = $request->file('upload')->getClientOriginalExtension();
                $fileName = 'Lavishstay_' . time() . '.' . $extension;

                // Lưu file vào storage/app/public/uploads/ckeditor
                $path = $request->file('upload')->storeAs('uploads/ckeditor', $fileName, 'public');
                $url = asset('storage/uploads/ckeditor/' . $fileName);

                // Lưu thông tin file vào bảng media_files
                $mediaFile = MediaFile::create([
                    'filename' => $fileName,
                    'filepath' => $url,
                    'alt_text' => $request->input('alt_text') ?? 'Hình ảnh bài viết',
                    'title' => $request->input('title') ?? 'Hình ảnh bài viết',
                    'type' => $request->file('upload')->getClientMimeType(),
                    'size' => $request->file('upload')->getSize(),
                    'used_in' => 'news_ckeditor',
                ]);

                // // Lưu liên kết với news_id nếu có
                // if ($request->has('news_id')) {
                //     NewsMediaFile::create([
                //         'news_id' => $request->input('news_id'),
                //         'media_file_id' => $mediaFile->id,
                //     ]);
                // }

                // Lấy CKEditorFuncNum từ request
                $CKEditorFuncNum = $request->input('CKEditorFuncNum', 0);
                $msg = 'Image uploaded successfully';

                // Trả về response HTML cho CKEditor, bao gồm data-filepath
                $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg');";
                $response .= "document.dispatchEvent(new CustomEvent('imageUploaded', { detail: { filepath: '$url' } }));</script>";

                header('Content-Type: text/html; charset=utf-8');
                echo $response;
            } else {
                throw new \Exception('No file uploaded');
            }
        } catch (\Exception $e) {
            \Log::error('Lỗi khi upload ảnh qua CKEditor: ' . $e->getMessage());
            $CKEditorFuncNum = $request->input('CKEditorFuncNum', 0);
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '', 'Lỗi khi upload ảnh: {$e->getMessage()}');</script>";
            header('Content-Type: text/html; charset=utf-8');
            echo $response;
        }
    }



    public function edit(News $news)
    {
        // Lấy danh mục và media files
        $categories = NewsCategory::select('id', 'name')->get();
        $mediaFiles = MediaFile::select('id', 'filename', 'filepath', 'alt_text', 'title')->get();

        // Chuyển dữ liệu thành dạng JSON để AlpineJS có thể sử dụng
        $mediaJson = $mediaFiles->map(function ($file) {
            return [
                'id' => $file->id,
                'filename' => $file->filename,
                'filepath' => $file->filepath,
                'alt_text' => $file->alt_text,
                'title' => $file->title,
            ];
        })->values();

        // Trả về view với các dữ liệu cần thiết
        return view('admin.news.edit', compact('news', 'categories', 'mediaFiles', 'mediaJson'));
    }

    public function update(Request $request, News $news)
{
    // Xác thực dữ liệu đầu vào
    $validated = $request->validate([
        'meta_title' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255',
        'meta_description' => 'required|string|max:160',
        'meta_keywords' => 'required|string|max:100',
        'content' => 'required|string',
        'summary' => 'nullable|string|max:500',
        'tags' => 'nullable|string', // Chuỗi tags cách nhau bởi dấu phẩy
        'is_featured' => 'nullable|boolean',
        'canonical_url' => 'nullable|url|max:255',
        'schema_json' => 'nullable|json',
        'category_id' => 'required|exists:news_categories,id',
        'status' => 'required|boolean',
        'publish_date' => 'required|date_format:Y-m-d\TH:i',
        'thumbnail_id' => 'nullable|exists:media_files,id',
        'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
    ], [
        'meta_description.required' => 'Mô tả ngắn không được để trống.',
        'meta_keywords.required' => 'Từ khóa SEO không được để trống.',
        'publish_date.required' => 'Ngày đăng bài không được để trống.',
        'canonical_url.url' => 'Canonical URL phải là một URL hợp lệ.',
        'schema_json.json' => 'Schema JSON phải là định dạng JSON hợp lệ.',
    ]);

    // Kiểm tra ảnh đại diện
    if (!$request->hasFile('thumbnail') && !$request->input('thumbnail_id')) {
        return back()->withErrors(['thumbnail' => 'Bạn phải chọn hoặc tải lên một ảnh đại diện.'])->withInput();
    }

    // Tạo slug duy nhất
    $slug = $request->input('slug') ? Str::slug($request->slug) : Str::slug($validated['meta_title']);
    $originalSlug = $slug;
    $i = 1;
    while (News::where('slug', $slug)->where('id', '!=', $news->id)->exists()) {
        $slug = "$originalSlug-$i";
        $i++;
    }

    // Xử lý tags: Lưu trực tiếp chuỗi tags từ input
    $tags = $request->tags ? trim($request->tags) : null;
    // - Dòng trên: Lấy chuỗi tags từ request, loại bỏ khoảng trắng thừa, nếu không có thì gán null.
    // + Lợi ích: Lưu tags dưới dạng chuỗi thô như "Tôi là Phước,Phước ơi,Tôi đây,Tin hot", đúng yêu cầu không mã hóa.
    // + Lý do: Cột tags trong bảng news là TEXT, không cần mã hóa JSON.

    // Xử lý is_featured: Kiểm tra và gán giá trị boolean
    $isFeatured = $request->has('is_featured') ? 1 : 0;
    // - Dòng trên: Kiểm tra xem is_featured có trong request không, gán 1 nếu có, 0 nếu không.
    // + Lợi ích: Đảm bảo is_featured được lưu đúng giá trị (1 nếu chọn checkbox, 0 nếu không).
    // + Lý do: Checkbox chỉ gửi giá trị khi được chọn, dùng $request->has để kiểm tra rõ ràng.

    // Xử lý canonical_url
    $canonical_url = $request->canonical_url ?: (config('app.url') . "/news/$slug");

    // Xử lý schema_json
    $thumbnail_id = $request->thumbnail_id;
    $schema_json = $request->schema_json;
    if (!$schema_json) {
        $thumbnail = $thumbnail_id ? MediaFile::find($thumbnail_id) : null;
        $schema_json = json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $validated['meta_title'],
            'description' => $validated['meta_description'],
            'author' => ['@type' => 'Person', 'name' => Auth::user()->name],
            'publisher' => ['@type' => 'Organization', 'name' => 'LavishStay Resort'],
            'datePublished' => Carbon::parse($validated['publish_date'])->toIso8601String(),
            'image' => $thumbnail ? [
                '@type' => 'ImageObject',
                'url' => $thumbnail->filepath,
                'width' => $thumbnail->width ?? 800,
                'height' => $thumbnail->height ?? 600,
            ] : null,
        ], JSON_UNESCAPED_UNICODE);
    }

    // Xử lý ảnh đại diện
    if ($request->hasFile('thumbnail')) {
        $file = $request->file('thumbnail');
        if ($file->isValid()) {
            $filename = time() . '_' . Str::slug($file->getClientOriginalName()) . '.' . $file->extension();
            $path = $file->storeAs('uploads/ckeditor', $filename, 'public');
            $url = asset("storage/$path");
            [$width, $height] = getimagesize(storage_path("app/public/$path")) ?: [0, 0];

            $mediaFile = MediaFile::create([
                'filename' => $filename,
                'filepath' => $url,
                'alt_text' => $validated['meta_description'],
                'title' => $validated['meta_title'],
                'type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'used_in' => 'news',
            ]);
            $thumbnail_id = $mediaFile->id;
        } else {
            return back()->withErrors(['thumbnail' => 'Ảnh tải lên không hợp lệ.'])->withInput();
        }
    }

    // Cập nhật bài viết
    $news->update([
        'title' => $validated['meta_title'],
        'slug' => $slug,
        'meta_title' => $validated['meta_title'],
        'meta_description' => $validated['meta_description'],
        'meta_keywords' => $validated['meta_keywords'],
        'content' => $validated['content'],
        'summary' => $validated['summary'],
        'tags' => $tags, // Lưu tags dưới dạng chuỗi thô
        'is_featured' => $isFeatured, // Sử dụng biến $isFeatured
        'canonical_url' => $canonical_url,
        'schema_json' => $schema_json,
        'category_id' => $validated['category_id'],
        'author_id' => Auth::id(),
        'thumbnail_id' => $thumbnail_id,
        'status' => $validated['status'],
        'published_at' => Carbon::parse($validated['publish_date']),
    ]);
    // - Dòng $news->update: Cập nhật bài viết với tags dạng chuỗi thô và is_featured đúng giá trị.
    // + Lợi ích: Đảm bảo tags không mã hóa JSON và bài viết được đánh dấu nổi bật nếu chọn checkbox.
    // + Lý do: Đồng nhất với hàm store, phù hợp với bảng news (tags là TEXT, is_featured là tinyint).

    return redirect()->route('admin.news.index')->with('success', 'Bài viết đã được cập nhật thành công!');
}



    public function destroy($id)
    {
        $news = News::find($id);

        if (!$news) {
            return redirect()->route('admin.news.index')->with('error', 'Không tìm thấy bài viết.');
        }

        // Kiểm tra ràng buộc
        if ($news->comments()->exists()) {
            return redirect()->route('admin.news.index')->with('error', 'Không thể xóa bài viết vì có bình luận liên kết.');
        }

        if ($news->userActions()->exists()) {
            return redirect()->route('admin.news.index')->with('error', 'Không thể xóa bài viết vì có hành động (thích, đánh dấu, đánh giá) liên kết.');
        }

        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'Bài viết đã được xóa!');
    }

    public function destroyMedia($id)
    {
        $media = MediaFile::findOrFail($id);

        // Xoá ảnh khỏi storage
        Storage::delete(str_replace('/storage', '', $media->filepath));

        // Xoá media từ cơ sở dữ liệu
        $media->delete();

        return response()->json(['success' => true]);
    }

}

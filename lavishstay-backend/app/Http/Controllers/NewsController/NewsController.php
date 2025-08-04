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
        // Truy vấn bài viết
        $query = News::select('id', 'meta_title', 'category_id', 'author_id', 'thumbnail_id', 'status', 'published_at')
            ->with([
                'category:id,name',
                'thumbnail:id,filepath,alt_text',
                'author:id,name'
            ]);

        // Áp dụng bộ lọc (nếu có)
        if ($request->has('search_title') && $request->search_title !== null) {
            $query->where('meta_title', 'like', '%' . $request->search_title . '%');
        }
        if ($request->has('category_id') && $request->category_id !== null) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->has('author_id') && $request->author_id !== null) {
            $query->where('author_id', $request->author_id);
        }
        if ($request->has('status') && in_array($request->input('status'), ['0', '1'], true)) {
            $query->where('status', (int) $request->input('status'));
        }
        if ($request->has('search_date') && $request->search_date !== null) {
            $query->whereDate('published_at', $request->search_date);
        }

        $news = $query->paginate(10);

        // Lấy danh mục và tác giả
        $categories = NewsCategory::select('id', 'name')->get();
        $authors = User::select('id', 'name')
            ->whereExists(fn($q) => $q->select('id')->from('news')->whereColumn('news.author_id', 'users.id'))
            ->get();

        return view('admin.news.index', compact('news', 'categories', 'authors'));
    }

    public function create()
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
        return view('admin.news.create', compact('categories', 'mediaFiles', 'mediaJson'));
    }


    public function store(Request $request)
    {
        // Xác thực dữ liệu từ form
        $validated = $request->validate([
            'meta_title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'meta_description' => 'required|string|max:160', // Bắt buộc, giới hạn 160 ký tự
            'meta_keywords' => 'required|string|max:100', // Bắt buộc, giới hạn 100 ký tự
            'content' => 'required|string',
            'category_id' => 'required|exists:news_categories,id',
            'status' => 'required|boolean',
            'publish_date' => 'required|date_format:Y-m-d\TH:i', // Bắt buộc
            'thumbnail_id' => 'nullable|exists:media_files,id',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ], [
            // Tùy chỉnh thông báo lỗi
            'meta_description.required' => 'Mô tả ngắn không được để trống.',
            'meta_keywords.required' => 'Từ khóa SEO không được để trống.',
            'publish_date.required' => 'Ngày đăng bài không được để trống.',
        ]);

        // Kiểm tra bắt buộc phải có thumbnail hoặc thumbnail_id
        if (!$request->hasFile('thumbnail') && !$request->input('thumbnail_id')) {
            return redirect()->back()->withErrors(['thumbnail' => 'Bạn phải chọn hoặc tải lên một ảnh đại diện.']);
        }

        // Tạo slug tự động từ meta_title nếu không có slug
        $slug = $request->input('slug') ? Str::slug($request->input('slug')) : Str::slug($validated['meta_title']);

        // Kiểm tra slug có trùng không và tạo slug duy nhất nếu cần
        $originalSlug = $slug;
        $i = 1;
        while (News::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $i;
            $i++;
        }

        // Xử lý tải ảnh đại diện lên (nếu có)
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');

            // Kiểm tra nếu file hợp lệ
            if (!$file->isValid()) {
                return redirect()->back()->withErrors(['thumbnail' => 'Ảnh tải lên không hợp lệ.']);
            }

            $filename = time() . '_' . Str::slug($file->getClientOriginalName(), '-') . '.' . $file->getClientOriginalExtension();
            $filepath = $file->storeAs('media', $filename, 'public');

            // Lấy kích thước ảnh
            $fullPath = storage_path('app/public/media/' . $filename);
            [$width, $height] = getimagesize($fullPath);

            // Lưu thông tin ảnh vào bảng media_files
            $mediaFile = MediaFile::create([
                'filename' => $filename,
                'filepath' => Storage::url($filepath), // Sử dụng Storage::url() để tạo đường dẫn
                'alt_text' => $validated['meta_description'],
                'title' => $validated['meta_title'],
                'type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'width' => $width,
                'height' => $height,
                'used_in' => 'news',
            ]);

            $thumbnail_id = $mediaFile->id;
        } else {
            // Sử dụng thumbnail_id từ request
            $thumbnail_id = $validated['thumbnail_id'];
        }

        // Gán tác giả là người dùng đang đăng nhập
        $author_id = Auth()->user()->id;

        // Tạo bài viết mới
        $news = News::create([
            'meta_title' => $validated['meta_title'],
            'slug' => $slug,
            'meta_description' => $validated['meta_description'],
            'meta_keywords' => $validated['meta_keywords'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'author_id' => $author_id,
            'status' => $validated['status'],
            'published_at' => Carbon::parse($validated['publish_date']),
            'thumbnail_id' => $thumbnail_id,
        ]);

        

        // Trả về thông báo thành công
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
                $fileName = 'phuocxanh_' . time() . '.' . $extension;

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



   public function edit($id)
{
    $news = News::findOrFail($id);
    $categories = NewsCategory::select('id', 'name')->get();
    $mediaFiles = MediaFile::select('id', 'filename', 'filepath', 'alt_text', 'title')->get();
    $mediaJson = $mediaFiles->map(function ($file) {
        return [
            'id' => $file->id,
            'filename' => $file->filename,
            'filepath' => $file->filepath,
            'alt_text' => $file->alt_text,
            'title' => $file->title,
        ];
    })->values();

    return view('admin.news.edit', compact('news', 'categories', 'mediaFiles', 'mediaJson'));
}


    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $validated = $request->validate([
            'meta_title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'meta_description' => 'required|string|max:160',
            'meta_keywords' => 'required|string|max:100',
            'content' => 'required|string',
            'category_id' => 'required|exists:news_categories,id',
            'status' => 'required|boolean',
            'publish_date' => 'required|date_format:Y-m-d\TH:i',
            'thumbnail_id' => 'nullable|exists:media_files,id',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ], [
            'meta_description.required' => 'Mô tả ngắn không được để trống.',
            'meta_keywords.required' => 'Từ khóa SEO không được để trống.',
            'publish_date.required' => 'Ngày đăng bài không được để trống.',
            'category_id.required' => 'Danh mục bài viết không được để trống.',
            'content.required' => 'Nội dung bài viết không được để trống.',
        ]);

        if (!$request->hasFile('thumbnail') && !$request->input('thumbnail_id')) {
            return redirect()->back()->withErrors(['thumbnail' => 'Bạn phải chọn hoặc tải lên một ảnh đại diện.'])->withInput();
        }

        $slug = $request->input('slug') ? Str::slug($request->input('slug')) : Str::slug($validated['meta_title']);
        $originalSlug = $slug;
        $i = 1;
        while (News::where('slug', $slug)->where('id', '!=', $news->id)->exists()) {
            $slug = $originalSlug . '-' . $i;
            $i++;
        }

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            if (!$file->isValid()) {
                return redirect()->back()->withErrors(['thumbnail' => 'Ảnh tải lên không hợp lệ.'])->withInput();
            }
            $filename = 'phuocxanh_' . time() . '.' . $file->getClientOriginalExtension();
            $filepath = $file->storeAs('uploads/ckeditor', $filename, 'public');
            $fullPath = storage_path('app/public/uploads/ckeditor/' . $filename);
            [$width, $height] = getimagesize($fullPath) ?: [0, 0];

            $mediaFile = MediaFile::create([
                'filename' => $filename,
                'filepath' => Storage::url($filepath),
                'alt_text' => $validated['meta_description'],
                'title' => $validated['meta_title'],
                'type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'width' => $width,
                'height' => $height,
                'used_in' => 'news',
            ]);
            $thumbnail_id = $mediaFile->id;

            // Xóa ảnh đại diện cũ nếu có
            if ($news->thumbnail_id) {
                $oldMedia = MediaFile::find($news->thumbnail_id);
                if ($oldMedia && $oldMedia->used_in === 'news') {
                    Storage::disk('public')->delete(str_replace(Storage::url(''), '', $oldMedia->filepath));
                    $oldMedia->delete();
                }
            }
        } else {
            $thumbnail_id = $validated['thumbnail_id'];
        }

        try {
            $news->update([
                'meta_title' => $validated['meta_title'],
                'slug' => $slug,
                'meta_description' => $validated['meta_description'],
                'meta_keywords' => $validated['meta_keywords'],
                'content' => $validated['content'],
                'category_id' => $validated['category_id'],
                'status' => $validated['status'],
                'published_at' => Carbon::parse($validated['publish_date']),
                'thumbnail_id' => $thumbnail_id,
            ]);

            

            return redirect()->route('admin.news.index')->with('success', 'Bài viết đã được cập nhật thành công!');
        } catch (\Exception $e) {
            \Log::error('Error updating news: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Lỗi hệ thống khi cập nhật bài viết. Vui lòng thử lại.'])->withInput();
        }
    }


    // Xóa bài viết
    public function destroy($id)
    {
        // Tìm và xóa bài viết
        $news = News::findOrFail($id);
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

    // public function uploadImage(Request $request)
    // {
    //     try {
    //         // Xác thực file upload
    //         $request->validate([
    //             'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         ]);

    //         // Kiểm tra file upload
    //         if ($request->hasFile('upload')) {
    //             $originName = $request->file('upload')->getClientOriginalName();
    //             $extension = $request->file('upload')->getClientOriginalExtension();
    //             $fileName = 'phuocxanh_' . time() . '.' . $extension;

    //             // Lưu file vào storage/app/public/uploads/ckeditor
    //             $path = $request->file('upload')->storeAs('uploads/ckeditor', $fileName, 'public');
    //             $url = asset('storage/uploads/ckeditor/' . $fileName);

    //             // Lưu thông tin file vào bảng media_files
    //             $mediaFile = MediaFile::create([
    //                 'filename' => $fileName,
    //                 'filepath' => $url,
    //                 'alt_text' => $request->input('alt_text') ?? 'Hình ảnh bài viết',
    //                 'title' => $request->input('title') ?? 'Hình ảnh bài viết',
    //                 'type' => $request->file('upload')->getClientMimeType(),
    //                 'size' => $request->file('upload')->getSize(),
    //                 'used_in' => 'news_ckeditor',
    //             ]);

    //             // Lưu liên kết với news_id nếu có
    //             if ($request->has('news_id')) {
    //                 NewsMediaFile::create([
    //                     'news_id' => $request->input('news_id'),
    //                     'media_file_id' => $mediaFile->id,
    //                 ]);
    //             }

    //             // Lấy CKEditorFuncNum từ request
    //             $CKEditorFuncNum = $request->input('CKEditorFuncNum', 0);
    //             $msg = 'Image uploaded successfully';

    //             // Trả về response HTML cho CKEditor, bao gồm data-filepath
    //             $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg');";
    //             $response .= "document.dispatchEvent(new CustomEvent('imageUploaded', { detail: { filepath: '$url' } }));</script>";

    //             header('Content-Type: text/html; charset=utf-8');
    //             echo $response;
    //         } else {
    //             throw new \Exception('No file uploaded');
    //         }
    //     } catch (\Exception $e) {
    //         \Log::error('Lỗi khi upload ảnh qua CKEditor: ' . $e->getMessage());
    //         $CKEditorFuncNum = $request->input('CKEditorFuncNum', 0);
    //         $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '', 'Lỗi khi upload ảnh: {$e->getMessage()}');</script>";
    //         header('Content-Type: text/html; charset=utf-8');
    //         echo $response;
    //     }
    // }

}
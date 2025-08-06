<?php
namespace App\Http\Controllers\NewsController;

use App\Models\News\MediaFile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image; // nếu dùng Intervention/Image


class MediaController extends Controller
{
    // Hiển thị tất cả các media
    public function index()
    {
        $mediaFiles = MediaFile::all();
        return view('admin.media.index', compact('mediaFiles'));
    }

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'file.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            $files = $request->file('file');
            if (!is_array($files)) $files = [$files];

            $uploadedFiles = [];

            foreach ($files as $file) {
                $filename = time() . '_' . Str::slug($file->getClientOriginalName(), '-') . '.' . $file->getClientOriginalExtension();

                // Lưu vào storage/app/public/media
                $path = $file->storeAs('media', $filename, 'public');

                // Tạo URL đầy đủ
                $url = Storage::disk('public')->url($path);

                // Lấy kích thước ảnh
                $fullPath = storage_path('app/public/media/' . $filename);
                [$width, $height] = getimagesize($fullPath);
                $size = $file->getSize();

                $media = MediaFile::create([
                    'filename' => $filename,
                    'filepath' => $url,
                    'alt_text' => $request->input('alt_text') ?? 'Hình ảnh bài viết',
                    'title' => $request->input('title') ?? 'Hình ảnh bài viết',
                    'type' => $file->getClientMimeType(),
                    'size' => $size,
                    'used_in' => 'news',
                ]);

                $uploadedFiles[] = [
                    'id' => $media->id,
                    'filename' => $media->filename,
                    'filepath' => $media->filepath,
                    'alt_text' => $media->alt_text,
                    'title' => $media->title,
                    'width' => $width, // Thêm width
                    'height' => $height, // Thêm height
                    'size' => $size, // Thêm size
                ];
            }

            return response()->json([
                'success' => true,
                'files' => $uploadedFiles
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Lỗi khi upload ảnh: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tải ảnh lên: ' . $e->getMessage()
            ], 500);
        }
    }




    public function updateMeta(Request $request, $mediaId)
    {
        $validated = $request->validate([
            'alt_text' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
        ]);

        $mediaFile = MediaFile::findOrFail($mediaId);
        $hasChanges = false;

        // Lấy dữ liệu mới từ form
        $newAltText = trim($validated['alt_text']);
        $newTitle = trim($validated['title'] ?? '') ?: $newAltText; // Sử dụng alt_text nếu title để trống

        // Cập nhật alt_text
        if ($newAltText !== $mediaFile->alt_text) {
            $mediaFile->alt_text = $newAltText;
            $hasChanges = true;
        }

        // Cập nhật title
        if ($newTitle !== $mediaFile->title) {
            $mediaFile->title = $newTitle;
            $hasChanges = true;
        }

        // Tạo filename mới từ alt_text
        $extension = pathinfo($mediaFile->filename, PATHINFO_EXTENSION);
        $newFilename = Str::slug($newAltText) . '.' . $extension;

        // Cập nhật filename và filepath nếu filename thay đổi
        if ($newFilename !== $mediaFile->filename) {
            $oldPath = storage_path('app/public/media/' . $mediaFile->filename);
            $newPath = storage_path('app/public/media/' . $newFilename);

            // Kiểm tra tránh ghi đè
            if (file_exists($oldPath) && !file_exists($newPath)) {
                rename($oldPath, $newPath);
                $mediaFile->filename = $newFilename;
                $mediaFile->filepath = Storage::url('media/' . $newFilename);
                $hasChanges = true;
            } else if (file_exists($newPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tên file mới đã tồn tại, vui lòng chọn alt text khác.'
                ], 400);
            }
        }

        if ($hasChanges) {
            $mediaFile->save();

            // Lấy kích thước ảnh
            $fullPath = storage_path('app/public/media/' . $mediaFile->filename);
            [$width, $height] = getimagesize($fullPath);
            $fileSize = File::size($fullPath);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thông tin ảnh thành công!',
                'media' => [
                    'id' => $mediaFile->id,
                    'alt_text' => $mediaFile->alt_text,
                    'title' => $mediaFile->title,
                    'filename' => $mediaFile->filename,
                    'filepath' => asset('storage/media/' . $mediaFile->filename),
                    'width' => $width,
                    'height' => $height,
                    'size' => $fileSize,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không có thay đổi nào được thực hiện.'
        ]);
    }





    // Xóa media
    public function destroy($id)
    {
        try {
            $mediaFile = MediaFile::findOrFail($id);

            // Kiểm tra file tồn tại trong storage
            $filePath = str_replace('/storage/', 'public/', $mediaFile->filepath);
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            } else {
                \Log::warning("File không tồn tại trong storage: " . $filePath);
            }

            // Xóa record trong database
            $mediaFile->delete();

            return response()->json([
                'success' => true,
                'message' => 'Ảnh đã được xóa thành công!'
            ]);
        } catch (\Exception $e) {
            \Log::error("Lỗi khi xóa media ID {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống khi xóa ảnh: ' . $e->getMessage()
            ], 500);
        }
    }

    // Xóa nhiều media
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids');
        $mediaFiles = MediaFile::whereIn('id', $ids)->get();

        foreach ($mediaFiles as $mediaFile) {
            Storage::disk('public')->delete(Str::after($mediaFile->filepath, '/storage/'));
            $mediaFile->delete();
        }

        return response()->json(['success' => true, 'message' => 'Đã xóa các media']);
    }
}

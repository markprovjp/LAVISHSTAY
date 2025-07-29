<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaFile extends Model
{
    use HasFactory;

    protected $table = 'media_files';

    protected $fillable = [
        'filename',   // Tên file (ví dụ: khachsan1.jpg)
        'filepath',   // Đường dẫn đến file (ví dụ: /storage/uploads/ckeditor/filename.jpg)
        'alt_text',   // Thuộc tính ALT cho hình ảnh
        'title',      // Tiêu đề hình ảnh khi hover
        'type',       // Loại file (image/jpeg, image/webp...)
        'size',       // Dung lượng file
        'used_in',    // Ngữ cảnh sử dụng (ví dụ: news, news_ckeditor)
    ];


    public function news()
    {
        return $this->belongsToMany(News::class, 'news_media_files', 'media_file_id', 'news_id');
    }
}
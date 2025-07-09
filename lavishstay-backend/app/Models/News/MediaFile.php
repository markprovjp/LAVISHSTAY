<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MediaFile extends Model
{
    use HasFactory;

    protected $table = 'media_files';

   
    protected $fillable = [
        'filename',      // Tên file gốc: khachsan1.jpg
        'filepath',      // Đường dẫn lưu trữ: /storage/media/khachsan1.jpg
        'alt_text',      // ALT – dùng cho SEO hình ảnh
        'title',         // Tiêu đề khi hover
        'type',          // MIME type: image/jpeg, image/webp...
        'size',          // Dung lượng (byte)
        'used_in',       // Ngữ cảnh: news, banner, home,...
    ];

 
    public function thumbnailsOfNews()
    {
        return $this->hasMany(\App\Models\News\News::class, 'thumbnail_id');
    }


    public function getUrlAttribute(): string
    {
        // Nếu bạn dùng Storage::url có symlink storage -> public/storage
        return Storage::exists($this->filepath)
            ? Storage::url($this->filepath)
            : asset('images/placeholder.png');
    }

   
    protected static function booted(): void
    {
        static::creating(function (MediaFile $file) {
            if (empty($file->alt_text)) {
                // Lấy tên file không có đuôi
                $file->alt_text = pathinfo($file->filename, PATHINFO_FILENAME);
            }
        });
    }
}
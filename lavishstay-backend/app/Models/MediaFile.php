<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaFile extends Model
{
    use HasFactory;

    protected $table = 'media_files';

    protected $fillable = [
        'filename',
        'filepath',
        'alt_text',
        'title',
        'type',
        'size',
        'used_in',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    // Helper methods
    public function getUrlAttribute()
    {
        return asset($this->filepath);
    }

    public function getFullPathAttribute()
    {
        return public_path($this->filepath);
    }

    public function getSizeFormatted()
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function isImage()
    {
        return str_starts_with($this->type, 'image/');
    }
}

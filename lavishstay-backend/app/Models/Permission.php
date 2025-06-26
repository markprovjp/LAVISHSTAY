<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Role;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    protected $fillable = [
        'name',        // Tên kỹ thuật (VD: create_user)
        'description', // Mô tả tiếng Việt (VD: Tạo người dùng)
    ];

    /**
     * Mối quan hệ: Một quyền có thể thuộc nhiều vai trò
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role');
    }

    /**
     * Lấy mô tả tiếng Việt nếu có, ngược lại dùng name
     */
    public function getLabelAttribute()
    {
        return $this->description ?? ucfirst(str_replace('_', ' ', $this->name));
    }
}

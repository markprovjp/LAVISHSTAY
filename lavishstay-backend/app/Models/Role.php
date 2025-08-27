<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Permission;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'description', 
    ];

    /**
     * Mối quan hệ: Một vai trò có nhiều người dùng
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    /**
     * Mối quan hệ: Một vai trò có nhiều quyền
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }



    /**
     * Gán một quyền cho role (nếu chưa có)
     */
    public function givePermissionTo($permissionId)
    {
        if (!$this->permissions->contains($permissionId)) {
            $this->permissions()->attach($permissionId);
        }
    }

    /**
     * Gỡ bỏ một quyền khỏi role
     */
    public function revokePermission($permissionId)
    {
        $this->permissions()->detach($permissionId);
    }

    /**
     * Gán lại toàn bộ quyền cho role
     */
    public function syncPermissions(array $permissionIds)
    {
        $this->permissions()->sync($permissionIds);
    }

    /**
     * Trả về tên hiển thị tiếng Việt cho vai trò (nếu có)
     */
     public static function getRoleLabel($name)
    {
        return [
            'system_admin' => 'Quản trị hệ thống',
            'guest' => 'Khách hàng',
            'receptionist' => 'Lễ tân',
            'hotel_manager' => 'Quản lý khách sạn',
            'dept_manager' => 'Quản lý bộ phận',
            'housekeeping' => 'Nhân viên buồng phòng',
            'marketing' => 'Đội Marketing & SEO',
            'finance' => 'Kế toán/Tài chính',
        ][$name] ?? ucfirst($name);
    }

}
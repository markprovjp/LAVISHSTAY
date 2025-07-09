<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionController extends Controller
{
    public function index($roleId)
    {
        $role = Role::findOrFail($roleId);
        $permissions = Permission::all();
        $rolePermissionIds = $role->permissions()->pluck('permissions.id')->toArray();

        return view('admin.roles.permissions.index', compact('role', 'permissions', 'rolePermissionIds'));
    }

    public function update(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);
        $selected = $request->input('permissions', []); // Quyền được chọn từ UI

        // Lấy toàn bộ quyền cha đệ quy để lưu cùng quyền con
        $allPermissionIds = array_unique(array_merge($selected, $this->getAllParentPermissions($selected)));

        // Nếu không có thay đổi gì thì không cập nhật
        $currentPermissions = $role->permissions()->pluck('permissions.id')->toArray();
        sort($currentPermissions);
        sort($allPermissionIds);

        if ($currentPermissions === $allPermissionIds) {
            return redirect()->back()->with('error', 'Không có thay đổi nào để cập nhật!');
        }

        try {
            $role->permissions()->sync($allPermissionIds);
            return redirect()->back()->with('success', 'Cập nhật quyền thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi cập nhật quyền!');
        }
    }

    // Lấy tất cả quyền cha đệ quy từ danh sách quyền con được chọn
    private function getAllParentPermissions(array $permissionIds): array
    {
        $all = [];

        foreach ($permissionIds as $id) {
            $permission = Permission::find($id);
            while ($permission && $permission->parent_id) {
                $parentId = $permission->parent_id;
                if (!in_array($parentId, $all)) {
                    $all[] = $parentId;
                    $permission = Permission::find($parentId);
                } else {
                    break; // Tránh vòng lặp vô hạn
                }
            }
        }

        return $all;
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RolePermissionController extends Controller
{
    public function index(Request $request)
{
    $roles = Role::all();
    $permissions = Permission::all();

    $rolePermissionsMap = [];
    foreach ($roles as $role) {
        $rolePermissionsMap[$role->id] = $role->permissions->pluck('id')->toArray();
    }

    return view('admin.roles.index', [
        'roles' => $roles,
        'permissions' => $permissions,
        'rolePermissionsMap' => $rolePermissionsMap,
    ]);
}

public function add(Request $request, $id)
{
    $role = Role::findOrFail($id);

    $request->validate([
        'permissions' => 'required|array',
        'permissions.*' => 'exists:permissions,id',
    ]);

    $role->permissions()->syncWithoutDetaching($request->permissions);

    return redirect()->route('admin.roles', ['role_id' => $id])
        ->with('success', 'Thêm quyền thành công.')
        ->withInput(['selectedRoleId' => $id]);
}

public function remove(Request $request, $id)
{
    $role = Role::findOrFail($id);

    $request->validate([
        'permissions' => 'array',
        'permissions.*' => 'exists:permissions,id',
    ]);

    $role->permissions()->detach($request->permissions);

    return redirect()->route('admin.roles', ['role_id' => $id])
        ->with('success', 'Xoá quyền thành công.')
        ->withInput(['selectedRoleId' => $id]);
}

}

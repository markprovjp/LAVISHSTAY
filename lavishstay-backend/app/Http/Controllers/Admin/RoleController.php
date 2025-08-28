<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Database\QueryException;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                'unique:roles,name',
                function ($attribute, $value, $fail) {
                    if (in_array(strtolower($value), ['admin', 'guest'])) {
                        $fail('Không được tạo vai trò đặc biệt: admin hoặc guest!');
                    }
                }
            ],
            'description' => 'nullable|string|max:255'
        ]);
        Role::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Thêm vai trò thành công!');
    }

    // public function update(Request $request, $id)
    // {
    //     $role = Role::findOrFail($id);
    //     if (in_array(strtolower($role->name), ['admin', 'guest'])) {
    //         return redirect()->route('admin.roles.index')
    //             ->with('error', 'Không thể sửa vai trò đặc biệt: ' . $role->name . '!');
    //     }

    //     $validated = $request->validate([
    //         'name' => 'required|string|max:50|unique:roles,name,' . $id,
    //         'description' => 'nullable|string|max:255',
    //     ]);
    //     $role->update($validated);

    //     return redirect()->route('admin.roles.index')->with('success', 'Cập nhật vai trò thành công!');
    // }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // Không cho xoá role đặc biệt
        if (in_array(strtolower($role->name), ['system_admin', 'guest', 'receptionist'])) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Không thể xoá vai trò đặc biệt: ' . $role->name . '!');
        }

        // Nếu vai trò còn user đang sử dụng thì không cho xoá
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Không thể xoá vai trò "' . $role->name . '" vì vẫn còn tài khoản đang sử dụng vai trò này!');
        }

        try {
            $role->delete();

            return redirect()->route('admin.roles.index')
                ->with('success', 'Vai trò đã được xoá thành công!');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '23000') {
                return redirect()->route('admin.roles.index')
                    ->with('error', 'Không thể xoá vai trò "' . $role->name . '" vì vẫn đang được sử dụng. Vui lòng gỡ role khỏi người dùng hoặc quyền liên quan trước khi xoá.');
            }
            throw $e;
        }
    }
}

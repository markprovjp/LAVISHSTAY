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

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        if (in_array(strtolower($role->name), ['admin', 'guest'])) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Không thể sửa vai trò đặc biệt: ' . $role->name . '!');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:roles,name,' . $id,
            'description' => 'nullable|string|max:255',
        ]);
        $role->update($validated);

        return redirect()->route('admin.roles.index')->with('success', 'Cập nhật vai trò thành công!');
    }

    public function destroy($id)
    {
        // Tìm role theo ID, nếu không có thì trả về 404
        $role = Role::findOrFail($id);

        // Ngăn không cho xoá role đặc biệt
        if (in_array(strtolower($role->name), ['system_admin', 'guest'])) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Không thể xoá vai trò đặc biệt: ' . $role->name . '!');
        }

        try {
            // Tiến hành xoá role
            $role->delete();

            return redirect()->route('admin.roles.index')
                ->with('success', 'Vai trò đã được xoá thành công!');
        } catch (QueryException $e) {
            if ($e->getCode() == '23000') {
                return redirect()->route('admin.roles.index')
                    ->with(
                        'error',
                        'Không thể xoá vai trò "' . $role->name . '" vì vẫn đang được sử dụng. 
                     Vui lòng gỡ role khỏi người dùng hoặc quyền liên quan trước khi xoá.'
                    );
            }

            // Các lỗi khác thì ném ra cho Laravel xử lý
            throw $e;
        }
    }
}

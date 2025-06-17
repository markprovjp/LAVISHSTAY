<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index()
    {
        $users = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['admin', 'manager', 'receptionist']);
        })->with('roles')->paginate(10);

        return view('admin.staffs.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.staffs.show', compact('user'));
    }

    public function create()
    {
        // Lấy các vai trò dành cho nhân viên (không bao gồm guest)
        $staffRoles = Role::whereIn('name', ['admin', 'manager', 'receptionist'])->get();
        return view('admin.staffs.create', compact('staffRoles'));
    }

    public function store(Request $request)
    {
        // Validate
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'role_id' => 'required|exists:roles,id',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'password.regex' => 'Mật khẩu phải chứa ít nhất 1 chữ hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt.',
        ]);

        // Bảo vệ: không cho chọn vai trò không hợp lệ (guest)
        $role = Role::findOrFail($request->role_id);
        if (!in_array($role->name, ['admin', 'manager', 'receptionist'])) {
            abort(403, 'Vai trò không hợp lệ cho nhân viên');
        }

        // Mã hóa mật khẩu
        $validated['password'] = Hash::make($validated['password']);

        // Xử lý ảnh đại diện nếu có
        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            $photoPath = $request->file('profile_photo')->storePublicly(
                'profile-photos',
                ['disk' => 'public']
            );
            $validated['profile_photo_path'] = $photoPath;
        }

        // Tạo user
        $user = User::create($validated);

        // Gán role nhân viên
        $user->roles()->attach($role->id);

        return redirect()->route('admin.staffs')->with('success', 'Nhân viên đã được tạo thành công!');
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);

        // Đảm bảo user là nhân viên
        if (!$user->hasAnyRole(['admin', 'manager', 'receptionist'])) {
            abort(403, 'Không thể sửa người không phải là nhân viên');
        }

        // Lấy danh sách vai trò nhân viên
        $staffRoles = Role::whereIn('name', ['admin', 'manager', 'receptionist'])->get();

        return view('admin.staffs.edit', compact('user', 'staffRoles'));
    }
    public function update(Request $request, $id)
    {
        $user = User::with('roles')->findOrFail($id);

        if (!$user->hasAnyRole(['admin', 'manager', 'receptionist'])) {
            abort(403, 'Không thể cập nhật người không phải là nhân viên');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => [
                'nullable',
                'string',
                'min:6',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/'
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'role_id' => 'required|exists:roles,id',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'password.regex' => 'Mật khẩu phải chứa ít nhất 1 chữ hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt.',
        ]);

        // Lấy role và đảm bảo chỉ thuộc vai trò nhân viên
        $role = Role::findOrFail($validated['role_id']);
        if (!in_array($role->name, ['admin', 'manager', 'receptionist'])) {
            abort(403, 'Vai trò không hợp lệ cho nhân viên');
        }

        // Mã hóa mật khẩu nếu có
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Xử lý ảnh nếu có
        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            $validated['profile_photo_path'] = $request->file('profile_photo')->storePublicly(
                'profile-photos',
                ['disk' => 'public']
            );
        }

        // Cập nhật user
        $user->update($validated);

        // Đồng bộ vai trò
        $user->roles()->sync([$role->id]);

        return redirect()->route('admin.staffs')->with('success', 'Nhân viên đã được cập nhật thành công!');
    }


     public function changePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
        ], [
            'password.regex' => 'Mật khẩu phải chứa ít nhất 1 chữ hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt.',
        ]);

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Mật khẩu hiện tại không chính xác.');
        }

        // Cập nhật mật khẩu mới
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with('success', 'Mật khẩu đã được thay đổi thành công.');
    }


     public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Không cho phép xóa chính mình
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.staffs')->with('error', 'Bạn không thể xóa tài khoản của chính mình!');
        }

        // Xóa ảnh đại diện nếu có (sử dụng method của Jetstream)
        if ($user->profile_photo_path) {
            $user->deleteProfilePhoto();
        }

        $user->delete();

        return redirect()->route('admin.staffs')->with('success', 'Người dùng đã được xóa thành công!');
    }
}

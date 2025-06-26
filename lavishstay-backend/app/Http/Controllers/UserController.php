<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\Role;

/**
 * UserController - Quản lý người dùng trong hệ thống
 */
class UserController extends Controller
{
    /**
     * Hiển thị danh sách người dùng
     */
    public function index()
    {
        $users = User::with('roles')->paginate(8); // eager loading roles
        return view('admin.users.index', compact('users'));
    }


    /**
     * Hiển thị form tạo người dùng mới
     */
    public function create()
    {
        $roles = Role::all(); // Lấy tất cả vai trò để hiển thị trong form
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
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
            'role_id' => 'required|exists:roles,id', // đổi từ role → role_id
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'password.regex' => 'Mật khẩu phải chứa ít nhất 1 chữ hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt.',
            'profile_photo.image' => 'File phải là hình ảnh.',
            'profile_photo.mimes' => 'Chỉ chấp nhận file PNG, JPG, JPEG, GIF.',
            'profile_photo.max' => 'Kích thước file không được vượt quá 2MB.',
        ]);

        // Mã hóa mật khẩu
        $validated['password'] = Hash::make($validated['password']);

        // Xử lý upload ảnh
        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            $photoPath = $request->file('profile_photo')->storePublicly(
                'profile-photos',
                ['disk' => 'public']
            );
            $validated['profile_photo_path'] = $photoPath;
        }

        // Tạo user
        $user = User::create($validated);

        // Gán vai trò vào bảng role_user
        $user->roles()->attach($request->role_id); // <- Đây là gán vai trò

        return redirect()->route('admin.users')->with('success', 'Người dùng đã được tạo thành công!');
    }

    /**
     * Hiển thị chi tiết người dùng
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Hiển thị form chỉnh sửa người dùng
     */
    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();
        // Kiểm tra xem người dùng có vai trò nào không
        $currentRole = $user->roles->first()->name ?? null;

        return view('admin.users.edit', compact('user', 'roles', 'currentRole'));
    }


    /**
     * Cập nhật thông tin người dùng
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'role' => 'required|in:admin,manager,staff,customer,guest,receptionist',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'password.regex' => 'Mật khẩu phải chứa ít nhất 1 chữ hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt.',
            'profile_photo.image' => 'File phải là hình ảnh.',
            'profile_photo.mimes' => 'Chỉ chấp nhận file PNG, JPG, JPEG, GIF.',
            'profile_photo.max' => 'Kích thước file không được vượt quá 2MB.',
        ]);

        // Xử lý mật khẩu
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Xử lý upload ảnh đại diện
        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            // Xóa ảnh cũ nếu có
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $photoPath = $request->file('profile_photo')->storePublicly(
                'profile-photos',
                ['disk' => 'public']
            );
            $validated['profile_photo_path'] = $photoPath;
        }

        $user->update($validated);


        if ($request->filled('role')) {
            $role = Role::where('name', $request->role)->first();

            if ($role) {
                $user->roles()->sync([$role->id]); // Xóa vai trò cũ và gán vai trò mới
            }
        }


        return redirect()->route('admin.users.show', $user->id)->with('success', 'Thông tin người dùng đã được cập nhật!');
    }

    /**
     * Đổi mật khẩu người dùng
     */
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

    /**
     * Xóa người dùng
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Không cho phép xóa chính mình
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'Bạn không thể xóa tài khoản của chính mình!');
        }

        // Xóa ảnh đại diện nếu có (sử dụng method của Jetstream)
        if ($user->profile_photo_path) {
            $user->deleteProfilePhoto();
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Người dùng đã được xóa thành công!');
    }
}

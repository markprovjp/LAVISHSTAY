<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index()
    {
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'guest');
        })->with('roles')->paginate(10);

        return view('admin.customers.index', compact('users'));
    }

    public function create()
    {
        $guestRole = Role::where('name', 'guest')->firstOrFail();
        return view('admin.customers.create', ['guestRoleId' => $guestRole->id]);
    }

    public function store(Request $request)
    {
        // 1. Validate form (không có role_id nữa)
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
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'password.regex' => 'Mật khẩu phải chứa ít nhất 1 chữ hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt.',
            'profile_photo.image' => 'File phải là hình ảnh.',
            'profile_photo.mimes' => 'Chỉ chấp nhận file PNG, JPG, JPEG, GIF.',
            'profile_photo.max' => 'Kích thước file không được vượt quá 2MB.',
        ]);

        // 2. Mã hóa mật khẩu
        $validated['password'] = Hash::make($validated['password']);

        // 3. Xử lý ảnh đại diện nếu có
        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            $photoPath = $request->file('profile_photo')->storePublicly(
                'profile-photos',
                ['disk' => 'public']
            );
            $validated['profile_photo_path'] = $photoPath;
        }

        // 4. Tạo user
        $user = User::create($validated);

        // 5. Gán role mặc định là "guest"
        $guestRole = Role::where('name', 'guest')->firstOrFail();
        $user->roles()->attach($guestRole->id);

        return redirect()->route('admin.customers')->with('success', 'Khách hàng đã được tạo thành công!');
    }


    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.customers.show', compact('user'));
    }



    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);

        // Đảm bảo user này là khách hàng
        if (!$user->hasRole('guest')) {
            abort(403, 'Không thể sửa người không phải là khách hàng');
        }

        $guestRole = Role::where('name', 'guest')->firstOrFail();

        return view('admin.customers.edit', [
            'user' => $user,
            'guestRoleId' => $guestRole->id,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (!$user->hasRole('guest')) {
            abort(403, 'Không thể cập nhật người không phải là khách hàng');
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
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'password.regex' => 'Mật khẩu phải chứa ít nhất 1 chữ hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt.',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            $photoPath = $request->file('profile_photo')->storePublicly(
                'profile-photos',
                ['disk' => 'public']
            );
            $validated['profile_photo_path'] = $photoPath;
        }

        $user->update($validated);

        return redirect()->route('admin.customers')->with('success', 'Khách hàng đã được cập nhật thành công!');
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
            return redirect()->route('admin.customers')->with('error', 'Bạn không thể xóa tài khoản của chính mình!');
        }

        // Xóa ảnh đại diện nếu có (sử dụng method của Jetstream)
        if ($user->profile_photo_path) {
            $user->deleteProfilePhoto();
        }

        $user->delete();

        return redirect()->route('admin.customers')->with('success', 'Người dùng đã được xóa thành công!');
    }
}

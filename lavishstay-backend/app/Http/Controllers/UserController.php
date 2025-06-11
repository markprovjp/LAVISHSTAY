<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }
}
=======
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

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
        $users = User::paginate(8);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Hiển thị form tạo người dùng mới
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Lưu người dùng mới vào database
     */
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
            'role' => 'required|in:' . implode(',', array_keys(User::getRoles())),

            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'password.regex' => 'Mật khẩu phải chứa ít nhất 1 chữ hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt.',
            'profile_photo.image' => 'File phải là hình ảnh.',
            'profile_photo.mimes' => 'Chỉ chấp nhận file PNG, JPG, JPEG, GIF.',
            'profile_photo.max' => 'Kích thước file không được vượt quá 2MB.',
        ]);

        // Mã hóa mật khẩu
        $validated['password'] = Hash::make($validated['password']);
        // dd($validated);
        // Xử lý upload profile photo (sử dụng Jetstream)
        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            $photoPath = $request->file('profile_photo')->storePublicly(
                'profile-photos', ['disk' => 'public']
            );
            $validated['profile_photo_path'] = $photoPath;
        }

        User::create($validated);

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
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
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
                'profile-photos', ['disk' => 'public']
            );
            $validated['profile_photo_path'] = $photoPath;
        }

        $user->update($validated);

        return redirect()->route('admin.users')->with('success', 'Thông tin người dùng đã được cập nhật!');
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
>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc

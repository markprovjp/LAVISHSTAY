<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index()
    {
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'guest');
        })->with('roles')->paginate(10);

        return view('admin.customers.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        if (!$user->hasRole('guest')) {
            abort(403, 'Người dùng không phải là khách hàng');
        }
        return view('admin.customers.show', compact('user'));
    }

    public function create()
    {
        $guestRole = Role::where('name', 'guest')->firstOrFail();
        return view('admin.customers.create', compact('guestRole'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'identity_code' => 'nullable|string|max:50|unique:users,identity_code',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'password.regex' => 'Mật khẩu phải chứa ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt.',
        ]);

        // Get the guest role
        $role = Role::where('name', 'guest')->firstOrFail();

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        // Handle profile photo
        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            $photoPath = $request->file('profile_photo')->storePublicly(
                'profile-photos',
                ['disk' => 'public']
            );
            $validated['profile_photo_path'] = $photoPath;
        }

        // Create user
        $user = User::create($validated);

        // Assign guest role
        $user->roles()->attach($role->id);

        return redirect()->route('admin.customers')->with('success', 'Khách hàng đã được tạo thành công!');
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        if (!$user->hasRole('guest')) {
            abort(403, 'Không thể sửa người không phải là khách hàng');
        }
        $guestRole = Role::where('name', 'guest')->firstOrFail();
        return view('admin.customers.edit', compact('user', 'guestRole'));
    }

    public function update(Request $request, $id)
    {
        $user = User::with('roles')->findOrFail($id);
        if (!$user->hasRole('guest')) {
            abort(403, 'Không thể cập nhật người không phải là khách hàng');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'identity_code' => 'nullable|string|max:50|unique:users,identity_code,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle profile photo
        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            $validated['profile_photo_path'] = $request->file('profile_photo')->storePublicly(
                'profile-photos',
                ['disk' => 'public']
            );
        }

        // Update user
        $user->update($validated);

        // Ensure guest role is maintained
        $role = Role::where('name', 'guest')->firstOrFail();
        $user->roles()->sync([$role->id]);

        return redirect()->route('admin.customers')->with('success', 'Khách hàng đã được cập nhật thành công!');
    }

    public function changePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if (!$user->hasRole('guest')) {
            abort(403, 'Không thể đổi mật khẩu cho người không phải khách hàng');
        }

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
        ], [
            'password.regex' => 'Mật khẩu phải chứa ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt.',
        ]);

        // Verify current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->back()->with('error', 'Mật khẩu hiện tại không đúng.');
        }

        // Update password
        $user->update([
            'password' => Hash::make($validated['password']),
            'must_change_password' => false,
        ]);

        return redirect()->back()->with('success', 'Mật khẩu đã được cập nhật thành công!');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        if (!$user->hasRole('guest')) {
            abort(403, 'Không thể đặt lại mật khẩu cho người không phải khách hàng');
        }

        if (empty($user->email)) {
            return redirect()->back()->with('error', 'Tài khoản chưa được đăng ký bằng email. Vui lòng thêm email trước khi đặt lại mật khẩu.');
        }

        $newPassword = Str::random(10);
        $user->update([
            'password' => Hash::make($newPassword),
            'must_change_password' => true,
        ]);

        Mail::to($user->email)->send(new ResetPasswordMail($newPassword));

        return redirect()->back()->with('success', 'Mật khẩu đã được đặt lại và gửi về email của khách hàng.');
    }
    
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if (!$user->hasRole('guest')) {
            abort(403, 'Không thể xóa người không phải khách hàng');
        }

        if ($user->id === auth()->id()) {
            return redirect()->route('admin.customers')->with('error', 'Bạn không thể xóa tài khoản của chính mình!');
        }

        if ($user->profile_photo_path) {
            $user->deleteProfilePhoto();
        }

        $user->delete();

        return redirect()->route('admin.customers')->with('success', 'Khách hàng đã được xóa thành công!');
    }
}

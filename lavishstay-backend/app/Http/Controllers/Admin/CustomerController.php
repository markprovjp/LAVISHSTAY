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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereHas('roles', function ($q) {
            $q->where('name', 'guest');
        });

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('email', 'like', '%' . $request->keyword . '%')
                    ->orWhere('phone', 'like', '%' . $request->keyword . '%');
            });
        }

        if ($request->filled('identity_code')) {
            $query->where('identity_code', 'like', '%' . $request->identity_code . '%');
        }

        $users = $query->with('roles')
            ->paginate(10)
            ->appends($request->query());

        return view('admin.users.customers.index', compact('users'));
    }


    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        if (!$user->hasRole('guest')) {
            abort(403, 'Người dùng không phải là khách hàng');
        }
        return view('admin.users.customers.show', compact('user'));
    }

    public function create()
    {
        $guestRole = Role::where('name', 'guest')->firstOrFail();
        return view('admin.users.customers.create', compact('guestRole'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'email' => 'required_without:phone|nullable|email|max:255|unique:users,email',
            'phone' => 'required_without:email|nullable|string|max:20|unique:users,phone',

            'identity_code' => 'required|string|max:50|unique:users,identity_code',
            'address' => 'nullable|string|max:500',

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'email.required_without' => 'Vui lòng nhập email hoặc số điện thoại.',
            'phone.required_without' => 'Vui lòng nhập số điện thoại hoặc email.',
            'phone.unique' => 'Số điện thoại đã tồn tại.',
            'email.unique' => 'Email đã tồn tại.',
            'identity_code.required' => 'Vui lòng nhập CCCD/Hộ chiếu.',
            'identity_code.unique' => 'CCCD/Hộ chiếu đã tồn tại.',
            'password.regex' => 'Mật khẩu phải chứa ít nhất 8 ký tự, gồm chữ hoa, chữ thường, số và ký tự đặc biệt.',
        ]);
        // dd($validated);


        //Kiểm tra vai trò guest
        $role = Role::where('name', 'guest')->firstOrFail();

        $validated['password'] = Hash::make($validated['password']);

        // Nếu có ảnh đại diện thì lưu vào storage
        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            $photoPath = $request->file('profile_photo')->storePublicly(
                'profile-photos',
                ['disk' => 'public']
            );
            $validated['profile_photo_path'] = $photoPath;
        }

        $user = User::create($validated);

        // Gán vai trò guest cho user mới
        $user->roles()->attach($role->id);

        return redirect()->route('admin.users.customers.index')
            ->with('success', 'Khách hàng đã được tạo thành công!');
    }


    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        if (!$user->hasRole('guest')) {
            abort(403, 'Không thể sửa người không phải là khách hàng');
        }
        $guestRole = Role::where('name', 'guest')->firstOrFail();
        return view('admin.users.customers.edit', compact('user', 'guestRole'));
    }

    public function update(Request $request, $id)
    {
        $user = User::with('roles')->findOrFail($id);

        if (!$user->hasRole('guest')) {
            abort(403, 'Không thể cập nhật người không phải là khách hàng.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'email' => 'required_without:phone|nullable|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required_without:email|nullable|string|max:20|unique:users,phone,' . $user->id,

            'identity_code' => 'required|string|max:50|unique:users,identity_code,' . $user->id,

            'address' => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'email.required_without' => 'Vui lòng nhập email hoặc số điện thoại.',
            'phone.required_without' => 'Vui lòng nhập số điện thoại hoặc email.',
            'email.unique' => 'Email đã tồn tại.',
            'phone.unique' => 'Số điện thoại đã tồn tại.',
            'identity_code.required' => 'Vui lòng nhập CCCD/Hộ chiếu.',
            'identity_code.unique' => 'CCCD/Hộ chiếu đã tồn tại.',
        ]);

        if (empty($validated['email'])) {
            $validated['email'] = null;  // Ghi đè thành NULL trong DB
        }

        if (empty($validated['phone'])) {
            $validated['phone'] = null;  // Ghi đè thành NULL trong DB
        }


        //Xử lý ảnh đại diện nếu có
        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            $validated['profile_photo_path'] = $request->file('profile_photo')->storePublicly(
                'profile-photos',
                ['disk' => 'public']
            );
        }

        // Cập nhật user
        $user->update($validated);

        // Giữ vai trò guest
        $role = Role::where('name', 'guest')->firstOrFail();
        $user->roles()->sync([$role->id]);

        return redirect()->route('admin.users.customers.show', $user->id)
            ->with('success', 'Khách hàng đã được cập nhật thành công!');
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

        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.customers.index')->with('error', 'Bạn không thể xóa tài khoản của chính mình!');
        }

        if ($user->profile_photo_path) {
            $user->deleteProfilePhoto();
        }

        $user->delete();

        return redirect()->route('admin.users.customers.index')->with('success', 'Khách hàng đã được xóa thành công!');
    }
}
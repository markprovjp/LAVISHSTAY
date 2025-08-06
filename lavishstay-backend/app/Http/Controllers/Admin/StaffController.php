<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class StaffController extends Controller
{
    public function index(Request $request)
    {
         $query = User::whereDoesntHave('roles', function ($q) {
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

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $users = $query->with('roles')->paginate(10)->appends($request->query());

        return view('admin.users.staffs.index', compact('users'));
    }


    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        // dd($user);
        return view('admin.users.staffs.show', compact('user'));
    }

    public function create()
    {
        // Lấy các vai trò dành cho nhân viên (không bao gồm guest)
        $staffRoles = Role::where('name', '!=', 'guest')->get();
        return view('admin.users.staffs.create', compact('staffRoles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required_without:phone|nullable|email|max:255|unique:users,email',
            'phone' => 'required_without:email|nullable|string|max:20|unique:users,phone',
            'identity_code' => 'required|string|max:50|unique:users,identity_code',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'address' => 'nullable|string|max:500',
            'role_id' => 'required|exists:roles,id',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'email.required_without' => 'Vui lòng nhập email hoặc số điện thoại.',
            'phone.required_without' => 'Vui lòng nhập số điện thoại hoặc email.',
            'phone.unique' => 'Số điện thoại đã tồn tại.',
            'email.unique' => 'Email đã tồn tại.',
            'identity_code.required' => 'Vui lòng nhập CCCD/Hộ chiếu.',
            'identity_code.unique' => 'CCCD/Hộ chiếu đã tồn tại.',
            'password.regex' => 'Mật khẩu phải chứa ít nhất 1 chữ hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt.',
        ]);

        // Xóa trường rỗng để tránh lỗi khi insert
        if (empty($validated['email'])) unset($validated['email']);
        if (empty($validated['phone'])) unset($validated['phone']);

        // Kiểm tra vai trò: không cho phép gán vai trò 'guest'
        $role = Role::findOrFail($request->role_id);
        if ($role->name === 'guest') {
            abort(403, 'Vai trò "guest" không được phép gán cho nhân viên.');
        }

        // Hash mật khẩu
        $validated['password'] = Hash::make($validated['password']);

        // Lưu ảnh đại diện nếu có
        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            $photoPath = $request->file('profile_photo')->storePublicly(
                'profile-photos',
                ['disk' => 'public']
            );
            $validated['profile_photo_path'] = $photoPath;
        }

        // Tạo user staff
        $user = User::create($validated);

        // Gán role staff
        $user->roles()->attach($role->id);

        return redirect()->route('admin.users.staffs.index')->with('success', 'Nhân viên đã được tạo thành công!');
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);

        // Đảm bảo user là nhân viên (không có vai trò là guest)
        if ($user->hasRole('guest')) {
            abort(403, 'Không thể sửa người không phải là nhân viên');
        }

        // Lấy tất cả vai trò ngoại trừ guest
        $staffRoles = Role::where('name', '!=', 'guest')->get();
        return view('admin.users.staffs.edit', compact('user', 'staffRoles'));
    }
    public function update(Request $request, $id)
    {
        $user = User::with('roles')->findOrFail($id);

        // Kiểm tra xem người dùng có phải là nhân viên không (không có vai trò guest)
        if ($user->hasRole('guest')) {
            abort(403, 'Không thể cập nhật người có vai trò guest.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'identity_code' => 'required|string|max:50|unique:users,identity_code,' . $user->id,
            'password' => [
                'nullable',
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

        // Kiểm tra vai trò: không cho phép gán vai trò 'guest'
        $role = Role::findOrFail($validated['role_id']);
        if ($role->name === 'guest') {
            abort(403, 'Vai trò "guest" không được phép gán cho nhân viên.');
        }

        // Hash password nếu có nhập
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Xử lý ảnh
        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            $validated['profile_photo_path'] = $request->file('profile_photo')->storePublicly(
                'profile-photos',
                ['disk' => 'public']
            );
        }

        // Cập nhật
        $user->update($validated);

        // Gán lại vai trò
        $user->roles()->sync([$role->id]);

        return redirect()->route('admin.users.staffs.show', $user->id)->with('success', 'Nhân viên đã được cập nhật thành công!');
    }


    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        // Kiểm tra nếu không có email
        if (empty($user->email)) {
            return redirect()->back()->with('error', 'Tài khoản chưa được đăng ký bằng email. Vui lòng thêm email trước khi reset mật khẩu.');
        }

        // Sinh mật khẩu ngẫu nhiên
        $newPassword = Str::random(10);

        // Cập nhật mật khẩu và yêu cầu đổi sau khi đăng nhập
        $user->update([
            'password' => Hash::make($newPassword),
            'must_change_password' => true,
        ]);

        // Gửi mật khẩu mới qua email
        Mail::to($user->email)->send(new ResetPasswordMail($newPassword));

        return redirect()->back()->with('success', 'Mật khẩu đã được reset và gửi về email của nhân viên.');
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Không cho phép xóa chính mình
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.staffs.index')->with('error', 'Bạn không thể xóa tài khoản của chính mình!');
        }

        // Xóa ảnh đại diện nếu có (sử dụng method của Jetstream)
        if ($user->profile_photo_path) {
            $user->deleteProfilePhoto();
        }

        $user->delete();

        return redirect()->route('admin.users.staffs.index')->with('success', 'Người dùng đã được xóa thành công!');
    }
}
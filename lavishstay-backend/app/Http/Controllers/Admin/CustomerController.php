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
use Illuminate\Database\QueryException;

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
        return view('admin.users.customers.show', compact('user'));
    }

    public function create()
    {
        $guestRole = Role::where('name', 'guest')->firstOrFail();
        return view('admin.users.customers.create', compact('guestRole'));
    }

    public function store(Request $request)
    {
        // Loại bỏ dấu cách trong mật khẩu trước khi validate
        if ($request->filled('password')) {
            $request->merge(['password' => preg_replace('/\s+/', '', $request->input('password'))]);
        }

        // Xác thực dữ liệu đầu vào
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required_without:phone|nullable|email|max:255|unique:users,email',
            'phone' => [
                'required_without:email',
                'nullable',
                'string',
                'max:20',
                'unique:users,phone',
                'regex:/^[0-9]+$/'
            ],
            'identity_code' => 'required|string|max:50|unique:users,identity_code',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/'
            ],
            'address' => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'name.string' => 'Họ và tên phải là chuỗi ký tự hợp lệ.',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'email.required_without' => 'Vui lòng cung cấp ít nhất email hoặc số điện thoại.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.max' => 'Địa chỉ email không được vượt quá 255 ký tự.',
            'email.unique' => 'Địa chỉ email này đã được sử dụng bởi người dùng khác.',
            'phone.required_without' => 'Vui lòng cung cấp ít nhất email hoặc số điện thoại.',
            'phone.string' => 'Số điện thoại phải là chuỗi ký tự hợp lệ.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'phone.unique' => 'Số điện thoại này đã được sử dụng bởi người dùng khác.',
            'phone.regex' => 'Số điện thoại chỉ được chứa các ký tự số.',
            'identity_code.required' => 'Vui lòng nhập mã định danh.',
            'identity_code.string' => 'Mã định danh phải là chuỗi ký tự hợp lệ.',
            'identity_code.max' => 'Mã định danh không được vượt quá 50 ký tự.',
            'identity_code.unique' => 'Mã định danh này đã được sử dụng bởi người dùng khác.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.string' => 'Mật khẩu phải là chuỗi ký tự hợp lệ.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp với mật khẩu đã nhập.',
            'password.regex' => 'Mật khẩu phải bao gồm ít nhất 1 chữ hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt (@$!%*?&#).',
            'address.max' => 'Địa chỉ không được vượt quá 500 ký tự.',
            'profile_photo.image' => 'Ảnh đại diện phải là file ảnh hợp lệ.',
            'profile_photo.mimes' => 'Ảnh đại diện chỉ hỗ trợ định dạng JPEG, PNG, JPG hoặc GIF.',
            'profile_photo.max' => 'Dung lượng ảnh đại diện không được vượt quá 2MB.',
        ]);

        $role = Role::where('name', 'guest')->firstOrFail();
        // Kiểm tra vai trò: không cho phép gán vai trò 'guest'


        // Chuẩn bị dữ liệu để tạo người dùng
        $userData = [
            'name' => $validated['name'],
            'identity_code' => $validated['identity_code'],
            'password' => Hash::make($validated['password']),
            'address' => $validated['address'] ?? null,
        ];

        if (!empty($validated['email'])) {
            $userData['email'] = $validated['email'];
        }
        if (!empty($validated['phone'])) {
            $userData['phone'] = $validated['phone'];
        }

        // Lưu ảnh đại diện nếu có
        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            $userData['profile_photo_path'] = $request->file('profile_photo')->storePublicly(
                'profile-photos',
                ['disk' => 'public']
            );
        }

        // Tạo user mới
        $user = User::create($userData);

        // Gán vai trò guest cho user
        $user->roles()->attach($role->id);

        return redirect()->route('admin.users.customers.index')
            ->with('success', 'guest đã được tạo thành công!');
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $guestRole = Role::where('name', 'guest')->firstOrFail();
        return view('admin.users.customers.edit', compact('user', 'guestRole'));
    }

    public function update(Request $request, $id)
    {
        $user = User::with('roles')->findOrFail($id);

        // Xác thực dữ liệu đầu vào
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required_without:phone|nullable|email|max:255|unique:users,email,' . $user->id,
            'phone' => [
                'required_without:email',
                'nullable',
                'string',
                'max:20',
                'unique:users,phone,' . $user->id,
                'regex:/^[0-9]+$/'
            ],
            'identity_code' => 'required|string|max:50|unique:users,identity_code,' . $user->id,
            'address' => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'name.string' => 'Họ và tên phải là chuỗi ký tự hợp lệ.',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'email.required_without' => 'Vui lòng cung cấp ít nhất email hoặc số điện thoại.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.max' => 'Địa chỉ email không được vượt quá 255 ký tự.',
            'email.unique' => 'Địa chỉ email này đã được sử dụng bởi người dùng khác.',
            'phone.required_without' => 'Vui lòng cung cấp ít nhất email hoặc số điện thoại.',
            'phone.string' => 'Số điện thoại phải là chuỗi ký tự hợp lệ.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'phone.unique' => 'Số điện thoại này đã được sử dụng bởi người dùng khác.',
            'phone.regex' => 'Số điện thoại chỉ được chứa các ký tự số.',
            'identity_code.required' => 'Vui lòng nhập mã định danh.',
            'identity_code.string' => 'Mã định danh phải là chuỗi ký tự hợp lệ.',
            'identity_code.max' => 'Mã định danh không được vượt quá 50 ký tự.',
            'identity_code.unique' => 'Mã định danh này đã được sử dụng bởi người dùng khác.',
            'address.max' => 'Địa chỉ không được vượt quá 500 ký tự.',
            'profile_photo.image' => 'Ảnh đại diện phải là file ảnh hợp lệ.',
            'profile_photo.mimes' => 'Ảnh đại diện chỉ hỗ trợ định dạng JPEG, PNG, JPG hoặc GIF.',
            'profile_photo.max' => 'Dung lượng ảnh đại diện không được vượt quá 2MB.',
        ]);

        // Chuẩn bị dữ liệu để cập nhật
        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'identity_code' => $validated['identity_code'],
            'address' => $validated['address'] ?? null,
        ];

        // Cập nhật mật khẩu nếu được cung cấp
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        // Xử lý ảnh đại diện nếu có
        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            $userData['profile_photo_path'] = $request->file('profile_photo')->storePublicly(
                'profile-photos',
                ['disk' => 'public']
            );
        }

        // Cập nhật user
        $user->update($userData);

        // Giữ vai trò guest
        $role = Role::where('name', 'guest')->firstOrFail();
        $user->roles()->sync([$role->id]);

        return redirect()->route('admin.users.customers.show', $user->id)
            ->with('success', 'guest đã được cập nhật thành công!');
    }


    public function changePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        // if (!$user->hasRole('guest')) {
        //     abort(403, 'Không thể đổi mật khẩu cho người không phải guest');
        // }

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
            abort(403, 'Không thể đặt lại mật khẩu cho người không phải guest');
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

        return redirect()->back()->with('success', 'Mật khẩu đã được đặt lại và gửi về email của guest.');
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Chỉ cho phép xoá user có role = guest
        if (!$user->hasRole('guest')) {
            abort(403, 'Không thể xóa người không phải guest');
        }

        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.customers.index')
                ->with('error', 'Bạn không thể xóa tài khoản của chính mình!');
        }

        try {
            if ($user->profile_photo_path) {
                $user->deleteProfilePhoto();
            }
            $user->delete();

            return redirect()->route('admin.users.customers.index')
                ->with('success', 'Khách hàng đã được xóa thành công!');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '23000') {
                return redirect()->route('admin.users.customers.index')
                    ->with('error', 'Không thể xoá khách hàng này vì vẫn còn dữ liệu liên quan. Vui lòng xoá hoặc cập nhật các dữ liệu liên quan trước.');
            }
            throw $e;
        }
    }
}

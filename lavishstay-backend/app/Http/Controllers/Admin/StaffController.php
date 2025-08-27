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
use Illuminate\Database\QueryException;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'guest'); // Loại bỏ người dùng có vai trò "guest"
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

        // Lấy danh sách người dùng với phân trang (10 người/trang) và kèm thông tin vai trò
        $users = $query->with('roles')->paginate(10)->appends($request->query());

        $roles = Role::where('name', '!=', 'guest')
            ->whereHas('users') // Chỉ lấy vai trò có người dùng liên kết
            ->get();

        return view('admin.users.staffs.index', compact('users', 'roles'));
    }


    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        // dd($user);
        return view('admin.users.staffs.show', compact('user'));
    }

    /**
     * Hiển thị form tạo nhân viên
     */
    public function create()
    {
        // Lấy tất cả vai trò trừ vai trò "guest"
        $staffRoles = Role::where('name', '!=', 'guest')->get();
        return view('admin.users.staffs.create', compact('staffRoles'));
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
            'email' => 'nullable|string|email|max:255|unique:users,email|required_without:phone',
            'phone' => 'nullable|string|max:20|unique:users,phone|required_without:email',
            'identity_code' => 'required|string|max:50|unique:users,identity_code',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/'
            ],
            'address' => 'nullable|string|max:500',
            'role_id' => 'required|exists:roles,id',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'name.string' => 'Họ và tên phải là chuỗi ký tự hợp lệ.',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.max' => 'Địa chỉ email không được vượt quá 255 ký tự.',
            'email.unique' => 'Địa chỉ email này đã được sử dụng bởi người dùng khác.',
            'email.required_without' => 'Vui lòng cung cấp ít nhất email hoặc số điện thoại.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'phone.unique' => 'Số điện thoại này đã được sử dụng bởi người dùng khác.',
            'phone.required_without' => 'Vui lòng cung cấp ít nhất email hoặc số điện thoại.',
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
            'role_id.required' => 'Vui lòng chọn vai trò cho nhân viên.',
            'role_id.exists' => 'Vai trò được chọn không tồn tại trong hệ thống.',
            'profile_photo.image' => 'Ảnh đại diện phải là file ảnh hợp lệ.',
            'profile_photo.mimes' => 'Ảnh đại diện chỉ hỗ trợ định dạng JPEG, PNG, JPG hoặc GIF.',
            'profile_photo.max' => 'Dung lượng ảnh đại diện không được vượt quá 2MB.',
        ]);


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

        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            $userData['profile_photo_path'] = $request->file('profile_photo')->storePublicly(
                'profile-photos',
                ['disk' => 'public']
            );
        }

        // Tạo user staff
        $user = User::create($userData);

        // Gán vai trò cho nhân viên
        $user->roles()->attach($validated['role_id']);

        return redirect()->route('admin.users.staffs.index')->with('success', 'Thông tin nhân viên đã được tạo thành công!');
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $staffRoles = Role::where('name', '!=', 'guest')->get();
        return view('admin.users.staffs.edit', compact('user', 'staffRoles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::with('roles')->findOrFail($id);

        // Validate dữ liệu
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id . '|required_without:phone',
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id . '|required_without:email',
            'identity_code' => 'required|string|max:50|unique:users,identity_code,' . $user->id,
            'address' => 'nullable|string|max:500',
            'role_id' => 'required|exists:roles,id',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'name.string' => 'Họ và tên phải là chuỗi ký tự hợp lệ.',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.max' => 'Địa chỉ email không được vượt quá 255 ký tự.',
            'email.unique' => 'Địa chỉ email này đã được sử dụng bởi người dùng khác.',
            'email.required_without' => 'Vui lòng cung cấp ít nhất email hoặc số điện thoại.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'phone.unique' => 'Số điện thoại này đã được sử dụng bởi người dùng khác.',
            'phone.required_without' => 'Vui lòng cung cấp ít nhất email hoặc số điện thoại.',
            'identity_code.required' => 'Vui lòng nhập mã định danh.',
            'identity_code.string' => 'Mã định danh phải là chuỗi ký tự hợp lệ.',
            'identity_code.max' => 'Mã định danh không được vượt quá 50 ký tự.',
            'identity_code.unique' => 'Mã định danh này đã được sử dụng bởi người dùng khác.',
            'address.max' => 'Địa chỉ không được vượt quá 500 ký tự.',
            'role_id.required' => 'Vui lòng chọn vai trò cho nhân viên.',
            'role_id.exists' => 'Vai trò được chọn không tồn tại trong hệ thống.',
            'profile_photo.image' => 'Ảnh đại diện phải là file ảnh hợp lệ.',
            'profile_photo.mimes' => 'Ảnh đại diện chỉ hỗ trợ định dạng JPEG, PNG, JPG hoặc GIF.',
            'profile_photo.max' => 'Dung lượng ảnh đại diện không được vượt quá 2MB.',
        ]);

        // Kiểm tra vai trò: không cho phép gán vai trò 'guest'
        $role = Role::findOrFail($validated['role_id']);

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
        } else {
            unset($validated['profile_photo']);
        }

        // Cập nhật thông tin người dùng
        $user->update($validated);

        // Gán lại vai trò
        $user->roles()->sync([$role->id]);

        return redirect()->route('admin.users.staffs.show', $user->id)
            ->with('success', 'Thông tin nhân viên đã được cập nhật thành công!');
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
        // Tìm user theo ID, nếu không có thì báo lỗi 404
        $user = User::findOrFail($id);

        // Chặn trường hợp admin tự xoá chính mình
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.staffs.index')
                ->with('error', 'Bạn không thể xóa tài khoản của chính mình!');
        }

        try {
            // Xóa ảnh đại diện nếu có (theo Jetstream)
            $user->deleteProfilePhoto();

            // Tiến hành xoá user trong DB
            $user->delete();

            // Nếu xoá thành công thì trả về thông báo
            return redirect()->route('admin.users.staffs.index')
                ->with('success', 'Người dùng đã được xóa thành công!');
        } catch (QueryException $e) {
            if ($e->getCode() == '23000') {
                return redirect()->route('admin.users.staffs.index')
                    ->with(
                        'error',
                        'Không thể xoá tài khoản này vì vẫn còn dữ liệu liên quan. ' .
                            'Vui lòng xoá hoặc cập nhật dữ liệu liên quan trước.'
                    );
            }

            throw $e;
        }
    }
}
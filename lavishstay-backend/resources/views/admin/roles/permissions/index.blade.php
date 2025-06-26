<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page Header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">
                    Phân quyền: <span class="text-violet-600 dark:text-violet-400">{{ $role->name }}</span>
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Quản lý quyền truy cập cho vai trò này
                </p>
            </div>
            <div class="flex items-center space-x-3 mb-4">
                <a href="{{ route('admin.roles.index') }}">
                    <button
                        class="btn cursor-pointer bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white px-3 py-2 rounded transition">
                        <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                            <path
                                d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                        </svg>
                        <span class="max-xs:sr-only">Quay lại</span>
                    </button>
                </a>
            </div>
        </div>

        <!-- Notifications -->
        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Two-Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Danh sách quyền</h2>
                <form method="POST" action="{{ route('admin.roles.permissions.update', $role->id) }}"
                    x-data="permissionTree({{ Js::from($rolePermissionIds) }})"
                    @submit="submitForm($event)">
                    @csrf
                    <div class="max-h-[600px] overflow-y-auto pr-4">
                        <ul class="space-y-3">
                            @foreach ($permissions->where('parent_id', null) as $perm)
                                @include('admin.roles._permission_tree_alpine', [
                                    'permission' => $perm,
                                    'permissions' => $permissions,
                                ])
                            @endforeach
                        </ul>
                    </div>
                    <div class="mt-6">
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 dark:hover:bg-blue-500 font-semibold transition-colors duration-150">
                            Cập nhật quyền
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Column -->
          <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Hướng dẫn phân quyền</h2>
    <div class="space-y-4 text-gray-600 dark:text-gray-300 text-sm">
        
        <!-- 1. Giới thiệu -->
        <div>
            <h3 class="font-medium text-gray-800 dark:text-gray-100 mb-2">Tổng quan</h3>
            <p>
                Danh sách quyền được trình bày theo dạng **cây phân cấp**, gồm các **quyền cha** (nhóm chính) và **quyền con** (chức năng cụ thể). 
                Bạn có thể phân quyền cho vai trò <span class="font-semibold text-violet-600 dark:text-violet-400">{{ $role->name }}</span> bằng cách chọn các ô kiểm tương ứng.
            </p>
        </div>

        <!-- 2. Hướng dẫn sử dụng -->
        <div>
            <h3 class="font-medium text-gray-800 dark:text-gray-100 mb-2">Cách thao tác</h3>
            <ul class="list-disc pl-5 space-y-1">
                <li>Nhấn vào nút <strong>+</strong> để mở rộng danh sách quyền con, hoặc <strong>−</strong> để thu gọn.</li>
                <li>Nhấp vào ô <strong>checkbox</strong> để bật hoặc tắt quyền tương ứng.</li>
                <li>Chọn một quyền **cha** sẽ tự động chọn tất cả quyền **con** bên dưới.</li>
                <li>Bỏ chọn một quyền **con** sẽ làm quyền **cha** trở thành trạng thái chọn một phần.</li>
            </ul>
        </div>

        <!-- 3. Màu sắc -->
        <div>
            <h3 class="font-medium text-gray-800 dark:text-gray-100 mb-2">Ý nghĩa màu sắc</h3>
            <ul class="list-disc pl-5 space-y-1">
                <li><span class="text-blue-600 dark:text-blue-400 font-semibold">Xanh đậm</span>: Quyền cha và toàn bộ quyền con đều được chọn.</li>
                <li><span class="text-blue-400 dark:text-blue-300 font-semibold">Xanh nhạt</span>: Quyền cha chỉ được chọn một phần (một số quyền con).</li>
                <li><span class="text-gray-800 dark:text-gray-200">Xám</span>: Quyền chưa được chọn.</li>
            </ul>
        </div>

        <!-- 4. Ghi chú -->
        <div>
            <h3 class="font-medium text-gray-800 dark:text-gray-100 mb-2">Lưu ý</h3>
            <ul class="list-disc pl-5 space-y-1">
                <li>Danh sách quyền có thể cuộn dọc nếu quá dài.</li>
                <li>Sau khi chọn xong, nhấn nút <strong>"Cập nhật quyền"</strong> để lưu lại thay đổi.</li>
                <li>Nếu không có thay đổi nào, hệ thống sẽ thông báo và không gửi dữ liệu lên.</li>
                <li>Hãy kiểm tra kỹ các quyền được chọn để tránh cấp quyền sai.</li>
            </ul>
        </div>
    </div>
</div>

        </div>
    </div>

    <!-- Script -->
    <script>
        function permissionTree(initialPermissions) {
            return {
                checkedPermissions: [...initialPermissions],
                original: [...initialPermissions],
                tempPermissions: [...initialPermissions],
                init() {
                    this.tempPermissions.forEach(id => {
                        const checkbox = document.querySelector(`input[value='${id}']`);
                        if (checkbox) {
                            checkbox.checked = true;
                            this.updateParent(id);
                        }
                    });
                },
                updateChildren(parentId, isChecked) {
                    const descendants = document.querySelectorAll(`input[data-parent='${parentId}']`);
                    descendants.forEach(child => {
                        child.checked = isChecked;
                        const value = parseInt(child.value);
                        if (isChecked) {
                            if (!this.tempPermissions.includes(value)) {
                                this.tempPermissions.push(value);
                            }
                        } else {
                            this.tempPermissions = this.tempPermissions.filter(id => id !== value);
                        }
                        this.updateChildren(child.value, isChecked);
                    });

                    const parentValue = parseInt(parentId);
                    if (isChecked && !this.tempPermissions.includes(parentValue)) {
                        this.tempPermissions.push(parentValue);
                    } else if (!isChecked) {
                        this.tempPermissions = this.tempPermissions.filter(id => id !== parentValue);
                    }
                },
                updateParent(childId) {
                    const child = document.querySelector(`input[value='${childId}']`);
                    if (!child) return;
                    const parentId = child.dataset.parent;
                    if (!parentId) return;

                    const siblings = document.querySelectorAll(`input[data-parent='${parentId}']`);
                    const parent = document.querySelector(`input[value='${parentId}']`);
                    if (!parent) return;

                    const allChecked = [...siblings].every(cb => cb.checked);
                    const someChecked = [...siblings].some(cb => cb.checked);

                    parent.checked = someChecked;
                    parent.indeterminate = someChecked && !allChecked;

                    const parentValue = parseInt(parentId);
                    if (someChecked && !this.tempPermissions.includes(parentValue)) {
                        this.tempPermissions.push(parentValue);
                    } else if (!someChecked) {
                        this.tempPermissions = this.tempPermissions.filter(id => id !== parentValue);
                    }

                    this.updateParent(parentId);
                },
                handleCheckboxChange(id, isChecked) {
                    if (isChecked) {
                        this.tempPermissions = [...new Set([...this.tempPermissions, parseInt(id)])];
                    } else {
                        this.tempPermissions = this.tempPermissions.filter(p => p !== parseInt(id));
                    }
                    this.updateChildren(id, isChecked);
                    this.updateParent(id);
                },
                submitForm(event) {
                    const current = [...this.tempPermissions].sort();
                    const original = [...this.original].sort();
                    if (JSON.stringify(current) === JSON.stringify(original)) {
                        event.preventDefault();
                        alert('Không có thay đổi nào để cập nhật!');
                    } else {
                        this.checkedPermissions = [...this.tempPermissions];
                        this.checkedPermissions.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'permissions[]';
                            input.value = id;
                            event.target.appendChild(input);
                        });
                    }
                }
            }
        }
    </script>
</x-app-layout>

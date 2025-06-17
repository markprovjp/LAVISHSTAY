<x-app-layout>
    <div class="max-w-5xl mx-auto p-6" x-data="permissionManager()" x-init="selectedRoleId = '{{ request('role_id') ?? old('selectedRoleId') }}'">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Quản lý phân quyền vai trò</h1>

        <!-- Select Role -->
        <div class="mb-6">
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Chọn vai trò:</label>
            <select x-model="selectedRoleId"
                class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option value="">-- Chọn vai trò --</option>
                @foreach ($roles as $role)
                {{-- @if($role->name !== 'admin') --}}
                    <option value="{{ $role->id }}">{{ \App\Models\Role::getRoleLabel($role->name) }}</option>
                {{-- @endif --}}
                @endforeach
            </select>
        </div>

        <!-- Assigned Permissions -->
        <template x-if="selectedRoleId">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-indigo-600">
                        Quyền của vai trò: <span x-text="getRoleName(selectedRoleId)"></span>
                    </h2>
                    <button @click="openModal()"
                        class="btn cursor-pointer bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                        ➕ Thêm quyền
                    </button>
                </div>

                <form :action="removeUrl()" method="POST" @submit.prevent="submitRemove">
                    @csrf
                    <input type="hidden" name="selectedRoleId" :value="selectedRoleId">

                    <!-- Chọn tất cả -->
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" @change="toggleAllPermissions($event)"
                                class="text-indigo-600 rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Chọn tất cả</span>
                        </label>
                    </div>

                    <!-- Danh sách quyền -->
                    <div class="grid w-full grid-cols-6 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        <template x-for="perm in getPermissionsOfRole()" :key="perm.id">
                            <label
                                class="flex flex-col justify-between border border-gray-300 dark:border-gray-600 p-4 rounded-lg bg-white dark:bg-gray-800 shadow-sm hover:shadow-md hover:border-violet-500 dark:hover:border-violet-400 transition duration-200 ease-in-out">
                                <div class="flex items-start space-x-3">
                                    <input type="checkbox" name="permissions[]" :value="perm.id"
                                        class="perm-checkbox mt-1 text-violet-600 focus:ring-violet-500 border-gray-300 rounded-sm">
                                    <div>
                                        <div class="font-semibold text-gray-800 dark:text-white text-sm"
                                            x-text="perm.name"></div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-snug"
                                            x-text="perm.description"></div>
                                    </div>
                                </div>
                            </label>
                        </template>
                    </div>

                    <!-- Nút xóa -->
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="btn bg-red-600 text-white hover:bg-red-700">
                            🗑 Xoá quyền đã chọn
                        </button>
                    </div>
                </form>
            </div>
        </template>


        <!-- Modal Thêm quyền -->

        <div x-show="modalOpen" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
            x-cloak>
            <div class="bg-white dark:bg-gray-800 w-full max-w-2xl p-6 rounded-xl shadow-lg relative">
                <h3 class="text-xl font-bold mb-4 text-indigo-700 dark:text-indigo-400 border-b pb-2">
                    ➕ Thêm quyền cho vai trò
                </h3>

                <form :action="addUrl()" method="POST" @submit.prevent="submitAdd">
                    @csrf

                    <!-- Chọn tất cả -->
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" @change="toggleAllAddPermissions($event)"
                                class="text-indigo-600 focus:ring-indigo-500 rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Chọn tất cả quyền</span>
                        </label>
                    </div>

                    <!-- Danh sách quyền -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 max-h-80 overflow-y-auto pr-2">
                        <template x-for="perm in getAvailablePermissions()" :key="perm.id">
                            <label
                                class="flex flex-col justify-between border border-gray-300 dark:border-gray-600 p-3 rounded-lg bg-gray-50 dark:bg-gray-700 shadow-sm hover:shadow-md hover:border-indigo-500 dark:hover:border-indigo-400 transition duration-200 ease-in-out cursor-pointer">
                                <div class="flex items-start space-x-3">
                                    <input type="checkbox" name="permissions[]" :value="perm.id"
                                        class="perm-add-checkbox mt-1 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded-sm">
                                    <div>
                                        <div class="font-medium text-gray-800 dark:text-white text-sm"
                                            x-text="perm.name"></div>
                                        <div class="text-xs text-gray-500 dark:text-gray-300 mt-1 leading-snug"
                                            x-text="perm.description"></div>
                                    </div>
                                </div>
                            </label>
                        </template>
                    </div>

                    <!-- Nút hành động -->
                    <div class="mt-6 flex justify-end space-x-2">
                        <button type="button" @click="modalOpen = false"
                            class="px-4 py-2 rounded-md border font-medium transition duration-150
           bg-white text-gray-700 border-gray-300 hover:bg-gray-100
           dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-700">
                            Đóng
                        </button>

                        <button type="submit"
                            class="btn cursor-pointer bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white ml-2">
                            Thêm quyền
                        </button>
                    </div>
                </form>
            </div>
        </div>


    </div>

    <script>
        function permissionManager() {
            return {
                selectedRoleId: '{{ request('role_id') ?? old('selectedRoleId') }}',
                modalOpen: false,
                roles: @json($roles),
                permissions: @json($permissions),
                rolePermissionsMap: @json($rolePermissionsMap),

                getRoleName(id) {
                    const r = this.roles.find(role => role.id == id);
                    return r ? r.name : '';
                },

                getPermissionsOfRole() {
                    const ids = this.rolePermissionsMap[this.selectedRoleId] || [];
                    return this.permissions.filter(p => ids.includes(p.id));
                },

                getAvailablePermissions() {
                    const current = this.rolePermissionsMap[this.selectedRoleId] || [];
                    return this.permissions.filter(p => !current.includes(p.id));
                },

                removeUrl() {
                    return `/admin/roles/permissions/remove/${this.selectedRoleId}`;
                },

                addUrl() {
                    return `/admin/roles/permissions/add/${this.selectedRoleId}`;
                },

                openModal() {
                    this.modalOpen = true;
                },

                toggleAllPermissions(e) {
                    document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = e.target.checked);
                },

                toggleAllAddPermissions(e) {
                    document.querySelectorAll('.perm-add-checkbox').forEach(cb => cb.checked = e.target.checked);
                },

                submitRemove(e) {
                    const checked = document.querySelectorAll('.perm-checkbox:checked');
                    if (!checked.length) {
                        alert('Vui lòng chọn ít nhất một quyền để xóa.');
                        return;
                    }
                    e.target.closest('form').submit();
                },

                submitAdd(e) {
                    const checked = document.querySelectorAll('.perm-add-checkbox:checked');
                    if (!checked.length) {
                        alert('Vui lòng chọn ít nhất một quyền để thêm.');
                        return;
                    }
                    e.target.closest('form').submit();
                },
            };
        }
    </script>
</x-app-layout>

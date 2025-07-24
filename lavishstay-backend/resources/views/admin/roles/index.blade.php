<style>
    .button-action {
        position: relative;
    }

    .menu-button-action {
        position: absolute;
        top: 0%;
        right: 130%;
        z-index: 50;
        width: 200px;
    }
</style>

<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto"
         x-data="{ showAddModal: false, showEditModal: false, editRole: {}, editId: null }">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-white font-bold">Quản lý vai trò</h1>
                <p class="text-sm text-gray-600 dark:text-gray-300">Quản lý tất cả vai trò trong hệ thống</p>
            </div>

            <!-- Actions -->
            <div class="flex gap-2">
                <button @click="showAddModal = true" class="btn bg-violet-900 text-white hover:bg-violet-700">
                    <i class="fa fa-plus mr-2"></i> Thêm vai trò
                </button>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 dark:bg-green-200 dark:text-green-800 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 dark:bg-red-200 dark:text-red-800 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Main Content -->
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-white">
                @if ($roles->count() > 0)
                    <div>
                        <table class="w-full table-auto">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-700">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tên vai trò</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Mô tả</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Chức năng</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($roles as $role)
                                    <tr>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $role->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                            {{ $role->description ?? 'Không có mô tả' }}</td>
                                        <td class="px-2 py-3 text-center">
                                            <div x-data="{ openAction: false, dropUp: false }" class="relative inline-block text-left">
                                                <button
                                                    @click="
                                                        const rect = $el.getBoundingClientRect();
                                                        dropUp = (window.innerHeight - rect.bottom) < 160;
                                                        openAction = !openAction;
                                                    "
                                                    class="button-action inline-flex items-center justify-center w-8 h-8 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-500 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-violet-500 transition-colors duration-200"
                                                    :class="{ 'ring-2 ring-violet-400': openAction }"
                                                    id="dropdown-button-{{ $role->id }}">
                                                    <i class="fa fa-ellipsis-v text-gray-700 dark:text-gray-200"></i>
                                                </button>
                                                <!-- Dropdown Menu -->
                                                <div x-show="openAction" @click.away="openAction = false" x-transition
                                                    :class="dropUp
                                                        ? 'absolute right-0 bottom-full mb-2 w-48 rounded-xl shadow-lg bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 ring-1 ring-black ring-opacity-5 z-50'
                                                        : 'absolute right-0 mt-2 w-48 rounded-xl shadow-lg bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 ring-1 ring-black ring-opacity-5 z-50'">
                                                    @php
                                                        $isSpecialRole = in_array(strtolower($role->name), ['admin', 'guest']);
                                                    @endphp

                                                    <div class="py-1" role="menu">
                                                        <a href="{{ route('admin.roles.permissions.index', $role->id) }}"
                                                            class="flex items-center w-full px-4 py-2 text-sm text-blue-700 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-800 hover:text-blue-900 dark:hover:text-white transition-colors duration-150">
                                                            <i class="fa fa-key mr-2"></i> Xem quyền
                                                        </a>

                                                        @if (!$isSpecialRole)
                                                            <button
                                                                class="flex items-center w-full px-4 py-2 text-sm text-yellow-700 dark:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-700 hover:text-yellow-900 dark:hover:text-white transition-colors duration-150"
                                                                @click="
                                                                    openAction = false;
                                                                    showEditModal = true;
                                                                    editRole = {{ json_encode(['name' => $role->name, 'description' => $role->description]) }};
                                                                    editId = {{ $role->id }};
                                                                ">
                                                                <i class="fa fa-edit mr-2"></i> Sửa vai trò
                                                            </button>
                                                        @else
                                                            <button disabled style="opacity:0.5; pointer-events:none;"
                                                                class="flex items-center w-full px-4 py-2 text-sm text-yellow-400">
                                                                <i class="fa fa-edit mr-2"></i> Sửa vai trò
                                                            </button>
                                                        @endif

                                                        @if (!$isSpecialRole)
                                                            <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST"
                                                                onsubmit="return confirm('Bạn chắc chắn muốn xoá vai trò này?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="flex items-center w-full px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-700 hover:text-red-900 dark:hover:text-white transition-colors duration-150">
                                                                    <i class="fa fa-trash mr-2"></i> Xoá vai trò
                                                                </button>
                                                            </form>
                                                        @else
                                                            <button disabled style="opacity:0.5; pointer-events:none;"
                                                                class="flex items-center w-full px-4 py-2 text-sm text-red-400">
                                                                <i class="fa fa-trash mr-2"></i> Xoá vai trò
                                                            </button>
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($roles->hasPages())
                        <div class="mt-4">{{ $roles->links() }}</div>
                    @endif
                @else
                    <div class="text-center py-16">
                        <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6">
                            <span class="text-4xl text-gray-400">👥</span>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Chưa có vai trò nào</h3>
                        <p class="text-gray-500 dark:text-gray-300 mb-6">Hãy thêm vai trò mới cho hệ thống.</p>
                        <button @click="showAddModal = true"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-violet-600 hover:bg-violet-700 transition-colors duration-200">
                            Thêm vai trò mới
                        </button>
                    </div>
                @endif
            </div>
        </div>
        <!-- Modal Thêm vai trò -->
        <div x-show="showAddModal" x-transition
            class="fixed inset-0 z-50 flex items-center justify-center bg-opacity-20 backdrop-blur-sm"
            style="display: none;">
            <div @click.away="showAddModal = false" class="bg-white rounded-xl w-full max-w-md p-6 shadow-lg relative">
                <h3 class="text-lg font-bold mb-4">Thêm vai trò mới</h3>
                <form method="POST" action="{{ route('admin.roles.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="block font-semibold mb-1">Tên vai trò</label>
                        <input type="text" name="name" required
                            class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-400" />
                    </div>
                    <div class="mb-3">
                        <label class="block font-semibold mb-1">Mô tả</label>
                        <textarea name="description"
                            class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-400"></textarea>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="showAddModal = false"
                            class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800">
                            Đóng
                        </button>
                        <button type="submit"
                            class="px-4 py-2 rounded bg-blue-600 text-white font-semibold hover:bg-blue-700">
                            Lưu
                        </button>
                    </div>
                </form>
                <button @click="showAddModal = false"
                    class="absolute top-2 right-2 text-xl text-gray-400 hover:text-gray-700">&times;</button>
            </div>
        </div>

        <!-- Modal Sửa vai trò -->
        <div x-show="showEditModal" x-transition
            class="fixed inset-0 z-50 flex items-center justify-center bg-opacity-20 backdrop-blur-sm"
            style="display: none;">
            <div @click.away="showEditModal = false" class="bg-white rounded-xl w-full max-w-md p-6 shadow-lg relative">
                <h3 class="text-lg font-bold mb-4">Sửa vai trò</h3>
                <form method="POST" :action="'/admin/roles/update/'+ editId">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="block font-semibold mb-1">Tên vai trò</label>
                        <input type="text" name="name" required
                            class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-400"
                            x-model="editRole.name" />
                    </div>
                    <div class="mb-3">
                        <label class="block font-semibold mb-1">Mô tả</label>
                        <textarea name="description"
                            class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-400"
                            x-model="editRole.description"></textarea>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="showEditModal = false"
                            class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800">
                            Đóng
                        </button>
                        <button type="submit"
                            class="px-4 py-2 rounded bg-yellow-500 text-white font-semibold hover:bg-yellow-600">
                            Cập nhật
                        </button>
                    </div>
                </form>
                <button @click="showEditModal = false"
                    class="absolute top-2 right-2 text-xl text-gray-400 hover:text-gray-700">&times;</button>
            </div>
        </div>

    </div>
    
</x-app-layout>
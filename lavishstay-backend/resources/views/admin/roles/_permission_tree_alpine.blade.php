<li x-data="{ expanded: false }" x-init="expanded = false" class="relative group">
    <div class="flex items-start py-2 px-3 rounded-lg group-hover:bg-purple-50 dark:group-hover:bg-purple-900 transition">
        {{-- Nút mở rộng/thu gọn nếu có quyền con --}}
        @if ($permissions->where('parent_id', $permission->id)->count())
            <button type="button" @click="expanded = !expanded"
                class="mr-2 mt-1 w-6 h-6 flex items-center justify-center rounded-full border border-purple-200 bg-white dark:bg-gray-800 text-purple-600 dark:text-purple-300 hover:bg-purple-100 dark:hover:bg-purple-700 transition focus:outline-none focus:ring-2 focus:ring-purple-400"
                :aria-label="expanded ? 'Thu gọn' : 'Mở rộng'">
                <svg x-show="!expanded" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <svg x-show="expanded" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
            </button>
        @else
            <span class="mr-2 w-6"></span>
        @endif

        {{-- Checkbox và nội dung --}}
        <label class="flex items-center w-full space-x-3 cursor-pointer select-none">
            <input type="checkbox" value="{{ $permission->id }}" data-parent="{{ $permission->parent_id }}"
                :checked="checkedPermissions.includes({{ $permission->id }})"
                @change="handleCheckboxChange({{ $permission->id }}, $event.target.checked)"
                class="h-5 w-5 text-purple-600 border-purple-300 rounded focus:ring-purple-500 transition checked:bg-purple-600 checked:border-purple-600 hover:border-purple-500" />

            <div class="flex w-full gap-4 items-center">
                {{-- Mô tả nằm trái, chữ đứng, to --}}
                @if ($permission->description)
                    <span class="w-1/3 text-[15px] font-semibold leading-snug"
                        :class="{
                            'text-blue-700 dark:text-blue-400': checkedPermissions.includes({{ $permission->id }}) && areAllChildrenChecked({{ $permission->id }}),
                            'text-blue-400 dark:text-blue-300': checkedPermissions.includes({{ $permission->id }}) && !areAllChildrenChecked({{ $permission->id }}),
                            'text-gray-500 dark:text-gray-400': !checkedPermissions.includes({{ $permission->id }})
                        }">
                        {{ $permission->description }}
                    </span>
                @endif

                {{-- Tên quyền bên phải, chữ nghiêng --}}
                <span class="flex-1 text-base italic"
                    :class="{
                        'text-blue-700 dark:text-blue-400 font-semibold': checkedPermissions.includes({{ $permission->id }}) && areAllChildrenChecked({{ $permission->id }}),
                        'text-blue-400 dark:text-blue-300 font-semibold': checkedPermissions.includes({{ $permission->id }}) && !areAllChildrenChecked({{ $permission->id }}),
                        'text-gray-800 dark:text-gray-300': !checkedPermissions.includes({{ $permission->id }})
                    }">
                    {{ $permission->name }}
                </span>
            </div>
        </label>
    </div>

    {{-- Danh sách quyền con --}}
    @if ($permissions->where('parent_id', $permission->id)->count())
        <ul x-show="expanded"
            class="ml-6 mt-1 border-l-2 border-purple-100 dark:border-purple-600 pl-4 space-y-1 transition-all duration-200 ease-in-out">
            @foreach ($permissions->where('parent_id', $permission->id) as $child)
                @include('admin.roles._permission_tree_alpine', [
                    'permission' => $child,
                    'permissions' => $permissions,
                ])
            @endforeach
        </ul>
    @endif
</li>
<li>
    <div class="flex items-center space-x-2 py-1">
        <input type="checkbox"
            name="permissions[]"
            value="{{ $permission->id }}"
            {{ in_array($permission->id, $rolePermissionIds) ? 'checked' : '' }}
            class="form-checkbox h-4 w-4 text-blue-600 rounded" />
        <span>{{ $permission->name }}</span>
    </div>

    @php
        $children = $permissions->where('parent_id', $permission->id);
    @endphp

    @if($children->count())
        <ul class="ml-6 border-l border-gray-200 pl-4">
            @foreach($children as $child)
                @include('admin.roles._permission_tree_edit', [
                    'permission' => $child,
                    'permissions' => $permissions,
                    'rolePermissionIds' => $rolePermissionIds
                ])
            @endforeach
        </ul>
    @endif
</li>
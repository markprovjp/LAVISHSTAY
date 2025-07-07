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
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Customer Management</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Manage all customers in the system</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a href="{{ route('admin.customers.create') }}">
                    <button
                        class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                        <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                            <path
                                d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                        </svg>
                        <span class="max-xs:sr-only">Add Customer</span>
                    </button>
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Main Content -->
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                @if ($users->count() > 0)
                    <div class="">
                        <table class="w-full table-auto">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-700">
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Avatar</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Name</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Email</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Phone</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Số CCCD / Hộ chiếu</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Role</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Join Date</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($users as $user)
                                    <tr>
                                        <!-- Avatar -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($user->profile_photo_url)
                                                <img class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600"
                                                    src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                            @else
                                                <div
                                                    class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center border-2 border-gray-200 dark:border-gray-600">
                                                    <span
                                                        class="text-white font-semibold text-sm">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Name -->
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            <div class="font-medium">{{ $user->name }}</div>
                                            @if ($user->address)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ Str::limit($user->address, 30) }}</div>
                                            @endif
                                        </td>

                                        <!-- Email -->
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            <div>{{ $user->email }}</div>
                                            <div class="text-xs mt-1">
                                                @if ($user->email_verified_at)
                                                    <span class="text-green-600 dark:text-green-400">Verified</span>
                                                @else
                                                    <span class="text-amber-600 dark:text-amber-400">Not verified</span>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Phone -->
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->phone ?: 'Not provided' }}
                                        </td>
                                        <!-- Identity Code -->
                                         <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->identity_code ?: 'Not provided' }}
                                        </td>

                                        <!-- Role -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @foreach ($user->roles as $role)
                                                @php
                                                    $roleColors = [
                                                        'admin' => 'bg-red-100 text-red-800',
                                                        'manager' => 'bg-blue-100 text-blue-800',
                                                        'receptionist' => 'bg-yellow-100 text-yellow-800',
                                                        'guest' => 'bg-purple-100 text-purple-800',
                                                    ];
                                                    $color = $roleColors[$role->name] ?? 'bg-gray-100 text-gray-800';
                                                @endphp

                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }} mr-1">
                                                    {{ \App\Models\Role::getRoleLabel($role->name) }}
                                                </span>
                                            @endforeach
                                        </td>


                                        <!-- Join Date -->
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ optional($user->created_at)->format('d/m/Y H:i') ?? 'Unknown' }}
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap text-center">
                                            <div class="relative inline-block text-left">
                                                <button type="button"
                                                    class="button-action inline-flex items-center justify-center w-8 h-8 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-colors duration-200"
                                                    onclick="toggleDropdown({{ $user->id }})"
                                                    id="dropdown-button-{{ $user->id }}">
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                                        </path>
                                                    </svg>
                                                </button>

                                                <!-- Dropdown Menu -->
                                                <div id="dropdown-menu-{{ $user->id }}"
                                                    class="hidden menu-button-action absolute right-0 z-50 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                                    <div class="py-1 z-500" role="menu">
                                                        <!-- View Details -->
                                                        {{-- <button
                                                            onclick="toggleDetails({{ $user->id }}); closeDropdown({{ $user->id }})"
                                                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                            role="menuitem">
                                                            View Details
                                                        </button> --}}

                                                        <!-- Edit -->
                                                        <a href="{{ route('admin.customers.show', $user->id) }}"
                                                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                            role="menuitem">
                                                            View Customer
                                                        </a>
                                                        <!-- Edit -->
                                                        {{-- <a href="{{ route('admin.users.edit', $user->id) }}"
                                                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                            role="menuitem">
                                                            Edit User
                                                        </a> --}}

                                                        <!-- Divider -->
                                                        <div class="border-t border-gray-100 dark:border-gray-700">
                                                        </div>

                                                        <!-- Delete -->
                                                        <button
                                                            onclick="deleteUser({{ $user->id }}); closeDropdown({{ $user->id }})"
                                                            class="flex mt-2 items-center w-full px-4 py-2 cursor-pointer text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150"
                                                            role="menuitem">
                                                            <svg style="width: 20px; align-items: center" class="mr-3"
                                                                fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            </svg>
                                                            Delete Customer
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Expandable Details Row -->
                                    <tr id="details-{{ $user->id }}" class="hidden bg-gray-50 dark:bg-gray-700/50">
                                        <td colspan="9" class="px-5 py-4">
                                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                                <!-- Left Column -->
                                                <div class="space-y-4">
                                                    <!-- User Info -->
                                                    <div>
                                                        <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                            User Information</h4>
                                                        <div class="space-y-2">
                                                            <div>
                                                                <span
                                                                    class="text-xs font-medium text-gray-500 dark:text-gray-400">Full
                                                                    Name:</span>
                                                                <p class="text-sm text-green-600 dark:text-green-400">
                                                                    {{ $user->name }}</p>
                                                            </div>
                                                            @if ($user->address)
                                                                <div>
                                                                    <span
                                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Address:</span>
                                                                    <p
                                                                        class="text-sm text-green-600 dark:text-green-400">
                                                                        {{ $user->address }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Contact Info -->
                                                    <div>
                                                        <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                            Contact Information</h4>
                                                        <div class="space-y-2">
                                                            <div>
                                                                <span
                                                                    class="text-xs font-medium text-gray-500 dark:text-gray-400">Email:</span>
                                                                <div class="flex flex-wrap gap-1 mt-1">
                                                                    <span
                                                                        class="inline-flex items-center font-normal py-1 rounded-full text-xs bg-blue-100 dark:bg-blue-400/30 text-blue-600 dark:text-blue-400">
                                                                        {{ $user->email }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            @if ($user->phone)
                                                                <div>
                                                                    <span
                                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Phone:</span>
                                                                    <div class="flex flex-wrap gap-1 mt-1">
                                                                        <span
                                                                            class="inline-flex items-center font-normal py-1 rounded-full text-xs bg-green-100 dark:bg-green-400/30 text-green-600 dark:text-green-400">
                                                                            {{ $user->phone }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Right Column -->
                                                <div class="space-y-4">
                                                    <!-- Role & Status -->
                                                    <div>
                                                        <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                            Role & Status</h4>
                                                        <div class="space-y-2">
                                                            <div>
                                                                <span
                                                                    class="text-xs font-medium text-gray-500 dark:text-gray-400">Role:</span>
                                                                <p
                                                                    class="text-sm text-purple-600 dark:text-purple-400">
                                                                    {{ ucfirst($user->role) }}</p>
                                                            </div>
                                                            <div>
                                                                <span
                                                                    class="text-xs font-medium text-gray-500 dark:text-gray-400">Email
                                                                    Status:</span>
                                                                <p
                                                                    class="text-sm {{ $user->email_verified_at ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                                    {{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Timestamps -->
                                                    <div>
                                                        <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                            Timestamps</h4>
                                                        <div
                                                            class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                                            <div>Created:
                                                                {{ optional($user->created_at)->format('M d, Y H:i') ?? 'Unknown' }}
                                                            </div>
                                                            <div>Updated:
                                                                {{ optional($user->updated_at)->format('M d, Y H:i') ?? 'Unknown' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($users->hasPages())
                        <div class="mt-4">
                            {{ $users->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center py-16">
                        <div
                            class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6">
                            <span class="text-4xl text-gray-400"><svg class="shrink-0 fill-current @if(in_array(Request::segment(1), ['settings'])){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif" width="40px" height="40px"						
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                            </svg>
                        </span>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No customers found</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">Get started by creating the first customer in
                            the
                            system.</p>
                        <a href="{{ route('admin.customers.create') }}"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-violet-600 hover:bg-violet-700 transition-colors duration-200">
                            Add New Customer
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Toggle user details
        // function toggleDetails(userId) {
        //     const detailsRow = document.getElementById(`details-${userId}`);
        //     const isHidden = detailsRow.classList.contains('hidden');

        //     // Close all other details first
        //     const allDetails = document.querySelectorAll('[id^="details-"]');
        //     allDetails.forEach(detail => {
        //         detail.classList.add('hidden');
        //     });

        //     // Toggle current details
        //     if (isHidden) {
        //         detailsRow.classList.remove('hidden');
        //         // Smooth scroll to the details
        //         setTimeout(() => {
        //             detailsRow.scrollIntoView({
        //                 behavior: 'smooth',
        //                 block: 'nearest'
        //             });
        //         }, 100);
        //     }
        // }

        // Toggle dropdown menu
        function toggleDropdown(userId) {
            const dropdown = document.getElementById(`dropdown-menu-${userId}`);
            const allDropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');

            // Close all other dropdowns
            allDropdowns.forEach(menu => {
                if (menu.id !== `dropdown-menu-${userId}`) {
                    menu.classList.add('hidden');
                }
            });

            // Toggle current dropdown
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown
        function closeDropdown(userId) {
            const dropdown = document.getElementById(`dropdown-menu-${userId}`);
            dropdown.classList.add('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');
            const buttons = document.querySelectorAll('[id^="dropdown-button-"]');

            let clickedInsideDropdown = false;

            // Check if clicked inside any dropdown or button
            dropdowns.forEach(dropdown => {
                if (dropdown.contains(event.target)) {
                    clickedInsideDropdown = true;
                }
            });

            buttons.forEach(button => {
                if (button.contains(event.target)) {
                    clickedInsideDropdown = true;
                }
            });

            // If clicked outside, close all dropdowns
            if (!clickedInsideDropdown) {
                dropdowns.forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            }
        });

        // Delete user
        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user? This action cannot be undone!')) {
                // Create and submit form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/customers/destroy/${userId}`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                form.appendChild(csrfToken);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-app-layout>

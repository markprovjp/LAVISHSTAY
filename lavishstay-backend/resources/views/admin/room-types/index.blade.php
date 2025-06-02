<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Room Types Management</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Manage all room types and their configurations</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Add room type button -->
                <a href="{{ route('admin.room-types.create') }}">
                    <button
                        class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                        <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                            <path
                                d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                        </svg>
                        <span class="max-xs:sr-only">Add Room Type</span>
                    </button>
                </a>
            </div>

        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700/60 mb-8">
            <div class="p-6">
                <form method="GET" action="{{ route('admin.room-types') }}"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                            placeholder="Search room types..." class="form-input w-full">
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label for="category"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                        <select id="category" name="category" class="form-select w-full">
                            <option value="">All Categories</option>
                            <option value="standard" {{ request('category') == 'standard' ? 'selected' : '' }}>Standard
                            </option>
                            <option value="presidential" {{ request('category') == 'presidential' ? 'selected' : '' }}>
                                Presidential</option>
                            <option value="the_level" {{ request('category') == 'the_level' ? 'selected' : '' }}>The
                                Level</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select id="status" name="status" class="form-select w-full">
                            <option value="">All Status</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                            Filter
                        </button>
                        <a href="{{ route('admin.room-types') }}"
                            class="btn border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Content -->

        <!-- Room Types Table -->
        <div class="col-span-12">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700/60">
                <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Room Types ({{ $roomTypes->total() }})
                    </h2>
                </header>

                <div class="overflow-x-auto">
                    <table class="table-auto w-full dark:text-gray-300">
                        <!-- Table header -->
                        <thead
                            class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700 dark:bg-opacity-50">
                            <tr>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">ID</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">Room Type</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">Category</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">Capacity</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">Pricing (USD)</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">View & Size</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">Sale Info</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">Status</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">Actions</div>
                                </th>
                            </tr>
                        </thead>
                        <!-- Table body -->
                        <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                            @forelse($roomTypes as $roomType)
                                <tr>
                                    <!-- ID -->
                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                        <div class="font-medium text-gray-800 dark:text-gray-100">
                                            #{{ $roomType->room_type_id }}
                                        </div>
                                    </td>

                                    <!-- Room Type Names -->
                                    <td class="px-2 first:pl-5 last:pr-5 py-3">
                                        <div class="font-medium text-gray-800 dark:text-gray-100">
                                            {{ $roomType->type_name_en }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $roomType->type_name_vi }}
                                        </div>
                                        @if ($roomType->bed_type)
                                            <div class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                                                🛏️ {{ $roomType->bed_type }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Category -->
                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                        <div
                                            class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5 text-xs
            @if ($roomType->category == 'standard') bg-emerald-100 dark:bg-emerald-400/0.3 text-emerald-600 dark:text-emerald-400
            @elseif ($roomType->category == 'presidential') bg-violet-100 dark:bg-violet-400/0.3 text-violet-600 dark:text-violet-400
            @else bg-amber-100 dark:bg-amber-400/0.3 text-amber-600 dark:text-amber-400 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $roomType->category)) }}
                                        </div>
                                    </td>

                                    <!-- Capacity -->
                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                        <div class="text-sm">
                                            <div>👥 {{ $roomType->max_occupancy ?? 'N/A' }} guests</div>
                                            <div class="text-xs text-gray-500">
                                                {{ $roomType->max_adults ?? 0 }} adults,
                                                {{ $roomType->max_children ?? 0 }} children
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Pricing -->
                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                        <div class="text-sm">
                                            <div class="font-medium text-gray-800 dark:text-gray-100">
                                                ${{ number_format($roomType->base_price_usd, 2) }}
                                            </div>
                                            @if ($roomType->weekend_price_usd)
                                                <div class="text-xs text-gray-500">
                                                    Weekend: ${{ number_format($roomType->weekend_price_usd, 2) }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- View & Size -->
                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                        <div class="text-sm">
                                            @if ($roomType->view_type)
                                                <div class="capitalize">
                                                    @if ($roomType->view_type == 'ocean')
                                                        🌊
                                                    @elseif($roomType->view_type == 'city')
                                                        🏙️
                                                    @elseif($roomType->view_type == 'garden')
                                                        🌳
                                                    @elseif($roomType->view_type == 'mountain')
                                                        ⛰️
                                                    @endif
                                                    {{ $roomType->view_type }}
                                                </div>
                                            @endif
                                            @if ($roomType->size_sqm)
                                                <div class="text-xs text-gray-500">
                                                    📐 {{ $roomType->size_sqm }} m²
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Sale Info -->
                                    <td class="px-2 first:pl-5 last:pr-5


                                                                <!-- Sale Info -->
                                <td class="px-2
                                        first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                        @if ($roomType->is_sale)
                                            <div class="text-sm">
                                                <div
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-red-100 dark:bg-red-400/30 text-red-600 dark:text-red-400">
                                                    🏷️ {{ $roomType->sale_percentage }}% OFF
                                                </div>
                                                @if ($roomType->sale_price_usd)
                                                    <div class="text-xs text-green-600 dark:text-green-400 mt-1">
                                                        ${{ number_format($roomType->sale_price_usd, 2) }}
                                                    </div>
                                                @endif
                                                @if ($roomType->sale_start_date && $roomType->sale_end_date)
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        {{ $roomType->sale_start_date->format('M d') }} -
                                                        {{ $roomType->sale_end_date->format('M d') }}
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">No sale</span>
                                        @endif
                                    </td>

                                    <!-- Status -->
                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                        <div
                                            class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5 text-xs
                                        @if ($roomType->is_active) bg-emerald-100 dark:bg-emerald-400/30 text-emerald-600 dark:text-emerald-400
                                        @else bg-gray-100 dark:bg-gray-400/30 text-gray-500 dark:text-gray-400 @endif">
                                            {{ $roomType->is_active ? 'Active' : 'Inactive' }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Order: {{ $roomType->sort_order ?? 0 }}
                                        </div>
                                    </td>

                                    <!-- Actions -->

                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                        <div class="relative inline-block text-left">
                                            <button type="button"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-colors duration-200"
                                                onclick="toggleDropdown({{ $roomType->room_type_id }})"
                                                id="dropdown-button-{{ $roomType->room_type_id }}">
                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                                    </path>
                                                </svg>
                                            </button>

                                            <!-- Dropdown Menu -->
                                            <div id="dropdown-menu-{{ $roomType->room_type_id }}"
                                                class="hidden absolute right-0 z-50 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                                <div class="py-1 z-500" role="menu">
                                                    <!-- View Details -->
                                                    <button
                                                        onclick="toggleDetails({{ $roomType->room_type_id }}); closeDropdown({{ $roomType->room_type_id }})"
                                                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                        role="menuitem">
                                                        {{-- <svg class="w-4 h-4 mr-3 text-blue-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                            </path>
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                            </path>
                                                        </svg> --}}
                                                        View Details
                                                    </button>

                                                    <!-- Edit -->
                                                    <a href="{{ route('admin.room-types.edit', $roomType->room_type_id) }}"
                                                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                        role="menuitem">
                                                        {{-- <svg class="w-4 h-4 mr-3 text-green-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                            </path>
                                                        </svg> --}}
                                                        Edit Room Type
                                                    </a>

                                                    <!-- Toggle Status -->
                                                    <button
                                                        onclick="toggleStatus({{ $roomType->room_type_id }}); closeDropdown({{ $roomType->room_type_id }})"
                                                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                        role="menuitem">
                                                        @if ($roomType->is_active)
                                                            {{-- <svg class="w-4 h-4 mr-3 text-orange-500" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L5.636 5.636">
                                                                </path>
                                                            </svg> --}}
                                                            Deactivate
                                                        @else
                                                            {{-- <svg class="w-4 h-4 mr-3 text-green-500" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                </path>
                                                            </svg> --}}
                                                            Activate
                                                        @endif
                                                    </button>

                                                    <!-- Divider -->
                                                    <div class="border-t border-gray-100 dark:border-gray-700"></div>

                                                    <!-- Delete -->
                                                    <button
                                                        onclick="deleteRoomType({{ $roomType->room_type_id }}); closeDropdown({{ $roomType->room_type_id }})"
                                                        class="flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150"
                                                        role="menuitem">
                                                        <svg class="w-4 h-4 mr-3" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                        Delete Room Type
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                </tr>

                                <!-- Expandable Details Row -->
                                <tr id="details-{{ $roomType->room_type_id }}"
                                    class="hidden bg-gray-50 dark:bg-gray-700/50">
                                    <td colspan="9" class="px-5 py-4">
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                            <!-- Left Column -->
                                            <div class="space-y-4">
                                                <!-- Descriptions -->
                                                <div>
                                                    <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                        Descriptions</h4>
                                                    <div class="space-y-2">
                                                        @if ($roomType->description_en)
                                                            <div>
                                                                <span
                                                                    class="text-xs font-medium text-gray-500 dark:text-gray-400">English:</span>
                                                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                                                    {{ Str::limit($roomType->description_en, 150) }}
                                                                </p>
                                                            </div>
                                                        @endif
                                                        @if ($roomType->description_vi)
                                                            <div>
                                                                <span
                                                                    class="text-xs font-medium text-gray-500 dark:text-gray-400">Vietnamese:</span>
                                                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                                                    {{ Str::limit($roomType->description_vi, 150) }}
                                                                </p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Room Features -->
                                                @if ($roomType->room_features_en || $roomType->room_features_vi)
                                                    <div>
                                                        <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                            Room Features</h4>
                                                        <div class="space-y-2">
                                                            @if ($roomType->room_features_en)
                                                                <div>
                                                                    <span
                                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">English:</span>
                                                                    <div class="flex flex-wrap gap-1 mt-1">
                                                                        @foreach ($roomType->room_features_en as $feature)
                                                                            <span
                                                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 dark:bg-blue-400/30 text-blue-600 dark:text-blue-400">
                                                                                {{ $feature }}
                                                                            </span>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($roomType->room_features_vi)
                                                                <div>
                                                                    <span
                                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Vietnamese:</span>
                                                                    <div class="flex flex-wrap gap-1 mt-1">
                                                                        @foreach ($roomType->room_features_vi as $feature)
                                                                            <span
                                                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 dark:bg-green-400/30 text-green-600 dark:text-green-400">
                                                                                {{ $feature }}
                                                                            </span>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Right Column -->
                                            <div class="space-y-4">
                                                <!-- Policies -->
                                                @if ($roomType->policies_en || $roomType->policies_vi)
                                                    <div>
                                                        <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                            Policies</h4>
                                                        <div class="space-y-2">
                                                            @if ($roomType->policies_en)
                                                                <div>
                                                                    <span
                                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">English:</span>
                                                                    <p
                                                                        class="text-sm text-gray-700 dark:text-gray-300">
                                                                        {{ Str::limit($roomType->policies_en, 100) }}
                                                                    </p>
                                                                </div>
                                                            @endif
                                                            @if ($roomType->policies_vi)
                                                                <div>
                                                                    <span
                                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Vietnamese:</span>
                                                                    <p
                                                                        class="text-sm text-gray-700 dark:text-gray-300">
                                                                        {{ Str::limit($roomType->policies_vi, 100) }}
                                                                    </p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Cancellation Policies -->
                                                @if ($roomType->cancellation_policy_en || $roomType->cancellation_policy_vi)
                                                    <div>
                                                        <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                            Cancellation Policies</h4>
                                                        <div class="space-y-2">
                                                            @if ($roomType->cancellation_policy_en)
                                                                <div>
                                                                    <span
                                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">English:</span>
                                                                    <p
                                                                        class="text-sm text-gray-700 dark:text-gray-300">
                                                                        {{ Str::limit($roomType->cancellation_policy_en, 100) }}
                                                                    </p>
                                                                </div>
                                                            @endif
                                                            @if ($roomType->cancellation_policy_vi)
                                                                <div>
                                                                    <span
                                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Vietnamese:</span>
                                                                    <p
                                                                        class="text-sm text-gray-700 dark:text-gray-300">
                                                                        {{ Str::limit($roomType->cancellation_policy_vi, 100) }}
                                                                    </p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Timestamps -->
                                                <div>
                                                    <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                        Timestamps</h4>
                                                    <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                                        <div>Created: {{ $roomType->created_at->format('M d, Y H:i') }}
                                                        </div>
                                                        <div>Updated: {{ $roomType->updated_at->format('M d, Y H:i') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-5 py-8 text-center">
                                        <div class="text-gray-400 dark:text-gray-500">
                                            <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                </path>
                                            </svg>
                                            <p class="text-lg font-medium">No room types found</p>
                                            <p class="text-sm">Get started by creating your first room type.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($roomTypes->hasPages())
                    <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700/60">
                        {{ $roomTypes->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="col-span-12 mt-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Room Types -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700/60 p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-blue-500 rounded-full">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Room
                                    Types</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ $roomTypes->total() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Active Room Types -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700/60 p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-green-500 rounded-full">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Active</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ \App\Models\RoomType::where('is_active', true)->count() }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- On Sale -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700/60 p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-red-500 rounded-full">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">On Sale</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ \App\Models\RoomType::where('is_sale', true)->count() }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Average Price -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700/60 p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-yellow-500 rounded-full">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Avg. Price
                                </dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    ${{ number_format(\App\Models\RoomType::avg('base_price_usd') ?? 0, 2) }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- JavaScript for interactions -->
    <script>
        function toggleDetails(roomTypeId) {
            const detailsRow = document.getElementById(`details-${roomTypeId}`);
            detailsRow.classList.toggle('hidden');
        }

        function toggleStatus(roomTypeId) {
            if (confirm('Are you sure you want to toggle the status of this room type?')) {
                fetch(`/api/room-types/${roomTypeId}/toggle-active`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error updating status');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error updating status');
                    });
            }
        }

        function deleteRoomType(roomTypeId) {
            if (confirm('Are you sure you want to delete this room type? This action cannot be undone.')) {
                fetch(`/api/room-types/${roomTypeId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error deleting room type');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error deleting room type');
                    });
            }
        }

        // Auto-submit form on filter change
        document.addEventListener('DOMContentLoaded', function() {
            const filterSelects = document.querySelectorAll('#category, #status');
            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    this.form.submit();
                });
            });
        });
    </script>
    <script>
        // Dropdown functionality
        function toggleDropdown(roomTypeId) {
            const dropdown = document.getElementById(`dropdown-menu-${roomTypeId}`);
            const button = document.getElementById(`dropdown-button-${roomTypeId}`);

            // Close all other dropdowns first
            closeAllDropdowns();

            // Toggle current dropdown
            dropdown.classList.toggle('hidden');

            // Add click outside listener
            if (!dropdown.classList.contains('hidden')) {
                setTimeout(() => {
                    document.addEventListener('click', function closeOnClickOutside(event) {
                        if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                            dropdown.classList.add('hidden');
                            document.removeEventListener('click', closeOnClickOutside);
                        }
                    });
                }, 100);
            }
        }

        function closeDropdown(roomTypeId) {
            const dropdown = document.getElementById(`dropdown-menu-${roomTypeId}`);
            dropdown.classList.add('hidden');
        }

        function closeAllDropdowns() {
            const dropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');
            dropdowns.forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        }

        // Close dropdowns when pressing Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeAllDropdowns();
            }
        });

        function toggleDetails(roomTypeId) {
            const detailsRow = document.getElementById(`details-${roomTypeId}`);
            const isHidden = detailsRow.classList.contains('hidden');

            // Close all other details first
            const allDetails = document.querySelectorAll('[id^="details-"]');
            allDetails.forEach(detail => {
                detail.classList.add('hidden');
            });

            // Toggle current details
            if (isHidden) {
                detailsRow.classList.remove('hidden');
                // Smooth scroll to the details
                setTimeout(() => {
                    detailsRow.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                }, 100);
            }
        }

        function toggleStatus(roomTypeId) {
            // Show loading state
            const button = document.getElementById(`dropdown-button-${roomTypeId}`);
            const originalContent = button.innerHTML;
            button.innerHTML =
                '<svg class="animate-spin w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            button.disabled = true;

            if (confirm('Are you sure you want to toggle the status of this room type?')) {
                fetch(`/api/room-types/${roomTypeId}/toggle-active`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            showNotification('Status updated successfully!', 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            showNotification('Error updating status', 'error');
                            button.innerHTML = originalContent;
                            button.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Error updating status', 'error');
                        button.innerHTML = originalContent;
                        button.disabled = false;
                    });
            } else {
                button.innerHTML = originalContent;
                button.disabled = false;
            }
        }

        function deleteRoomType(roomTypeId) {
            if (confirm('Are you sure you want to delete this room type? This action cannot be undone.')) {
                // Show loading state
                const button = document.getElementById(`dropdown-button-${roomTypeId}`);
                const originalContent = button.innerHTML;
                button.innerHTML =
                    '<svg class="animate-spin w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                button.disabled = true;

                fetch(`/api/room-types/${roomTypeId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('Room type deleted successfully!', 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            showNotification('Error deleting room type', 'error');
                            button.innerHTML = originalContent;
                            button.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Error deleting room type', 'error');
                        button.innerHTML = originalContent;
                        button.disabled = false;
                    });
            }
        }

        // Notification system
        function showNotification(message, type = 'info') {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.notification');
            existingNotifications.forEach(notification => notification.remove());

            const notification = document.createElement('div');
            notification.className =
                `notification fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;

            const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
            notification.className += ` ${bgColor} text-white`;

            notification.innerHTML = `
            <div class="flex items-center">
                <span class="mr-2">
                     ${type === 'success' ? '✓' : type === 'error' ? '✗' : 'i'}              
                </span>
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 5000);
        }

        // Auto-submit form on filter change
        document.addEventListener('DOMContentLoaded', function() {
            const filterSelects = document.querySelectorAll('#category, #status');
            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    this.form.submit();
                });
            });
        });
    </script>


    <!-- Custom Styles -->
    <style>
        .table-auto th {
            position: sticky;
            top: 0;
            background: inherit;
            z-index: 10;
        }

        .form-input,
        .form-select {
            @apply block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm;
        }

        .btn {
            @apply inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150;
        }

        /* Responsive table scroll */
        @media (max-width: 1024px) {
            .overflow-x-auto {
                overflow-x: scroll;
                -webkit-overflow-scrolling: touch;
            }

            .table-auto {
                min-width: 1200px;
            }
        }

        /* Loading states */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        /* Hover effects */
        .table-auto tbody tr:hover {
            @apply bg-gray-50 dark:bg-gray-700/30;
        }

        /* Status badges */
        .status-badge {
            @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
        }

        /* Feature tags */
        .feature-tag {
            @apply inline-flex items-center px-2 py-1 rounded-full text-xs;
        }

        .table-auto th {
            position: sticky;
            top: 0;
            background: inherit;
            z-index: 10;
        }

        .form-input,
        .form-select {
            @apply block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm;
        }

        .btn {
            @apply inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150;
        }

        /* Dropdown animations */
        .dropdown-enter {
            opacity: 0;
            transform: scale(0.95);
        }

        .dropdown-enter-active {
            opacity: 1;
            transform: scale(1);
            transition: opacity 150ms ease-out, transform 150ms ease-out;
        }

        .dropdown-exit {
            opacity: 1;
            transform: scale(1);
        }

        .dropdown-exit-active {
            opacity: 0;
            transform: scale(0.95);
            transition: opacity 75ms ease-in, transform 75ms ease-in;
        }

        /* Action button hover effects */
        .action-button {
            @apply relative overflow-hidden;
        }

        .action-button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(139, 92, 246, 0.1);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.3s ease, height 0.3s ease;
        }

        .action-button:hover::before {
            width: 100%;
            height: 100%;
        }

        /* Responsive table scroll */
        @media (max-width: 1024px) {
            .overflow-x-auto {
                overflow-x: scroll;
                -webkit-overflow-scrolling: touch;
            }

            .table-auto {
                min-width: 1200px;
            }

            /* Adjust dropdown position on mobile */
            .dropdown-menu-mobile {
                position: fixed !important;
                right: 1rem !important;
                left: auto !important;
                transform: none !important;
            }
        }

        /* Loading states */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        /* Hover effects */
        .table-auto tbody tr:hover {
            @apply bg-gray-50 dark:bg-gray-700/30;
        }

        /* Status badges */
        .status-badge {
            @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
        }

        /* Feature tags */
        .feature-tag {
            @apply inline-flex items-center px-2 py-1 rounded-full text-xs;
        }

        /* Notification animations */
        .notification {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Smooth transitions for details expansion */
        .details-row {
            transition: all 0.3s ease-in-out;
        }

        .details-row.hidden {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
        }

        .details-row:not(.hidden) {
            max-height: 1000px;
            opacity: 1;
        }

        /* Custom scrollbar for dropdown */
        .dropdown-menu::-webkit-scrollbar {
            width: 4px;
        }

        .dropdown-menu::-webkit-scrollbar-track {
            background: transparent;
        }

        .dropdown-menu::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.5);
            border-radius: 2px;
        }

        .dropdown-menu::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 163, 175, 0.7);
        }

        /* Focus states for accessibility */
        .dropdown-menu button:focus,
        .dropdown-menu a:focus {
            @apply outline-none ring-2 ring-violet-500 ring-inset;
        }

        /* Animation for action button */
        .action-button {
            transition: all 0.2s ease-in-out;
        }

        .action-button:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .action-button:active {
            transform: scale(0.95);
        }

        /* Pulse animation for loading states */
        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Dropdown shadow */
        .dropdown-shadow {
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Dark mode adjustments */
        @media (prefers-color-scheme: dark) {
            .dropdown-shadow {
                box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2);
            }
        }
    </style>


</x-app-layout>

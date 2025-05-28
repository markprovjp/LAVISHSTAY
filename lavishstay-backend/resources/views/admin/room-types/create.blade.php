<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        
        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Create New Room Type</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Add a new room type to your hotel inventory</p>
            </div>
            
            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Back Button -->
                <a href="{{ route('admin.room-types') }}" 
                   class="btn bg-gray-500 hover:bg-gray-600 text-white">
                    {{-- <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">
                        <path d="M6.6 13.4L5.2 12l4-4-4-4 1.4-1.4L12 8z" transform="rotate(180 8 8)"/>
                    </svg> --}}
                    <span class="ml-2">Back to List</span>
                </a>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.room-types.store') }}" method="POST" enctype="multipart/form-data" id="roomTypeForm">
            @csrf
            
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700/60">
                
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <button type="button" 
                                class="tab-button active border-b-2 border-violet-500 py-4 px-1 text-sm font-medium text-violet-600 dark:text-violet-400" 
                                data-tab="basic">
                            Basic Information
                        </button>
                        <button type="button" 
                                class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300" 
                                data-tab="content">
                            Content & Features
                        </button>
                        <button type="button" 
                                class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300" 
                                data-tab="pricing">
                            Pricing & Sales
                        </button>
                        <button type="button" 
                                class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300" 
                                data-tab="policies">
                            Policies
                        </button>
                        <button type="button" 
                                class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300" 
                                data-tab="settings">
                            Settings
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    
                    <!-- Basic Information Tab -->
                    <div id="basic-tab" class="tab-content">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Room Type Names</h3>
                                    
                                    <!-- English Name -->
                                    <div class="mb-4">
                                        <label for="type_name_en" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            English Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               id="type_name_en" 
                                               name="type_name_en" 
                                               value="{{ old('type_name_en') }}"
                                               class="form-input w-full @error('type_name_en') border-red-500 @enderror" 
                                               placeholder="e.g., Deluxe Ocean View Room"
                                               required>
                                        @error('type_name_en')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Vietnamese Name -->
                                    <div>
                                        <label for="type_name_vi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Vietnamese Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               id="type_name_vi" 
                                               name="type_name_vi" 
                                               value="{{ old('type_name_vi') }}"
                                               class="form-input w-full @error('type_name_vi') border-red-500 @enderror" 
                                               placeholder="e.g., Phòng Deluxe Hướng Biển"
                                               required>
                                        @error('type_name_vi')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Category & View Type -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Category <span class="text-red-500">*</span>
                                        </label>
                                        <select id="category" 
                                                name="category" 
                                                class="form-select @error('category') border-red-500 @enderror" 
                                                required>
                                            <option value="">Select Category</option>
                                            <option value="standard" {{ old('category') == 'standard' ? 'selected' : '' }}>Standard</option>
                                            <option value="presidential" {{ old('category') == 'presidential' ? 'selected' : '' }}>Presidential</option>
                                            <option value="the_level" {{ old('category') == 'the_level' ? 'selected' : '' }}>The Level</option>
                                        </select>
                                        @error('category')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="view_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            View Type
                                        </label>
                                        <select id="view_type" 
                                                name="view_type" 
                                                class="form-select @error('view_type') border-red-500 @enderror">
                                            <option value="city" {{ old('view_type') == 'city' ? 'selected' : '' }}>City View</option>
                                            <option value="ocean" {{ old('view_type') == 'ocean' ? 'selected' : '' }}>Ocean View</option>
                                            <option value="garden" {{ old('view_type') == 'garden' ? 'selected' : '' }}>Garden View</option>
                                            <option value="mountain" {{ old('view_type') == 'mountain' ? 'selected' : '' }}>Mountain View</option>
                                        </select>
                                        @error('view_type')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Room Specifications</h3>
                                    
                                    <!-- Size & Bed Type -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label for="size_sqm" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Size (sqm)
                                            </label>
                                            <input type="number" 
                                                   id="size_sqm" 
                                                   name="size_sqm" 
                                                   value="{{ old('size_sqm') }}"
                                                   class="form-input @error('size_sqm') border-red-500 @enderror" 
                                                   placeholder="e.g., 45"
                                                   min="1">
                                            @error('size_sqm')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="bed_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Bed Type
                                            </label>
                                            <input type="text" 
                                                   id="bed_type" 
                                                   name="bed_type" 
                                                   value="{{ old('bed_type') }}"
                                                   class="form-input @error('bed_type') border-red-500 @enderror" 
                                                   placeholder="e.g., King Size Bed">
                                            @error('bed_type')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Occupancy -->
                                    <div class="grid grid-cols-3 gap-4">
                                        <div>
                                            <label for="max_adults" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Max Adults <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number" 
                                                   id="max_adults" 
                                                   name="max_adults" 
                                                   value="{{ old('max_adults', 2) }}"
                                                   class="form-input @error('max_adults') border-red-500 @enderror" 
                                                   min="1" 
                                                   max="10"
                                                   required>
                                            @error('max_adults')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="max_children" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Max Children
                                            </label>
                                            <input type="number" 
                                                   id="max_children" 
                                                   name="max_children" 
                                                   value="{{ old('max_children', 1) }}"
                                                   class="form-input @error('max_children') border-red-500 @enderror" 
                                                   min="0" 
                                                   max="5">
                                            @error('max_children')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="max_occupancy" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Max Occupancy <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number" 
                                                   id="max_occupancy" 
                                                   name="max_occupancy" 
                                                   value="{{ old('max_occupancy', 3) }}"
                                                   class="form-input @error('max_occupancy') border-red-500 @enderror" 
                                                   min="1" 
                                                   max="15"
                                                   required>
                                            @error('max_occupancy')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content & Features Tab -->
                    <div id="content-tab" class="tab-content hidden">
                        <div class="space-y-8">
                            
                            <!-- Descriptions -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Room Descriptions</h3>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <!-- English Description -->
                                    <div>
                                        <label for="description_en" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            English Description
                                        </label>
                                        <textarea id="description_en" 
                                                  name="description_en" 
                                                  rows="6"
                                                  class="form-input w-full @error('description_en') border-red-500 @enderror" 
                                                  placeholder="Describe the room in English">{{ old('description_en') }}</textarea>
                                        @error('description_en')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Vietnamese Description -->
                                    <div>
                                        <label for="description_vi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Vietnamese Description
                                        </label>
                                        <textarea id="description_vi" 
                                                  name="description_vi" 
                                                  rows="6"
                                                  class="form-input w-full @error('description_vi') border-red-500 @enderror" 
                                                  placeholder="Mô tả phòng bằng tiếng Việt...">{{ old('description_vi') }}</textarea>
                                        @error('description_vi')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Room Features -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Room Features</h3>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <!-- English Features -->
                                    <div>
                                        <label for="room_features_en" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Features (English)
                                        </label>
                                        <div class="space-y-2">
                                            <div class="flex items-center space-x-2">
                                                <input type="text" 
                                                       id="feature_en_input" 
                                                       class="form-input flex-1" 
                                                       placeholder="Add a feature in English..."
                                                       onkeypress="addFeature(event, 'en')">
                                                <button type="button" 
                                                        onclick="addFeature(null, 'en')"
                                                        class="btn bg-violet-500 hover:bg-violet-600 text-white px-3 py-2">
                                                    Add
                                                </button>
                                            </div>
                                            <div id="features_en_list" class="space-y-1 max-h-40 overflow-y-auto">
                                                <!-- Features will be added here dynamically -->
                                            </div>
                                        </div>
                                        <input type="hidden" name="room_features_en" id="room_features_en_hidden">
                                    </div>

                                    <!-- Vietnamese Features -->
                                    <div>
                                        <label for="room_features_vi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Features (Vietnamese)
                                        </label>
                                        <div class="space-y-2">
                                            <div class="flex items-center space-x-2">
                                                <input type="text" 
                                                       id="feature_vi_input" 
                                                       class="form-input flex-1" 
                                                       placeholder="Thêm tiện nghi bằng tiếng Việt..."
                                                       onkeypress="addFeature(event, 'vi')">
                                                <button type="button" 
                                                        onclick="addFeature(null, 'vi')"
                                                        class="btn bg-violet-500 hover:bg-violet-600 text-white px-3 py-2">
                                                    Thêm
                                                </button>
                                            </div>
                                            <div id="features_vi_list" class="space-y-1 max-h-40 overflow-y-auto">
                                                <!-- Features will be added here dynamically -->
                                            </div>
                                        </div>
                                        <input type="hidden" name="room_features_vi" id="room_features_vi_hidden">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Sales Tab -->
                    <div id="pricing-tab" class="tab-content hidden">
                        <div class="space-y-8">
                            
                            <!-- Base Pricing -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Base Pricing</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="base_price_usd" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Base Price (USD) <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">$</span>
                                            </div>
                                            <input type="number" 
                                                   id="base_price_usd" 
                                                   name="base_price_usd" 
                                                   value="{{ old('base_price_usd') }}"
                                                   class="form-input pl-7 @error('base_price_usd') border-red-500 @enderror" 
                                                   placeholder="0.00"
                                                   step="0.01"
                                                   min="0"
                                                   required>
                                        </div>
                                        @error('base_price_usd')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="weekend_price_usd" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Weekend Price (USD)
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">$</span>
                                            </div>
                                            <input type="number" 
                                                   id="weekend_price_usd" 
                                                   name="weekend_price_usd" 
                                                   value="{{ old('weekend_price_usd') }}"
                                                   class="form-input pl-7 @error('weekend_price_usd') border-red-500 @enderror" 
                                                   placeholder="0.00"
                                                   step="0.01"
                                                   min="0">
                                        </div>
                                        @error('weekend_price_usd')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Sale Settings -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Sale Settings</h3>
                                
                                <!-- Sale Toggle -->
                                <div class="mb-6">
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               id="is_sale" 
                                               name="is_sale" 
                                               value="1"
                                               {{ old('is_sale') ? 'checked' : '' }}
                                               class="form-checkbox h-5 w-5 text-violet-600"
                                               onchange="toggleSaleFields()">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">This room type is on sale</span>
                                    </label>
                                </div>

                                <!-- Sale Fields -->
                                <div id="sale_fields" class="space-y-4 {{ old('is_sale') ? '' : 'hidden' }}">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="sale_percentage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Sale Percentage
                                            </label>
                                            <div class="relative">
                                                <input type="number" 
                                                       id="sale_percentage" 
                                                       name="sale_percentage" 
                                                       value="{{ old('sale_percentage') }}"
                                                       class="form-input pr-8 @error('sale_percentage') border-red-500 @enderror" 
                                                       placeholder="0"
                                                       step="0.01"
                                                       min="0"
                                                       max="100"
                                                       onchange="calculateSalePrice()">
                                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">%</span>
                                                </div>
                                            </div>
                                            @error('sale_percentage')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="sale_price_usd" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Sale Price (USD)
                                            </label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">$</span>
                                                </div>
                                                <input type="number" 
                                                       id="sale_price_usd" 
                                                       name="sale_price_usd" 
                                                       value="{{ old('sale_price_usd') }}"
                                                       class="form-input pl-7 @error('sale_price_usd') border-red-500 @enderror" 
                                                       placeholder="0.00"
                                                       step="0.01"
                                                       min="0"
                                                       readonly>
                                            </div>
                                            @error('sale_price_usd')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Sale Date Range -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="sale_start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Sale Start Date
                                            </label>
                                            <input type="date" 
                                                   id="sale_start_date" 
                                                   name="sale_start_date" 
                                                   value="{{ old('sale_start_date') }}"
                                                   class="form-input @error('sale_start_date') border-red-500 @enderror">
                                            @error('sale_start_date')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="sale_end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Sale End Date
                                            </label>
                                            <input type="date" 
                                                   id="sale_end_date" 
                                                   name="sale_end_date" 
                                                   value="{{ old('sale_end_date') }}"
                                                   class="form-input @error('sale_end_date') border-red-500 @enderror">
                                            @error('sale_end_date')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Policies Tab -->
                    <div id="policies-tab" class="tab-content hidden">
                        <div class="space-y-8">
                            
                            <!-- Room Policies -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Room Policies</h3>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <!-- English Policies -->
                                    <div>
                                        <label for="policies_en" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Policies (English)
                                        </label>
                                        <textarea id="policies_en" 
                                                  name="policies_en" 
                                                  rows="6"
                                                  class="form-input w-full @error('policies_en') border-red-500 @enderror" 
                                                  placeholder="Enter room policies in English...">{{ old('policies_en') }}</textarea>
                                        @error('policies_en')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Vietnamese Policies -->
                                    <div>
                                        <label for="policies_vi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Policies (Vietnamese)
                                        </label>
                                        <textarea id="policies_vi" 
                                                  name="policies_vi" 
                                                  rows="6"
                                                  class="form-input w-full @error('policies_vi') border-red-500 @enderror" 
                                                  placeholder="Nhập chính sách phòng bằng tiếng Việt...">{{ old('policies_vi') }}</textarea>
                                        @error('policies_vi')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Cancellation Policies -->
                            <!-- Cancellation Policies -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Cancellation Policies</h3>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <!-- English Cancellation Policy -->
                                    <div>
                                        <label for="cancellation_policy_en" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Cancellation Policy (English)
                                        </label>
                                        <textarea id="cancellation_policy_en" 
                                                  name="cancellation_policy_en" 
                                                  rows="6"
                                                  class="form-input w-full @error('cancellation_policy_en') border-red-500 @enderror" 
                                                  placeholder="Enter cancellation policy in English...">{{ old('cancellation_policy_en') }}</textarea>
                                        @error('cancellation_policy_en')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Vietnamese Cancellation Policy -->
                                    <div>
                                        <label for="cancellation_policy_vi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Cancellation Policy (Vietnamese)
                                        </label>
                                        <textarea id="cancellation_policy_vi" 
                                                  name="cancellation_policy_vi" 
                                                  rows="6"
                                                  class="form-input w-full @error('cancellation_policy_vi') border-red-500 @enderror" 
                                                  placeholder="Nhập chính sách hủy phòng bằng tiếng Việt...">{{ old('cancellation_policy_vi') }}</textarea>
                                        @error('cancellation_policy_vi')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Tab -->
                    <div id="settings-tab" class="tab-content hidden">
                        <div class="space-y-8">
                            
                            <!-- Display Settings -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Display Settings</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="sort_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Sort Order
                                        </label>
                                        <input type="number" 
                                               id="sort_order" 
                                               name="sort_order" 
                                               value="{{ old('sort_order', 0) }}"
                                               class="form-input @error('sort_order') border-red-500 @enderror" 
                                               placeholder="0"
                                               min="0">
                                        <p class="mt-1 text-xs text-gray-500">Lower numbers appear first</p>
                                        @error('sort_order')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Status
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" 
                                                   id="is_active" 
                                                   name="is_active" 
                                                   value="1"
                                                   {{ old('is_active', true) ? 'checked' : '' }}
                                                   class="form-checkbox h-5 w-5 text-violet-600">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active (visible to customers)</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Image Upload -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Room Images</h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="images" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Upload Images
                                        </label>
                                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors duration-200">
                                            <div class="space-y-1 text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <div class="flex text-sm text-gray-600">
                                                    <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-violet-600 hover:text-violet-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-violet-500">
                                                        <span class="">Upload files</span>
                                                        <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*" onchange="previewImages(this)">
                                                    </label>
                                                    <p class="pl-1">or drag and drop</p>
                                                </div>
                                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB each</p>
                                            </div>
                                        </div>
                                        @error('images')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Image Preview -->
                                    <div id="image_preview" class="hidden grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-4">
                                        <!-- Preview images will be added here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 rounded-b-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <button type="button" 
                                    id="save_draft_btn"
                                    class="btn bg-gray-500 hover:bg-gray-600 text-white">
                                {{-- <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg> --}}
                                Save as Draft
                            </button>
                        </div>

                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.room-types') }}" 
                               class="btn bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                Cancel
                            </a>
                            
                            <button type="submit" 
                                    class="btn bg-violet-500 hover:bg-violet-600 text-white">
                                {{-- <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg> --}}
                                Create Room Type
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- JavaScript -->
    <script>
        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');
                    
                    // Remove active class from all buttons
                    tabButtons.forEach(btn => {
                        btn.classList.remove('border-violet-500', 'text-violet-600', 'dark:text-violet-400');
                        btn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
                    });
                    
                    // Add active class to clicked button
                    this.classList.add('border-violet-500', 'text-violet-600', 'dark:text-violet-400');
                    this.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
                    
                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });
                    
                    // Show target tab content
                    document.getElementById(targetTab + '-tab').classList.remove('hidden');
                });
            });
        });

        // Features management
        let featuresEn = [];
        let featuresVi = [];

        function addFeature(event, lang) {
            if (event && event.key !== 'Enter') return;
            
            const input = document.getElementById(`feature_${lang}_input`);
            const value = input.value.trim();
            
            if (!value) return;
            
            if (lang === 'en') {
                featuresEn.push(value);
                updateFeaturesList('en');
            } else {
                featuresVi.push(value);
                updateFeaturesList('vi');
            }
            
            input.value = '';
        }

        function updateFeaturesList(lang) {
            const features = lang === 'en' ? featuresEn : featuresVi;
            const listContainer = document.getElementById(`features_${lang}_list`);
            const hiddenInput = document.getElementById(`room_features_${lang}_hidden`);
            
            listContainer.innerHTML = '';
            
            features.forEach((feature, index) => {
                const featureElement = document.createElement('div');
                featureElement.className = 'flex items-center justify-between bg-gray-100 dark:bg-gray-700 px-3 py-2 rounded-md';
                featureElement.innerHTML = `
                    <span class="text-sm text-gray-700 dark:text-gray-300">${feature}</span>
                    <button type="button" 
                            onclick="removeFeature(${index}, '${lang}')"
                            class="text-red-500 hover:text-red-700 ml-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;
                listContainer.appendChild(featureElement);
            });
            
            hiddenInput.value = JSON.stringify(features);
        }

        function removeFeature(index, lang) {
            if (lang === 'en') {
                featuresEn.splice(index, 1);
                updateFeaturesList('en');
            } else {
                featuresVi.splice(index, 1);
                updateFeaturesList('vi');
            }
        }

        // Sale functionality
        function toggleSaleFields() {
            const isChecked = document.getElementById('is_sale').checked;
            const saleFields = document.getElementById('sale_fields');
            
            if (isChecked) {
                saleFields.classList.remove('hidden');
            } else {
                saleFields.classList.add('hidden');
                // Clear sale fields
                document.getElementById('sale_percentage').value = '';
                document.getElementById('sale_price_usd').value = '';
                document.getElementById('sale_start_date').value = '';
                document.getElementById('sale_end_date').value = '';
            }
        }

        function calculateSalePrice() {
            const basePrice = parseFloat(document.getElementById('base_price_usd').value) || 0;
            const salePercentage = parseFloat(document.getElementById('sale_percentage').value) || 0;
            
            if (basePrice > 0 && salePercentage > 0) {
                const salePrice = basePrice - (basePrice * salePercentage / 100);
                document.getElementById('sale_price_usd').value = salePrice.toFixed(2);
            } else {
                document.getElementById('sale_price_usd').value = '';
            }
        }

        // Auto-calculate sale price when base price changes
        document.getElementById('base_price_usd').addEventListener('input', calculateSalePrice);

        // Image preview functionality
                function previewImages(input) {
            const previewContainer = document.getElementById('image_preview');
            previewContainer.innerHTML = '';
            
            if (input.files && input.files.length > 0) {
                previewContainer.classList.remove('hidden');
                
                Array.from(input.files).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        
                        reader.onload = function(e) {
                            const imageDiv = document.createElement('div');
                            imageDiv.className = 'relative group';
                            imageDiv.innerHTML = `
                                <img src="${e.target.result}" 
                                     alt="Preview ${index + 1}" 
                                     class="w-full h-32 object-cover rounded-lg border border-gray-300 dark:border-gray-600">
                                <button type="button" 
                                        onclick="removePreviewImage(${index})"
                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                                <div class="absolute bottom-2 left-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                    ${file.name}
                                </div>
                            `;
                            previewContainer.appendChild(imageDiv);
                        };
                        
                        reader.readAsDataURL(file);
                    }
                });
            } else {
                previewContainer.classList.add('hidden');
            }
        }

        function removePreviewImage(index) {
            const input = document.getElementById('images');
            const dt = new DataTransfer();
            
            Array.from(input.files).forEach((file, i) => {
                if (i !== index) {
                    dt.items.add(file);
                }
            });
            
            input.files = dt.files;
            previewImages(input);
        }

        // Auto-calculate max occupancy
        function updateMaxOccupancy() {
            const maxAdults = parseInt(document.getElementById('max_adults').value) || 0;
            const maxChildren = parseInt(document.getElementById('max_children').value) || 0;
            const maxOccupancy = document.getElementById('max_occupancy');
            
            maxOccupancy.value = maxAdults + maxChildren;
        }

        document.getElementById('max_adults').addEventListener('input', updateMaxOccupancy);
        document.getElementById('max_children').addEventListener('input', updateMaxOccupancy);

        // Form validation
        function validateForm() {
            let isValid = true;
            const requiredFields = [
                'type_name_en',
                'type_name_vi',
                'category',
                'max_adults',
                'max_occupancy',
                'base_price_usd'
            ];

            requiredFields.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (!field.value.trim()) {
                    field.classList.add('border-red-500');
                    isValid = false;
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            // Validate sale fields if sale is enabled
            if (document.getElementById('is_sale').checked) {
                const salePercentage = document.getElementById('sale_percentage');
                if (!salePercentage.value || parseFloat(salePercentage.value) <= 0) {
                    salePercentage.classList.add('border-red-500');
                    isValid = false;
                } else {
                    salePercentage.classList.remove('border-red-500');
                }
            }

            return isValid;
        }

        // Save as draft functionality
        document.getElementById('save_draft_btn').addEventListener('click', function() {
            const form = document.getElementById('roomTypeForm');
            const isActiveField = document.getElementById('is_active');
            
            // Set as inactive for draft
            isActiveField.checked = false;
            
            // Add draft indicator
            const draftInput = document.createElement('input');
            draftInput.type = 'hidden';
            draftInput.name = 'is_draft';
            draftInput.value = '1';
            form.appendChild(draftInput);
            
            form.submit();
        });

        // Form submission
        document.getElementById('roomTypeForm').addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                showNotification('Please fill in all required fields', 'error');
                return;
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = `
                <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Creating...
            `;
            submitBtn.disabled = true;
        });

        // Notification system
        function showNotification(message, type = 'info') {
            const existingNotifications = document.querySelectorAll('.notification');
            existingNotifications.forEach(notification => notification.remove());

            const notification = document.createElement('div');
            notification.className = `notification fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
            
            const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
            notification.className += ` ${bgColor} text-white`;
            
            notification.innerHTML = `
                <div class="flex items-center">
                    <span class="mr-2">
                        ${type === 'success' ? '✓' : type === 'error' ? '✗' : 'ℹ'}
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
            
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 5000);
        }

        // Drag and drop functionality for images
        const dropZone = document.querySelector('.border-dashed');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('border-violet-500', 'bg-violet-50', 'dark:bg-violet-900/20');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-violet-500', 'bg-violet-50', 'dark:bg-violet-900/20');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            const input = document.getElementById('images');
            
            input.files = files;
            previewImages(input);
        }

        // Auto-save functionality (optional)
        let autoSaveTimer;
        const formInputs = document.querySelectorAll('input, textarea, select');
        
        formInputs.forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(autoSave, 30000); // Auto-save after 30 seconds of inactivity
            });
        });

        function autoSave() {
            const formData = new FormData(document.getElementById('roomTypeForm'));
            formData.append('auto_save', '1');
            
            fetch('{{ route("admin.room-types.auto-save") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Draft saved automatically', 'success');
                }
            })
            .catch(error => {
                console.log('Auto-save failed:', error);
            });
        }
    </script>

    <style>
        .form-input, .form-select, .form-checkbox {
            @apply transition-colors duration-200;
        }
        
        .form-input:focus, .form-select:focus {
            @apply ring-2 ring-violet-500 border-violet-500;
        }
        
        .tab-button {
            @apply transition-all duration-200;
        }
        
        .tab-button.active {
            @apply border-violet-500 text-violet-600 dark:text-violet-400;
        }
        
        .btn {
            @apply transition-all duration-200 transform;
        }
        
        .btn:hover {
            @apply scale-105;
        }
        
        .btn:active {
            @apply scale-95;
        }
        
        /* Custom scrollbar for feature lists */
        .max-h-40::-webkit-scrollbar {
            width: 4px;
        }
        
        .max-h-40::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .max-h-40::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.5);
            border-radius: 2px;
        }
        
        .max-h-40::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 163, 175, 0.7);
        }
        
        /* Animation for image preview */
        #image_preview > div {
            animation: fadeInUp 0.3s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .tab-button {
                @apply text-xs px-2;
            }
            
            .grid-cols-2 {
                @apply grid-cols-1;
            }
            
            .md\:grid-cols-2 {
                @apply grid-cols-1;
            }
        }
    </style>
</x-app-layout>

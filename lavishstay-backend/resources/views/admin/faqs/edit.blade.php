<x-app-layout>
    <style>
        .form-input,
        .form-select,
        .form-checkbox {
            @apply transition-colors duration-200;
        }

        .form-input:focus,
        .form-select:focus {
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
        #image_preview>div {
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

    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Edit FAQ</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Edit FAQ to your hotel inventory</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Back Button -->
                <a href="{{ route('admin.room-types') }}" class="btn bg-gray-500 hover:bg-gray-600 text-white">
                    <i class="fa-solid fa-backward"></i><span class="ml-2">Back to List</span>
                </a>
            </div>
        </div>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Thêm FAQ mới') }}
                </h2>
                <a href="{{ route('admin.faqs') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Quay lại
                </a>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.faqs.update', $faq->faq_id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Câu hỏi tiếng Việt -->
                                <div class="md:col-span-2">
                                    <label for="question_vi"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Câu hỏi (Tiếng Việt) <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="question_vi" id="question_vi" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                        placeholder="Nhập câu hỏi bằng tiếng Việt..." required>{{ old('question_vi', $faq->question_vi) }}</textarea>
                                </div>

                                <!-- Câu hỏi tiếng Anh -->
                                <div class="md:col-span-2">
                                    <label for="question_en"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Câu hỏi (Tiếng Anh) <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="question_en" id="question_en" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                        placeholder="Enter question in English..." required>{{ old('question_en', $faq->question_en) }}</textarea>
                                </div>

                                <!-- Câu trả lời tiếng Việt -->
                                <div class="md:col-span-2">
                                    <label for="answer_vi"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Câu trả lời (Tiếng Việt) <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="answer_vi" id="answer_vi" rows="5"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                        placeholder="Nhập câu trả lời bằng tiếng Việt..." required>{{ old('answer_vi', $faq->answer_vi) }}</textarea>
                                </div>

                                <!-- Câu trả lời tiếng Anh -->
                                <div class="md:col-span-2">
                                    <label for="answer_en"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Câu trả lời (Tiếng Anh) <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="answer_en" id="answer_en" rows="5"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                        placeholder="Enter answer in English..." required>{{ old('answer_en', $faq->answer_en) }}</textarea>
                                </div>

                                <!-- Thứ tự sắp xếp -->
                                <div>
                                    <label for="sort_order"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Thứ tự sắp xếp
                                    </label>
                                    <input type="number" name="sort_order" id="sort_order" min="0"
                                        value="{{ old('sort_order', $faq->sort_order) }}"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                        placeholder="0">
                                </div>

                                <!-- Trạng thái hoạt động -->
                                <div class="">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Status
                                    </label>
                                    <label class="flex mt-3 items-center">
                                        <input type="checkbox" id="is_active" name="is_active" value="1"
                                            {{ old('is_active', $faq->is_active) ? 'checked' : '' }}
                                            {{ old('is_active', true) ? 'checked' : '' }}
                                            class="form-checkbox  h-5 w-5 text-violet-600">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active (visible to
                                            customers)</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="px-6 py-4 border-t mt-5 rounded-b-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">

                                    </div>

                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('admin.faqs') }}"
                                            class="btn bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                            Cancel
                                        </a>

                                        <button type="submit" class="btn bg-violet-500 hover:bg-violet-600 text-white">

                                            Update FAQ
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

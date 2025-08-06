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
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">FAQs Management</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Manage all FAQs and their configurations</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Add room type button -->
                <a href="{{ route('admin.faqs.create') }}">
                    <button
                        class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                        <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                            <path
                                d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                        </svg>
                        <span class="max-xs:sr-only">Add FAQs</span>
                    </button>
                </a>
            </div>

        </div>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Quản lý FAQs') }}
                </h2>
                <a href="{{ route('admin.faqs.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Thêm FAQ mới
                </a>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="">
                <div class="bg-white dark:bg-gray-800  shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        @if (session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="">
                            <table class="w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-700">
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            ID
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Câu hỏi (VI)
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Câu hỏi (EN)
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Thứ tự
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Trạng thái
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Ngày tạo
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Hành động
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($faqs as $faq)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $faq->faq_id }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                <div class="max-w-xs truncate">
                                                    {{ Str::limit($faq->question_vi, 50) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                <div class="max-w-xs truncate">
                                                    {{ Str::limit($faq->question_en, 50) }}
                                                </div>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $faq->sort_order }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <button onclick="toggleStatus({{ $faq->faq_id }})"
                                                    class="status-toggle px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $faq->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $faq->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                                </button>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $faq->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap text-center">
                                                <div class="relative inline-block text-left">
                                                    <button type="button"
                                                        class="button-action inline-flex items-center justify-center w-8 h-8 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-colors duration-200"
                                                        onclick="toggleDropdown({{ $faq->faq_id }})"
                                                        id="dropdown-button-{{ $faq->faq_id }}">
                                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                                            </path>
                                                        </svg>
                                                    </button>

                                                    <!-- Dropdown Menu -->
                                                    <div id="dropdown-menu-{{ $faq->faq_id }}"
                                                        class="hidden menu-button-action absolute right-0 z-50 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                                        <div class="py-1 z-500" role="menu">
                                                            <!-- View Details -->
                                                            <button
                                                                onclick="toggleDetails({{ $faq->faq_id }}); closeDropdown({{ $faq->faq_id }})"
                                                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                                role="menuitem">

                                                                View Details
                                                            </button>

                                                            <!-- Edit -->
                                                            <a href="{{ route('admin.faqs.edit', $faq->faq_id) }}"
                                                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                                role="menuitem">
                                                                Edit FAQ
                                                            </a>


                                                            <!-- Divider -->
                                                            <div class="border-t border-gray-100 dark:border-gray-700">
                                                            </div>

                                                            <!-- Delete -->
                                                            <button
                                                                onclick="deleteFAQ({{ $faq->faq_id }}); closeDropdown({{ $faq->faq_id }})"
                                                                class="flex mt-2 items-center w-full px-4 py-2 cursor-pointer text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150"
                                                                role="menuitem">
                                                                <svg style="width: 20px; align-items: center" class=" mr-3" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                    </path>
                                                                </svg>
                                                                Delete FAQ
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Expandable Details Row -->
                                        <tr id="details-{{ $faq->faq_id }}"
                                            class="hidden bg-gray-50 dark:bg-gray-700/50">
                                            <td colspan="9" class="px-5 py-4">
                                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                                    <!-- Left Column -->
                                                    <div class="space-y-4">
                                                        <!-- Descriptions -->
                                                        <div>
                                                            <h4
                                                                class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                                The Question</h4>
                                                            <div class="space-y-2">
                                                                @if ($faq->question_en)
                                                                    <div>
                                                                        <span
                                                                            class="text-xs font-medium text-gray-500 dark:text-gray-400">English:</span>
                                                                        <p
                                                                            class="text-sm  text-green-600 dark:text-green-400 ">
                                                                            {{ Str::limit($faq->question_en, 150) }}
                                                                        </p>
                                                                    </div>
                                                                @endif
                                                                @if ($faq->question_vi)
                                                                    <div>
                                                                        <span
                                                                            class="text-xs font-medium text-gray-500 dark:text-gray-400">Vietnamese:</span>
                                                                        <p
                                                                            class="text-sm  text-green-600 dark:text-green-400 ">
                                                                            {{ Str::limit($faq->question_vi, 150) }}
                                                                        </p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <!-- Room Features -->
                                                        @if ($faq->answer_en || $faq->answer_vi)
                                                            <div>
                                                                <h4
                                                                    class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                                    The Answer</h4>
                                                                <div class="space-y-2">
                                                                    @if ($faq->answer_en)
                                                                        <div>
                                                                            <span
                                                                                class="text-xs font-medium text-gray-500 dark:text-gray-400">English:</span>
                                                                            <div class="flex flex-wrap gap-1 mt-1">
                                                                                <span
                                                                                    class="inline-flex items-center font-normal py-1 rounded-full text-xs bg-green-100 dark:bg-green-400/30 text-green-600 dark:text-green-400">
                                                                                    {{ $faq->answer_en }}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                    @if ($faq->answer_vi)
                                                                        <div>
                                                                            <span
                                                                                class="text-xs font-medium text-gray-500 dark:text-gray-400">Vietnamese:</span>
                                                                            <div class="flex flex-wrap gap-1 mt-1">
                                                                                 <span
                                                                                    class="inline-flex items-center font-normal  py-1 rounded-full text-xs bg-green-100 dark:bg-green-400/30 text-green-600 dark:text-green-400">
                                                                                    {{ $faq->answer_vi }}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Right Column -->
                                                    <div class="space-y-4">
                                                       
                                                        <!-- Timestamps -->
                                                        <div>
                                                            <h4
                                                                class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                                Timestamps</h4>
                                                            <div
                                                                class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                                                <div>Created:
                                                                    {{ $faq->created_at->format('M d, Y H:i') }}
                                                                </div>
                                                                <div>Updated:
                                                                    {{ $faq->updated_at->format('M d, Y H:i') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7"
                                                class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                                Chưa có FAQ nào được tạo.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $faqs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Toggle FAQ details - chỉ giữ lại một hàm duy nhất
    function toggleDetails(faqId) {
        const detailsRow = document.getElementById(`details-${faqId}`);
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

    // Toggle dropdown menu
    function toggleDropdown(faqId) {
        const dropdown = document.getElementById(`dropdown-menu-${faqId}`);
        const allDropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');

        // Close all other dropdowns
        allDropdowns.forEach(menu => {
            if (menu.id !== `dropdown-menu-${faqId}`) {
                menu.classList.add('hidden');
            }
        });

        // Toggle current dropdown
        dropdown.classList.toggle('hidden');
    }

    // Close dropdown
    function closeDropdown(faqId) {
        const dropdown = document.getElementById(`dropdown-menu-${faqId}`);
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

    // Toggle FAQ status
    function toggleStatus(faqId) {
        if (confirm('Bạn có chắc chắn muốn thay đổi trạng thái FAQ này?')) {
            fetch(`/admin/faqs/toggle-status/${faqId}`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi cập nhật trạng thái!');
            });
        }
    }

    // Delete FAQ
    function deleteFAQ(faqId) {
        if (confirm('Bạn có chắc chắn muốn xóa FAQ này? Hành động này không thể hoàn tác!')) {
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/faqs/destroy/${faqId}`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>


</x-app-layout>

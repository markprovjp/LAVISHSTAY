<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full mx-auto">
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Chi tiết Bảng: {{ $table }}</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Quản lý bản dịch cho bảng {{ $table }}</p>
            </div>
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a href="{{ route('admin.translation.index') }}">
                    <button class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white cursor-pointer" >
                        <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                            <path d="M7 16l-4-4h3V4h2v8h3z"/>
                        </svg>
                        <span class="max-xs:sr-only">Quay lại</span>
                    </button>
                </a>
                <button onclick="openAddModal()" class="btn bg-violet-500 hover:bg-violet-600 text-white">
                    Thêm Bản dịch
                </button>
            </div>
        </div>

        <div class="py-12">
            <div class="">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        @if (session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="w-full">
                            <table class="w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-700">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID BẢN DỊCH</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">CỘT</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID BẢN GHI</th>
                                        @foreach ($languages as $lang)
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ $lang->name }}</th>
                                        @endforeach
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">HÀNH ĐỘNG</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @php
                                        $records = $translations->groupBy('record_id');
                                    @endphp
                                    @foreach ($records as $recordId => $recordTranslations)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $recordTranslations->first()->translation_id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $recordTranslations->first()->column_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $recordId }}
                                            </td>
                                            @foreach ($languages as $lang)
                                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100" data-translation-id="{{ $recordTranslations->firstWhere('language_code', $lang->language_code)?->translation_id }}" data-value="{{ $recordTranslations->firstWhere('language_code', $lang->language_code)?->value ?? 'Chưa dịch' }}">
                                                    {{ $recordTranslations->firstWhere('language_code', $lang->language_code)?->value ?? 'Chưa dịch' }}
                                                </td>
                                            @endforeach
                                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap text-center">
                                                <div class="relative inline-block text-left">
                                                    <button type="button" class="button-action inline-flex items-center justify-center w-8 h-8 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-colors duration-200" onclick="toggleDropdown('{{ $recordTranslations->first()->translation_id }}')" id="dropdown-button-{{ $recordTranslations->first()->translation_id }}">
                                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                        </svg>
                                                    </button>
                                                    <div id="dropdown-menu-{{ $recordTranslations->first()->translation_id }}" class="hidden menu-button-action absolute right-0 z-50 mt-2 w-auto min-w-[160px] max-w-[200px] bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                                        <div class="py-1" role="menu">
                                                            <button onclick="openEditModal('{{ $recordTranslations->first()->translation_id }}', '{{ $recordTranslations->first()->table_name }}', '{{ $recordTranslations->first()->column_name }}', '{{ $recordId }}', '{{ $recordTranslations->first()->language_code }}', '{{ $recordTranslations->first()->value ?? '' }}')" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150" role="menuitem">Sửa bản dịch</button>
                                                            <div class="relative">
                                                                <button onclick="toggleLanguageDropdown('{{ $recordTranslations->first()->translation_id }}')" class="flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150" role="menuitem">
                                                                    <svg style="width: 20px; align-items: center" class="mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                    </svg>
                                                                    Xóa Bản dịch
                                                                </button>
                                                                <div id="language-dropdown-{{ $recordTranslations->first()->translation_id }}" class="hidden absolute left-0 top-full mt-1 w-auto min-w-[160px] max-w-[200px] bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 ring-1 ring-black ring-opacity-5">
                                                                    @foreach ($languages as $lang)
                                                                        <button onclick="deleteTranslation('{{ $recordTranslations->first()->translation_id }}', '{{ $recordId }}', '{{ $lang->language_code }}')" class="flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150" role="menuitem">
                                                                            Xóa {{ $lang->name }}
                                                                        </button>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <button onclick="deleteRecordTranslations('{{ $table }}', '{{ $recordId }}')" class="flex mt-2 items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150" role="menuitem">
                                                                <svg style="width: 20px; align-items: center" class="mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                </svg>
                                                                Xóa Tất cả Bản dịch của Record
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Thêm Bản dịch -->
        <div id="add-translation-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 overflow-auto">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-sm min-h-[300px]">
                <h2 class="text-lg font-semibold mb-4">Thêm Bản dịch</h2>
                <form id="add-translation-form" action="{{ route('admin.translation.add-for-table', $table) }}" method="POST">
                    @csrf
                    <input type="hidden" name="table" value="{{ $table }}">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Cột</label>
                            <select name="column_name" class="border w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 
 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                                @php
                                    try {
                                        $columns = DB::getSchemaBuilder()->getColumnListing($table);
                                    } catch (\Exception $e) {
                                        $columns = [];
                                    }
                                @endphp
                                @foreach ($columns as $column)
                                    <option value="{{ $column }}">{{ $column }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">ID Bản ghi</label>
                            <input type="text" name="record_id" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm 
 placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500" placeholder="Nhập ID bản ghi">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Ngôn ngữ</label>
                            <select name="language_code" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm 
 placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                                @foreach ($languages as $lang)
                                    <option value="{{ $lang->language_code }}">{{ $lang->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Giá trị</label>
                            <input type="text" name="value" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm 
 placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                        </div>
                    </div>
                    <div class="flex justify-end gap-4 mt-6">
                        <button type="button" onclick="closeAddModal()" class="px-6 py-3 bg-red-400 text-white rounded-lg font-semibold shadow-md hover:bg-red-500 focus:outline-none focus:ring-4 focus:ring-red-300 transition duration-200 min-w-[100px]">
                            Hủy
                        </button>
                        <button type="submit" class="px-6 py-3 btn bg-violet-500 hover:bg-violet-600 text-white rounded-lg 
                        font-semibold shadow-md  focus:outline-none focus:ring-4 focus:ring-green-300 transition duration-200 min-w-[100px]">
                            Lưu
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Sửa Bản dịch -->
        <div id="edit-translation-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 overflow-auto">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-sm min-h-[300px]">
                <h2 class="text-lg font-semibold mb-4">Sửa Bản dịch</h2>
                <form id="edit-translation-form">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" id="edit-translation-id" name="translation_id">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium">Cột</label>
                            <input type="text" id="edit-column-name" name="column_name" class="border w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 
 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">ID Bản ghi</label>
                            <input type="text" id="edit-record-id" name="record_id" class="border w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 
 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500" readonly>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium">Ngôn ngữ</label>
                            <select id="edit-language-code" name="language_code" class="border w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 
 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                                @foreach ($languages as $lang)
                                    <option value="{{ $lang->language_code }}">{{ $lang->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Giá trị</label>
                            <input type="text" id="edit-value" name="value" class="border w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 
 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                        </div>
                    </div>
                    <div id="edit-error-message" class="hidden text-red-600 text-sm mt-2"></div>
                    <div class="flex justify-end gap-4 mt-6">
                        <button type="button" onclick="closeEditModal()" class="px-6 py-3 bg-red-400 text-white rounded-lg font-semibold shadow-md hover:bg-red-500 focus:outline-none focus:ring-4 focus:ring-red-300 transition duration-200 min-w-[100px]">
                            Hủy
                        </button>
                        <button type="submit" class="px-6 py-3 btn bg-violet-500 hover:bg-violet-600 text-white rounded-lg 
                        font-semibold shadow-md  focus:outline-none focus:ring-4 focus:ring-green-300 transition duration-200 min-w-[100px]">
                            Lưu
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openAddModal() {
                document.getElementById('add-translation-modal').classList.remove('hidden');
            }

            function closeAddModal() {
                document.getElementById('add-translation-modal').classList.add('hidden');
            }

            document.getElementById('add-translation-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const data = {};
                formData.forEach((value, key) => {
                    data[key] = value;
                });

                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeAddModal();
                        location.reload();
                    } else {
                        alert(data.error || 'Đã xảy ra lỗi không xác định');
                    }
                })
                .catch(error => console.error('Error:', error));
            });

            function openEditModal(translationId, tableName, columnName, recordId, languageCode, value) {
                const editModal = document.getElementById('edit-translation-modal');
                const errorMessage = document.getElementById('edit-error-message');
                errorMessage.classList.add('hidden');
                errorMessage.textContent = '';

                document.getElementById('edit-translation-id').value = translationId;
                document.getElementById('edit-column-name').value = columnName;
                document.getElementById('edit-record-id').value = recordId;
                const languageSelect = document.getElementById('edit-language-code');
                languageSelect.value = languageCode;
                const valueInput = document.getElementById('edit-value');
                valueInput.value = value || '';

                const fetchTranslationValue = function() {
                    const selectedLanguage = this.value;
                    const url = `/admin/translation/get-value/${encodeURIComponent(tableName)}/${encodeURIComponent(columnName)}/${recordId}/${selectedLanguage}`;
                    console.log('Fetching translation from:', url);
                    valueInput.disabled = true;
                    valueInput.value = 'Đang tải...';
                    errorMessage.classList.add('hidden');

                    fetch(url, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        valueInput.disabled = false;
                        if (data.success && data.value !== null) {
                            valueInput.value = data.value;
                            document.getElementById('edit-translation-id').value = data.translation_id || '';
                        } else {
                            valueInput.value = '';
                            document.getElementById('edit-translation-id').value = '';
                            errorMessage.textContent = 'Chưa có bản dịch cho ngôn ngữ này';
                            errorMessage.classList.remove('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        valueInput.disabled = false;
                        valueInput.value = '';
                        document.getElementById('edit-translation-id').value = '';
                        errorMessage.textContent = 'Lỗi khi tải giá trị: ' + error.message;
                        errorMessage.classList.remove('hidden');
                    });
                };

                if (languageSelect._fetchTranslationValue) {
                    languageSelect.removeEventListener('change', languageSelect._fetchTranslationValue);
                }
                languageSelect.addEventListener('change', fetchTranslationValue);
                languageSelect._fetchTranslationValue = fetchTranslationValue;

                editModal.classList.remove('hidden');
                languageSelect.dispatchEvent(new Event('change'));
            }

            function closeEditModal() {
                const languageSelect = document.getElementById('edit-language-code');
                if (languageSelect._fetchTranslationValue) {
                    languageSelect.removeEventListener('change', languageSelect._fetchTranslationValue);
                }
                document.getElementById('edit-translation-modal').classList.add('hidden');
            }

            document.getElementById('edit-translation-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const errorMessage = document.getElementById('edit-error-message');
                errorMessage.classList.add('hidden');
                errorMessage.textContent = '';

                const translationId = document.getElementById('edit-translation-id').value;
                const newValue = document.getElementById('edit-value').value;
                const languageCode = document.getElementById('edit-language-code').value;
                const columnName = document.getElementById('edit-column-name').value;
                const recordId = document.getElementById('edit-record-id').value;
                const tableName = '{{ $table }}';

                if (!languageCode) {
                    errorMessage.textContent = 'Vui lòng chọn một ngôn ngữ';
                    errorMessage.classList.remove('hidden');
                    return;
                }

                if (!newValue.trim()) {
                    errorMessage.textContent = 'Vui lòng nhập giá trị bản dịch';
                    errorMessage.classList.remove('hidden');
                    return;
                }

                const url = translationId
                    ? `/admin/translation/${translationId}/update-value`
                    : `/admin/translation/${tableName}/add`;
                const method = translationId ? 'PATCH' : 'POST';
                const data = translationId
                    ? { value: newValue }
                    : { column_name: columnName, record_id: recordId, language_code: languageCode, value: newValue };

                fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeEditModal();
                        location.reload();
                    } else {
                        errorMessage.textContent = data.error || 'Cập nhật thất bại';
                        errorMessage.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    errorMessage.textContent = 'Lỗi khi cập nhật: ' + error.message;
                    errorMessage.classList.remove('hidden');
                });
            });

            function toggleDropdown(translationId) {
                const dropdown = document.getElementById(`dropdown-menu-${translationId}`);
                if (dropdown) {
                    dropdown.classList.toggle('hidden');
                    // Điều chỉnh vị trí để tránh tràn
                    const buttonRect = document.getElementById(`dropdown-button-${translationId}`).getBoundingClientRect();
                    const dropdownRect = dropdown.getBoundingClientRect();
                    const windowWidth = window.innerWidth;
                    const windowHeight = window.innerHeight;

                    if (buttonRect.right + dropdownRect.width > windowWidth) {
                        dropdown.style.right = '0';
                        dropdown.style.left = 'auto';
                    } else {
                        dropdown.style.right = 'auto';
                        dropdown.style.left = buttonRect.left + 'px';
                    }

                    if (buttonRect.bottom + dropdownRect.height > windowHeight) {
                        dropdown.style.top = 'auto';
                        dropdown.style.bottom = '100%';
                    } else {
                        dropdown.style.top = '100%';
                        dropdown.style.bottom = 'auto';
                    }
                }
            }

            function toggleLanguageDropdown(translationId) {
                const languageDropdown = document.getElementById(`language-dropdown-${translationId}`);
                if (languageDropdown) {
                    languageDropdown.classList.toggle('hidden');
                    // Điều chỉnh vị trí để tránh tràn
                    const parentDropdown = document.getElementById(`dropdown-menu-${translationId}`);
                    const buttonRect = parentDropdown.getBoundingClientRect();
                    const dropdownRect = languageDropdown.getBoundingClientRect();
                    const windowWidth = window.innerWidth;
                    const windowHeight = window.innerHeight;

                    if (buttonRect.right + dropdownRect.width > windowWidth) {
                        languageDropdown.style.right = '0';
                        languageDropdown.style.left = 'auto';
                    } else {
                        languageDropdown.style.right = 'auto';
                        languageDropdown.style.left = buttonRect.left + 'px';
                    }

                    if (buttonRect.bottom + dropdownRect.height > windowHeight) {
                        languageDropdown.style.top = 'auto';
                        languageDropdown.style.bottom = '100%';
                    } else {
                        languageDropdown.style.top = '100%';
                        languageDropdown.style.bottom = 'auto';
                    }
                }
            }

            document.addEventListener('click', function(event) {
                const dropdowns = document.querySelectorAll('[id^="dropdown-menu-"], [id^="language-dropdown-"]');
                const buttons = document.querySelectorAll('[id^="dropdown-button-"]');
                let clickedInsideDropdown = false;

                dropdowns.forEach(dropdown => {
                    if (dropdown.contains(event.target)) clickedInsideDropdown = true;
                });

                buttons.forEach(button => {
                    if (button.contains(event.target)) clickedInsideDropdown = true;
                });

                if (!clickedInsideDropdown) dropdowns.forEach(dropdown => dropdown.classList.add('hidden'));
            });

            function deleteTranslation(translationId, recordId, languageCode) {
                if (confirm(`Bạn có chắc chắn muốn xóa bản dịch ngôn ngữ ${languageCode} không? Hành động này không thể hoàn tác!`)) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                    if (!csrfToken) {
                        console.error('CSRF token not found');
                        alert('Không tìm thấy CSRF token');
                        return;
                    }
                    console.log('Requesting deletion:', { translationId, recordId, languageCode, csrfToken, url: `/admin/translation/destroy/${translationId}/language/${languageCode}` });
                    fetch(`/admin/translation/destroy/${translationId}/language/${languageCode}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        if (data.success) {
                            showToast(data.message || 'Xóa bản dịch thành công!');
                            setTimeout(() => location.reload(), 2000);
                        } else {
                            alert(data.error || 'Xóa bản dịch thất bại');
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        alert('Lỗi khi xóa bản dịch: ' + error.message);
                    });
                }
            }

            function deleteRecordTranslations(table, recordId) {
                if (confirm('Bạn có chắc chắn muốn xóa tất cả bản dịch của record này? Hành động này không thể hoàn tác!')) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                    if (!csrfToken) {
                        console.error('CSRF token not found');
                        alert('Không tìm thấy CSRF token');
                        return;
                    }
                    console.log('Deleting all translations:', { table, recordId });
                    fetch(`/admin/translation/${table}/destroy-record/${recordId}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        if (data.success) {
                            alert('Xóa tất cả bản dịch thành công!');
                            location.reload();
                        } else {
                            alert(data.error || 'Xóa tất cả bản dịch thất bại');
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        alert('Lỗi khi xóa tất cả bản dịch: ' + error.message);
                    });
                }
            }

            function showToast(message) {
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-5 right-5 z-50 bg-green-500 text-white px-4 py-3 rounded-md shadow-lg transition-opacity duration-300 opacity-0';
                toast.textContent = message;
                document.body.appendChild(toast);
                setTimeout(() => {
                    toast.classList.add('opacity-100');
                    setTimeout(() => {
                        toast.classList.remove('opacity-100');
                        toast.classList.add('opacity-0');
                        setTimeout(() => toast.remove(), 300);
                    }, 2000); // Hiển thị trong 2 giây
                }, 10);
            }
        </script>
    </div>
</x-app-layout> 
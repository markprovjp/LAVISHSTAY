<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <nav class="flex mb-3" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.room-types') }}"
                               class="text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                                Quản lý loại phòng
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('admin.room-types.show', $roomType) }}"
                                   class="ml-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">{{ $roomType->name }}</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-gray-500 dark:text-gray-400">{{ $package->name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Quản lý dịch vụ - {{ $package->name }}</h1>
            </div>
        </div>

        <!-- Services Management -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">
                    Danh sách dịch vụ
                    <span class="text-gray-400 dark:text-gray-500 font-medium">({{ $package->services->count() }})</span>
                </h2>
            </div>
            <div class="p-6">
                @if ($package->services->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($package->services as $service)
                            <div class="service-item relative p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:shadow-md transition-all duration-200"
                                 data-service-id="{{ $service->service_id }}">
                                <div class="flex justify-between items-start gap-6">
                                    <input type="checkbox" class="service-checkbox" value="{{ $service->service_id }}" onchange="toggleServiceSelection({{ $service->service_id }})">
                                </div>
                                <div class="mt-2 flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        @if ($service->unit)
                                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                                <span class="text-green-600 dark:text-green-400 text-lg">{{ substr($service->unit, 0, 1) }}</span>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">{{ $service->name }}</h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ $service->price_with_unit }}</p>
                                    </div>
                                    <div class="flex-shrink-0 flex flex-col space-y-1">
                                        <button onclick="removeService({{ $service->service_id }})" class="p-1 text-red-600 hover:text-red-700 hover:bg-red-50 rounded transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div id="bulkActions" class="mt-4 space-x-2 hidden">
                        <button onclick="removeSelected()" class="btn-sm bg-red-500 hover:bg-red-600 text-white">
                            Xóa đã chọn
                        </button>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Chưa có dịch vụ nào</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">Thêm dịch vụ vào gói để quản lý.</p>
                    </div>
                @endif

                <!-- Action Section for Adding Services -->
                <div class="mt-6 text-center">
                    @php
                        $allServicesAdded = $availableServices->flatten()->every(function ($service) use ($package) {
                            return $package->services->contains('service_id', $service->service_id);
                        });
                    @endphp
                    @if($allServicesAdded)
                        <div class="text-center py-4">
                            <p class="text-gray-500 dark:text-gray-400">Tất cả dịch vụ đã được thêm vào gói {{ $package->name }}.</p>
                        </div>
                    @else
                        <button id="addServiceButton" onclick="openAddServiceModal()"
                                class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Thêm dịch vụ
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Add Service Modal -->
        <div id="addServiceModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl mt-10 w-full max-w-7xl max-h-[90vh] flex flex-col">
                    <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Thêm dịch vụ vào {{ $package->name }}</h3>
                        </div>
                        <button onclick="closeAddServiceModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="flex-1 overflow-y-auto p-6 modal-body">
                        @php
                            $allServicesAdded = $availableServices->flatten()->every(function ($service) use ($package) {
                                return $package->services->contains('service_id', $service->service_id);
                            });
                        @endphp
                        @if($availableServices->flatten()->count() > 0 && !$allServicesAdded)
                            @foreach($availableServices as $unit => $services)
                                <div class="mb-8">
                                    <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4">
                                        {{ $unit }} ({{ $services->filter()->count() }} dịch vụ)
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($services as $service)
                                            @if(is_object($service) && $service instanceof \App\Models\Service)
                                                @php
                                                    $isAdded = $package->services->contains('service_id', $service->service_id);
                                                @endphp
                                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-all duration-200 {{ $isAdded ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                     data-service-id="{{ $service->service_id }}">
                                                    <div class="flex items-start space-x-3">
                                                        <input type="checkbox"
                                                               class="modal-service-checkbox"
                                                               value="{{ $service->service_id }}"
                                                               data-service-id="{{ $service->service_id }}"
                                                               onchange="toggleModalSelection({{ $service->service_id }})"
                                                               {{ $isAdded ? 'disabled' : '' }}>
                                                        <div class="flex-shrink-0">
                                                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                                                <span class="text-green-600 dark:text-green-400 text-lg">{{ substr($service->unit, 0, 1) }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <h5 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $service->name }}</h5>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $service->price_with_unit }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Tất cả dịch vụ đã được thêm</h3>
                                <p class="text-gray-500 dark:text-gray-400">Không còn dịch vụ nào để thêm vào gói {{ $package->name }}.</p>
                            </div>
                        @endif
                    </div>
                    @if($availableServices->flatten()->count() > 0 && !$allServicesAdded)
                    <div class="flex items-center justify-end p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 space-x-3">
                        <button onclick="closeAddServiceModal()"
                                class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-500">
                            Hủy
                        </button>
                        <button onclick="addSelectedServices()"
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                            Thêm đã chọn
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 flex items-center space-x-3">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div>
                <span class="text-gray-700 dark:text-gray-300">Đang xử lý...</span>
            </div>
        </div>

        <!-- JavaScript -->
        <script>
            const packageId = {{ $package->package_id }};
            let selectedServices = new Set();
            let modalSelectedServices = new Set();

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            function openAddServiceModal() {
                document.getElementById('addServiceModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                modalSelectedServices.clear();
                updateModalSelectionUI();
            }

            function closeAddServiceModal() {
                document.getElementById('addServiceModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
                modalSelectedServices.clear();
                document.querySelectorAll('.modal-service-checkbox').forEach(cb => cb.checked = false);
                updateModalSelectionUI();
            }

            function toggleServiceSelection(serviceId) {
                if (selectedServices.has(serviceId)) {
                    selectedServices.delete(serviceId);
                } else {
                    selectedServices.add(serviceId);
                }
                updateBulkActionsUI();
            }

            function toggleModalSelection(serviceId) {
                const checkbox = document.querySelector(`.modal-service-checkbox[value="${serviceId}"]`);
                if (checkbox && !checkbox.disabled) {
                    if (modalSelectedServices.has(serviceId)) {
                        modalSelectedServices.delete(serviceId);
                    } else {
                        modalSelectedServices.add(serviceId);
                    }
                    updateModalSelectionUI();
                }
            }

            function updateModalSelectionUI() {
                const counter = document.getElementById('modalSelectionCounter');
                if (counter) counter.textContent = `${modalSelectedServices.size} đã chọn`;
            }

            function updateBulkActionsUI() {
                const bulkActions = document.getElementById('bulkActions');
                if (bulkActions) {
                    bulkActions.classList.toggle('hidden', selectedServices.size === 0);
                }
            }

            async function removeService(serviceId) {
                if (!confirm('Bạn có chắc chắn muốn xóa dịch vụ này?')) return;
                showLoading();
                try {
                    const response = await fetch(`/admin/room-types/packages/${packageId}/services/${serviceId}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrfToken },
                    });
                    const data = await response.json();
                    if (data.success) {
                        showNotification(data.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification(data.message, 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('Có lỗi xảy ra khi xóa dịch vụ.', 'error');
                } finally {
                    hideLoading();
                }
            }

            async function removeSelected() {
                if (selectedServices.size === 0) {
                    showNotification('Vui lòng chọn ít nhất một dịch vụ.', 'warning');
                    return;
                }
                if (!confirm(`Bạn có chắc chắn muốn xóa ${selectedServices.size} dịch vụ đã chọn?`)) return;
                showLoading();
                try {
                    const promises = Array.from(selectedServices).map(serviceId =>
                        fetch(`/admin/room-types/packages/${packageId}/services/${serviceId}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': csrfToken },
                        })
                    );
                    const responses = await Promise.all(promises);
                    const results = await Promise.all(responses.map(r => r.json()));
                    const successCount = results.filter(r => r.success).length;
                    if (successCount > 0) {
                        showNotification(`Đã xóa ${successCount} dịch vụ!`, 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification('Không có dịch vụ nào được xóa.', 'warning');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('Có lỗi xảy ra khi xóa dịch vụ.', 'error');
                } finally {
                    hideLoading();
                }
            }

            async function addSelectedServices() {
                if (modalSelectedServices.size === 0) {
                    showNotification('Vui lòng chọn ít nhất một dịch vụ.', 'warning');
                    return;
                }
                showLoading();
                try {
                    const response = await fetch(`/admin/room-types/packages/${packageId}/services`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify({ service_ids: Array.from(modalSelectedServices) })
                    });
                    const data = await response.json();
                    if (data.success) {
                        showNotification(data.message, 'success');
                        closeAddServiceModal();
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification(data.message, 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('Có lỗi xảy ra khi thêm dịch vụ.', 'error');
                } finally {
                    hideLoading();
                }
            }

            function showLoading() {
                document.getElementById('loadingOverlay').classList.remove('hidden');
            }

            function hideLoading() {
                document.getElementById('loadingOverlay').classList.add('hidden');
            }

            function showNotification(message, type = 'info') {
                document.querySelectorAll('.notification').forEach(n => n.remove());
                const notification = document.createElement('div');
                notification.className = `notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full max-w-sm`;
                switch (type) {
                    case 'success': notification.className += ' bg-green-500 text-white'; break;
                    case 'error': notification.className += ' bg-red-500 text-white'; break;
                    case 'warning': notification.className += ' bg-yellow-500 text-white'; break;
                    default: notification.className += ' bg-blue-500 text-white';
                }
                notification.innerHTML = `
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium">${message}</span>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                `;
                document.body.appendChild(notification);
                setTimeout(() => { notification.classList.remove('translate-x-full'); }, 100);
                setTimeout(() => { notification.classList.add('translate-x-full'); setTimeout(() => notification.remove(), 300); }, 5000);
            }

            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('addServiceModal').addEventListener('click', function(e) {
                    if (e.target === this) closeAddServiceModal();
                });
                updateBulkActionsUI();
            });
        </script>

        <style>
            .modal-body::-webkit-scrollbar { width: 6px; }
        </style>
    </div>
</x-app-layout>
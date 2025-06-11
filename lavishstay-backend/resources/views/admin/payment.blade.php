<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Quản lý thanh toán</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div x-data="adminPanel()" x-init="init()">
        <!-- Header -->
        <header class="bg-blue-600 text-white shadow-lg">
            <div class="container mx-auto px-4 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <i class="fas fa-university text-2xl"></i>
                        <h1 class="text-xl font-bold">Admin - Quản lý thanh toán</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm">
                            <i class="fas fa-clock mr-1"></i>
                            Cập nhật: <span x-text="lastUpdate"></span>
                        </span>
                        <button @click="fetchBookings()" 
                                class="bg-blue-500 hover:bg-blue-400 px-4 py-2 rounded transition-colors">
                            <i class="fas fa-sync-alt mr-1" :class="{'animate-spin': loading}"></i>
                            Refresh
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto px-4 py-8">
            <!-- Instructions -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-400 mt-1 mr-3"></i>
                    <div>
                        <h3 class="text-lg font-medium text-blue-800 mb-2">Hướng dẫn sử dụng</h3>
                        <ul class="text-blue-700 text-sm space-y-1">
                            <li><strong>Bước 1:</strong> Khách hàng quét QR và chuyển khoản</li>
                            <li><strong>Bước 2:</strong> Kiểm tra Internet Banking để xác nhận giao dịch</li>
                            <li><strong>Bước 3:</strong> Click "Xác nhận" để hoàn tất booking</li>
                            <li><strong>Lưu ý:</strong> Trang tự động refresh mỗi 30 giây</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Danh sách booking chờ thanh toán</h2>
                        <p class="text-gray-600">Có <span x-text="bookings.length" class="font-bold text-blue-600"></span> booking đang chờ xác nhận</p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Thông tin ngân hàng</div>
                        <div class="font-bold">MB Bank - 0335920306</div>
                        <div class="text-sm text-gray-600">NGUYEN VAN QUYEN</div>
                    </div>
                </div>
            </div>

            <!-- Bookings Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mã booking
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Khách hàng
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Số tiền
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nội dung CK
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Thời gian
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Hành động
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="booking in bookings" :key="booking.id">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-blue-600" x-text="booking.booking_code"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="font-medium text-gray-900" x-text="booking.customer_name"></div>
                                            <div class="text-sm text-gray-500" x-text="booking.customer_phone"></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-lg font-bold text-red-600" x-text="formatVND(booking.total_amount)"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <code class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm" x-text="'LAVISH ' + booking.booking_code"></code>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div x-text="formatDate(booking.created_at)"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <button @click="viewDetails(booking)" 
                                                class="text-blue-600 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 px-3 py-1 rounded transition-colors">
                                            <i class="fas fa-eye mr-1"></i>Chi tiết
                                        </button>
                                        <button @click="confirmPayment(booking.booking_code)" 
                                                :disabled="confirmingBooking === booking.booking_code"
                                                class="text-white bg-green-600 hover:bg-green-700 disabled:bg-gray-400 px-3 py-1 rounded transition-colors">
                                            <i class="fas fa-check mr-1" :class="{'animate-spin': confirmingBooking === booking.booking_code}"></i>
                                            <span x-text="confirmingBooking === booking.booking_code ? 'Đang xử lý...' : 'Xác nhận'"></span>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    
                    <!-- Empty State -->
                    <div x-show="bookings.length === 0 && !loading" class="text-center py-12">
                        <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Không có booking nào</h3>
                        <p class="text-gray-500">Tất cả booking đã được xử lý hoặc chưa có booking mới</p>
                    </div>

                    <!-- Loading State -->
                    <div x-show="loading" class="text-center py-12">
                        <i class="fas fa-spinner animate-spin text-blue-600 text-4xl mb-4"></i>
                        <p class="text-gray-600">Đang tải dữ liệu...</p>
                    </div>
                </div>
            </div>
        </main>

        <!-- Detail Modal -->
        <div x-show="showModal" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <!-- Modal Header -->
                    <div class="flex justify-between items-center pb-4 border-b">
                        <h3 class="text-lg font-bold text-gray-900">
                            Chi tiết booking <span x-text="selectedBooking?.booking_code" class="text-blue-600"></span>
                        </h3>
                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Modal Content -->
                    <div class="py-4" x-show="selectedBooking">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Khách hàng</label>
                                <p class="text-gray-900" x-text="selectedBooking?.customer_name"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="text-gray-900" x-text="selectedBooking?.customer_email"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Điện thoại</label>
                                <p class="text-gray-900" x-text="selectedBooking?.customer_phone"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Số tiền</label>
                                <p class="text-red-600 font-bold text-lg" x-text="formatVND(selectedBooking?.total_amount)"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Check-in</label>
                                <p class="text-gray-900" x-text="selectedBooking?.check_in"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Check-out</label>
                                <p class="text-gray-900" x-text="selectedBooking?.check_out"></p>
                            </div>
                        </div>

                        <!-- Payment Info -->
                        <div class="bg-gray-50 p-4 rounded-lg mb-4">
                            <h4 class="font-medium text-gray-900 mb-3">Thông tin chuyển khoản</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Ngân hàng:</span>
                                    <span class="font-medium ml-2">MB Bank</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Số tài khoản:</span>
                                    <span class="font-medium ml-2">0335920306</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Chủ tài khoản:</span>
                                    <span class="font-medium ml-2">NGUYEN VAN QUYEN</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Nội dung:</span>
                                    <code class="bg-green-100 text-green-800 px-2 py-1 rounded ml-2" x-text="'LAVISH ' + selectedBooking?.booking_code"></code>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-3">
                            <button @click="showModal = false" 
                                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition-colors">
                                Đóng
                            </button>
                            <button @click="confirmPayment(selectedBooking?.booking_code); showModal = false" 
                                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                                <i class="fas fa-check mr-1"></i>
                                Xác nhận thanh toán
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function adminPanel() {
            return {
                bookings: [],
                loading: false,
                showModal: false,
                selectedBooking: null,
                confirmingBooking: null,
                lastUpdate: '',

                async init() {
                    await this.fetchBookings();
                    this.updateLastUpdateTime();
                    
                    // Auto refresh mỗi 30 giây
                    setInterval(() => {
                        this.fetchBookings();
                    }, 30000);
                },

                async fetchBookings() {
                    this.loading = true;
                    try {
                        const response = await fetch('/api/payment/admin/pending');
                        const result = await response.json();
                        
                        if (result.success) {
                            this.bookings = result.data;
                            this.updateLastUpdateTime();
                        } else {
                            this.showError('Lỗi khi tải dữ liệu');
                        }
                    } catch (error) {
                        console.error('Error fetching bookings:', error);
                        this.showError('Lỗi kết nối API');
                    } finally {
                        this.loading = false;
                    }
                },

                async confirmPayment(bookingCode) {
                    if (!confirm(`Xác nhận thanh toán cho booking ${bookingCode}?`)) {
                        return;
                    }

                    this.confirmingBooking = bookingCode;
                    try {
                        const response = await fetch(`/api/payment/admin/confirm/${bookingCode}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                            }
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            this.showSuccess(`Đã xác nhận thanh toán cho booking ${bookingCode}`);
                            await this.fetchBookings(); // Refresh list
                        } else {
                            this.showError(result.message || 'Lỗi khi xác nhận thanh toán');
                        }
                    } catch (error) {
                        console.error('Error confirming payment:', error);
                        this.showError('Lỗi kết nối API');
                    } finally {
                        this.confirmingBooking = null;
                    }
                },

                viewDetails(booking) {
                    this.selectedBooking = booking;
                    this.showModal = true;
                },

                formatVND(amount) {
                    return new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0,
                    }).format(amount);
                },

                formatDate(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleString('vi-VN');
                },

                updateLastUpdateTime() {
                    this.lastUpdate = new Date().toLocaleTimeString('vi-VN');
                },

                showSuccess(message) {
                    // Simple notification - có thể thay thế bằng toast library
                    alert('✅ ' + message);
                },

                showError(message) {
                    // Simple notification - có thể thay thế bằng toast library  
                    alert('❌ ' + message);
                }
            }
        }
    </script>
</body>
</html>

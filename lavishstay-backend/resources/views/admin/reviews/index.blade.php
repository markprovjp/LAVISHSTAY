<style>
    .button-action {
        position: relative;
        z-index: 60;
    }
    .menu-button-action {
        position: fixed !important; /* Đưa dropdown ra ngoài flow của table */
        top: 0;
        left: 0;
        z-index: 9999;
        width: 200px;
        display: none;
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        border: 1px solid #e5e7eb;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr); /* 4 cột, mỗi cột chiếm 1/4 */
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .stats-card {
        background-color: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1rem;
        transition: transform 0.2s ease-in-out;
    }
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .stats-card h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .stats-card p {
        margin: 0.25rem 0;
        color: #374151;
    }
    .stats-card .label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
    }
    /* Responsive: Giảm xuống 2 cột trên màn hình nhỏ */
    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr); /* 2 cột trên tablet */
        }
    }
    @media (max-width: 640px) {
        .stats-grid {
            grid-template-columns: 1fr; /* 1 cột trên mobile */
        }
    }
</style>

<x-app-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Danh sách Đánh giá</h2>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Thống kê dạng card -->
        <div class="stats-grid">
            @forelse ($stats as $stat)
                <div class="stats-card">
                    <h3>{{ $stat->room_type_name ?? 'N/A' }}</h3>
                    <p class="label">Số lượng Đánh giá:</p>
                    <p class="text-lg font-semibold">{{ $stat->total_reviews }}</p>
                    <p class="label">Điểm Trung Bình:</p>
                    <p class="text-lg font-semibold">{{ number_format($stat->average_rating, 2) }}</p>
                </div>
            @empty
                <div class="stats-card text-center">
                    <p>Không có dữ liệu thống kê.</p>
                </div>
            @endforelse
        </div>

        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Review ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Booking</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Người dùng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loại phòng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tiêu đề</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Đánh giá</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Bình luận</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày bình luận</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($reviews as $review)
                        @if ($review->review_id)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $review->review_id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $review->booking->booking_id ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $review->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    @if ($review->booking && $review->booking->room_type_id)
                                        {{ \App\Models\RoomType::find($review->booking->room_type_id)->name ?? 'N/A' }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $review->title ?? 'Chưa có' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $review->rating ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $review->comment ?? 'Không có bình luận' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $review->review_date ? $review->review_date->format('d/m/Y') : 'Chưa có' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $review->status == 'approved' ? 'bg-green-100 text-green-800' : ($review->status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($review->status) }}
                                    </span>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap text-center">
                                    <div class="relative inline-block text-left">
                                        <button type="button"
                                            class="button-action inline-flex items-center justify-center w-8 h-8 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-colors duration-200"
                                            onclick="toggleDropdown({{ $review->review_id }}, event)"
                                            id="dropdown-button-{{ $review->review_id }}">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                                </path>
                                            </svg>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <div id="dropdown-menu-{{ $review->review_id }}"
                                            class="menu-button-action absolute right-0 z-50 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                            <div class="py-1 z-500" role="menu">
                                                <!-- View Details -->
                                                <button
                                                    onclick="toggleDetails({{ $review->review_id }}); closeDropdown({{ $review->review_id }})"
                                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                    role="menuitem">
                                                    View Details
                                                </button>

                                                <!-- Delete -->
                                                <button
                                                    onclick="deleteReview({{ $review->review_id }}); closeDropdown({{ $review->review_id }})"
                                                    class="flex mt-2 items-center w-full px-4 py-2 cursor-pointer text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150"
                                                    role="menuitem">
                                                    <svg style="width: 20px; align-items: center"
                                                        class="mr-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                    Delete Review
                                                </button>

                                                @if ($review->status === 'pending')
                                                    <button
                                                        onclick="showApprovePopup({{ $review->review_id }}); closeDropdown({{ $review->review_id }})"
                                                        class="flex items-center w-full px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/20 transition-colors duration-150"
                                                        role="menuitem">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                                                        </svg>
                                                        Phê duyệt
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>                                
                            </tr>
                            <!-- Expandable Details Row -->
                            <tr id="details-{{ $review->review_id }}"
                                class="hidden bg-gray-50 dark:bg-gray-700/50">
                                <td colspan="10" class="px-5 py-4 relative">
                                    <!-- Nút đóng ở góc trên bên phải -->
                                    <button type="button"
                                        onclick="closeDetails({{ $review->review_id }})"
                                        class="absolute top-2 right-2 text-gray-400 hover:text-red-500 transition-colors z-10"
                                        title="Đóng">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        <div class="space-y-4">
                                            <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">Chi tiết đánh giá</h4>
                                            <div class="space-y-2">
                                                @if ($review->review_id)
                                                    <div>
                                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Review ID:</span>
                                                        <p class="text-sm text-green-600 dark:text-green-400">{{ $review->review_id }}</p>
                                                    </div>
                                                @endif
                                                @if ($review->booking)
                                                    <div>
                                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Booking ID:</span>
                                                        <p class="text-sm text-green-600 dark:text-green-400">{{ $review->booking->booking_id ?? 'N/A' }}</p>
                                                    </div>
                                                @endif
                                                @if ($review->user)
                                                    <div>
                                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Người dùng:</span>
                                                        <p class="text-sm text-green-600 dark:text-green-400">{{ $review->user->name ?? 'N/A' }}</p>
                                                    </div>
                                                @endif
                                                @if ($review->booking)
                                                    <div>
                                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Loại phòng:</span>
                                                        <p class="text-sm text-green-600 dark:text-green-400">
                                                            {{ \App\Models\RoomType::find($review->booking->room_type_id)->name ?? 'N/A' }}
                                                        </p>
                                                    </div>
                                                @endif
                                                @if ($review->title)
                                                    <div>
                                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Tiêu đề:</span>
                                                        <p class="text-sm text-green-600 dark:text-green-400">{{ $review->title }}</p>
                                                    </div>
                                                @endif
                                                @if ($review->rating)
                                                    <div>
                                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Đánh giá:</span>
                                                        <p class="text-sm text-green-600 dark:text-green-400">{{ $review->rating }}</p>
                                                    </div>
                                                @endif
                                                @if ($review->comment)
                                                    <div>
                                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Bình luận:</span>
                                                        <p class="text-sm text-green-600 dark:text-green-400">{{ $review->comment }}</p>
                                                    </div>
                                                @endif
                                                @if ($review->review_date)
                                                    <div>
                                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Ngày bình luận:</span>
                                                        <p class="text-sm text-green-600 dark:text-green-400">{{ $review->review_date->format('d/m/Y') }}</p>
                                                    </div>
                                                @endif
                                                @if ($review->status)
                                                    <div>
                                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Trạng thái:</span>
                                                        <p class="text-sm text-green-600 dark:text-green-400">{{ ucfirst($review->status) }}</p>
                                                    </div>
                                                @endif
                                                @if ($review->helpful)
                                                    <div>
                                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Hữu ích:</span>
                                                        <p class="text-sm text-green-600 dark:text-green-400">{{ $review->helpful }}</p>
                                                    </div>
                                                @endif
                                                @if ($review->not_helpful)
                                                    <div>
                                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Không hữu ích:</span>
                                                        <p class="text-sm text-green-600 dark:text-green-400">{{ $review->not_helpful }}</p>
                                                    </div>
                                                @endif
                                                @if ($review->travel_type)
                                                    <div>
                                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Loại du lịch:</span>
                                                        <p class="text-sm text-green-600 dark:text-green-400">{{ $review->travel_type }}</p>
                                                    </div>
                                                @endif
                                                @if ($review->admin_reply_content)
                                                    <div>
                                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Phản hồi admin:</span>
                                                        <p class="text-sm text-green-600 dark:text-green-400">{{ $review->admin_reply_content }}</p>
                                                    </div>
                                                @endif
                                                @if ($review->admin_reply_date)
                                                    <div>
                                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Ngày phản hồi:</span>
                                                        <p class="text-sm text-green-600 dark:text-green-400">{{ $review->admin_reply_date->format('d/m/Y') }}</p>
                                                    </div>
                                                @endif
                                                @if ($review->admin_name)
                                                    <div>
                                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Tên admin:</span>
                                                        <p class="text-sm text-green-600 dark:text-green-400">{{ $review->admin_name }}</p>
                                                    </div>
                                                @endif
                                                @if ($review->score_cleanliness)
                                                    <div>
                                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Điểm sạch sẽ:</span>
                                                        <p class="text-sm text-green-600 dark:text-green-400">{{ $review->score_cleanliness }}</p>
                                                    </div>
                                                @endif
                                                @if ($review->score_location)
                                                    <div>
                                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Điểm vị trí:</span>
                                                        <p class="text-sm text-green-600 dark:text-green-400">{{ $review->score_location }}</p>
                                                    </div>
                                                @endif
                                                @if ($review->score_facilities)
                                                    <div>
                                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Điểm tiện nghi:</span>
                                                        <p class="text-sm text-green-600 dark:text-green-400">{{ $review->score_facilities }}</p>
                                                    </div>
                                                @endif
                                                @if ($review->score_service)
                                                    <div>
                                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Điểm dịch vụ:</span>
                                                        <p class="text-sm text-green-600 dark:text-green-400">{{ $review->score_service }}</p>
                                                    </div>
                                                @endif
                                                @if ($review->score_value)
                                                    <div>
                                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Điểm giá trị:</span>
                                                        <p class="text-sm text-green-600 dark:text-green-400">{{ $review->score_value }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="10" class="px-6 py-4 text-center text-sm text-red-500">Dữ liệu đánh giá không hợp lệ (Review ID thiếu).</td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">Không có đánh giá nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Toggle dropdown menu 
        function toggleDropdown(reviewId, event) {
            event.stopPropagation();
            const dropdown = document.getElementById(`dropdown-menu-${reviewId}`);
            const button = document.getElementById(`dropdown-button-${reviewId}`);
            const allDropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');

            // Nếu dropdown đang hiển thị, ẩn đi và thoát
            if (dropdown.style.display === 'block') {
                dropdown.style.display = 'none';
                return;
            }

            // Ẩn tất cả dropdown khác
            allDropdowns.forEach(menu => {
                if (menu.id !== `dropdown-menu-${reviewId}`) {
                    menu.style.display = 'none';
                }
            });

            const rect = button.getBoundingClientRect();
            const spacing = 8;

            // Hiển thị tạm dropdown để đo thực tế
            dropdown.style.display = 'block';
            dropdown.style.visibility = 'hidden';
            dropdown.style.top = '0px';
            dropdown.style.left = '0px';
            const realHeight = dropdown.getBoundingClientRect().height;
            const realWidth = dropdown.getBoundingClientRect().width;
            dropdown.style.visibility = 'visible';

            // Tính toán vị trí
            let top;
            const windowHeight = window.innerHeight;
            if (rect.bottom + realHeight + spacing < windowHeight) {
                top = rect.bottom + window.scrollY + spacing;
            } else if (rect.top - realHeight - spacing > 0) {
                top = rect.top + window.scrollY - realHeight - spacing;
            } else {
                top = window.scrollY + 10;
            }

            let left = rect.left + window.scrollX;
            if (left + realWidth > window.innerWidth) {
                left = window.innerWidth - realWidth - 10;
            }

            // Đặt lại vị trí chính xác
            dropdown.style.top = `${top}px`;
            dropdown.style.left = `${left}px`;
            dropdown.style.position = 'fixed';
            dropdown.style.zIndex = 9999;
        }


        function closeDropdown(reviewId) {
            const dropdown = document.getElementById(`dropdown-menu-${reviewId}`);
            if (dropdown) {
                dropdown.style.display = 'none';
            }
        }

        function toggleDetails(reviewId) {
            const detailsRow = document.getElementById(`details-${reviewId}`);
            if (!detailsRow) return;
            const isHidden = detailsRow.classList.contains('hidden');
            const allDetails = document.querySelectorAll('[id^="details-"]');
            allDetails.forEach(detail => {
                detail.classList.add('hidden');
            });
            if (isHidden) {
                detailsRow.classList.remove('hidden');
                setTimeout(() => {
                    detailsRow.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 100);
            }
        }

        function closeDetails(reviewId) {
            const detailsRow = document.getElementById(`details-${reviewId}`);
            if (detailsRow) {
                detailsRow.classList.add('hidden');
            }
        }

        function deleteReview(reviewId) {
            Swal.fire({
                title: 'Có chắc chắn xóa?',
                text: "Hành động này sẽ xóa đánh giá vĩnh viễn và không thể khôi phục!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Có, xóa!',
                cancelButtonText: 'Không, hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/reviews/destroy/${reviewId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            Swal.fire('Lỗi!', data.error || 'Lỗi khi xóa đánh giá', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Delete failed:', error);
                        Swal.fire('Lỗi!', 'Không thể xóa đánh giá. Vui lòng kiểm tra log server.', 'error');
                    });
                }
            });
        }

        function showApprovePopup(reviewId) {
            Swal.fire({
                title: 'Phê duyệt đánh giá',
                text: 'Chọn trạng thái cho đánh giá này:',
                icon: 'question',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: 'Duyệt',
                denyButtonText: 'Từ chối',
                cancelButtonText: 'Hủy',
                confirmButtonColor: '#16a34a',
                denyButtonColor: '#d33',
                cancelButtonColor: '#3085d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    approveReview(reviewId, 'approved');
                } else if (result.isDenied) {
                    approveReview(reviewId, 'rejected');
                }
                // Nếu cancel thì không làm gì cả
            });
        }

        function approveReview(reviewId, status) {
            fetch(`/admin/reviews/${reviewId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    Swal.fire('Lỗi!', data.error || 'Không thể cập nhật trạng thái', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Lỗi!', 'Không thể cập nhật trạng thái', 'error');
            });
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');
            const buttons = document.querySelectorAll('[id^="dropdown-button-"]');
            let clickedInsideDropdown = false;
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
            if (!clickedInsideDropdown) {
                dropdowns.forEach(dropdown => {
                    dropdown.style.display = 'none';
                });
            }
        });
    </script>
</x-app-layout>
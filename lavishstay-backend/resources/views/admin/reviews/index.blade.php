<style>
    .button-action {
        position: relative;
        z-index: 60;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stats-card {
        background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
        border-radius: 1rem;
        padding: 1.5rem;
        color: white;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(31, 41, 55, 0.3);
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(25px, -25px);
    }
    
    .stats-card h3 {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
        color: #f9fafb;
    }
    
    .stats-number {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
        position: relative;
        z-index: 1;
        color: #ffffff;
    }
    
    .stats-label {
        font-size: 0.875rem;
        opacity: 0.9;
        margin-top: 0.5rem;
        position: relative;
        z-index: 1;
        color: #e5e7eb;
    }

    .modern-table {
        background: #1f2937;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .table-header {
        background: #374151;
        border-bottom: 2px solid #4b5563;
    }

    .table-header th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #f9fafb;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .table-row {
        border-bottom: 1px solid #374151;
        transition: all 0.2s ease;
        background-color: #1f2937;
    }

    .table-row:hover {
        background-color: #374151;
        transform: translateY(-1px);
    }

    .table-cell {
        padding: 1rem;
        vertical-align: middle;
        color: #f3f4f6;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-approved {
        background-color: rgba(34, 197, 94, 0.2);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .status-pending {
        background-color: rgba(245, 158, 11, 0.2);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .status-rejected {
        background-color: rgba(239, 68, 68, 0.2);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .rating-stars {
        display: flex;
        gap: 2px;
    }

    .star {
        width: 16px;
        height: 16px;
        color: #fbbf24;
    }

    .star.empty {
        color: #6b7280;
    }

    .action-button {
        background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
        color: white;
        border: 1px solid #4b5563;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .action-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(31, 41, 55, 0.3);
        background: linear-gradient(135deg, #374151 0%, #4b5563 100%);
    }

    .comment-preview {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        cursor: pointer;
        color: #d1d5db;
    }

    .comment-preview:hover {
        color: #f3f4f6;
        text-decoration: underline;
    }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 10000;
        backdrop-filter: blur(4px);
    }

    .modal-content {
        background: #1f2937;
        border-radius: 1rem;
        max-width: 800px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        border: 1px solid #374151;
    }

    .modal-header {
        background: linear-gradient(135deg, #374151 0%, #4b5563 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 1rem 1rem 0 0;
        position: relative;
    }

    .modal-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        padding: 0.5rem;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .modal-body {
        padding: 1.5rem;
        background: #1f2937;
        color: #f3f4f6;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .detail-section {
        background: #374151;
        border-radius: 0.5rem;
        padding: 1rem;
    }

    .detail-section h4 {
        font-weight: 600;
        color: #f9fafb;
        margin-bottom: 0.75rem;
        border-bottom: 2px solid #4b5563;
        padding-bottom: 0.5rem;
    }

    .detail-item {
        margin-bottom: 0.75rem;
    }

    .detail-label {
        font-size: 0.75rem;
        font-weight: 500;
        color: #d1d5db;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .detail-value {
        color: #f3f4f6;
        font-weight: 500;
        margin-top: 0.25rem;
    }

    .media-gallery {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .media-item {
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
    }

    .media-item img,
    .media-item video {
        width: 120px;
        height: 120px;
        object-fit: cover;
    }


    .dropdown-item {
        display: flex;
        align-items: center;
        width: 100%;
        padding: 0.75rem 1rem;
        background: none;
        border: none;
        text-align: left;
        color: #f3f4f6;
        font-size: 0.875rem;
        cursor: pointer;
        transition: background-color 0.15s ease;
    }

    .dropdown-item:hover {
        background-color: #374151;
    }

    .dropdown-item.danger {
        color: #ef4444;
    }

    .dropdown-item.danger:hover {
        background-color: rgba(239, 68, 68, 0.1);
    }

    .dropdown-item.success {
        color: #22c55e;
    }

    .dropdown-item.success:hover {
        background-color: rgba(34, 197, 94, 0.1);
    }

    .dropdown-item.primary {
        color: #3b82f6;
    }

    .dropdown-item.primary:hover {
        background-color: rgba(59, 130, 246, 0.1);
    }

    .dropdown-item svg {
        margin-right: 0.5rem;
        width: 16px;
        height: 16px;
    }

    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .table-cell {
            padding: 0.75rem 0.5rem;
            font-size: 0.875rem;
        }
    }

    @media (max-width: 640px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .modal-content {
            width: 95%;
            margin: 1rem;
        }
        
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<x-app-layout>
    <div class="p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Quản lý Đánh giá</h2>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Theo dõi và quản lý các đánh giá từ khách hàng</p>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg mb-6 flex items-center" role="alert">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @forelse ($stats as $stat)
                <div class="rounded-xl p-6 bg-white dark:bg-gray-800 shadow hover:shadow-lg transition-all">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ $stat->room_option_name ?? 'N/A' }}</h3>
                    <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stat->total_reviews }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Đánh giá</div>
                    <div class="flex items-center mt-2">
                        <div class="flex gap-1 mr-2">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= round($stat->average_rating) ? 'text-yellow-400' : 'text-gray-400 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                        </div>
                        <span class="text-sm opacity-90">{{ number_format($stat->average_rating, 1) }}</span>
                    </div>
                </div>
            @empty
                <div class="rounded-xl p-6 bg-white dark:bg-gray-800 shadow">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-2">Không có dữ liệu</h3>
                    <div class="text-2xl font-bold text-gray-800 dark:text-white">0</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Thống kê</div>
                </div>
            @endforelse
        </div>

        <!-- Reviews Table -->
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <th class="px-4 py-3 text-left font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase">Khách hàng</th>
                        <th class="px-4 py-3 text-left font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase">Phòng</th>
                        <th class="px-4 py-3 text-left font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase">Đánh giá</th>
                        <th class="px-4 py-3 text-left font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase">Bình luận</th>
                        <th class="px-4 py-3 text-left font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase">Ngày</th>
                        <th class="px-4 py-3 text-left font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase">Trạng thái</th>
                        <th class="px-4 py-3 text-left font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reviews as $review)
                        @if ($review->review_id)
                            <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <td class="px-4 py-3 align-middle">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full flex items-center justify-center text-white font-semibold text-sm mr-3">
                                            {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ $review->user->name ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-400">ID: {{ $review->review_id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 align-middle">
                                    <div class="font-medium text-gray-800 dark:text-gray-200">{{ $review->roomOption->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-400">Booking: {{ $review->booking ? $review->booking->booking_id : 'N/A' }}</div>
                                </td>
                                <td class="px-4 py-3 align-middle">
                                    <div class="flex items-center">
                                        <div class="flex gap-1 mr-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= ($review->rating ?? 0) ? 'text-yellow-400' : 'text-gray-400 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $review->rating ?? 'N/A' }}/5.0</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 align-middle">
                                    <div class="max-w-xs truncate cursor-pointer text-gray-500 dark:text-gray-300 hover:underline" onclick="showDetailModal({{ $review->review_id }})">
                                        {{ Str::limit($review->comment ?? 'Không có bình luận', 40) }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 align-middle">
                                    <div class="text-xs text-gray-600 dark:text-gray-300">{{ $review->review_date ? $review->review_date->format('d/m/Y') : 'Chưa có' }}</div>
                                </td>
                                <td class="px-4 py-3 align-middle">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                        @if($review->status === 'approved') bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200
                                        @elseif($review->status === 'pending') bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-200
                                        @else bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 @endif">
                                        {{ ucfirst($review->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 align-middle">
                                    <div class="relative">
                                        <button type="button"
                                            id="action-btn-{{ $review->review_id }}"
                                            class="w-8 h-8 flex items-center justify-center bg-gray-200 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                                            onclick="toggleDropdown({{ $review->review_id }}, event)">
                                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                            </svg>
                                        </button>
                                        <!-- Dropdown menu sẽ được render ở cuối body bằng JS -->
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-400 dark:text-gray-500">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p>Không có đánh giá nào</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Enhanced Detail Modal -->
    <div id="detailModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white dark:bg-gray-900 rounded-3xl max-w-4xl w-full mx-4 shadow-2xl relative transform scale-95 transition-transform duration-300 border border-white/20 dark:border-gray-700/30 max-h-[90vh] overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-slate-800 via-slate-700 to-slate-800 px-8 py-6 border-b border-slate-600">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-star text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white">Chi tiết Đánh giá</h3>
                    </div>
                    <button class="w-10 h-10 flex items-center justify-center bg-white/10 hover:bg-white/20 rounded-xl transition-colors text-white" onclick="closeDetailModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="px-8 py-6 overflow-y-auto max-h-[calc(90vh-120px)] bg-gradient-to-b from-white to-slate-50 dark:from-gray-900 dark:to-slate-900" id="modalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Store review data for modal display
        const reviewsData = @json($reviews->keyBy('review_id'));
        const scoreLabels = {
            room_comfort: 'Thoải mái phòng',
            food_breakfast: 'Bữa sáng',
            room_amenities: 'Tiện nghi phòng',
            facilities_pool: 'Hồ bơi',
            value_for_money: 'Giá trị so với tiền',
            room_cleanliness: 'Sạch sẽ phòng',
            service_reception: 'Lễ tân',
            service_housekeeping: 'Dọn phòng'
        };

        function toggleDropdown(reviewId, event) {
            event.stopPropagation();

            // Nếu dropdown đang mở cho đúng reviewId thì đóng lại
            if (currentDropdownId === reviewId) {
                closeAllDropdowns();
                return;
            }
            closeAllDropdowns();

            const btn = document.getElementById('action-btn-' + reviewId);
            const rect = btn.getBoundingClientRect();

            // Tạo dropdown nếu chưa có
            let dropdown = document.getElementById('dropdown-menu-fixed');
            if (!dropdown) {
                dropdown = document.createElement('div');
                dropdown.id = 'dropdown-menu-fixed';
                dropdown.className = 'fixed bg-white dark:bg-gray-800 rounded-lg shadow-lg z-50 w-48';
                dropdown.style.display = 'none';
                document.body.appendChild(dropdown);
            }

            // Nội dung dropdown
            let review = reviewsData[reviewId];
            let approveBtn = '';
            if (review && review.status === 'pending') {
                approveBtn = `<button onclick="showApprovePopup(${reviewId}); closeAllDropdowns()" class="w-full text-left px-4 py-2 hover:bg-blue-100 dark:hover:bg-blue-900 text-blue-700 dark:text-blue-300">Phê duyệt</button>`;
            }
            dropdown.innerHTML = `
                <button onclick="showDetailModal(${reviewId}); closeAllDropdowns()" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">Xem Chi tiết</button>
                ${approveBtn}
                <button onclick="showNotePopup(${reviewId}); closeAllDropdowns()" class="w-full text-left px-4 py-2 hover:bg-green-100 dark:hover:bg-green-900 text-green-700 dark:text-green-300">Ghi chú</button>
                <button onclick="deleteReview(${reviewId}); closeAllDropdowns()" class="w-full text-left px-4 py-2 hover:bg-red-100 dark:hover:bg-red-900 text-red-700 dark:text-red-300">Xóa</button>
            `;

            // Tính toán vị trí dropdown (trên hoặc dưới)
            const dropdownHeight = 48 * 4; // 4 items * 48px (tailwind py-2)
            const spaceBelow = window.innerHeight - rect.bottom;
            const spaceAbove = rect.top;

            let top, left;
            if (spaceBelow < dropdownHeight && spaceAbove > dropdownHeight) {
                // Hiển thị lên trên
                top = rect.top + window.scrollY - dropdownHeight - 4;
            } else {
                // Hiển thị xuống dưới
                top = rect.bottom + window.scrollY + 4;
            }
            left = rect.right - 192; // 192px = w-48

            dropdown.style.top = `${top}px`;
            dropdown.style.left = `${left}px`;
            dropdown.style.display = 'block';
            currentDropdownId = reviewId;
        }

        function closeAllDropdowns() {
            const dropdown = document.getElementById('dropdown-menu-fixed');
            if (dropdown) dropdown.style.display = 'none';
            currentDropdownId = null;
        }

        // Đóng khi click ngoài
        document.addEventListener('click', function(event) {
            if (!event.target.closest('#dropdown-menu-fixed')) {
                closeAllDropdowns();
            }
        });
        // Đóng khi bấm ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeAllDropdowns();
            }
        });

        function showDetailModal(reviewId) {
            const review = reviewsData[reviewId];
            if (!review) return;
            
            let mediaHtml = '';
            if (review.review_media && review.review_media.length > 0) {
                mediaHtml = `
                    <div class="mt-6 p-6 bg-gray-50 dark:bg-gray-800 rounded-2xl">
                        <div class="flex items-center space-x-2 mb-4">
                            <i class="fas fa-images text-blue-500"></i>
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">Ảnh đính kèm</h4>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                            ${review.review_media.map(media => {
                                if (media.file_type && media.file_type.includes('image')) {
                                    return `<img src="${media.file_url}" alt="Ảnh đánh giá" class="w-full h-24 object-cover rounded-xl shadow-md hover:shadow-lg transition-shadow cursor-pointer" loading="lazy">`;
                                }
                                return '';
                            }).join('')}
                        </div>
                    </div>`;
            }
            
            let detailedScoresHtml = '';
            if (review.detailed_scores) {
                detailedScoresHtml = `
                    <div class="mt-6 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl border border-blue-200 dark:border-blue-800">
                        <div class="flex items-center space-x-2 mb-4">
                            <i class="fas fa-chart-bar text-blue-500"></i>
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">Điểm chi tiết</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            ${Object.entries(review.detailed_scores).map(([key, score]) => `
                                <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">${scoreLabels[key] || key}:</span>
                                    <div class="flex items-center space-x-2">
                                        <div class="flex space-x-1">
                                            ${[1,2,3,4,5].map(i => `<svg class="w-3 h-3 ${i <= score ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>`).join('')}
                                        </div>
                                        <span class="text-sm font-bold text-blue-600 dark:text-blue-400">${score}</span>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>`;
            }
            
            document.getElementById('modalContent').innerHTML = `
                <div class="space-y-6">
                    <!-- Review Header -->
                    <div class="flex items-center justify-between p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                ${(review.user ? review.user.name : 'U').charAt(0).toUpperCase()}
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">${review.user ? review.user.name : 'N/A'}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">ID: ${review.review_id}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="flex items-center space-x-2 mb-2">
                                ${[1,2,3,4,5].map(i => `<svg class="w-5 h-5 ${i <= (review.rating || 0) ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>`).join('')}
                            </div>
                            <span class="text-2xl font-bold bg-gradient-to-r from-yellow-600 to-orange-600 bg-clip-text text-transparent">${review.rating || 'N/A'}/5</span>
                        </div>
                    </div>

                    <!-- Review Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Info -->
                        <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700">
                            <div class="flex items-center space-x-2 mb-4">
                                <i class="fas fa-info-circle text-blue-500"></i>
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">Thông tin cơ bản</h4>
                            </div>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Phòng:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">${review.room_option ? review.room_option.name : 'N/A'}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Ngày đánh giá:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">${review.review_date ? new Date(review.review_date).toLocaleDateString('vi-VN') : 'Chưa có'}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Loại du lịch:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">${review.travel_type || 'N/A'}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Hữu ích:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">${review.helpful_count || 0} lượt</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Trạng thái:</span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ${
                                        review.status === 'approved' ? 'bg-gradient-to-r from-green-500 to-emerald-500 text-white' :
                                        (review.status === 'pending' ? 'bg-gradient-to-r from-yellow-500 to-orange-500 text-white' :
                                        'bg-gradient-to-r from-red-500 to-pink-500 text-white')
                                    }">
                                        ${review.status.charAt(0).toUpperCase() + review.status.slice(1)}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Review Content -->
                        <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700">
                            <div class="flex items-center space-x-2 mb-4">
                                <i class="fas fa-comment text-green-500"></i>
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">Nội dung đánh giá</h4>
                            </div>
                            <div class="space-y-4">
                                ${review.title ? `
                                    <div>
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400 block mb-1">Tiêu đề:</span>
                                        <p class="text-gray-900 dark:text-gray-100 font-medium">${review.title}</p>
                                    </div>
                                ` : ''}
                                <div>
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400 block mb-1">Bình luận:</span>
                                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">${review.comment || 'Không có bình luận'}</p>
                                </div>
                                ${review.pros ? `
                                    <div>
                                        <span class="text-sm font-medium text-green-600 dark:text-green-400 block mb-1">
                                            <i class="fas fa-thumbs-up mr-1"></i>Ưu điểm:
                                        </span>
                                        <p class="text-gray-700 dark:text-gray-300">${review.pros}</p>
                                    </div>
                                ` : ''}
                                ${review.cons ? `
                                    <div>
                                        <span class="text-sm font-medium text-red-600 dark:text-red-400 block mb-1">
                                            <i class="fas fa-thumbs-down mr-1"></i>Nhược điểm:
                                        </span>
                                        <p class="text-gray-700 dark:text-gray-300">${review.cons}</p>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>

                    ${detailedScoresHtml}

                    ${review.admin_note ? `
                        <div class="p-6 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-2xl border border-amber-200 dark:border-amber-800">
                            <div class="flex items-center space-x-2 mb-3">
                                <i class="fas fa-sticky-note text-amber-500"></i>
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">Ghi chú của Admin</h4>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">${review.admin_note}</p>
                        </div>
                    ` : ''}

                    ${mediaHtml}
                </div>
            `;
            
            const modal = document.getElementById('detailModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.querySelector('div').classList.remove('scale-95');
                modal.querySelector('div').classList.add('scale-100');
            }, 10);
        }

        function closeDetailModal() {
            const modal = document.getElementById('detailModal');
            modal.classList.add('opacity-0');
            modal.querySelector('div').classList.remove('scale-100');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }


        function deleteReview(reviewId) {
            Swal.fire({
                title: 'Xác nhận xóa',
                text: "Bạn có chắc chắn muốn xóa đánh giá này? Hành động này không thể hoàn tác!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Có, xóa ngay!',
                cancelButtonText: 'Hủy bỏ',
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'rounded-lg',
                    cancelButton: 'rounded-lg'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Đang xóa...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'rounded-xl'
                        },
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

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
                            Swal.fire({
                                title: 'Đã xóa!',
                                text: 'Đánh giá đã được xóa thành công.',
                                icon: 'success',
                                confirmButtonColor: '#059669',
                                customClass: {
                                    popup: 'rounded-xl',
                                    confirmButton: 'rounded-lg'
                                }
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            throw new Error(data.error || 'Lỗi khi xóa đánh giá');
                        }
                    })
                    .catch(error => {
                        console.error('Delete failed:', error);
                        Swal.fire({
                            title: 'Lỗi!',
                            text: 'Không thể xóa đánh giá. Vui lòng thử lại sau.',
                            icon: 'error',
                            confirmButtonColor: '#dc2626',
                            customClass: {
                                popup: 'rounded-xl',
                                confirmButton: 'rounded-lg'
                            }
                        });
                    });
                }
            });
        }

        function showApprovePopup(reviewId) {
            Swal.fire({
                title: 'Phê duyệt đánh giá',
                text: 'Chọn hành động cho đánh giá này:',
                icon: 'question',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: '<i class="fas fa-check mr-2"></i>Phê duyệt',
                denyButtonText: '<i class="fas fa-times mr-2"></i>Từ chối',
                cancelButtonText: 'Hủy bỏ',
                confirmButtonColor: '#059669',
                denyButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'rounded-lg',
                    denyButton: 'rounded-lg',
                    cancelButton: 'rounded-lg'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    approveReview(reviewId, 'approved');
                } else if (result.isDenied) {
                    approveReview(reviewId, 'rejected');
                }
            });
        }

        function approveReview(reviewId, status) {
            // Show loading
            Swal.fire({
                title: 'Đang xử lý...',
                allowOutsideClick: false,
                showConfirmButton: false,
                customClass: {
                    popup: 'rounded-xl'
                },
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/admin/reviews/${reviewId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Thành công!',
                        text: `Đánh giá đã được ${status === 'approved' ? 'phê duyệt' : 'từ chối'}.`,
                        icon: 'success',
                        confirmButtonColor: '#059669',
                        customClass: {
                            popup: 'rounded-xl',
                            confirmButton: 'rounded-lg'
                        }
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.error || 'Không thể cập nhật trạng thái');
                }
            })
            .catch(error => {
                console.error('Approve failed:', error);
                Swal.fire({
                    title: 'Lỗi!',
                    text: 'Không thể cập nhật trạng thái. Vui lòng thử lại sau.',
                    icon: 'error',
                    confirmButtonColor: '#dc2626',
                    customClass: {
                        popup: 'rounded-xl',
                        confirmButton: 'rounded-lg'
                    }
                });
            });
        }

        function showNotePopup(reviewId) {
            const review = reviewsData[reviewId];
            
            Swal.fire({
                title: 'Ghi chú cho đánh giá',
                html: `
                    <div class="text-left mb-4">
                        <div class="bg-gray-50 p-3 rounded-lg mb-4">
                            <p class="text-sm text-gray-600 mb-2"><strong>Đánh giá từ:</strong> ${review.user ? review.user.name : 'N/A'}</p>
                            <p class="text-sm text-gray-800">"${review.comment || 'Không có bình luận'}"</p>
                        </div>
                        <label for="noteContent" class="block text-sm font-medium text-gray-700 mb-2">Ghi chú của admin:</label>
                        <textarea 
                            id="noteContent" 
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" 
                            rows="4" 
                            placeholder="Nhập ghi chú của bạn..."
                            style="resize: vertical; min-height: 100px;"
                        >${review.admin_note || ''}</textarea>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-save mr-2"></i>Lưu ghi chú',
                cancelButtonText: 'Hủy bỏ',
                confirmButtonColor: '#059669',
                cancelButtonColor: '#6b7280',
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'rounded-lg',
                    cancelButton: 'rounded-lg',
                    htmlContainer: 'text-left'
                },
                preConfirm: () => {
                    const noteContent = document.getElementById('noteContent').value.trim();
                    if (!noteContent) {
                        Swal.showValidationMessage('Vui lòng nhập nội dung ghi chú!');
                        return false;
                    }
                    return noteContent;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    // Show loading
                    Swal.fire({
                        title: 'Đang lưu ghi chú...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'rounded-xl'
                        },
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch(`/admin/reviews/${reviewId}/note`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ 
                            admin_note: result.value 
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Thành công!',
                                text: 'Ghi chú đã được lưu thành công.',
                                icon: 'success',
                                confirmButtonColor: '#059669',
                                customClass: {
                                    popup: 'rounded-xl',
                                    confirmButton: 'rounded-lg'
                                }
                            }).then(() => {
                                // Update local data
                                reviewsData[reviewId].admin_note = result.value;
                            });
                        } else {
                            throw new Error(data.error || 'Không thể lưu ghi chú');
                        }
                    })
                    .catch(error => {
                        console.error('Note save failed:', error);
                        Swal.fire({
                            title: 'Lỗi!',
                            text: 'Không thể lưu ghi chú. Vui lòng kiểm tra kết nối và thử lại.',
                            icon: 'error',
                            confirmButtonColor: '#dc2626',
                            customClass: {
                                popup: 'rounded-xl',
                                confirmButton: 'rounded-lg'
                            }
                        });
                    });
                }
            });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.button-action') && !event.target.closest('.menu-button-action')) {
                const dropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');
                dropdowns.forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            }
        });

        // Close modal when clicking outside
        document.getElementById('detailModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeDetailModal();
            }
        });

        // Handle escape key for modal
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDetailModal();
                // Close dropdowns
                const dropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');
                dropdowns.forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            }
        });
    </script>
</x-app-layout>
<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0 w-full flex items-end justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">
                        Quản lý phòng
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Tổng quan tất cả các loại phòng trong hệ thống
                    </p>
                </div>
                <div>
                    Hiện đang có : {{ $totalRooms }} phòng
                </div>
            </div>
        </div>

        <!-- Rooms Grid -->
        @if($allrooms->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach($allrooms as $room)
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden hover:shadow-xl transition-shadow duration-200">
                        <!-- Room Image -->
                        <div class="relative h-48 bg-gray-200 dark:bg-gray-700">
                            @if($room->images && $room->images->count() > 0)
                                <img src="{{ asset($room->images->first()->image_url) }}" 
                                     alt="{{ $room->name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20" width="20px" height="20px">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Room Code Badge -->
                            @if($room->room_code)
                                <div class="absolute top-3 left-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white text-gray-800 shadow-sm">
                                        {{ $room->room_code }}
                                    </span>
                                </div>
                            @endif

                            <!-- Status Badge -->
                            <div class="absolute top-3 right-3">
                                @php
                                    $statusCounts = $room->rooms->groupBy('status')->map->count();
                                    $dominantStatus = $statusCounts->keys()->first() ?? 'available';
                                    $statusText = [
                                        'available' => 'Trống',
                                        'occupied' => 'Đang sử dụng',
                                        'maintenance' => 'Đang bảo trì',
                                        'cleaning' => 'Đang dọn dẹp'
                                    ];
                                    $statusColor = [
                                        'available' => 'bg-green-100 text-green-800',
                                        'occupied' => 'bg-violet-100 text-violet-800',
                                        'maintenance' => 'bg-yellow-100 text-yellow-800',
                                        'cleaning' => 'bg-blue-100 text-blue-800'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor[$dominantStatus] }} shadow-sm">
                                    {{ $statusText[$dominantStatus] }}
                                </span>
                            </div>
                        </div>

                        <!-- Room Info -->
                        <div class="p-4">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 truncate">
                                    {{ $room->name }}
                                </h3>
                            </div>

                            @if($room->description)
                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                                    {{ Str::limit($room->description, 80) }}
                                </p>
                            @endif

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600 rounded-sm dark:text-gray-400">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" width="20px" height="20px">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                    </svg>
                                    Tổng: {{ $room->rooms_count }} phòng
                                </div>
                                
                                <div class="flex items-center text-sm text-gray-600 rounded-sm dark:text-gray-400">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" width="20px" height="20px">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    Đã đặt: <span class="text-orange-600 font-medium ml-1">{{ $room->active_bookings_count }}</span>
                                </div>

                                <div class="flex items-center text-sm text-gray-600 rounded-sm dark:text-gray-400 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 rounded transition-colors duration-200" 
                                     onclick="window.location.href='{{ route('admin.rooms.by-type', $room->room_type_id) }}?status=available&search=&max_guests=&view=&check_in=&check_out='">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" width="20px" height="20px">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Trống: <span class="text-green-600 font-medium ml-1">{{ $room->rooms->where('status', 'available')->count() }}</span>
                                </div>

                                <div class="flex items-center text-sm text-gray-600 rounded-sm dark:text-gray-400 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 rounded transition-colors duration-200" 
                                     onclick="window.location.href='{{ route('admin.rooms.by-type', $room->room_type_id) }}?status=maintenance&search=&max_guests=&view=&check_in=&check_out='">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" width="20px" height="20px">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                    </svg>
                                    Bảo trì: <span class="text-yellow-600 font-medium ml-1">{{ $room->rooms->where('status', 'maintenance')->count() }}</span>
                                </div>

                                <div class="flex items-center text-sm rounded-sm text-gray-600 dark:text-gray-400 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 rounded transition-colors duration-200" 
                                     onclick="window.location.href='{{ route('admin.rooms.by-type', $room->room_type_id) }}?status=cleaning&search=&max_guests=&view=&check_in=&check_out='">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" width="20px" height="20px">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm2 2h12v10H4V5zm2 2a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm0 4a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    Dọn dẹp: <span class="text-blue-600 font-medium ml-1">{{ $room->rooms->where('status', 'cleaning')->count() }}</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-end">
                                <a href="{{ route('admin.rooms.by-type', $room->room_type_id) }}" 
                                   class="btn bg-violet-500 hover:bg-violet-600 text-white text-sm px-4 py-2">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $allrooms->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="w-24 h-24 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                    Chưa có loại phòng nào
                </h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">
                    Hãy thêm loại phòng đầu tiên để bắt đầu quản lý.
                </p>
                
                <button class="btn bg-violet-500 hover:bg-violet-600 text-white">
                    <svg class="fill-current shrink-0 w-4 h-4" viewBox="0 0 16 16" width="20px" height="20px">
                        <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="ml-2">Thêm loại phòng</span>
                </button>
            </div>
        @endif

    </div>

    <!-- Custom Styles -->
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Ensure consistent card heights */
        .grid > div {
            display: flex;
            flex-direction: column;
        }

        .grid > div > div:last-child {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Button styles matching the original design */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            outline: 0;
            cursor: pointer;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            line-height: 1.25rem;
            text-decoration: none;
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
            padding: 0.5rem 1rem;
        }

        .btn:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            box-shadow: 0 0 0 2px rgba(139, 92, 246, 0.5);
        }

        /* Hover effects */
        .hover\:shadow-xl:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Responsive grid adjustments */
        @media (min-width: 768px) {
            .md\:grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .lg\:grid-cols-3 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (min-width: 1280px) {
            .xl\:grid-cols-4 {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        /* Dark mode improvements */
        .dark .bg-white {
            background-color: rgb(31 41 55);
        }

        .dark .text-gray-900 {
            color: rgb(243 244 246);
        }

        .dark .text-gray-600 {
            color: rgb(156 163 175);
        }

        .dark .border-gray-200 {
            border-color: rgb(75 85 99);
        }
    </style>

    <!-- JavaScript for enhanced functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading states to buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    if (this.href && !this.href.includes('#')) {
                        this.style.opacity = '0.7';
                        this.innerHTML = this.innerHTML.replace('Xem chi tiết', 'Đang tải...');
                    }
                });
            });

            // Add smooth scroll behavior
            document.documentElement.style.scrollBehavior = 'smooth';

            // Add intersection observer for animations
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            // Observe all room cards for animation
            const roomCards = document.querySelectorAll('.grid > div');
            roomCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                card.style.transitionDelay = `${index * 100}ms`;
                observer.observe(card);
            });

            // Add keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
                    const focusedElement = document.activeElement;
                    const allButtons = Array.from(document.querySelectorAll('a[href*="rooms.by-type"]'));
                    const currentIndex = allButtons.indexOf(focusedElement);
                    
                    if (currentIndex !== -1) {
                        let nextIndex;
                        if (e.key === 'ArrowLeft') {
                            nextIndex = currentIndex > 0 ? currentIndex - 1 : allButtons.length - 1;
                        } else {
                            nextIndex = currentIndex < allButtons.length - 1 ? currentIndex + 1 : 0;
                        }
                        
                        allButtons[nextIndex].focus();
                        allButtons[nextIndex].scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });

            // Add search functionality
            const addSearchBox = () => {
                const searchHTML = `
                    <div class="mb-6">
                        <div class="max-w-md">
                            <div class="relative">
                                <input type="text" 
                                       id="roomSearch" 
                                       placeholder="Tìm kiếm loại phòng..." 
                                       class="form-input w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                                <div class="absolute top-0 mt-3 inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" width="20px" height="20px">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                const gridContainer = document.querySelector('.grid');
                if (gridContainer) {
                    gridContainer.insertAdjacentHTML('beforebegin', searchHTML);
                    
                    // Search functionality
                    const searchInput = document.getElementById('roomSearch');
                    let searchTimeout;
                    
                    searchInput.addEventListener('input', function() {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            const searchTerm = this.value.toLowerCase().trim();
                            const cards = document.querySelectorAll('.grid > div');
                            let visibleCount = 0;
                            
                            cards.forEach(card => {
                                const roomName = card.querySelector('h3').textContent.toLowerCase();
                                const roomDescription = card.querySelector('p')?.textContent.toLowerCase() || '';
                                const roomCode = card.querySelector('.bg-white')?.textContent.toLowerCase() || '';
                                
                                if (!searchTerm || 
                                    roomName.includes(searchTerm) || 
                                    roomDescription.includes(searchTerm) || 
                                    roomCode.includes(searchTerm)) {
                                    card.style.display = 'block';
                                    visibleCount++;
                                } else {
                                    card.style.display = 'none';
                                }
                            });
                            
                            // Show/hide no results message
                            let noResultsMsg = document.querySelector('.no-results-message');
                            if (visibleCount === 0 && searchTerm) {
                                if (!noResultsMsg) {
                                    noResultsMsg = document.createElement('div');
                                    noResultsMsg.className = 'no-results-message text-center py-8 col-span-full';
                                    noResultsMsg.innerHTML = `
                                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20" width="20px" height="20px">>
                                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                            Không tìm thấy kết quả
                                        </h3>
                                        <p class="text-gray-500 dark:text-gray-400">
                                            Không có loại phòng nào phù hợp với từ khóa "<span class="font-medium">${searchTerm}</span>"
                                        </p>
                                    `;
                                    document.querySelector('.grid').appendChild(noResultsMsg);
                                }
                            } else if (noResultsMsg) {
                                noResultsMsg.remove();
                            }
                        }, 300);
                    });
                }
            };

            // Add search box if there are rooms
            if (document.querySelectorAll('.grid > div').length > 0) {
                addSearchBox();
            }

            // Add accessibility improvements
            const cards = document.querySelectorAll('.grid > div');
            cards.forEach((card, index) => {
                card.setAttribute('role', 'article');
                card.setAttribute('aria-label', `Room type ${index + 1}`);
                card.setAttribute('tabindex', '0');
                
                // Add keyboard interaction for cards
                card.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        const link = this.querySelector('a[href*="rooms.by-type"]');
                        if (link) {
                            link.click();
                        }
                    }
                });
            });

            // Add tooltip functionality
            const addTooltips = () => {
                const badges = document.querySelectorAll('.absolute span');
                badges.forEach(badge => {
                    badge.setAttribute('title', badge.textContent);
                });
            };

            addTooltips();

            // Add auto-refresh for room status (optional)
            let autoRefreshInterval;
            const startAutoRefresh = () => {
                autoRefreshInterval = setInterval(() => {
                    if (!document.hidden) {
                        // Update room status badges
                        fetch(window.location.href, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Update status badges if data structure supports it
                            console.log('Status updated');
                        })
                        .catch(error => {
                            console.log('Auto-refresh failed:', error);
                        });
                    }
                }, 60000); // Refresh every minute
            };

            // Uncomment to enable auto-refresh
            // startAutoRefresh();

            // Stop auto-refresh when page is not visible
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    clearInterval(autoRefreshInterval);
                } else if (autoRefreshInterval) {
                    startAutoRefresh();
                }
            });

            // Add performance monitoring
            if ('PerformanceObserver' in window) {
                const perfObserver = new PerformanceObserver((list) => {
                    for (const entry of list.getEntries()) {
                        if (entry.entryType === 'navigation') {
                            console.log(`Page loaded in ${Math.round(entry.loadEventEnd - entry.loadEventStart)}ms`);
                        }
                    }
                });
                
                try {
                    perfObserver.observe({ entryTypes: ['navigation'] });
                } catch (e) {
                    // Performance Observer not supported
                }
            }

            // Add form input styles
            const formInputs = document.querySelectorAll('.form-input');
            formInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.borderColor = '#8b5cf6';
                    this.style.boxShadow = '0 0 0 3px rgba(139, 92, 246, 0.1)';
                });
                
                input.addEventListener('blur', function() {
                    this.style.borderColor = '';
                    this.style.boxShadow = '';
                });
            });

            console.log('Room management page initialized successfully');
        });

        // Add utility functions
        window.roomManagement = {
            // Function to manually refresh room data
            refreshRoomData: function() {
                location.reload();
            },
            
            // Function to filter rooms by status
            filterByStatus: function(status) {
                const cards = document.querySelectorAll('.grid > div');
                cards.forEach(card => {
                    const statusBadge = card.querySelector('.absolute span:last-child');
                    if (statusBadge) {
                        const cardStatus = statusBadge.textContent.toLowerCase();
                        if (status === 'all' || cardStatus.includes(status.toLowerCase())) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    }
                });
            },
            
            // Function to get room statistics
            getRoomStats: function() {
                const cards = document.querySelectorAll('.grid > div');
                let totalRooms = 0;
                let availableRooms = 0;
                let bookedRooms = 0;
                let maintenanceRooms = 0;
                let cleaningRooms = 0;
                
                cards.forEach(card => {
                    const statsText = card.querySelector('.space-y-2').textContent;
                    const totalMatch = statsText.match(/Tổng: (\d+)/);
                    const availableMatch = statsText.match(/Trống: (\d+)/);
                    const bookedMatch = statsText.match(/Đã đặt: (\d+)/);
                    const maintenanceMatch = statsText.match(/Bảo trì: (\d+)/);
                    const cleaningMatch = statsText.match(/Dọn dẹp: (\d+)/);
                    
                    if (totalMatch) totalRooms += parseInt(totalMatch[1]);
                    if (availableMatch) availableRooms += parseInt(availableMatch[1]);
                    if (bookedMatch) bookedRooms += parseInt(bookedMatch[1]);
                    if (maintenanceMatch) maintenanceRooms += parseInt(maintenanceMatch[1]);
                    if (cleaningMatch) cleaningRooms += parseInt(cleaningMatch[1]);
                });
                
                return {
                    total: totalRooms,
                    available: availableRooms,
                    booked: bookedRooms,
                    maintenance: maintenanceRooms,
                    cleaning: cleaningRooms,
                    occupancyRate: totalRooms > 0 ? ((bookedRooms / totalRooms) * 100).toFixed(1) : 0
                };
            }
        };
    </script>
</x-app-layout>
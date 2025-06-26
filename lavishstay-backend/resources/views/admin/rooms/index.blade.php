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

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">

                <!-- Filter button -->
                <x-dropdown-filter align="right" />

                <!-- Datepicker built with flatpickr -->
                <x-datepicker />


                
                <!-- Add view button -->
                <button
                    class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path
                            d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
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
                
                cards.forEach(card => {
                    const statsText = card.querySelector('.space-y-2').textContent;
                    const totalMatch = statsText.match(/Tổng: (\d+)/);
                    const availableMatch = statsText.match(/Trống: (\d+)/);
                    const bookedMatch = statsText.match(/Đã đặt: (\d+)/);
                    
                    if (totalMatch) totalRooms += parseInt(totalMatch[1]);
                    if (availableMatch) availableRooms += parseInt(availableMatch[1]);
                    if (bookedMatch) bookedRooms += parseInt(bookedMatch[1]);
                });
                
                return {
                    total: totalRooms,
                    available: availableRooms,
                    booked: bookedRooms,
                    occupancyRate: totalRooms > 0 ? ((bookedRooms / totalRooms) * 100).toFixed(1) : 0
                };
            }
        };
    </script>
</x-app-layout>


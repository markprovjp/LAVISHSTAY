@props([
    'align' => 'right'
])

<div class="relative inline-flex" x-data="notificationDropdown()" x-init="init()">
    <button
        class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 lg:hover:bg-gray-200 dark:hover:bg-gray-700/50 dark:lg:hover:bg-gray-800 rounded-full"
        :class="{ 'bg-gray-200 dark:bg-gray-800': open }"
        aria-haspopup="true"
        @click.prevent="toggleDropdown()"
        :aria-expanded="open"                        
    >
        <span class="sr-only">Notifications</span>
        <svg class="fill-current text-gray-500/80 dark:text-gray-400/80" width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <path d="M7 0a7 7 0 0 0-7 7c0 1.202.308 2.33.84 3.316l-.789 2.368a1 1 0 0 0 1.265 1.265l2.595-.865a1 1 0 0 0-.632-1.898l-.698.233.3-.9a1 1 0 0 0-.104-.85A4.97 4.97 0 0 1 2 7a5 5 0 0 1 5-5 4.99 4.99 0 0 1 4.093 2.135 1 1 0 1 0 1.638-1.148A6.99 6.99 0 0 0 7 0Z" />
            <path d="M11 6a5 5 0 0 0 0 10c.807 0 1.567-.194 2.24-.533l1.444.482a1 1 0 0 0 1.265-1.265l-.482-1.444A4.962 4.962 0 0 0 16 11a5 5 0 0 0-5-5Zm-3 5a3 3 0 0 1 6 0c0 .588-.171 1.134-.466 1.6a1 1 0 0 0-.115.82 1 1 0 0 0-.82.114A2.973 2.973 0 0 1 11 14a3 3 0 0 1-3-3Z" />                                        
        </svg>        
        <!-- Notification Badge -->
        <div 
            x-show="unreadCount > 0" 
            class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-500 border-2 border-gray-100 dark:border-gray-900 rounded-full animate-pulse"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-0"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-0"
        ></div>
        <!-- Notification Count (for larger numbers) -->
        <div 
            x-show="unreadCount > 9" 
            class="absolute -top-1 -right-1 min-w-[18px] h-[18px] bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center border-2 border-gray-100 dark:border-gray-900"
            x-text="unreadCount > 99 ? '99+' : unreadCount"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-0"
            x-transition:enter-end="opacity-100 scale-100"
        ></div>
    </button>
    <div
        class="origin-top-right z-10 absolute top-full -mr-48 sm:mr-0 min-w-80 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/60 py-1.5 rounded-lg shadow-lg overflow-hidden mt-1 {{$align === 'right' ? 'right-0' : 'left-0'}}"                
        @click.outside="open = false"
        @keydown.escape.window="open = false"
        x-show="open"
        x-transition:enter="transition ease-out duration-200 transform"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-out duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-cloak                    
    >
        <!-- Header -->
        <div class="flex items-center justify-between text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase pt-1.5 pb-2 px-4 border-b border-gray-200 dark:border-gray-700/60">
            <span>Notifications</span>
            <div class="flex items-center space-x-2">
                <span x-show="unreadCount > 0" class="text-red-500 normal-case" x-text="`${unreadCount} new`"></span>
                <button 
                    @click="markAllAsRead()" 
                    class="text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300 normal-case text-xs transition-colors duration-200"
                    x-show="unreadCount > 0"
                    :disabled="markingAllAsRead"
                    :class="{ 'opacity-50 cursor-not-allowed': markingAllAsRead }"
                >
                    <span x-show="!markingAllAsRead">Mark all read</span>
                    <span x-show="markingAllAsRead">Marking...</span>
                </button>
            </div>
        </div>
        
        <!-- Loading State -->
        <div x-show="loading" class="px-4 py-8 text-center">
            <div class="inline-flex items-center space-x-2 text-gray-500 dark:text-gray-400">
                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-sm">Loading notifications...</span>
            </div>
        </div>

        <!-- Error State -->
        <div x-show="error && !loading" class="px-4 py-6 text-center">
            <div class="text-red-500 dark:text-red-400 text-sm">
                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p>Failed to load notifications</p>
                <button @click="loadNotifications()" class="mt-2 text-blue-500 hover:text-blue-600 text-xs">
                    Try again
                </button>
            </div>
        </div>
        
        <!-- Notifications List -->
        <ul x-show="!loading && !error" class="max-h-96 overflow-y-auto">
            <template x-for="notification in notifications" :key="notification.id">
                <li class="border-b border-gray-200 dark:border-gray-700/60 last:border-0">
                    <a 
                        class="block py-3 px-4 hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-200 cursor-pointer" 
                        :href="notification.url || '#'" 
                        @click="handleNotificationClick(notification)"
                        :class="{ 
                            'bg-blue-50 dark:bg-blue-900/20 border-l-4 border-l-blue-500': !notification.is_read,
                            'opacity-75': notification.is_read 
                        }"
                    >
                        <div class="flex items-start space-x-3">
                            <!-- Icon with priority color -->
                            <div 
                                class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm"
                                :style="`background-color: ${notification.color || '#3B82F6'}20; color: ${notification.color || '#3B82F6'}`"
                            >
                                <span x-text="notification.icon || '🔔'"></span>
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <span 
                                        class="font-medium text-gray-800 dark:text-gray-100 text-sm truncate"
                                        x-text="notification.title"
                                    ></span>
                                    <!-- Priority Badge -->
                                    <span 
                                        x-show="notification.priority === 'urgent' || notification.priority === 'high'"
                                        class="flex-shrink-0 ml-2 px-2 py-1 text-xs font-medium rounded-full"
                                        :class="{
                                            'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400': notification.priority === 'urgent',
                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400': notification.priority === 'high'
                                        }"
                                        x-text="notification.priority"
                                    ></span>
                                </div>
                                
                                <p 
                                    class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2 mb-2"
                                    x-text="notification.message"
                                ></p>
                                
                                <div class="flex items-center justify-between">
                                    <span 
                                        class="text-xs font-medium text-gray-400 dark:text-gray-500"
                                        x-text="notification.time_ago || 'Just now'"
                                    ></span>
                                    
                                    <!-- Unread indicator -->
                                    <div 
                                        x-show="!notification.is_read"
                                        class="w-2 h-2 bg-blue-500 rounded-full"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            </template>
            
            <!-- Empty State -->
            <li x-show="notifications.length === 0 && !loading && !error" class="px-4 py-8 text-center">
                <div class="text-gray-500 dark:text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM9 7H4l5-5v5zm6 10V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2h6a2 2 0 002-2z"></path>
                    </svg>
                    <p class="text-sm font-medium">No notifications yet</p>
                    <p class="text-xs mt-1">You'll see notifications here when they arrive</p>
                </div>
            </li>
        </ul>

        <!-- Footer -->
        <div x-show="notifications.length > 0 && !loading" class="border-t border-gray-200 dark:border-gray-700/60 px-4 py-2">
            <a 
                href="/admin/notifications" 
                class="block text-center text-sm text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200"
                @click="open = false"
            >
                View all notifications
            </a>
        </div>
    </div>
</div>

<script>
function notificationDropdown() {
    return {
        open: false,
        notifications: [],
        unreadCount: 0,
        loading: true,
        error: false,
        markingAllAsRead: false,
        pusher: null,
        channel: null,
        reconnectAttempts: 0,
        maxReconnectAttempts: 5,

        init() {
            this.loadNotifications();
            this.initPusher();
            this.startPeriodicRefresh();
        },

        async loadNotifications() {
            this.loading = true;
            this.error = false;
            
            try {
                const response = await fetch(window.Laravel?.urls?.notifications?.recent || '/notifications/recent', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': window.Laravel?.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const data = await response.json();
                
                if (data.success) {
                    this.notifications = (data.data?.notifications || []).map(notification => ({
                        ...notification,
                        is_read: !!notification.read_at
                    }));
                    this.unreadCount = data.data?.unread_count || 0;
                } else {
                    throw new Error(data.message || 'Failed to load notifications');
                }

            } catch (error) {
                console.error('Error loading notifications:', error);
                this.error = true;
                this.showErrorToast('Failed to load notifications');
            } finally {
                this.loading = false;
            }
        },

        initPusher() {
            // Skip Pusher initialization if not configured or user not authenticated
            if (typeof Pusher === 'undefined') {
                console.warn('Pusher not loaded, real-time notifications disabled');
                return;
            }

            if (!window.Laravel?.user?.id) {
                console.warn('User not authenticated, real-time notifications disabled');
                return;
            }

            if (!window.Laravel?.pusher?.key) {
                console.warn('Pusher not configured, real-time notifications disabled');
                return;
            }

            try {
                this.pusher = new Pusher(window.Laravel.pusher.key, {
                    cluster: window.Laravel.pusher.cluster,
                    encrypted: window.Laravel.pusher.encrypted,
                    authEndpoint: '/broadcasting/auth',
                    auth: {
                        headers: {
                            'X-CSRF-TOKEN': window.Laravel.csrfToken
                        }
                    }
                });

                this.channel = this.pusher.subscribe(`private-users.${window.Laravel.user.id}`);
                
                this.channel.bind('notification.new', (data) => {
                    this.handleNewNotification(data);
                });

                this.channel.bind('pusher:subscription_succeeded', () => {
                    console.log('Successfully subscribed to notification channel');
                    this.reconnectAttempts = 0;
                });

                this.channel.bind('pusher:subscription_error', (error) => {
                    console.error('Pusher subscription error:', error);
                    this.handlePusherError();
                });

                this.pusher.connection.bind('error', (error) => {
                    console.error('Pusher connection error:', error);
                    this.handlePusherError();
                });

            } catch (error) {
                console.error('Error initializing Pusher:', error);
            }
        },

        handleNewNotification(data) {
            // Add new notification to the beginning of the list
            const newNotification = {
                id: data.id,
                title: data.title,
                message: data.message,
                icon: data.icon,
                color: data.color,
                url: data.url,
                priority: data.priority,
                created_at: data.created_at,
                time_ago: data.time_ago,
                is_read: false
            };

            this.notifications.unshift(newNotification);
            
            // Keep only the latest 10 notifications in dropdown
            if (this.notifications.length > 10) {
                this.notifications = this.notifications.slice(0, 10);
            }

            this.unreadCount++;
            
            // Show browser notification if permission granted
            this.showBrowserNotification(newNotification);
            
            // Show toast notification
            this.showSuccessToast(`New notification: ${data.title}`);
            
            // Add visual feedback
            this.flashNotificationIcon();
        },

        handlePusherError() {
            if (this.reconnectAttempts < this.maxReconnectAttempts) {
                this.reconnectAttempts++;
                setTimeout(() => {
                    console.log(`Attempting to reconnect Pusher (${this.reconnectAttempts}/${this.maxReconnectAttempts})`);
                    this.initPusher();
                }, 5000 * this.reconnectAttempts);
            } else {
                console.error('Max reconnection attempts reached');
            }
        },

        toggleDropdown() {
            this.open = !this.open;
            if (this.open && this.notifications.length === 0 && !this.loading) {
                this.loadNotifications();
            }
        },

        async handleNotificationClick(notification) {
            // Mark as read if not already read
            if (!notification.is_read) {
                await this.markAsRead(notification.id);
            }
            
            // Close dropdown
            this.open = false;
            
            // Navigate to URL if it's not just a hash
            if (notification.url && notification.url !== '#' && notification.url !== '#0') {
                window.location.href = notification.url;
            }
        },

        async markAsRead(notificationId) {
            try {
                const response = await fetch(`/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': window.Laravel?.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    credentials: 'same-origin'
                });

                if (response.ok) {
                    // Update local state
                    const notification = this.notifications.find(n => n.id === notificationId);
                    if (notification && !notification.is_read) {
                        notification.is_read = true;
                        this.unreadCount = Math.max(0, this.unreadCount - 1);
                    }
                }

            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        },

        async markAllAsRead() {
            if (this.unreadCount === 0 || this.markingAllAsRead) return;
            
            this.markingAllAsRead = true;
            
            try {
                const response = await fetch('/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': window.Laravel?.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    credentials: 'same-origin'
                });

                if (response.ok) {
                    // Update local state
                    this.notifications.forEach(notification => {
                        notification.is_read = true;
                    });
                    this.unreadCount = 0;
                    this.showSuccessToast('All notifications marked as read');
                } else {
                    throw new Error('Failed to mark all as read');
                }

            } catch (error) {
                console.error('Error marking all notifications as read:', error);
                this.showErrorToast('Failed to mark all notifications as read');
            } finally {
                this.markingAllAsRead = false;
            }
        },

        startPeriodicRefresh() {
            // Refresh notifications every 5 minutes
            setInterval(() => {
                if (!this.open) {
                    this.loadNotifications();
                }
            }, 5 * 60 * 1000);
        },

        showBrowserNotification(notification) {
            if ('Notification' in window && Notification.permission === 'granted') {
                new Notification(notification.title, {
                    body: notification.message,
                    icon: '/favicon.ico',
                    badge: '/favicon.ico',
                    tag: notification.id,
                    requireInteraction: notification.priority === 'urgent'
                });
            }
        },

        flashNotificationIcon() {
            // Add a flash animation to the notification icon
            const button = this.$el.querySelector('button');
            if (button) {
                button.classList.add('animate-bounce');
                setTimeout(() => {
                    button.classList.remove('animate-bounce');
                }, 1000);
            }
        },

        showSuccessToast(message) {
            if (window.showToast) {
                window.showToast(message, 'success');
            } else {
                console.log('Success:', message);
            }
        },

        showErrorToast(message) {
            if (window.showToast) {
                window.showToast(message, 'error');
            } else {
                console.error('Error:', message);
            }
        },

        destroy() {
            if (this.pusher) {
                this.pusher.disconnect();
            }
        }
    }
}

// Request notification permission on page load
document.addEventListener('DOMContentLoaded', function() {
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission().then(function(permission) {
            console.log('Notification permission:', permission);
        });
    }
});
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom scrollbar for notifications list */
.max-h-96::-webkit-scrollbar {
    width: 4px;
}

.max-h-96::-webkit-scrollbar-track {
    background: transparent;
}

.max-h-96::-webkit-scrollbar-thumb {
    background: rgba(156, 163, 175, 0.5);
    border-radius: 2px;
}

.max-h-96::-webkit-scrollbar-thumb:hover {
    background: rgba(156, 163, 175, 0.7);
}

/* Dark mode scrollbar */
.dark .max-h-96::-webkit-scrollbar-thumb {
    background: rgba(75, 85, 99, 0.5);
}

.dark .max-h-96::-webkit-scrollbar-thumb:hover {
    background: rgba(75, 85, 99, 0.7);
}
</style>


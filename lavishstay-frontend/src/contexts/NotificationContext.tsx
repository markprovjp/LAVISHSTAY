import React, { createContext, useContext, useEffect, useState, useCallback, useRef } from 'react';
import { useSelector } from 'react-redux';
// ...existing code...
import { RootState } from '../store';
import { NotificationData } from '../utils/echo';
import notificationService from '../services/notificationService';

interface NotificationContextType {
    notifications: NotificationData[];
    unreadCount: number;
    loading: boolean;
    fetchNotifications: (page?: number) => Promise<void>;
    markAsRead: (notificationId: string) => Promise<void>;
    markAllAsRead: () => Promise<void>;
    refreshUnreadCount: () => Promise<void>;
}

const NotificationContext = createContext<NotificationContextType | undefined>(undefined);

export const useNotifications = () => {
    const context = useContext(NotificationContext);
    if (!context) {
        throw new Error('useNotifications must be used within a NotificationProvider');
    }
    return context;
};

interface NotificationProviderProps {
    children: React.ReactNode;
}

export const NotificationProvider: React.FC<NotificationProviderProps> = ({ children }) => {
    const [notifications, setNotifications] = useState<NotificationData[]>([]);
    const [unreadCount, setUnreadCount] = useState(0);
    const [loading, setLoading] = useState(false);
    const { isAuthenticated, user } = useSelector((state: RootState) => state.auth);
    // ...existing code...
    const pollRef = useRef<number | null>(null);

    // Debug: log tokens and Echo env on mount for troubleshooting
    useEffect(() => {
        try {
            console.debug('DEBUG Tokens:', {
                accessToken: localStorage.getItem('accessToken'),
                token: localStorage.getItem('token'),
                authToken: localStorage.getItem('authToken'),
            });
            console.debug('DEBUG window.__ECHO_ENV:', (window as any).__ECHO_ENV);
        } catch (e) {
            // ignore
        }
    }, []);

    // Fetch notifications from API
    const fetchNotifications = useCallback(async (page: number = 1) => {
        if (!isAuthenticated) return;

        setLoading(true);
        try {
            const response = await notificationService.getNotifications(page, 20);
            if (page === 1) {
                setNotifications(response.data);
            } else {
                setNotifications(prev => [...prev, ...response.data]);
            }
            setUnreadCount(response.meta.unread_count);
        } catch (error) {
            console.error('Failed to fetch notifications:', error);
        } finally {
            setLoading(false);
        }
    }, [isAuthenticated]);

    // Refresh unread count
    const refreshUnreadCount = useCallback(async () => {
        if (!isAuthenticated) return;

        try {
            const count = await notificationService.getUnreadCount();
            setUnreadCount(count);
        } catch (error) {
            console.error('Failed to refresh unread count:', error);
        }
    }, [isAuthenticated]);

    // Mark single notification as read
    const markAsRead = useCallback(async (notificationId: string) => {
        try {
            const result = await notificationService.markAsRead(notificationId);

            // Update local state
            setNotifications(prev =>
                prev.map(notification =>
                    notification.id === notificationId
                        ? { ...notification, read_at: new Date().toISOString() }
                        : notification
                )
            );
            setUnreadCount(result.unread_count);
        } catch (error) {
            console.error('Failed to mark notification as read:', error);
        }
    }, []);

    // Mark all notifications as read
    const markAllAsRead = useCallback(async () => {
        try {
            await notificationService.markAllAsRead();

            // Update local state
            setNotifications(prev =>
                prev.map(notification => ({
                    ...notification,
                    read_at: notification.read_at || new Date().toISOString()
                }))
            );
            setUnreadCount(0);
        } catch (error) {
            console.error('Failed to mark all notifications as read:', error);
        }
    }, []);

    // (No real-time handler needed in polling-only mode)

    // Polling-only notifications (no real-time websocket)
    useEffect(() => {
        if (!isAuthenticated || !user?.id) return;

        // Start a polling interval to refresh notifications and unread count
        if (!pollRef.current) {
            pollRef.current = window.setInterval(() => {
                fetchNotifications(1);
                refreshUnreadCount();
            }, 15000);
        }

        return () => {
            if (pollRef.current) {
                clearInterval(pollRef.current);
                pollRef.current = null;
            }
        };
    }, [isAuthenticated, user?.id, fetchNotifications, refreshUnreadCount]);

    // Initial fetch when user logs in
    useEffect(() => {
        if (isAuthenticated && user?.id) {
            fetchNotifications();
        } else {
            setNotifications([]);
            setUnreadCount(0);
        }
    }, [isAuthenticated, user?.id, fetchNotifications]);

    const value: NotificationContextType = {
        notifications,
        unreadCount,
        loading,
        fetchNotifications,
        markAsRead,
        markAllAsRead,
        refreshUnreadCount,
    };

    return (
        <NotificationContext.Provider value={value}>
            {children}
        </NotificationContext.Provider>
    );
};

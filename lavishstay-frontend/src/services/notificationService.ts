import api from '../utils/api';
import { NotificationResponse, UnreadCountResponse } from '../utils/echo';

export class NotificationService {
    private static instance: NotificationService;

    public static getInstance(): NotificationService {
        if (!NotificationService.instance) {
            NotificationService.instance = new NotificationService();
        }
        return NotificationService.instance;
    }

    /**
     * Get paginated notifications
     */
    async getNotifications(page: number = 1, perPage: number = 20): Promise<NotificationResponse> {
        try {
            const response = await api.get('/reception/notifications', {
                params: { page, per_page: perPage }
            });
            return response.data;
        } catch (error) {
            console.error('Failed to fetch notifications:', error);
            throw error;
        }
    }

    /**
     * Get unread notifications count
     */
    async getUnreadCount(): Promise<number> {
        try {
            const response = await api.get('/reception/notifications/unread-count');
            const data: UnreadCountResponse = response.data;
            return data.unread_count;
        } catch (error) {
            console.error('Failed to fetch unread count:', error);
            return 0;
        }
    }

    /**
     * Mark notification as read
     */
    async markAsRead(notificationId: string): Promise<{ success: boolean; unread_count: number }> {
        try {
            const response = await api.post(`/reception/notifications/${notificationId}/read`);
            return response.data;
        } catch (error) {
            console.error('Failed to mark notification as read:', error);
            throw error;
        }
    }

    /**
     * Mark all notifications as read
     */
    async markAllAsRead(): Promise<{ success: boolean; unread_count: number }> {
        try {
            const response = await api.post('/reception/notifications/mark-all-read');
            return response.data;
        } catch (error) {
            console.error('Failed to mark all notifications as read:', error);
            throw error;
        }
    }
}

export default NotificationService.getInstance();

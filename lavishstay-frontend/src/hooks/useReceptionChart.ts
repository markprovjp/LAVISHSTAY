// Reception Dashboard Hook
// File: src/hooks/useReceptionChart.ts

import { useState, useEffect } from 'react';
import axios from 'axios';

// Types
export interface RevenueByMonthData {
    date: string;
    month: number;
    month_name: string;
    price: number;
    total_revenue: number;
    booking_count: number;
}

export interface RevenueByCategoryData {
    type: string;
    value: number;
    booking_count: number;
    avg_price: number;
}

export interface ActivityRateData {
    activity_rate: number;
    total_rooms: number;
    occupied_rooms: number;
    available_rooms: number;
    room_stats: Record<string, number>;
    weekly_trend: Array<{ x: number; y: number; date: string }>;
}

export interface ScheduleItem {
    type: 'checkin' | 'checkout';
    time: string;
    title: string;
    description: string;
    booking_id: number;
    room_type: string;
    status: string;
}

export interface NotificationItem {
    id: string;
    type: 'info' | 'warning' | 'error';
    priority: 'normal' | 'medium' | 'high' | 'urgent';
    title: string;
    message: string;
    time: string;
    created_at: string;
    read: boolean;
}

export interface TopServiceItem {
    rank: number;
    keyword: string;
    users: number;
    total_revenue: number;
    avg_price: number;
    rate: string;
    trend_direction: string;
    trend_color: string;
    percentage: number;
}

export interface DashboardStatsData {
    total_bookings: number;
    total_revenue: number;
    visits_trend: number[];
    payments_trend: number[];
    activity_rate: number;
}

// API Base URL - adjust according to your backend setup
const API_BASE = process.env.REACT_APP_API_URL || 'http://localhost:8000';

// Custom hook for Reception Chart APIs
export const useReceptionChart = () => {
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    const apiCall = async <T>(endpoint: string, params?: Record<string, any>): Promise<T | null> => {
        try {
            setLoading(true);
            setError(null);

            const response = await axios.get(`${API_BASE}${endpoint}`, {
                params,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });

            if (response.data.success) {
                return response.data.data;
            } else {
                throw new Error(response.data.message || 'API call failed');
            }
        } catch (err: any) {
            const errorMessage = err.response?.data?.message || err.message || 'Unknown error';
            setError(errorMessage);
            console.error('API Error:', errorMessage, err);
            return null;
        } finally {
            setLoading(false);
        }
    };

    return {
        loading,
        error,

        // API Methods
        getRevenueByMonth: async (year?: number) => {
            return await apiCall<RevenueByMonthData[]>('/api/reception/chart/revenue-by-month', { year });
        },

        getRevenueByCategory: async (period = 'month', date?: string) => {
            return await apiCall<RevenueByCategoryData[]>('/api/reception/chart/revenue-by-category', { period, date });
        },

        getActivityRate: async () => {
            return await apiCall<ActivityRateData>('/api/reception/chart/activity-rate');
        },

        getTodaySchedule: async (date?: string) => {
            const result = await apiCall<{
                timeline: ScheduleItem[];
                check_ins: any[];
                check_outs: any[];
                active_bookings: any[];
                summary: {
                    total_checkins: number;
                    total_checkouts: number;
                    active_bookings: number;
                    date: string;
                };
            }>('/api/reception/chart/today-schedule', { date });
            return result;
        },

        getNotifications: async (limit = 10) => {
            return await apiCall<NotificationItem[]>('/api/reception/chart/notifications', { limit });
        },

        getTopBookedServices: async (period = 'month', limit = 5) => {
            const result = await apiCall<{
                data: TopServiceItem[];
                trends: {
                    user_search: { value: number; trend: string; color: string; data: Array<{ x: number; y: number }> };
                    avg_search: { value: number; trend: string; color: string; data: Array<{ x: number; y: number }> };
                };
                summary: { period: string; total_services: number; total_bookings: number; total_revenue: number };
            }>('/api/reception/chart/top-booked-services', { period, limit });
            return result;
        },

        getDashboardStats: async () => {
            return await apiCall<DashboardStatsData>('/api/reception/chart/dashboard-stats');
        }
    };
};

// Hook for individual dashboard components
export const useDashboardData = () => {
    const [stats, setStats] = useState<DashboardStatsData>({
        total_bookings: 0,
        total_revenue: 0,
        visits_trend: [1200, 1400, 1100, 1600, 1300, 1700, 1500],
        payments_trend: [300, 400, 350, 500, 420, 480, 390],
        activity_rate: 78
    });

    const [revenueData, setRevenueData] = useState<RevenueByMonthData[]>([]);
    const [categoryData, setCategoryData] = useState<RevenueByCategoryData[]>([]);
    const [notifications, setNotifications] = useState<NotificationItem[]>([]);
    const [schedule, setSchedule] = useState<ScheduleItem[]>([]);
    const [topServices, setTopServices] = useState<TopServiceItem[]>([]);

    const { loading, error, ...api } = useReceptionChart();

    const loadDashboardData = async () => {
        try {
            // Load all dashboard data in parallel
            const [
                dashboardStats,
                monthlyRevenue,
                categoryRevenue,
                notificationsList,
                todaySchedule,
                topBookedServices
            ] = await Promise.all([
                api.getDashboardStats(),
                api.getRevenueByMonth(),
                api.getRevenueByCategory(),
                api.getNotifications(),
                api.getTodaySchedule(),
                api.getTopBookedServices()
            ]);

            if (dashboardStats) setStats(dashboardStats);
            if (monthlyRevenue) setRevenueData(monthlyRevenue);
            if (categoryRevenue) setCategoryData(categoryRevenue);
            if (notificationsList) setNotifications(notificationsList);
            if (todaySchedule) setSchedule(todaySchedule.timeline);
            if (topBookedServices) setTopServices(topBookedServices.data);

        } catch (err) {
            console.error('Error loading dashboard data:', err);
        }
    };

    useEffect(() => {
        loadDashboardData();
    }, []);

    return {
        loading,
        error,
        stats,
        revenueData,
        categoryData,
        notifications,
        schedule,
        topServices,
        reload: loadDashboardData
    };
};

export default useReceptionChart;

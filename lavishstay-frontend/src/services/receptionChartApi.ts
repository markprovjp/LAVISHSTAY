// Reception Chart API Service
// File: src/services/receptionChartApi.ts

import { request } from '@/utils/request';

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

export const receptionChartApi = {
    // API doanh thu theo tháng
    getRevenueByMonth: (params?: { year?: number }) =>
        request.get<{
            success: boolean;
            data: RevenueByMonthData[];
            summary: { total_revenue: number; total_bookings: number; year: number };
        }>('/api/reception/chart/revenue-by-month', { params }),

    // API phân loại doanh thu
    getRevenueByCategory: (params?: { period?: 'month' | 'quarter' | 'year'; date?: string }) =>
        request.get<{
            success: boolean;
            data: RevenueByCategoryData[];
            summary: { total_categories: number; total_revenue: number; period: string; date: string };
        }>('/api/reception/chart/revenue-by-category', { params }),

    // API hiệu suất hoạt động
    getActivityRate: () =>
        request.get<{
            success: boolean;
            data: ActivityRateData;
        }>('/api/reception/chart/activity-rate'),

    // API lịch trình hôm nay
    getTodaySchedule: (params?: { date?: string }) =>
        request.get<{
            success: boolean;
            data: {
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
            };
        }>('/api/reception/chart/today-schedule', { params }),

    // API thông báo quan trọng
    getNotifications: (params?: { limit?: number }) =>
        request.get<{
            success: boolean;
            data: NotificationItem[];
            summary: { total_notifications: number; unread_count: number; urgent_count: number };
        }>('/api/reception/chart/notifications', { params }),

    // API xếp hạng đặt phòng
    getTopBookedServices: (params?: { period?: 'week' | 'month' | 'quarter'; limit?: number }) =>
        request.get<{
            success: boolean;
            data: TopServiceItem[];
            trends: {
                user_search: { value: number; trend: string; color: string; data: Array<{ x: number; y: number }> };
                avg_search: { value: number; trend: string; color: string; data: Array<{ x: number; y: number }> };
            };
            summary: { period: string; total_services: number; total_bookings: number; total_revenue: number };
        }>('/api/reception/chart/top-booked-services', { params }),

    // API tổng hợp thống kê dashboard
    getDashboardStats: () =>
        request.get<{
            success: boolean;
            data: DashboardStatsData;
        }>('/api/reception/chart/dashboard-stats'),
};

export default receptionChartApi;

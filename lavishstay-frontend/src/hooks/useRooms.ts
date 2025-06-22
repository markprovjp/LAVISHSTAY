// src/hooks/useRooms.ts
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { roomsAPI } from '../utils/api';
import { message } from 'antd';

// Get all rooms
export const useGetRooms = (params?: any) => {
    return useQuery({
        queryKey: ['rooms', params],
        queryFn: () => roomsAPI.getAll(params),
        staleTime: 5 * 60 * 1000, // 5 minutes
        gcTime: 10 * 60 * 1000, // 10 minutes
    });
};

// Get rooms by type (support both ID and slug)
export const useGetRoomsByType = (roomTypeIdOrSlug: number | string, params?: any) => {
    return useQuery({
        queryKey: ['rooms', 'by-type', roomTypeIdOrSlug, params],
        queryFn: () => {
            // If it's a string (slug), we need to fetch by slug
            if (typeof roomTypeIdOrSlug === 'string') {
                return roomsAPI.getByTypeSlug(roomTypeIdOrSlug, params);
            }
            // If it's a number (ID), use the existing method
            return roomsAPI.getByType(roomTypeIdOrSlug, params);
        },
        enabled: !!roomTypeIdOrSlug,
        staleTime: 5 * 60 * 1000,
        gcTime: 10 * 60 * 1000,
    });
};

// Get room by ID
export const useGetRoom = (id: number) => {
    return useQuery({
        queryKey: ['rooms', id],
        queryFn: () => roomsAPI.getById(id),
        enabled: !!id,
        staleTime: 5 * 60 * 1000,
        gcTime: 10 * 60 * 1000,
    });
};

// Get room calendar data
export const useGetRoomCalendar = (roomId: number, params?: any) => {
    return useQuery({
        queryKey: ['rooms', roomId, 'calendar', params],
        queryFn: () => roomsAPI.getCalendarData(roomId, params),
        enabled: !!roomId,
        staleTime: 2 * 60 * 1000, // 2 minutes (shorter for calendar data)
        gcTime: 5 * 60 * 1000, // 5 minutes
    });
};

// Create room mutation
export const useCreateRoom = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: roomsAPI.create,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['rooms'] });
            message.success('Tạo phòng thành công!');
        },
        onError: (error: any) => {
            message.error(error.response?.data?.message || 'Có lỗi xảy ra khi tạo phòng');
        },
    });
};

// Update room mutation
export const useUpdateRoom = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({ id, data }: { id: number; data: any }) => roomsAPI.update(id, data),
        onSuccess: (_, variables) => {
            queryClient.invalidateQueries({ queryKey: ['rooms'] });
            queryClient.invalidateQueries({ queryKey: ['rooms', variables.id] });
            message.success('Cập nhật phòng thành công!');
        },
        onError: (error: any) => {
            message.error(error.response?.data?.message || 'Có lỗi xảy ra khi cập nhật phòng');
        },
    });
};

// Delete room mutation
export const useDeleteRoom = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: roomsAPI.delete,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['rooms'] });
            message.success('Xóa phòng thành công!');
        },
        onError: (error: any) => {
            message.error(error.response?.data?.message || 'Có lỗi xảy ra khi xóa phòng');
        },
    });
};

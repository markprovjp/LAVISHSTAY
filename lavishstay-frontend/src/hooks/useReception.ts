// src/hooks/useReception.ts
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { receptionAPI } from '../utils/api';
import { message } from 'antd';

// Get all rooms for reception dashboard
export const useGetReceptionRooms = (params?: any) => {
    return useQuery({
        queryKey: ['reception', 'rooms','room_types', params],
        queryFn: () => receptionAPI.getRooms(params),
        staleTime: 2 * 60 * 1000, // 2 minutes (shorter for real-time data)
        gcTime: 5 * 60 * 1000, // 5 minutes
    });
};

// Get room statistics
export const useGetRoomStatistics = (params?: any) => {
    return useQuery({
        queryKey: ['reception', 'room-statistics', params],
        queryFn: () => receptionAPI.getRoomStatistics(params),
        staleTime: 1 * 60 * 1000, // 1 minute (frequent updates)
        gcTime: 5 * 60 * 1000,
        refetchInterval: 30 * 1000, // Auto-refresh every 30 seconds
    });
};

// Get room details
export const useGetRoomDetails = (roomId: number) => {
    return useQuery({
        queryKey: ['reception', 'room-details', roomId],
        queryFn: () => receptionAPI.getRoomDetails(roomId),
        enabled: !!roomId,
        staleTime: 2 * 60 * 1000,
        gcTime: 5 * 60 * 1000,
    });
};

// Get room bookings for timeline
export const useGetRoomBookings = (params?: any) => {
    return useQuery({
        queryKey: ['reception', 'room-bookings', params],
        queryFn: () => receptionAPI.getRoomBookings(params),
        staleTime: 1 * 60 * 1000, // 1 minute
        gcTime: 5 * 60 * 1000,
    });
};

// Get floors for filtering
export const useGetFloors = () => {
    return useQuery({
        queryKey: ['reception', 'floors'],
        queryFn: () => receptionAPI.getFloors(),
        staleTime: 10 * 60 * 1000, // 10 minutes (rarely changes)
        gcTime: 30 * 60 * 1000, // 30 minutes
    });
};

// Get room types for filtering
export const useGetReceptionRoomTypes = () => {
    return useQuery({
        queryKey: ['reception', 'room-types'],
        queryFn: () => receptionAPI.getRoomTypes(),
        staleTime: 10 * 60 * 1000, // 10 minutes
        gcTime: 30 * 60 * 1000, // 30 minutes
    });
};

// Update room status mutation
export const useUpdateRoomStatus = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({ roomId, status }: { roomId: number; status: string }) =>
            receptionAPI.updateRoomStatus(roomId, status),
        onSuccess: () => {
            message.success('Cập nhật trạng thái phòng thành công');
            // Invalidate and refetch related queries
            queryClient.invalidateQueries({ queryKey: ['reception', 'rooms'] });
            queryClient.invalidateQueries({ queryKey: ['reception', 'room-statistics'] });
        },
        onError: (error: any) => {
            message.error(`Lỗi cập nhật trạng thái phòng: ${error.message}`);
        },
    });
};

// Transfer booking mutation
export const useTransferBooking = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (params: {
            booking_id: number;
            old_room_id: number;
            new_room_id: number;
            reason?: string;
        }) => receptionAPI.transferBooking(params),
        onSuccess: () => {
            message.success('Chuyển phòng thành công');
            // Invalidate and refetch related queries
            queryClient.invalidateQueries({ queryKey: ['reception', 'rooms'] });
            queryClient.invalidateQueries({ queryKey: ['reception', 'room-bookings'] });
            queryClient.invalidateQueries({ queryKey: ['reception', 'room-statistics'] });
        },
        onError: (error: any) => {
            message.error(`Lỗi chuyển phòng: ${error.message}`);
        },
    });
};

// Check-in mutation
export const useCheckIn = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (params: {
            booking_id: number;
            room_id: number;
            actual_check_in_time?: string;
        }) => receptionAPI.checkIn(params),
        onSuccess: () => {
            message.success('Nhận phòng thành công');
            // Invalidate and refetch related queries
            queryClient.invalidateQueries({ queryKey: ['reception', 'rooms'] });
            queryClient.invalidateQueries({ queryKey: ['reception', 'room-bookings'] });
            queryClient.invalidateQueries({ queryKey: ['reception', 'room-statistics'] });
        },
        onError: (error: any) => {
            message.error(`Lỗi nhận phòng: ${error.message}`);
        },
    });
};

// Check-out mutation
export const useCheckOut = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (params: {
            booking_id: number;
            room_id: number;
            actual_check_out_time?: string;
        }) => receptionAPI.checkOut(params),
        onSuccess: () => {
            message.success('Trả phòng thành công');
            // Invalidate and refetch related queries
            queryClient.invalidateQueries({ queryKey: ['reception', 'rooms'] });
            queryClient.invalidateQueries({ queryKey: ['reception', 'room-bookings'] });
            queryClient.invalidateQueries({ queryKey: ['reception', 'room-statistics'] });
        },
        onError: (error: any) => {
            message.error(`Lỗi trả phòng: ${error.message}`);
        },
    });
};

// src/hooks/useRoomTypes.ts
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { roomTypesAPI } from '../utils/api';
import { message } from 'antd';

// Get all room types
export const useGetRoomTypes = (params?: any) => {
    return useQuery({
        queryKey: ['room-types', params],
        queryFn: () => roomTypesAPI.getAll(params),
        staleTime: 5 * 60 * 1000, // 5 minutes
        gcTime: 10 * 60 * 1000, // 10 minutes (renamed from cacheTime in newer versions)
    });
};

// Get room type by ID
export const useGetRoomType = (id: number) => {
    return useQuery({
        queryKey: ['room-types', id],
        queryFn: () => roomTypesAPI.getById(id),
        enabled: !!id,
        staleTime: 5 * 60 * 1000,
        gcTime: 10 * 60 * 1000,
    });
};

// Create room type mutation
export const useCreateRoomType = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: roomTypesAPI.create,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['room-types'] });
            message.success('Tạo loại phòng thành công!');
        },
        onError: (error: any) => {
            message.error(error.response?.data?.message || 'Có lỗi xảy ra khi tạo loại phòng');
        },
    });
};

// Update room type mutation
export const useUpdateRoomType = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({ id, data }: { id: number; data: any }) => roomTypesAPI.update(id, data),
        onSuccess: (_, variables) => {
            queryClient.invalidateQueries({ queryKey: ['room-types'] });
            queryClient.invalidateQueries({ queryKey: ['room-types', variables.id] });
            message.success('Cập nhật loại phòng thành công!');
        },
        onError: (error: any) => {
            message.error(error.response?.data?.message || 'Có lỗi xảy ra khi cập nhật loại phòng');
        },
    });
};

// Delete room type mutation
export const useDeleteRoomType = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: roomTypesAPI.delete,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['room-types'] });
            message.success('Xóa loại phòng thành công!');
        },
        onError: (error: any) => {
            message.error(error.response?.data?.message || 'Có lỗi xảy ra khi xóa loại phòng');
        },
    });
};

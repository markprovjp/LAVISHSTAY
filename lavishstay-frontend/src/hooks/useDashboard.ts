// src/hooks/useDashboard.ts
import { useQuery } from '@tanstack/react-query';
import { dashboardAPI } from '../utils/api';

// Get room statistics
export const useGetRoomStatistics = () => {
    return useQuery({
        queryKey: ['dashboard', 'room-statistics'],
        queryFn: dashboardAPI.getRoomStatistics,
        staleTime: 2 * 60 * 1000, // 2 minutes
        gcTime: 5 * 60 * 1000, // 5 minutes
        refetchInterval: 5 * 60 * 1000, // Auto refetch every 5 minutes
    });
};

// Get filter options
export const useGetFilterOptions = () => {
    return useQuery({
        queryKey: ['dashboard', 'filter-options'],
        queryFn: dashboardAPI.getFilterOptions,
        staleTime: 30 * 60 * 1000, // 30 minutes (filter options don't change often)
        gcTime: 60 * 60 * 1000, // 1 hour
    });
};

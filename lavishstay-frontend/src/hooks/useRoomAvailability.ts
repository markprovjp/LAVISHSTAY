import { useQuery } from '@tanstack/react-query';
import api from '../utils/api';

interface RoomAvailabilityParams {
    check_in_date?: string;
    check_out_date?: string;
    enabled?: boolean;
}

interface RoomType {
    room_type_id: number;
    room_code: string;
    name: string;
    description: string;
    base_price: number;
    adjusted_price: number;
    size: number;
    max_guests: number;
    rating: number;
    images: Array<{
        id: number;
        room_type_id: number;
        image_url: string;
        alt_text: string;
        is_main: boolean;
    }>;
    main_image?: {
        id: number;
        room_type_id: number;
        image_url: string;
        alt_text: string;
        is_main: boolean;
    };
    amenities: Array<{
        id: number;
        name: string;
        icon: string;
        category: string;
        description?: string;
    }>;
    highlighted_amenities: Array<{
        id: number;
        name: string;
        icon: string;
        category: string;
        description?: string;
    }>;
    available_room_count: number;
    available_rooms: Array<{
        room_id: number;
        room_name: string;
        bed_type_name: string;
        room_status: string;
    }>;
    pricing_summary: {
        nights: number;
        price_per_night: number;
        total_price: number;
        currency: string;
    };
}

interface RoomAvailabilityResponse {
    success: boolean;
    data: RoomType[];
    summary: {
        total_room_types: number;
        total_available_rooms: number;
        search_criteria: {
            check_in_date: string;
            check_out_date: string;
        };
    };
    message: string;
}

export const useGetAvailableRooms = (params: RoomAvailabilityParams) => {
    return useQuery<RoomAvailabilityResponse>({
        queryKey: ['available-rooms', params.check_in_date, params.check_out_date],
        queryFn: async () => {
            const searchParams = new URLSearchParams();

            if (params.check_in_date) {
                searchParams.append('check_in_date', params.check_in_date);
            }
            if (params.check_out_date) {
                searchParams.append('check_out_date', params.check_out_date);
            }

            const response = await api.get(`/rooms/available?${searchParams.toString()}`);
            return response.data;
        },
        enabled: params.enabled !== false && !!(params.check_in_date && params.check_out_date),
        staleTime: 1000 * 60 * 5, // 5 minutes
        retry: 2,
    });
};

// Hook để debug database structure (có thể dùng khi cần troubleshoot)
export const useDebugDatabase = () => {
    return useQuery({
        queryKey: ['debug-database'],
        queryFn: async () => {
            const response = await api.get('/rooms/debug-database');
            return response.data;
        },
        enabled: false, // Chỉ chạy khi manually trigger
    });
};

// Hook để debug booking conflicts
export const useDebugBookingConflicts = (params: {
    check_in_date?: string;
    check_out_date?: string;
    room_type_id?: number;
}) => {
    return useQuery({
        queryKey: ['debug-booking-conflicts', params],
        queryFn: async () => {
            const searchParams = new URLSearchParams();
            if (params.check_in_date) searchParams.append('check_in_date', params.check_in_date);
            if (params.check_out_date) searchParams.append('check_out_date', params.check_out_date);
            if (params.room_type_id) searchParams.append('room_type_id', params.room_type_id.toString());

            const response = await api.get(`/rooms/debug-booking-conflicts?${searchParams.toString()}`);
            return response.data;
        },
        enabled: false, // Chỉ chạy khi manually trigger
    });
};

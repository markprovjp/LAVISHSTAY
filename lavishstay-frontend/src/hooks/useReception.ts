// src/hooks/useReception.ts
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { receptionAPI } from '../utils/api';
import { message } from 'antd';
import { BookingFilters, CreateBookingRequest, CreateMultiRoomBookingRequest } from '../types/booking';

// Interface for the assignment payload
interface Assignment {
    booking_room_id: number;
    room_id: number;
}

// Interface for the booking quote payload
export interface BookingQuotePayload {
    checkInDate: string;
    checkOutDate: string;
    rooms: Array<{
        room_id: string;
        adults: number;
        children: Array<{ age: number }>;
    }>;
}
// export const useGetReceptionRooms = (params: any = {}) => {
//     return useQuery({
//         queryKey: ['receptionRooms', params],
//         queryFn: () => receptionService.getRooms(params),
//     });
// };

// export const useGetRoomDetails = (roomId: string | number) => {
//     return useQuery({
//         queryKey: ['roomDetails', roomId],
//         queryFn: () => receptionService.getRoomDetails(roomId),
//         enabled: !!roomId, // Chỉ chạy query khi roomId có giá trị
//     });
// };

// Get all rooms for reception dashboard
export const useGetReceptionRooms = (params?: any) => {
    return useQuery({
        queryKey: ['reception', 'rooms', params],
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
            new_room_ids: number[];
            new_option_id: number;
            reason?: string;
        }) => receptionAPI.transferBooking({
            new_room_ids: params.new_room_ids.map(Number),
            new_option_id: Number(params.new_option_id),
            reason: params.reason,
        }),
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

// Booking Management hooks
export const useGetBookings = (params?: BookingFilters) => {
    return useQuery({
        queryKey: ['bookings', params],
        queryFn: () => receptionAPI.getBookings(params),
        staleTime: 1 * 60 * 1000, // 1 minute
        gcTime: 5 * 60 * 1000,
    });
};

export const useGetBookingStatistics = () => {
    return useQuery({
        queryKey: ['booking-statistics'],
        queryFn: () => receptionAPI.getBookingStatistics(),
        staleTime: 2 * 60 * 1000,
        gcTime: 5 * 60 * 1000,
        refetchInterval: 60 * 1000, // Refresh every minute
    });
};

export const useGetAssignmentPreview = (bookingId: number) => {
    return useQuery({
        queryKey: ['assignment-preview', bookingId],
        queryFn: () => receptionAPI.getAssignmentPreview(bookingId),
        enabled: !!bookingId, // Only run query if bookingId is available
        staleTime: 1 * 60 * 1000, // 1 minute
    });
};

// New hook for assigning multiple rooms
export const useAssignMultipleRooms = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (assignments: Assignment[]) => receptionAPI.assignMultipleRooms(assignments),
        onSuccess: () => {
            message.success('Gán phòng thành công!');
            // Invalidate relevant queries to refetch data
            queryClient.invalidateQueries({ queryKey: ['assignment-preview'] });
            queryClient.invalidateQueries({ queryKey: ['reception', 'rooms'] });
            queryClient.invalidateQueries({ queryKey: ['reception', 'room-statistics'] });
            queryClient.invalidateQueries({ queryKey: ['reception', 'room-bookings'] });
            queryClient.invalidateQueries({ queryKey: ['bookings'] });
        },
        onError: (error: any) => {
            message.error(`Lỗi gán phòng: ${error.response?.data?.message || error.message}`);
        },
    });
};

// export const useCreateBooking = () => {
//     const queryClient = useQueryClient();

//     return useMutation({
//         mutationFn: (data: CreateBookingRequest) => receptionAPI.createBooking(data),
//         onSuccess: () => {
//             message.success('Đặt phòng thành công');
//             queryClient.invalidateQueries({ queryKey: ['bookings'] });
//             queryClient.invalidateQueries({ queryKey: ['booking-statistics'] });
//             queryClient.invalidateQueries({ queryKey: ['reception', 'rooms'] });
//         },
//         onError: (error: any) => {
//             message.error(`Lỗi tạo đặt phòng: ${error.response?.data?.message || error.message}`);
//         },
//     });
// };




export const useUpdateBookingStatus = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({ bookingId, status }: { bookingId: number; status: string }) =>
            receptionAPI.updateBookingStatus(bookingId, status),
        onSuccess: () => {
            message.success('Cập nhật trạng thái đặt phòng thành công');
            queryClient.invalidateQueries({ queryKey: ['bookings'] });
            queryClient.invalidateQueries({ queryKey: ['booking-statistics'] });
            queryClient.invalidateQueries({ queryKey: ['reception', 'rooms'] });
        },
        onError: (error: any) => {
            message.error(`Lỗi cập nhật trạng thái: ${error.response?.data?.message || error.message}`);
        },
    });
};

export const useCancelBooking = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (bookingId: number) => receptionAPI.cancelBooking(bookingId),
        onSuccess: () => {
            message.success('Hủy đặt phòng thành công');
            queryClient.invalidateQueries({ queryKey: ['bookings'] });
            queryClient.invalidateQueries({ queryKey: ['booking-statistics'] });
            queryClient.invalidateQueries({ queryKey: ['reception', 'rooms'] });
        },
        onError: (error: any) => {
            message.error(`Lỗi hủy đặt phòng: ${error.response?.data?.message || error.message}`);
        },
    });
};

// Define a specific type for available rooms parameters for better type safety
interface AvailableRoomsParams {
    check_in_date?: string;
    check_out_date?: string;
    room_type_id?: number;
}

// Get available rooms for specific dates
export const useGetAvailableRooms = (params?: AvailableRoomsParams, options?: { enabled?: boolean }) => {
    return useQuery({
        queryKey: ['room-availability', params],
        queryFn: () => {
            if (!params?.check_in_date || !params?.check_out_date) {
                return Promise.resolve({ data: [] });
            }

            // Use URLSearchParams to dynamically build the query string
            const searchParams = new URLSearchParams();
            searchParams.append('check_in_date', params.check_in_date);
            searchParams.append('check_out_date', params.check_out_date);
            if (params.room_type_id) {
                searchParams.append('room_type_id', params.room_type_id.toString());
            }

            const url = `http://localhost:8888/api/rooms/available?${searchParams.toString()}`;
            console.log('API request:', url); // Debug log
            return fetch(url).then(res => res.json());
        },
        enabled: options?.enabled ?? (!!params?.check_in_date && !!params?.check_out_date),
        staleTime: 1 * 60 * 1000,
        gcTime: 5 * 60 * 1000,
    });
};

// --- NEW: Hook for calculating booking price ---
export const useCalculateBookingQuote = () => {
    return useMutation({
        mutationFn: (payload: BookingQuotePayload) => receptionAPI.calculatePrice(payload),
        onSuccess: (data) => {
            // You can optionally show a success message here if needed
            // message.success('Tải báo giá thành công!');
            return data;
        },
        onError: (error: any) => {
            // message.error(`Lỗi tính giá: ${error.response?.data?.message || error.message}`);
        },
    });
};

export const useCreateBooking = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: (bookingData: CreateMultiRoomBookingRequest) => receptionAPI.createBooking(bookingData),
        onSuccess: () => {
            // Invalidate and refetch relevant queries after a booking is created
            queryClient.invalidateQueries({ queryKey: ['bookings'] });
            queryClient.invalidateQueries({ queryKey: ['roomStatistics'] });
            queryClient.invalidateQueries({ queryKey: ['rooms'] });
        },
    });
};

export const useConfirmPaidBooking = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: (data: { booking_id: number; transaction_id?: string }) =>
            receptionAPI.confirmBooking(data.booking_id, data.transaction_id),
        onSuccess: (data, variables) => {
            // Invalidate and refetch relevant queries after confirmation
            queryClient.invalidateQueries({ queryKey: ['bookings'] });
            queryClient.invalidateQueries({ queryKey: ['booking', variables.booking_id] });
            queryClient.invalidateQueries({ queryKey: ['roomStatistics'] });
            queryClient.invalidateQueries({ queryKey: ['rooms'] });
        },
    });
};



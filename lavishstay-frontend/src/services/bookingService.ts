import axiosInstance from '../config/axios';
import { User } from './authService';

export interface Booking {
    booking_id: number;
    booking_code: string;
    check_in_date: string;
    check_out_date: string;
    status: string;
    total_price_vnd: number;
    created_at: string;
    room_type: string;
    room_name: string;
    room_image?: string;
    payment_amount?: number;
    payment_status?: string;
}

export interface GetUserBookingsResponse {
    success: boolean;
    bookings: Booking[];
}

const bookingService = {
    // Lấy danh sách booking của user hiện tại
    getUserBookings: async (): Promise<Booking[]> => {
        try {
            const response = await axiosInstance.get<GetUserBookingsResponse>('/user/bookings');
            if (response.data.success) {
                return response.data.bookings;
            } else {
                throw new Error('Không thể lấy danh sách booking');
            }
        } catch (error: any) {
            if (error.response?.data?.message) {
                throw new Error(error.response.data.message);
            }
            throw error;
        }
    }
};

export default bookingService;

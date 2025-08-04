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


// Kiểu dữ liệu trả về từ API chính sách huỷ
export interface CancelPolicyResponse {
    message: string;
    reason: string;
    formula: string;
    policy: string;
    penalty: number;
    penalty_type: string;
    penalty_percentage: string;
    penalty_fixed_amount: number;
    booking_info: any;
    room_info: any;
    hotel_info: any;
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
    },

<<<<<<< Updated upstream
    // Lấy chính sách huỷ phòng (dùng GET endpoint riêng, không huỷ thật)
    getCancelPolicy: async (bookingId: number | string): Promise<CancelPolicyResponse> => {
        try {
            const response = await axiosInstance.get(`/cancel-booking/${bookingId}`);
=======
    // Lấy chính sách huỷ phòng (GET hoặc POST đều được, tuỳ backend, ở đây dùng POST như mô tả)
    getCancelPolicy: async (bookingId: number | string): Promise<CancelPolicyResponse> => {
        try {
            const response = await axiosInstance.post(`/cancel-booking/${bookingId}`);
>>>>>>> Stashed changes
            return response.data;
        } catch (error: any) {
            if (error.response?.data) {
                throw error.response.data;
            }
            throw error;
        }
    },

    // Xác nhận huỷ thật (POST lại endpoint này, có thể truyền thêm param nếu backend yêu cầu)
    confirmCancelBooking: async (bookingId: number | string): Promise<CancelPolicyResponse> => {
        try {
            const response = await axiosInstance.post(`/cancel-booking/${bookingId}`, { confirm: true });
            return response.data;
        } catch (error: any) {
            if (error.response?.data) {
                throw error.response.data;
            }
            throw error;
        }
    }
};

export default bookingService;

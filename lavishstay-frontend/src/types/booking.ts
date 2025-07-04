// Booking Management Types
export interface Booking {
    id: number;
    booking_code: string;
    guest_name: string;
    guest_email: string;
    guest_phone: string;
    guest_count: number;
    room_id: number;
    room?: Room;
    check_in_date: string;
    check_out_date: string;
    total_amount: number;
    deposit_amount: number;
    payment_status: BookingPaymentStatus;
    booking_status: BookingStatus;
    payment_method?: string;
    notes?: string;
    created_at: string;
    updated_at: string;
    actual_check_in_time?: string;
    actual_check_out_time?: string;
    special_requests?: string;
    extra_services?: ExtraService[];
}

export interface Room {
    id: number;
    name: string;
    room_type: RoomType;
    floor: number;
    status: string;
}

export interface RoomType {
    id: number;
    name: string;
    base_price: number;
    max_guests: number;
    description?: string;
}

export interface ExtraService {
    id: number;
    name: string;
    price: number;
    quantity: number;
}

export type BookingPaymentStatus = 'pending' | 'paid' | 'partial' | 'refunded' | 'failed';
export type BookingStatus = 'pending' | 'confirmed' | 'checked_in' | 'checked_out' | 'cancelled' | 'no_show';

export interface BookingFilters {
    guest_name?: string;
    booking_code?: string;
    payment_status?: BookingPaymentStatus;
    booking_status?: BookingStatus;
    check_in_date?: string;
    check_out_date?: string;
    room_number?: string;
    date_range?: [string, string];
}

export interface CreateBookingRequest {
    guest_name: string;
    guest_email: string;
    guest_phone: string;
    guest_count: number;
    room_id: number;
    check_in_date: string;
    check_out_date: string;
    payment_method: string;
    deposit_amount?: number;
    special_requests?: string;
    extra_services?: { service_id: number; quantity: number }[];
}

export interface BookingStatistics {
    total_bookings: number;
    pending_bookings: number;
    confirmed_bookings: number;
    checked_in_bookings: number;
    checked_out_bookings: number;
    cancelled_bookings: number;
    total_revenue: number;
    pending_revenue: number;
    confirmed_revenue: number;
}

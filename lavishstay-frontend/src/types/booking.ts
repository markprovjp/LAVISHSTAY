// Booking Management Types
export interface Booking {
    booking_id: number; // Primary key theo schema
    booking_code: string;
    user_id?: number;
    option_id?: string;
    check_in_date: string;
    check_out_date: string;
    total_price_vnd: number; // Tên đúng theo schema
    guest_count: number;
    adults: number;
    children?: number;
    children_age?: number[]; // JSON field
    status: 'pending' | 'confirmed' | 'cancelled' | 'completed'; // Enum theo schema
    quantity?: number;
    created_at: string;
    updated_at: string;
    guest_name?: string;
    guest_email?: string;
    guest_phone?: string;
    room_id?: number;
    room?: Room;
    
    // Compatibility fields (map to actual schema fields)
    id?: number; // Map to booking_id
    total_amount?: number; // Map to total_price_vnd
    payment_status?: BookingPaymentStatus; // Not in schema, optional
    booking_status?: BookingStatus; // Map to status
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
export type BookingStatus = 'pending' | 'confirmed' | 'cancelled' | 'completed';

export interface BookingFilters {
    guest_name?: string;
    guest_phone?: string;
    booking_code?: string;
    payment_status?: BookingPaymentStatus;
    booking_status?: BookingStatus;
    check_in_date?: string;
    check_out_date?: string;
    room_number?: string;
    date_range?: [Date, Date];
    created_date_range?: [Date, Date];
}

// For single room booking from reception
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

// For multi-room booking from the new dashboard flow
export interface RepresentativeInfo {
    fullName: string;
    phoneNumber: string;
    email: string;
    idCard: string;
}

export interface CreateMultiRoomBookingRequest {
    booking_details: {
        check_in_date: string;
        check_out_date: string;
        adults: number;
        children: { age: number }[];
        total_price: number;
        status: string;
    };
    rooms: {
        room_id: string;
        package_id: number;
    }[];
    representative_info: {
        mode: 'all' | 'individual';
        details: RepresentativeInfo | Record<string, RepresentativeInfo>;
    };
    payment_method: string;
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
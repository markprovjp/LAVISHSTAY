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

// Additional types for LookupBookingByPhone enhancement
export interface RoomDetail {
    id: number;
    room_name: string;
    room_type: string;
    floor?: string;
    option_name?: string;
    adults?: number;
    children?: number;
    nights?: number;
    quantity: number;
    price_per_night: number;
    total_price: number;
}

export interface PaymentRecord {
    id: number;
    payment_id: number;
    method?: string;
    payment_type?: string;
    amount: number;
    amount_vnd?: string;
    status: string;
    created_at: string;
}

export interface Representative {
    id?: number;
    full_name?: string;
    phone_number?: string;
    email?: string;
    id_card?: string;
    // Legacy format for compatibility
    name?: string;
    phone?: string;
}

export interface BookingSummary {
    booking_id: number;
    booking_code: string;
    guest_name: string;
    guest_phone: string;
    guest_email?: string;
    check_in_date: string;
    check_out_date: string;
    nights?: number;
    total_price_vnd: number | string;
    total_price_formatted?: string;
    total_price_raw?: number;
    status: string;
    guest_count?: number;
    created_at: string;
    room_id?: number;
    room_name?: string;
    room_type?: string;
    room_type_images?: Array<{
        image_id: number;
        image_path: string;
        alt_text: string;
        is_main: number;
    }>;
    // Fields to be enriched from detail endpoint
    rooms?: Array<{
        room_id: number;
        room_name: string;
        room_type: string;
        option_name: string;
        adults: number;
        children: number;
        total_price: string;
    }>;
    rooms_detail?: RoomDetail[];
    booking_rooms?: Array<{
        id?: number;
        room_id?: number;
        room_name?: string;
        room_type?: string;
        option_name?: string;
        quantity?: number;
        price_per_night?: number;
        total_price?: number;
    }>;
    payments?: PaymentRecord[];
    representatives?: Representative[];
    notes?: string;
    updated_at?: string;
    total_paid?: number;
}

export interface BookingDetail extends BookingSummary {
    id: number;
    total_amount?: number;
    representatives: Representative[];
    rooms_detail: RoomDetail[];
    booking_rooms: Array<{
        id: number;
        room_type: string;
        room_name?: string;
        quantity: number;
        price_per_night: number;
        total_price: number;
    }>;
    payments: PaymentRecord[];
    notes?: string;
    updated_at: string;
}

export interface SearchResponse {
    success: boolean;
    data: BookingSummary[];
    meta: {
        total: number;
        page: number;
        per_page: number;
        last_page: number;
        search_type: string;
        search_term: string;
        // Optional filter counts
        status_counts?: Record<string, number>;
    };
}

export interface SearchFilters {
    search?: string;
    status?: string;
    from?: string;
    to?: string;
    room_type?: string;
    page?: number;
    per_page?: number;
}

// Coupon related types
export interface CouponRedemption {
    coupon_code: string;
    amount_saved: number;
    booking_code: string;
    redeemed_at: string;
    coupon?: {
        code: string;
        description: string;
        type: 'percent' | 'fixed';
        value: number;
    };
}

export interface CouponRedemptionsResponse {
    success: boolean;
    data: CouponRedemption[];
    pagination: {
        current_page: number;
        per_page: number;
        total: number;
    };
}
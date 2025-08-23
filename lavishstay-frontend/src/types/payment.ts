export interface BookingService {
    booking_service_id: number;
    service_id: number;
    service_name: string;
    service_description?: string;
    quantity: number;
    unit_price_vnd: string | number;
    total_price_vnd: number;
    paid_amount_vnd: string | number;
    outstanding_amount_vnd: number;
    payment_status: 'pending' | 'partial' | 'paid';
    payment_percentage: number;
    formatted_total_price: string;
    formatted_paid_amount: string;
    formatted_outstanding: string;
}

export interface ServicePaymentSummary {
    total_service_amount: number;
    total_paid_amount: number;
    total_outstanding: number;
    payment_percentage: number;
    services_count: number;
    pending_services: number;
    partial_services: number;
    paid_services: number;
}

export interface ServicePaymentInfo {
    booking_id: number;
    booking_code: string;
    services: BookingService[];
    summary: ServicePaymentSummary;
}

export interface BankInfo {
    bank_id: string;
    account_no: string;
    account_name: string;
}

export interface QRResponse {
    payment_id: number;
    qr_url: string;
    amount: number;
    formatted_amount: string;
    payment_content: string;
    transaction_id: string;
    bank_info: BankInfo;
    selected_services: number[];
    expires_at: string; // ISO datetime string
}

export interface PaymentCheckRequest {
    payment_id: number;
    booking_code: string;
    amount: number;
    service_ids?: number[];
}

export interface PaymentCheckResponse {
    success: boolean;
    payment_found: boolean;
    message: string;
    payment?: {
        payment_id: number;
        amount: number;
        status: string;
        transaction_id: string;
        confirmed_at: string;
    };
    // Optional flags returned by backend
    booking_id?: number;
    can_checkout?: boolean;
}

export interface PaymentHistoryItem {
    payment_id: number;
    amount_vnd: number;
    formatted_amount: string;
    payment_type: string;
    status: string;
    transaction_id: string;
    created_at: string;
    updated_at: string;
    formatted_date: string;
}

export interface PaymentHistory {
    payments: PaymentHistoryItem[];
    total_payments: number;
    total_amount: number;
    pending_amount: number;
}

export interface ServicePaymentModalProps {
    bookingId: number;
    bookingCode: string;
    visible: boolean;
    onClose: () => void;
    onSuccess?: (result: PaymentCheckResponse) => void;
}

export interface ApiResponse<T> {
    success: boolean;
    data?: T;
    message?: string;
    errors?: Record<string, string[]>;
}

// Types and interfaces for payment components

export interface BookingData {
    id?: string;
    hotelName: string;
    roomType: string;
    checkIn: string;
    checkOut: string;
    guests: number;
    nights: number;
    price: number;
    tax: number;
    total: number;
    location: string;
    description: string;
    amenities: string[];
    images: string[];
}

export interface VietQRConfig {
    bankCode: string;
    accountNo: string;
    accountName: string;
    template: string;
}

export interface PaymentFormData {
    name: string;
    email: string;
    phone: string;
    specialRequests?: string;
}

export type PaymentStatus = "pending" | "checking" | "success" | "failed" | "expired";

export interface PaymentStepProps {
    currentStep: number;
    bookingData: BookingData;
    onNext?: () => void;
    onPrev?: () => void;
}

export interface QRPaymentStepProps extends PaymentStepProps {
    countdown: number;
    paymentStatus: PaymentStatus;
    qrUrl: string;
    vietQRConfig: VietQRConfig;
    bookingId: string;
    loading: boolean;
    onManualCheck: () => void;
    onCopyToClipboard: (text: string, label: string) => void;
}

export interface BookingInfoStepProps extends PaymentStepProps {
    form: any;
    loading: boolean;
    onSubmit: (values: PaymentFormData) => void;
    user?: {
        name?: string;
        email?: string;
    };
}

export interface CompletionStepProps extends PaymentStepProps {
    bookingId: string;
    onGoToBookings: () => void;
    onGoToHome: () => void;
}

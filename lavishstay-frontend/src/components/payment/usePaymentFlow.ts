import { useState, useEffect } from "react";
import { message } from "antd";
import api from "../../utils/api";
import { PaymentStatus, BookingData, PaymentFormData } from "./types";
import { VietQRService } from "./VietQRService";

export const usePaymentFlow = () => {
    const [currentStep, setCurrentStep] = useState(0);
    const [loading, setLoading] = useState(false);
    const [qrUrl, setQrUrl] = useState<string>("");
    const [countdown, setCountdown] = useState(900); // 15 minutes
    const [paymentStatus, setPaymentStatus] = useState<PaymentStatus>("pending");
    const [transactionId, setTransactionId] = useState<string>("");
    const [checkingInterval, setCheckingInterval] = useState<NodeJS.Timeout | null>(null);
    const [bookingData, setBookingData] = useState<BookingData | null>(null);
    const [bookingId, setBookingId] = useState<string>("");
    const [customerInfo, setCustomerInfo] = useState<PaymentFormData>({
        name: "",
        email: "",
        phone: "",
        specialRequests: ""
    });
    const [error, setError] = useState<string | null>(null);

    // Countdown timer effect
    useEffect(() => {
        if (currentStep === 1 && countdown > 0 && paymentStatus !== "success") {
            const timer = setTimeout(() => setCountdown(countdown - 1), 1000);
            return () => clearTimeout(timer);
        } else if (countdown === 0 && paymentStatus === "pending") {
            setPaymentStatus("expired");
            message.error("Thời gian thanh toán đã hết hạn. Vui lòng thử lại.");
        }
    }, [currentStep, countdown, paymentStatus]);

    // Generate QR when reaching payment step
    useEffect(() => {
        if (currentStep === 1) {
            generateVietQRUrl();
            startPaymentChecking();
        }
        return () => {
            if (checkingInterval) {
                clearInterval(checkingInterval);
            }
        };
    }, [currentStep]);    // Generate VietQR URL
    const generateVietQRUrl = () => {
        if (!bookingData || !bookingId) return "";
        
        const content = VietQRService.generatePaymentContent(bookingId);
        const qrUrl = VietQRService.generateQRUrl(bookingData.total, content);
        setQrUrl(qrUrl);
        return qrUrl;
    };

    // Start auto-checking payment status
    const startPaymentChecking = () => {
        const interval = setInterval(async () => {
            await checkPaymentStatus();
        }, 5000); // Check every 5 seconds
        
        setCheckingInterval(interval);
    };

    // Check payment status with Laravel backend
    const checkPaymentStatus = async () => {
        if (paymentStatus === "success" || paymentStatus === "expired" || !bookingData || !bookingId) {
            return;
        }

        try {
            setPaymentStatus("checking");
            
            const response = await api.post('/bookings/check-payment', {
                booking_id: bookingId,
                transaction_id: transactionId,
                amount: bookingData.total,
                bank_account: VietQRService.getConfig().accountNo
            });

            if (response.data.success && response.data.payment_status === 'completed') {
                setPaymentStatus("success");
                if (checkingInterval) {
                    clearInterval(checkingInterval);
                }
                setCurrentStep(2);
                message.success("Thanh toán thành công!");
            } else {
                setPaymentStatus("pending");
            }
        } catch (error) {
            console.error('Payment check error:', error);
            setError('Lỗi kiểm tra thanh toán');
            setPaymentStatus("pending");
        }
    };

    // Manual payment check
    const handlePaymentCheck = async () => {
        setLoading(true);
        await checkPaymentStatus();
        setLoading(false);
    };

    // Manual verify button handler
    const handleManualVerify = async () => {
        setLoading(true);
        try {
            const response = await api.post('/bookings/manual-verify', {
                booking_id: bookingId,
                amount: bookingData?.total,
                bank_account: VietQRService.getConfig().accountNo
            });

            if (response.data.success) {
                setPaymentStatus("success");
                setCurrentStep(2);
                message.success("Xác nhận thanh toán thành công!");
            } else {
                message.error("Không thể xác nhận thanh toán. Vui lòng thử lại sau.");
            }
        } catch (error) {
            console.error('Manual verify error:', error);
            message.error("Có lỗi xảy ra khi xác nhận thanh toán.");
        } finally {
            setLoading(false);
        }
    };

    // Handle booking form submission
    const handleSubmitBooking = async (values: PaymentFormData) => {
        setLoading(true);
        setError(null);
        
        try {
            // Generate booking ID
            const newBookingId = `BK${Date.now()}`;
            setBookingId(newBookingId);
            setCustomerInfo(values);
            
            // For demo purposes, we'll use mock booking data
            // In a real app, this would come from a booking search/selection flow
            const mockBookingData: BookingData = {
                id: newBookingId,
                hotelName: "Melia Hotel Danang",
                roomType: "Deluxe Ocean View",
                checkIn: new Date().toISOString().split('T')[0],
                checkOut: new Date(Date.now() + 86400000).toISOString().split('T')[0],
                guests: 2,
                nights: 1,
                price: 2500000,
                tax: 250000,
                total: 2750000,
                location: "Đà Nẵng",
                description: "Phòng deluxe với view biển tuyệt đẹp",
                amenities: ["WiFi miễn phí", "Bể bơi", "Spa", "Nhà hàng"],
                images: ["/images/room1.jpg"]
            };
            
            setBookingData(mockBookingData);
            
            const response = await api.post('/bookings', {
                ...mockBookingData,
                ...values,
                booking_id: newBookingId,
                payment_method: 'vietqr',
                payment_status: 'pending'
            });
            
            if (response.data.success) {
                setCurrentStep(1);
                message.success("Đặt phòng thành công! Vui lòng tiến hành thanh toán.");
            }
        } catch (error) {
            console.error('Booking error:', error);
            setError('Có lỗi xảy ra khi đặt phòng. Vui lòng thử lại.');
            message.error("Có lỗi xảy ra khi đặt phòng. Vui lòng thử lại.");
        } finally {
            setLoading(false);
        }
    };

    // Copy to clipboard functionality
    const copyToClipboard = (text: string, label: string) => {
        navigator.clipboard.writeText(text);
        message.success(`Đã sao chép ${label}!`);
    };    return {
        currentStep,
        setCurrentStep,
        loading,
        qrUrl,
        countdown,
        paymentStatus,
        transactionId,
        setTransactionId,
        bookingData,
        setBookingData,
        bookingId,
        customerInfo,
        setCustomerInfo,
        error,
        handlePaymentCheck,
        handleManualVerify,
        handleSubmitBooking,
        copyToClipboard
    };
};

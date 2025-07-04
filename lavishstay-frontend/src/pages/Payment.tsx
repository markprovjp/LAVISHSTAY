import React, { useEffect } from "react";
import { useParams, useLocation, useNavigate } from "react-router-dom";
import { Layout, Steps, Form } from "antd";
import { 
    BookingInfoStep, 
    QRPaymentStep, 
    CompletionStep, 
    BookingSummary,
    usePaymentFlow,
    VietQRService
} from "../components/payment";
import type { BookingData } from "../components/payment/types";

const { Content } = Layout;

const Payment: React.FC = () => {
    const { bookingId: paramBookingId } = useParams<{ bookingId: string }>();
    const location = useLocation();
    const navigate = useNavigate();
    const [form] = Form.useForm();    // Initialize payment flow hook
    const {
        currentStep,
        setCurrentStep,
        bookingData,
        setBookingData,
        customerInfo,
        qrUrl,
        countdown,
        paymentStatus,
        loading,
        error,
        handleSubmitBooking,
        handlePaymentCheck,
        copyToClipboard,
        bookingId
    } = usePaymentFlow();

    // Extract booking data from location state or URL params
    useEffect(() => {
        if (location.state?.bookingData) {
            setBookingData(location.state.bookingData);
        } else if (paramBookingId) {
            // If booking ID is provided in URL, you might want to fetch booking data
            // This is a placeholder for fetching existing booking data
            const defaultBookingData: BookingData = {
                id: paramBookingId,
                hotelName: "LavishStay Thanh Hóa",
                roomType: "Deluxe Double Room",
                checkIn: new Date().toISOString().split('T')[0],
                checkOut: new Date(Date.now() + 86400000).toISOString().split('T')[0],
                guests: 2,
                nights: 1,
                price: 2400000,
                tax: 240000,
                total: 2640000,
                images: [
                    "https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3",
                    "https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-4.0.3"
                ],
                description: "Phòng Deluxe với view thành phố tuyệt đẹp, đầy đủ tiện nghi cao cấp",
                location: "Thanh Hóa, Việt Nam",
                amenities: ["Wi-Fi miễn phí", "Điều hòa", "TV 55 inch", "Minibar", "Két an toàn", "Ban công riêng"]
            };
            setBookingData(defaultBookingData);
        } else {
            // Redirect to search if no booking data
        }
    }, [location.state, paramBookingId, navigate, setBookingData]);

    // Steps configuration
    const steps = [
        {
            title: 'Thông tin đặt phòng',
            description: 'Xác nhận thông tin'
        },
        {
            title: 'Thanh toán',
            description: 'Quét mã QR'
        },
        {
            title: 'Hoàn thành',
            description: 'Xác nhận đặt phòng'
        }
    ];    // Render step content based on current step
    const renderStepContent = () => {
        if (!bookingData) return null;
        
        switch (currentStep) {            case 0:
                return (
                    <BookingInfoStep
                        currentStep={currentStep}
                        bookingData={bookingData}
                        form={form}
                        loading={loading}
                        onSubmit={handleSubmitBooking}
                        user={{
                            name: customerInfo.name,
                            email: customerInfo.email
                        }}
                    />
                );
            case 1:
                return (
                    <QRPaymentStep
                        currentStep={currentStep}
                        bookingData={bookingData}
                        bookingId={bookingId}
                        countdown={countdown}
                        paymentStatus={paymentStatus}
                        qrUrl={qrUrl}
                        vietQRConfig={VietQRService.getConfig()}
                        loading={loading}
                        onManualCheck={handlePaymentCheck}
                        onCopyToClipboard={copyToClipboard}
                        onNext={() => setCurrentStep(2)}
                        onPrev={() => setCurrentStep(0)}
                    />
                );
            case 2:
                return (
                    <CompletionStep
                        currentStep={currentStep}
                        bookingData={bookingData}
                        bookingId={bookingId}
                        onGoToBookings={() => navigate('/bookings')}
                        onGoToHome={() => navigate('/search')}
                        onNext={() => {}}
                        onPrev={() => setCurrentStep(1)}
                    />
                );
            default:
                return null;
        }
    };

    if (!bookingData) {
        return <div>Loading...</div>;
    }

    return (
        <Layout style={{ backgroundColor: '#ffffff', minHeight: '100vh' }}>
            <Content style={{ padding: '24px 0' }}>
                <div style={{ maxWidth: 1200, margin: '0 auto', padding: '0 24px' }}>
                    {/* Progress Steps */}
                    <div style={{ marginBottom: 32 }}>
                        <Steps
                            current={currentStep}
                            items={steps}
                            style={{ maxWidth: 600, margin: '0 auto' }}
                        />
                    </div>

                    {/* Main Content */}
                    <div style={{ display: 'flex', gap: 24, alignItems: 'flex-start' }}>
                        {/* Step Content */}
                        <div style={{ flex: 1 }}>
                            {renderStepContent()}
                        </div>                        {/* Booking Summary Sidebar */}
                        {currentStep < 2 && (
                            <div style={{ width: 350, flexShrink: 0 }}>
                                <BookingSummary 
                                    bookingData={bookingData} 
                                    bookingId={bookingId}
                                />
                            </div>
                        )}
                    </div>

                    {/* Error Display */}
                    {error && (
                        <div style={{
                            marginTop: 24,
                            padding: 16,
                            backgroundColor: '#fff2f0',
                            border: '1px solid #ffccc7',
                            borderRadius: 8,
                            color: '#a8071a'
                        }}>
                            {error}
                        </div>
                    )}
                </div>
            </Content>
        </Layout>
    );
};

export default Payment;

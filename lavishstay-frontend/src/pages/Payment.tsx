import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import {
    Layout, Typography, Form, Steps, Alert, Progress, message, Row, Col
} from 'antd';
import { useSelector } from 'react-redux';
import { selectBookingState, selectSelectedRoomsSummary, selectHasSelectedRooms } from "../store/slices/bookingSlice";
import { selectSearchData } from "../store/slices/searchSlice";
import { BookingInfoStep, PaymentStep, PaymentSummary, CompletionStep } from '../components/payment';

const { Content } = Layout;
const { Title } = Typography;

// Format VND Currency
const formatVND = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
};

// VietQR Configuration
const VIETQR_CONFIG = {
    bankId: 'MB',
    bankBin: '970422',
    accountNo: '0335920306',
    accountName: 'NGUYEN VAN QUYEN',
    template: 'compact2'
};

// Generate VietQR URL
const generateVietQRUrl = (amount: number, content: string) => {
    const baseUrl = 'https://img.vietqr.io/image';
    const imagePath = `${VIETQR_CONFIG.bankId}-${VIETQR_CONFIG.accountNo}-${VIETQR_CONFIG.template}.png`;

    const params = new URLSearchParams({
        amount: amount.toString(),
        addInfo: encodeURIComponent(content),
        accountName: encodeURIComponent(VIETQR_CONFIG.accountName)
    });

    return `${baseUrl}/${imagePath}?${params.toString()}`;
};

// Steps configuration
const steps = [
    {
        title: 'Thông tin',
        description: 'Nhập thông tin khách hàng',
    },
    {
        title: 'Thanh toán',
        description: 'Chọn phương thức thanh toán',
    },
    {
        title: 'Hoàn tất',
        description: 'Xác nhận đặt phòng',
    },
];

const Payment: React.FC = () => {
    const navigate = useNavigate();
    const [form] = Form.useForm();
    const [currentStep, setCurrentStep] = useState(0);
    const [selectedPaymentMethod, setSelectedPaymentMethod] = useState('vietqr');
    const [countdown, setCountdown] = useState(900); // 15 minutes
    const [isProcessing, setIsProcessing] = useState(false);
    const [bookingCode, setBookingCode] = useState('');

    // Redux state
    const bookingState = useSelector(selectBookingState);
    const searchData = useSelector(selectSearchData);
    const selectedRoomsSummary = useSelector(selectSelectedRoomsSummary);
    const hasSelectedRooms = useSelector(selectHasSelectedRooms);

    // Generate booking code
    useEffect(() => {
        const code = `LAVISH${Date.now().toString().slice(-8)}`;
        setBookingCode(code);
    }, []);

    // Check if we have booking data from navigation or Redux
    useEffect(() => {
        if (!hasSelectedRooms || selectedRoomsSummary.length === 0) {
            message.error('Không có thông tin đặt phòng. Vui lòng chọn phòng trước.');
            navigate('/search');
            return;
        }

        // Start countdown timer
        const timer = setInterval(() => {
            setCountdown(prev => {
                if (prev <= 1) {
                    clearInterval(timer);
                    message.warning('Phiên đặt phòng đã hết hạn. Vui lòng đặt lại.');
                    navigate('/search');
                    return 0;
                }
                return prev - 1;
            });
        }, 1000);

        return () => clearInterval(timer);
    }, [hasSelectedRooms, selectedRoomsSummary, navigate]);

    // Format countdown time
    const formatTime = (seconds: number) => {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    };

    // Handle form submission
    const handleSubmit = async (values: any) => {
        setIsProcessing(true);
        try {
            // Combine firstName and lastName into fullName for backend
            const customerData = {
                ...values,
                fullName: `${values.firstName || ''} ${values.lastName || ''}`.trim()
            };

            // Create booking first
            await createBooking(customerData);
            setCurrentStep(1);
            message.success('Đã xác nhận thông tin. Vui lòng thanh toán.');
        } catch (error) {
            message.error('Có lỗi xảy ra. Vui lòng thử lại.');
        } finally {
            setIsProcessing(false);
        }
    };

    // Handle payment
    const handlePayment = async () => {
        setIsProcessing(true);
        try {
            // Simulate payment processing
            await new Promise(resolve => setTimeout(resolve, 3000));
            setCurrentStep(2);
            message.success('Thanh toán thành công!');
        } catch (error) {
            message.error('Thanh toán thất bại. Vui lòng thử lại.');
        } finally {
            setIsProcessing(false);
        }
    };

    // Use totals directly from Redux state (already calculated in BookingSummary)
    const totals = {
        roomsTotal: bookingState.totals.roomsTotal,
        breakfastTotal: bookingState.totals.breakfastTotal || 0,
        finalTotal: bookingState.totals.finalTotal || bookingState.totals.roomsTotal,
        total: bookingState.totals.finalTotal || bookingState.totals.roomsTotal // Use finalTotal from Redux
    };
    const nights = searchData.checkIn && searchData.checkOut
        ? Math.ceil((new Date(searchData.checkOut).getTime() - new Date(searchData.checkIn).getTime()) / (1000 * 60 * 60 * 24))
        : 1;

    // API Base URL
    const API_BASE_URL = 'http://localhost:8000/api';

    // Create booking API call
    const createBooking = async (customerData: any) => {
        try {
            const response = await fetch(`${API_BASE_URL}/payment/create-booking`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    booking_code: bookingCode,
                    customer_name: customerData.fullName,
                    customer_email: customerData.email,
                    customer_phone: customerData.phone,
                    rooms_data: JSON.stringify({
                        rooms: selectedRoomsSummary,
                        preferences: bookingState.preferences
                    }),
                    total_amount: totals.total,
                    payment_method: selectedPaymentMethod,
                    check_in: searchData.checkIn,
                    check_out: searchData.checkOut,
                }),
            });

            if (!response.ok) {
                throw new Error('Failed to create booking');
            }

            return await response.json();
        } catch (error) {
            console.error('Error creating booking:', error);
            throw error;
        }
    };

    // Handle navigation functions
    const handleViewBookings = () => navigate('/bookings');
    const handleNewBooking = () => navigate('/search');
    const handleBack = () => setCurrentStep(0);

    // Render step content
    const renderStepContent = () => {
        switch (currentStep) {
            case 0:
                return (
                    <Row gutter={[24, 24]}>
                        <Col span={16}>
                            <BookingInfoStep
                                form={form}
                                onSubmit={handleSubmit}
                                isProcessing={isProcessing}
                            />
                        </Col>
                        <Col span={8}>
                            <PaymentSummary
                                bookingCode={bookingCode}
                                selectedRoomsSummary={selectedRoomsSummary}
                                searchData={searchData}
                                nights={nights}
                                totals={totals}
                                preferences={bookingState.preferences}
                                formatVND={formatVND}
                            />
                        </Col>
                    </Row>
                );
            case 1:
                return (
                    <Row gutter={[24, 24]}>
                        <Col span={16}>
                            <PaymentStep
                                selectedPaymentMethod={selectedPaymentMethod}
                                onPaymentMethodChange={setSelectedPaymentMethod}
                                onBack={handleBack}
                                onConfirmPayment={handlePayment}
                                isProcessing={isProcessing}
                                bookingCode={bookingCode}
                                totalAmount={totals.total}
                                countdown={countdown}
                                formatTime={formatTime}
                                generateVietQRUrl={generateVietQRUrl}
                            />
                        </Col>
                        <Col span={8}>
                            <PaymentSummary
                                bookingCode={bookingCode}
                                selectedRoomsSummary={selectedRoomsSummary}
                                searchData={searchData}
                                nights={nights}
                                totals={totals}
                                preferences={bookingState.preferences}
                                formatVND={formatVND}
                            />
                        </Col>
                    </Row>
                );
            case 2:
                return (
                    <CompletionStep
                        bookingCode={bookingCode}
                        selectedPaymentMethod={selectedPaymentMethod}
                        onViewBookings={handleViewBookings}
                        onNewBooking={handleNewBooking}
                    />
                );
            default:
                return null;
        }
    };

    return (
        <Layout style={{ backgroundColor: '#f0f2f5', minHeight: '100vh' }}>
            <Content style={{ padding: '24px' }}>
                <div style={{ maxWidth: 1200, margin: '0 auto' }}>
                    {/* Header */}
                    <div className="text-center mb-8">
                        <Title level={2}>Thanh toán đặt phòng</Title>
                        <div className="bg-white rounded-lg shadow-sm p-4 mb-6">
                            <Progress
                                percent={(currentStep + 1) * 33.33}
                                showInfo={false}
                                strokeColor="#1890ff"
                            />
                            <Steps
                                current={currentStep}
                                items={steps}
                                className="mt-4"
                            />
                        </div>
                    </div>

                    {/* Time remaining alert */}
                    {currentStep < 2 && (
                        <Alert
                            message={`Thời gian còn lại: ${formatTime(countdown)}`}
                            description="Vui lòng hoàn tất thanh toán trong thời gian quy định"
                            type="warning"
                            showIcon
                            closable
                            className="mb-6"
                        />
                    )}

                    {/* Step Content */}
                    {renderStepContent()}
                </div>
            </Content>
        </Layout>
    );
};

export default Payment;

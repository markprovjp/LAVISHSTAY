import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import {
    Layout, Typography, Form, Steps, Alert, Progress, message, Row, Col, Button, Space
} from 'antd';
import { useSelector } from 'react-redux';
import { selectBookingState, selectSelectedRoomsSummary, selectHasSelectedRooms } from "../store/slices/bookingSlice";
import { selectSearchData } from "../store/slices/searchSlice";
import { BookingInfoStep, PaymentStep, PaymentSummary, CompletionStep } from '../components/payment';
import { useBookingManager } from '../hooks/useBookingManager';

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

    // Redux state
    const bookingState = useSelector(selectBookingState);
    const searchData = useSelector(selectSearchData);
    const selectedRoomsSummary = useSelector(selectSelectedRoomsSummary);
    const hasSelectedRooms = useSelector(selectHasSelectedRooms);

    // Anti-spam booking manager
    const {
        bookingCode,
        isProcessing,
        canProceed,
        cooldownInfo,
        createBooking,
        resetBooking
    } = useBookingManager();    // Check if we have booking data from navigation or Redux
    useEffect(() => {
        if (!hasSelectedRooms || selectedRoomsSummary.length === 0) {
            message.error('Không có thông tin đặt phòng. Vui lòng chọn phòng trước.');
            navigate('/search');
            return;
        }

        // Check if search data is valid
        if (!searchData?.checkIn || !searchData?.checkOut) {
            message.error('Thông tin tìm kiếm không hợp lệ. Vui lòng tìm kiếm lại.');
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
    }, [hasSelectedRooms, selectedRoomsSummary, navigate, searchData]);

    // Format countdown time
    const formatTime = (seconds: number) => {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    };

    // Handle form submission
    const handleSubmit = async (values: any) => {
        if (!canProceed) {
            message.error('Không thể tiến hành đặt phòng lúc này');
            return;
        }

        try {
            // Combine firstName and lastName into fullName for backend
            const customerData = {
                ...values,
                fullName: `${values.firstName || ''} ${values.lastName || ''}`.trim()
            };

            // Create booking using anti-spam service
            await createBooking(customerData);
            setCurrentStep(1);
            message.success('Đã xác nhận thông tin. Vui lòng thanh toán.');
        } catch (error) {
            message.error('Có lỗi xảy ra. Vui lòng thử lại.');
        }
    };

    // Handle payment
    const handlePayment = async () => {
        try {
            // Simulate payment processing
            await new Promise(resolve => setTimeout(resolve, 3000));
            setCurrentStep(2);
            message.success('Thanh toán thành công!');
        } catch (error) {
            message.error('Thanh toán thất bại. Vui lòng thử lại.');
        }
    };    // Calculate nights from search data
    const nights = React.useMemo(() => {
        if (searchData.checkIn && searchData.checkOut) {
            return Math.ceil((new Date(searchData.checkOut).getTime() - new Date(searchData.checkIn).getTime()) / (1000 * 60 * 60 * 24));
        }
        return bookingState.totals?.nights || 1;
    }, [searchData.checkIn, searchData.checkOut, bookingState.totals?.nights]);

    // Use totals directly from Redux state (already calculated in BookingSummary)
    const totals = React.useMemo(() => ({
        roomsTotal: bookingState.totals?.roomsTotal || 0,
        breakfastTotal: bookingState.totals?.breakfastTotal || 0,
        serviceFee: bookingState.totals?.serviceFee || 0,
        taxAmount: bookingState.totals?.taxAmount || 0,
        discountAmount: bookingState.totals?.discountAmount || 0,
        finalTotal: bookingState.totals?.finalTotal || 0,
        nights: nights
    }), [bookingState.totals, nights]);

    // API Base URL
    const API_BASE_URL = 'http://localhost:8888/api';

    // Check payment status function
    const checkPaymentStatus = async () => {
        try {
            const response = await fetch(`${API_BASE_URL}/payment/status/${bookingCode}`);
            const result = await response.json();

            if (result.success && result.payment_status === 'confirmed') {
                setCurrentStep(2);
                message.success('Thanh toán đã được xác nhận thành công!');
                return true;
            }
            return false;
        } catch (error) {
            console.error('Error checking payment status:', error);
            return false;
        }
    };

    // Auto-check payment status when on payment step
    useEffect(() => {
        if (currentStep === 1 && selectedPaymentMethod === 'vietqr') {
            // Check immediately
            checkPaymentStatus();

            // Then check every 10 seconds
            const interval = setInterval(() => {
                checkPaymentStatus();
            }, 10000);

            return () => {
                clearInterval(interval);
            };
        }
    }, [currentStep, selectedPaymentMethod, bookingCode]);

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
                                disabled={!canProceed || cooldownInfo.inCooldown}
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
                                totalAmount={totals.finalTotal}
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

                        {/* Anti-spam warning */}
                        {cooldownInfo.inCooldown && (
                            <Alert
                                message="Tạm thời không thể đặt phòng"
                                description={`Bạn đã thực hiện quá nhiều lần đặt phòng. Vui lòng thử lại sau ${Math.ceil((cooldownInfo.remainingTime || 0) / (60 * 1000))} phút.`}
                                type="warning"
                                showIcon
                                className="mb-4"
                                action={
                                    <Space>
                                        {import.meta.env.DEV && (
                                            <Button size="small" onClick={resetBooking}>
                                                Xóa rate limit (Dev)
                                            </Button>
                                        )}
                                        <Button size="small" onClick={() => navigate('/search')}>
                                            Về trang chủ
                                        </Button>
                                    </Space>
                                }
                            />
                        )}

                        {!canProceed && !cooldownInfo.inCooldown && (
                            <Alert
                                message="Không thể tiến hành đặt phòng"
                                description="Vui lòng kiểm tra lại thông tin hoặc thử lại sau."
                                type="error"
                                showIcon
                                className="mb-4"
                                action={
                                    <Space>
                                        {import.meta.env.DEV && (
                                            <Button size="small" onClick={resetBooking}>
                                                Reset (Dev)
                                            </Button>
                                        )}
                                        <Button size="small" onClick={() => window.location.reload()}>
                                            Tải lại trang
                                        </Button>
                                    </Space>
                                }
                            />
                        )}

                        {bookingCode && (
                            <Alert
                                message={`Mã đặt phòng: ${bookingCode}`}
                                description="Mã này sẽ được sử dụng lại nếu bạn không thay đổi phòng hoặc ngày"
                                type="info"
                                showIcon
                                className="mb-4"
                                action={
                                    import.meta.env.DEV ? (
                                        <Button size="small" onClick={resetBooking}>
                                            Reset booking (Dev)
                                        </Button>
                                    ) : undefined
                                }
                            />
                        )}

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

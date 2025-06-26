import React, { useState, useEffect } from 'react';
import { useNavigate, useSearchParams } from 'react-router-dom';
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
    const [searchParams] = useSearchParams();
    const [form] = Form.useForm(); const [currentStep, setCurrentStep] = useState(0);
    const [selectedPaymentMethod, setSelectedPaymentMethod] = useState('vietqr'); // Back to default VietQR
    const [countdown, setCountdown] = useState(900); // 15 minutes
    const [backendBookingCode, setBackendBookingCode] = useState<string | null>(null); // Store booking code from backend
    const [paymentProcessing, setPaymentProcessing] = useState(false); // Processing state for payment

    // Redux state
    const bookingState = useSelector(selectBookingState);
    const searchData = useSelector(selectSearchData);
    const selectedRoomsSummary = useSelector(selectSelectedRoomsSummary);
    const hasSelectedRooms = useSelector(selectHasSelectedRooms);    // Anti-spam booking manager
    const {
        bookingCode,
        isProcessing,
        canProceed,
        cooldownInfo,
        resetBooking
    } = useBookingManager();// Check if we have booking data from navigation or Redux
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
    };    // Handle form submission (Step 0 -> Step 1)
    const handleSubmit = async (values: any) => {
        if (!canProceed) {
            message.error('Không thể tiến hành đặt phòng lúc này');
            return;
        }

        try {
            setPaymentProcessing(true);

            // Prepare booking data
            const bookingData = {
                customer_name: `${values.firstName || ''} ${values.lastName || ''}`.trim(),
                customer_email: values.email,
                customer_phone: values.phone,
                rooms_data: selectedRoomsSummary,
                total_amount: totals.finalTotal,
                payment_method: 'pending', // Don't set payment method yet
                check_in: searchData.checkIn,
                check_out: searchData.checkOut,
                special_requests: values.specialRequests || null
            };

            console.log('Sending booking data:', bookingData);

            // Send to backend to create booking (without payment method)
            const response = await fetch(`${API_BASE_URL}/payment/create-booking`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(bookingData)
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();
            console.log('Backend response:', result);

            if (result.success) {
                // Store booking code from backend
                if (result.booking_code) {
                    setBackendBookingCode(result.booking_code);
                }

                // Move to payment method selection step
                setCurrentStep(1);
                message.success('Đã xác nhận thông tin. Vui lòng chọn phương thức thanh toán.');
            } else {
                console.error('Backend error:', result);
                throw new Error(result.message || 'Có lỗi xảy ra khi tạo booking');
            }

        } catch (error) {
            console.error('Error creating booking:', error);

            // More specific error messages
            if (error instanceof TypeError && error.message.includes('fetch')) {
                message.error('Không thể kết nối đến server. Vui lòng kiểm tra kết nối internet.');
            } else if (error instanceof Error) {
                message.error(`Lỗi: ${error.message}`);
            } else {
                message.error('Có lỗi xảy ra. Vui lòng thử lại.');
            }
        } finally {
            setPaymentProcessing(false);
        }
    };    // Handle payment method selection and processing
    const handlePayment = async () => {
        try {
            setPaymentProcessing(true);

            if (!backendBookingCode) {
                message.error('Không tìm thấy thông tin đặt phòng. Vui lòng thử lại từ đầu.');
                return;
            }

            if (selectedPaymentMethod === 'vnpay') {
                // Create VNPay payment URL
                console.log('Creating VNPay payment for booking:', backendBookingCode);

                const vnpayData = {
                    booking_code: backendBookingCode,
                    amount: totals.finalTotal,
                    payment_method: 'vnpay'
                };

                console.log('VNPay request data:', vnpayData);

                const response = await fetch(`${API_BASE_URL}/payment/vnpay`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(vnpayData)
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                console.log('VNPay response:', result);

                if (result.success && result.vnpay_url) {
                    message.success('Đang chuyển hướng đến VNPay...');
                    // Redirect to VNPay
                    window.location.href = result.vnpay_url;
                } else {
                    throw new Error(result.message || 'Không thể tạo thanh toán VNPay');
                }

            } else if (selectedPaymentMethod === 'vietqr') {
                // For VietQR, just show the QR code (already displayed)
                message.info('Vui lòng quét mã QR để thanh toán');
                // Move to completion step to show QR
                setCurrentStep(2);
                return;

            } else if (selectedPaymentMethod === 'pay_at_hotel') {
                // For pay at hotel, just complete the booking
                const updateData = {
                    booking_code: backendBookingCode,
                    payment_method: 'pay_at_hotel'
                };

                const response = await fetch(`${API_BASE_URL}/payment/update-method`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(updateData)
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                if (result.success) {
                    setCurrentStep(2);
                    message.success('Đặt phòng thành công! Bạn sẽ thanh toán tại khách sạn.');
                } else {
                    throw new Error(result.message || 'Không thể cập nhật phương thức thanh toán');
                }
                return;
            }

        } catch (error) {
            console.error('Payment error:', error);

            if (error instanceof TypeError && error.message.includes('fetch')) {
                message.error('Không thể kết nối đến server. Vui lòng kiểm tra kết nối internet.');
            } else if (error instanceof Error) {
                message.error(`Lỗi thanh toán: ${error.message}`);
            } else {
                message.error('Thanh toán thất bại. Vui lòng thử lại.');
            }
        } finally {
            setPaymentProcessing(false);
        }
    };// Calculate nights from search data
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

    // Test backend connection (for debugging)
    const testBackendConnection = async () => {
        try {
            const response = await fetch(`http://localhost:8888/api/test`, {
                method: 'GET',
            });
            console.log('Backend test response status:', response.status);
        } catch (error) {
            console.error('Backend connection test failed:', error);
        }
    };

    // Test connection once when component mounts
    useEffect(() => {
        if (import.meta.env.DEV) {
            testBackendConnection();
        }
    }, []);    // Check payment status function
    const checkPaymentStatus = async () => {
        try {
            // Use the booking code from backend response or fallback to local booking code
            const currentBookingCode = backendBookingCode || bookingCode;

            if (!currentBookingCode) {
                console.log('No booking code available for status check');
                return false;
            }

            console.log('Checking payment status for:', currentBookingCode);

            const response = await fetch(`${API_BASE_URL}/payment/status/${currentBookingCode}`);

            // Check if response is ok
            if (!response.ok) {
                console.error(`Payment status check failed with status: ${response.status}`);
                return false;
            }

            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                console.error('Response is not JSON:', await response.text());
                return false;
            }

            const result = await response.json();
            console.log('Payment status response:', result);

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
    };    // Auto-check payment status when on payment step
    useEffect(() => {
        if (currentStep === 1 && selectedPaymentMethod === 'vietqr' && backendBookingCode) {
            console.log('Starting payment status monitoring for VietQR');

            // Check immediately
            checkPaymentStatus();

            // Then check every 10 seconds
            const interval = setInterval(() => {
                checkPaymentStatus();
            }, 10000);

            return () => {
                console.log('Stopping payment status monitoring');
                clearInterval(interval);
            };
        }
    }, [currentStep, selectedPaymentMethod, backendBookingCode]);

    // Handle VNPay return
    useEffect(() => {
        const handleVNPayReturn = async () => {
            // Check if this is a VNPay return
            const vnpResponseCode = searchParams.get('vnp_ResponseCode');
            if (vnpResponseCode) {
                try {
                    // Get all VNPay parameters
                    const vnpParams: { [key: string]: string } = {};
                    for (const [key, value] of searchParams.entries()) {
                        if (key.startsWith('vnp_')) {
                            vnpParams[key] = value;
                        }
                    }

                    // Send to backend for verification
                    const response = await fetch(`${API_BASE_URL}/payment/vnpay/return`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(vnpParams)
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Store booking code and proceed to completion
                        if (result.booking_code) {
                            setBackendBookingCode(result.booking_code);
                        }
                        setCurrentStep(2);
                        message.success('Thanh toán VNPay thành công!');

                        // Clear URL parameters
                        window.history.replaceState({}, '', '/payment');
                    } else {
                        message.error(result.message || 'Thanh toán VNPay thất bại');
                        setCurrentStep(1); // Back to payment step
                    }
                } catch (error) {
                    console.error('Error processing VNPay return:', error);
                    message.error('Có lỗi xảy ra khi xử lý kết quả thanh toán');
                }
            }
        };

        handleVNPayReturn();
    }, [searchParams]);

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
                                isProcessing={isProcessing || paymentProcessing}
                                disabled={!canProceed || cooldownInfo.inCooldown}
                                selectedPaymentMethod={selectedPaymentMethod}
                            />
                        </Col>                        <Col span={8}>
                            <PaymentSummary
                                bookingCode={backendBookingCode || bookingCode}
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
                    <Row gutter={[24, 24]}>                        <Col span={16}>
                        <PaymentStep
                            selectedPaymentMethod={selectedPaymentMethod}
                            onPaymentMethodChange={setSelectedPaymentMethod}
                            onBack={handleBack}
                            onConfirmPayment={handlePayment}
                            isProcessing={isProcessing}
                            bookingCode={backendBookingCode || bookingCode}
                            totalAmount={totals.finalTotal}
                            countdown={countdown}
                            formatTime={formatTime}
                            generateVietQRUrl={generateVietQRUrl}
                        />
                    </Col>
                        <Col span={8}>
                            <PaymentSummary
                                bookingCode={backendBookingCode || bookingCode}
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
            case 2: return (
                <CompletionStep
                    bookingCode={backendBookingCode || bookingCode}
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
                        )}                        {(backendBookingCode || bookingCode) && (
                            <Alert
                                message={`Mã đặt phòng: ${backendBookingCode || bookingCode}`}
                                description="Mã này sẽ được sử dụng để tra cứu đơn đặt phòng"
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

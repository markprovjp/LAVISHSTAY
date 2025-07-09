import React, { useState, useEffect, Children } from 'react';
import { useNavigate } from 'react-router-dom';
import {
    Layout, Typography, Form, Steps, Alert, message, Row, Col
} from 'antd';
import { useSelector, useDispatch } from 'react-redux';
import store from '../store';
import { selectBookingState, selectSelectedRoomsSummary, selectHasSelectedRooms, clearBookingData } from "../store/slices/bookingSlice";
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
    const dispatch = useDispatch();
    const [form] = Form.useForm();
    const [currentStep, setCurrentStep] = useState(0);
    const [selectedPaymentMethod, setSelectedPaymentMethod] = useState('vietqr'); // Default to VietQR
    const [countdown, setCountdown] = useState(900); // 15 minutes
    const [backendBookingCode, setBackendBookingCode] = useState<string | null>(null);
    const [paymentProcessing, setPaymentProcessing] = useState(false);
    const [customerInfo, setCustomerInfo] = useState<any>(null); // Store customer info separately

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
        cooldownInfo
    } = useBookingManager();

    // Check if we have booking data from navigation or Redux
    useEffect(() => {
        // Kiểm tra xem có dữ liệu trong Redux không
        if (!hasSelectedRooms || !selectedRoomsSummary || selectedRoomsSummary.length === 0) {
            // Thử kiểm tra localStorage để khôi phục dữ liệu
            try {
                const persistedData = localStorage.getItem('persist:root');
                if (persistedData) {
                    const parsedData = JSON.parse(persistedData);
                    const bookingData = parsedData.booking ? JSON.parse(parsedData.booking) : null;

                    // Nếu có dữ liệu trong localStorage nhưng Redux chưa được hydrate
                    if (bookingData?.selectedRoomsSummary?.length > 0) {
                        console.log('Found persisted booking data, waiting for Redux rehydration...');
                        // Đợi một chút để Redux persist rehydrate
                        setTimeout(() => {
                            // Kiểm tra lại sau khi đợi
                            const currentState = store.getState();
                            const currentBookingState = currentState.booking as any;
                            if (!currentBookingState.selectedRoomsSummary?.length) {
                                message.error('Dữ liệu đặt phòng đã hết hạn. Vui lòng đặt lại.');
                                navigate('/search');
                            }
                        }, 1000);
                        return;
                    }
                }

                // Nếu không có dữ liệu nào
                message.error('Không có thông tin đặt phòng. Vui lòng quay lại và chọn phòng.');
                navigate('/search');
                return;
            } catch (error) {
                console.error('Error checking persisted data:', error);
                message.error('Không có thông tin đặt phòng. Vui lòng quay lại và chọn phòng.');
                navigate('/search');
                return;
            }
        }

        // Start countdown timer
        const timer = setInterval(() => {
            setCountdown(prev => {
                if (prev <= 1) {
                    clearInterval(timer);
                    message.warning('Phiên đặt phòng đã hết hạn. Vui lòng đặt lại.');
                    navigate('/');
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

    // Enhanced error handling and retry mechanism
    const createBookingWithRetry = async (bookingPayload: any, retries = 3) => {
        for (let attempt = 1; attempt <= retries; attempt++) {
            try {
                console.log(`🔄 Booking attempt ${attempt}/${retries}`, {
                    url: `${API_BASE_URL}/payment/create-booking`,
                    payload: bookingPayload
                });

                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 15000); // 15s timeout

                const response = await fetch(`${API_BASE_URL}/payment/create-booking`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(bookingPayload),
                    signal: controller.signal,
                });

                clearTimeout(timeoutId);

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({
                        message: `Server trả về lỗi ${response.status}: ${response.statusText}`
                    }));
                    throw new Error(errorData.message || `HTTP ${response.status}: ${response.statusText}`);
                }

                return await response.json();
            } catch (error) {
                console.error(`❌ Booking attempt ${attempt} failed:`, error);

                if (error instanceof TypeError && error.message.includes('fetch')) {
                    if (attempt === retries) {
                        throw new Error('Không thể kết nối đến server. Vui lòng:\n1. Kiểm tra kết nối internet\n2. Đảm bảo backend đang chạy tại http://localhost:8888\n3. Thử lại sau vài giây');
                    }
                    // Wait before retry
                    await new Promise(resolve => setTimeout(resolve, 2000 * attempt));
                    continue;
                }

                if ((error as any)?.name === 'AbortError') {
                    throw new Error('Yêu cầu đã timeout. Vui lòng thử lại.');
                }

                // For other errors, don't retry
                throw error;
            }
        }
    };

    // Handle form submission (Step 0 -> Step 1)
    const handleSubmit = async (values: any) => {
        if (!canProceed || cooldownInfo.inCooldown) {
            message.warning('Vui lòng đợi trước khi thử lại.');
            return;
        }

        // Guard against empty selection
        if (!selectedRoomsSummary || selectedRoomsSummary.length === 0) {
            message.error('Không có phòng nào được chọn. Vui lòng quay lại và chọn phòng.');
            navigate('/search');
            return;
        }

        // Store customer info in state for use in CompletionStep
        setCustomerInfo({
            fullName: values.fullName,
            email: values.email,
            phone: values.phone,
            specialRequests: values.specialRequests
        });

        setPaymentProcessing(true);
        try {
            // Prepare the data for the backend - sử dụng 1 người đại diện cho tất cả phòng
            const roomsPayload = selectedRoomsSummary.map((roomSummary, index) => {
                const guestInfoForThisRoom = searchData.rooms?.[index] || { adults: 1, children: 0, childrenAges: [] };

                return {
                    room_id: roomSummary.room.id,
                    room_price: roomSummary.pricePerNight,
                    guest_name: values.fullName, // Sử dụng tên người đại diện cho tất cả phòng
                    adults: guestInfoForThisRoom.adults,
                    children: guestInfoForThisRoom.children,
                    children_age: guestInfoForThisRoom.childrenAges || [], // Lấy tuổi trẻ em từ searchData
                    option_id: roomSummary.optionId,
                    option_name: roomSummary.option.name,
                    option_price: typeof roomSummary.option.pricePerNight === 'object'
                        ? roomSummary.option.pricePerNight.vnd
                        : roomSummary.option.pricePerNight, // Xử lý option_price dạng object
                };
            });

            const totalGuests = roomsPayload.reduce((acc, room) => acc + room.adults + room.children, 0);

            const bookingPayload = {
                customer_name: values.fullName,
                customer_email: values.email,
                customer_phone: values.phone,
                check_in: searchData.checkIn,
                check_out: searchData.checkOut,
                total_guests: totalGuests,
                total_price: totals.finalTotal,
                payment_method: selectedPaymentMethod,
                notes: values.specialRequests,
                rooms: roomsPayload,
                totals: {
                    roomsTotal: totals.roomsTotal,
                    breakfastTotal: totals.breakfastTotal,
                    serviceFee: totals.serviceFee,
                    taxAmount: totals.taxAmount,
                    discountAmount: totals.discountAmount,
                    finalTotal: totals.finalTotal,
                    nights: nights,
                }
            };

            console.log('Submitting booking to backend:', JSON.stringify(bookingPayload, null, 2));

            // Use retry mechanism for better reliability
            const result = await createBookingWithRetry(bookingPayload);

            if (result.success && result.booking_code) {
                setBackendBookingCode(result.booking_code);
                message.success('Đã tạo đơn đặt phòng thành công!');

                // If pay_at_hotel, move to completion step
                if (selectedPaymentMethod === 'pay_at_hotel') {
                    setCurrentStep(2); // Move to completion step instead of navigating away
                } else {
                    setCurrentStep(1); // Move to payment step for other methods
                }
            } else {
                throw new Error(result.message || 'Không thể tạo đơn đặt phòng.');
            }

        } catch (error) {
            console.error('Error creating booking:', error);
            const errorMessage = error instanceof Error ? error.message : 'Đã xảy ra lỗi không mong muốn.';
            message.error(`Lỗi: ${errorMessage}`);
        } finally {
            setPaymentProcessing(false);
        }
    };

    // Handle payment method selection and processing
    const handlePayment = async () => {
        try {
            setPaymentProcessing(true);

            if (!backendBookingCode) {
                message.error('Không tìm thấy thông tin đặt phòng. Vui lòng thử lại từ đầu.');
                return;
            }

            if (selectedPaymentMethod === 'pay_at_hotel') {
                setCurrentStep(2); // Move to completion step
                message.success('Đặt phòng thành công! Bạn sẽ thanh toán tại khách sạn.');
                return;
            }

            if (selectedPaymentMethod === 'vietqr') {
                // For VietQR, create QR payment
                const paymentResponse = await fetch(`${API_BASE_URL}/payment/create-vietqr`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        booking_code: backendBookingCode,
                        amount: totals.finalTotal
                    }),
                });

                const paymentResult = await paymentResponse.json();

                if (paymentResult.success) {
                    message.success('Đã tạo mã QR thanh toán thành công!');
                    message.info('Vui lòng quét mã QR và thanh toán, sau đó nhấn "Đã thanh toán"');
                    return;
                } else {
                    throw new Error(paymentResult.message || 'Không thể tạo mã QR thanh toán.');
                }
            }

            if (selectedPaymentMethod === 'vnpay') {
                message.warning('Phương thức VNPay đang được phát triển.');
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
    };

    // Function to generate VietQR URL
    const generateVietQRUrl = (amount: number, _content: string) => {
        const bankId = 'MBBank';
        const accountNo = '0335920306';
        const template = 'print';
        // Use booking code in the content for easier tracking
        const paymentContent = `Thanh toan dat phong ${backendBookingCode || bookingCode}`;
        const encodedContent = encodeURIComponent(paymentContent);
        const accountName = encodeURIComponent('NGUYEN VAN QUYEN');

        return `https://img.vietqr.io/image/${bankId}-${accountNo}-${template}.png?amount=${amount}&addInfo=${encodedContent}&accountName=${accountName}`;
    };

    // Handle payment confirmation (for VietQR)
    const handleConfirmPayment = async (transaction?: any) => {
        console.log('🔄 handleConfirmPayment called with:', { transaction, selectedPaymentMethod });

        if (selectedPaymentMethod === 'vietqr') {
            try {
                setPaymentProcessing(true);

                // If we have a transaction from PaymentCheck, use it directly
                if (transaction) {
                    console.log('✅ Payment confirmed via CPay auto-check:', transaction);
                    message.success('Thanh toán thành công!');
                    setCurrentStep(2); // Move to completion step
                    setPaymentProcessing(false); // Reset processing state
                    return;
                }

                // Manual verification fallback
                const verifyResponse = await fetch(`${API_BASE_URL}/payment/verify-vietqr`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        booking_code: backendBookingCode,
                        transaction_id: 'TXN_' + Date.now(),
                        amount: totals.finalTotal
                    }),
                });

                const verifyResult = await verifyResponse.json();

                if (verifyResult.success) {
                    message.success('Thanh toán thành công!');
                    setCurrentStep(2); // Move to completion step instead of navigating away
                } else {
                    message.error('Không thể xác nhận thanh toán. Vui lòng liên hệ hỗ trợ.');
                }
            } catch (error) {
                console.error('Payment confirmation error:', error);
                message.error('Lỗi xác nhận thanh toán. Vui lòng thử lại.');
            } finally {
                setPaymentProcessing(false);
            }
        } else {
            // For other payment methods, call original logic
            handlePayment();
        }
    };

    // Calculate nights from search data
    const nights = React.useMemo(() => {
        if (searchData.checkIn && searchData.checkOut) {
            return Math.ceil((new Date(searchData.checkOut).getTime() - new Date(searchData.checkIn).getTime()) / (1000 * 60 * 60 * 24));
        }
        return bookingState.totals?.nights || 1;
    }, [searchData.checkIn, searchData.checkOut, bookingState.totals?.nights]);

    // Use totals calculated from selected rooms
    const totals = React.useMemo(() => {
        if (selectedRoomsSummary && selectedRoomsSummary.length > 0) {
            const roomsTotal = selectedRoomsSummary.reduce((sum, room) => sum + room.totalPrice, 0);
            return {
                roomsTotal,
                breakfastTotal: 0,
                serviceFee: 0,
                taxAmount: 0,
                discountAmount: 0,
                finalTotal: roomsTotal,
                nights: nights
            };
        }
        return {
            roomsTotal: bookingState.totals?.roomsTotal || 0,
            breakfastTotal: bookingState.totals?.breakfastTotal || 0,
            serviceFee: bookingState.totals?.serviceFee || 0,
            taxAmount: bookingState.totals?.taxAmount || 0,
            discountAmount: bookingState.totals?.discountAmount || 0,
            finalTotal: bookingState.totals?.finalTotal || 0,
            nights: nights
        };
    }, [selectedRoomsSummary, bookingState.totals, nights]);

    // API Base URL
    const API_BASE_URL = 'http://localhost:8888/api';

    // Handle navigation functions
    const handleViewBookings = () => {
        dispatch(clearBookingData()); // Clear booking data after completion
        navigate('/bookings');
    };

    const handleNewBooking = () => {
        dispatch(clearBookingData()); // Clear booking data for new booking
        navigate('/search');
    };

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
                        </Col>
                        <Col span={8}>
                            <PaymentSummary
                                bookingCode={backendBookingCode || bookingCode}
                                selectedRoomsSummary={selectedRoomsSummary}
                                searchData={searchData}
                                nights={nights}
                                totals={totals}
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
                                onConfirmPayment={handleConfirmPayment}
                                isProcessing={paymentProcessing}
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
                                formatVND={formatVND}
                            />
                        </Col>
                    </Row>
                );
            case 2:
                return (
                    <CompletionStep
                        bookingCode={backendBookingCode || bookingCode}
                        selectedPaymentMethod={selectedPaymentMethod}
                        onViewBookings={handleViewBookings}
                        onNewBooking={handleNewBooking}
                        customerInfo={customerInfo || form.getFieldsValue()}
                        selectedRoomsSummary={selectedRoomsSummary}
                        searchData={searchData}
                        totals={totals}
                        nights={nights}
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

                        {/* Debug button - Remove in production */}
                        {process.env.NODE_ENV === 'development' && (
                            <div className="mb-4">
                                <button
                                    onClick={() => {
                                        dispatch(clearBookingData());
                                        localStorage.clear();
                                        message.info('Đã xóa tất cả dữ liệu booking');
                                    }}
                                    className="text-xs bg-red-500 text-white px-2 py-1 rounded"
                                >
                                    [DEV] Clear All Data
                                </button>
                            </div>
                        )}

                        {/* Steps */}
                        <Steps
                            current={currentStep}
                            items={steps}
                            className="mb-6"
                        />
                    </div>

                    {/* Countdown timer */}
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

                    {/* Step content */}
                    {renderStepContent()}
                </div>
            </Content>
        </Layout>
    );
};

export default Payment;

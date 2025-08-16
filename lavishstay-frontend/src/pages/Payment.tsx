import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import {
    Layout, Typography, Form, Steps, Alert, message, Row, Col, Spin
} from 'antd';
import { useSelector, useDispatch } from 'react-redux';
import store from '../store';
import { selectBookingState, selectSelectedRoomsSummary, selectHasSelectedRooms, clearBookingData, setTotals } from "../store/slices/bookingSlice";
import { selectSearchData } from "../store/slices/searchSlice";
import { BookingInfoStep, PaymentStep, PaymentSummary, CompletionStep } from '../components/payment';
import { useBookingManager } from '../hooks/useBookingManager';
import { usePaymentSettings } from '../hooks/usePaymentSetting';

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
    const [countdown, setCountdown] = useState(900); // Will be updated from settings
    const [backendBookingCode, setBackendBookingCode] = useState<string | null>(null);
    const [paymentProcessing, setPaymentProcessing] = useState(false);
    const [customerInfo, setCustomerInfo] = useState<any>(null);
    const [selectedPaymentMethod, setSelectedPaymentMethod] = useState(''); // Will be set from settings

    // Redux state
    const bookingState = useSelector(selectBookingState);
    const searchData = useSelector(selectSearchData);
    const selectedRoomsSummary = useSelector(selectSelectedRoomsSummary);
    const hasSelectedRooms = useSelector(selectHasSelectedRooms);

    // Payment settings hook
    const {
        settings: paymentSettings,
        loading: settingsLoading,
        error: settingsError,
        generateVietQRUrl,
        getAvailablePaymentMethods
    } = usePaymentSettings();

    // Anti-spam booking manager
    const {
        bookingCode,
        isProcessing,
        canProceed,
        cooldownInfo
    } = useBookingManager();

    // Initialize settings-dependent state
    useEffect(() => {
        if (!settingsLoading && paymentSettings) {
            // Set default payment method from settings
            if (!selectedPaymentMethod) {
                setSelectedPaymentMethod(paymentSettings.general.default_payment_method);
            }
            
            // Set countdown from settings
            setCountdown(paymentSettings.general.payment_timeout);
        }
    }, [settingsLoading, paymentSettings, selectedPaymentMethod]);

    // API Base URL from settings
    const API_BASE_URL = paymentSettings.general.api_base_url;

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
                        throw new Error('Không thể kết nối đến server. Vui lòng:\n1. Kiểm tra kết nối internet\n2. Đảm bảo backend đang chạy tại ' + API_BASE_URL + '\n3. Thử lại sau vài giây');
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
            // Create rooms payload (existing logic)
            const roomsPayload = [];

            if (searchData.rooms && searchData.rooms.length > 0 && selectedRoomsSummary.length > 0) {
                // Sử dụng searchData.rooms làm nguồn chính cho số lượng phòng và guest info
                for (let i = 0; i < searchData.rooms.length; i++) {
                    const guestInfoForThisRoom = searchData.rooms[i];
                    const roomSummary = selectedRoomsSummary[0];

                    const roomGuestName = values[`room_${i}_guest_name`] || values.fullName;
                    const roomGuestEmail = values[`room_${i}_guest_email`] || values.email;
                    const roomGuestPhone = values[`room_${i}_guest_phone`] || values.phone;

                    const roomPrice = roomSummary.totalPrice;
                    const optionPricePerNight = roomSummary.pricePerNight;

                    const roomPayload = {
                        room_id: roomSummary.room.id,
                        room_type_id: roomSummary.room.room_type_id || roomSummary.room.id,
                        room_price: roomPrice,
                        guest_name: roomGuestName,
                        guest_email: roomGuestEmail,
                        guest_phone: roomGuestPhone,
                        adults: guestInfoForThisRoom.adults,
                        children: guestInfoForThisRoom.children,
                        children_age: guestInfoForThisRoom.childrenAges || [],
                        option_id: roomSummary.optionId,
                        option_name: roomSummary.option.name,
                        option_price: optionPricePerNight,
                        policies: (roomSummary.room as any)?.policies || (roomSummary.option as any)?.policies || {},
                        package_id: (roomSummary.option as any)?.id?.replace('pkg-', '') || null,
                        meal_type: (roomSummary.option as any)?.mealType || null,
                        bed_type: (roomSummary.option as any)?.bedType || null,
                        most_popular: (roomSummary.option as any)?.mostPopular ? 1 : 0,
                        recommended: (roomSummary.option as any)?.recommended ? 1 : 0,
                        urgency_message: (roomSummary.option as any)?.urgencyMessage || null,
                        recommendation_score: (roomSummary.option as any)?.recommendationScore || null,
                        cancellation_policy: (roomSummary.option as any)?.cancellationPolicy,
                        payment_policy: (roomSummary.option as any)?.paymentPolicy,
                        check_out_policy: (roomSummary.option as any)?.checkOutPolicy,
                        deposit_percentage: (roomSummary.option as any)?.depositPercentage,
                        deposit_fixed_amount: (roomSummary.option as any)?.depositFixedAmount,
                        free_cancellation_days: (roomSummary.option as any)?.freeCancellationDays,
                        penalty_percentage: (roomSummary.option as any)?.penaltyPercentage,
                        penalty_fixed_amount: (roomSummary.option as any)?.penaltyFixedAmount,
                        standard_check_out_time: (roomSummary.option as any)?.standardCheckOutTime
                    };

                    roomsPayload.push(roomPayload);
                }
            } else {
                // Fallback logic (existing code)
                roomsPayload.push(...selectedRoomsSummary.map((roomSummary, index) => {
                    const guestInfoForThisRoom = { adults: 1, children: 0, childrenAges: [] };

                    const roomGuestName = values[`room_${index}_guest_name`] || values.fullName;
                    const roomGuestEmail = values[`room_${index}_guest_email`] || values.email;
                    const roomGuestPhone = values[`room_${index}_guest_phone`] || values.phone;

                    const roomPrice = roomSummary.totalPrice;
                    const optionPricePerNight = roomSummary.pricePerNight;

                    return {
                        room_id: roomSummary.room.id,
                        room_price: roomPrice,
                        guest_name: roomGuestName,
                        guest_email: roomGuestEmail,
                        guest_phone: roomGuestPhone,
                        adults: guestInfoForThisRoom.adults,
                        children: guestInfoForThisRoom.children,
                        children_age: guestInfoForThisRoom.childrenAges || [],
                        option_id: roomSummary.optionId,
                        option_name: roomSummary.option.name,
                        option_price: optionPricePerNight,
                        policies: (roomSummary.option as any)?.policies ||
                            (roomSummary as any)?.policies ||
                        {
                            cancellation: { description: 'Chính sách hủy phòng sẽ được áp dụng theo quy định' },
                            deposit: { description: 'Chính sách thanh toán theo quy định' },
                            check_out: { description: 'Chính sách trả phòng theo quy định' }
                        },
                        package_id: (roomSummary.option as any)?.packageId || null,
                        meal_type: (roomSummary.option as any)?.mealType || null,
                        bed_type: (roomSummary.option as any)?.bedType || null,
                    };
                }));
            }

            const totalGuests = roomsPayload.reduce((acc, room) => acc + room.adults + room.children, 0);

            let userId = null;
            try {
                const userStr = localStorage.getItem('authUser');
                if (userStr) {
                    const user = JSON.parse(userStr);
                    userId = user?.id || null;
                }
            } catch (e) {
                userId = null;
            }

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
                room_type_id: roomsPayload[0].room_type_id,
                rooms: roomsPayload,
                totals: {
                    roomsTotal: totals.roomsTotal,
                    breakfastTotal: totals.breakfastTotal,
                    serviceFee: totals.serviceFee,
                    taxAmount: totals.taxAmount,
                    discountAmount: totals.discountAmount,
                    finalTotal: totals.finalTotal,
                    nights: nights,
                },
                user_id: userId
            };

            console.log('Submitting booking to backend:', JSON.stringify(bookingPayload, null, 2));
            
            // Use retry mechanism for better reliability
            const result = await createBookingWithRetry(bookingPayload);

            if (result.success && result.booking_code) {
                setBackendBookingCode(result.booking_code);
                message.success('Đã tạo đơn đặt phòng thành công!');

                // If pay_at_hotel, move to completion step
                if (selectedPaymentMethod === 'pay_at_hotel') {
                    setCurrentStep(2);
                } else {
                    setCurrentStep(1);
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
                setCurrentStep(2);
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
                    setCurrentStep(2);
                    setPaymentProcessing(false);
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
                    setCurrentStep(2);
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

    // Use totals from Redux store (already calculated with correct prices)
    const totals = React.useMemo(() => {
        const storeTotals = bookingState.totals || {
            roomsTotal: 0,
            breakfastTotal: 0,
            serviceFee: 0,
            taxAmount: 0,
            discountAmount: 0,
            finalTotal: 0,
            nights: 1
        };

        if (selectedRoomsSummary.length > 0) {
            const calculatedRoomsTotal = selectedRoomsSummary.reduce(
                (sum, room) => sum + room.totalPrice,
                0
            );

            const calculatedTotal = calculatedRoomsTotal +
                (storeTotals.breakfastTotal || 0) +
                (storeTotals.serviceFee || 0) +
                (storeTotals.taxAmount || 0) -
                (storeTotals.discountAmount || 0);

            if (Math.abs(calculatedTotal - storeTotals.finalTotal) > 1) {
                const updatedTotals = {
                    ...storeTotals,
                    roomsTotal: calculatedRoomsTotal,
                    finalTotal: calculatedTotal,
                    nights: nights
                };

                dispatch(setTotals(updatedTotals));
                return updatedTotals;
            }
        }

        const updatedTotals = {
            ...storeTotals,
            nights: nights
        };

        return updatedTotals;
    }, [bookingState.totals, nights, selectedRoomsSummary, dispatch]);

    // Handle navigation functions
    const handleViewBookings = () => {
        dispatch(clearBookingData());
        navigate('/bookings');
    };

    const handleNewBooking = () => {
        dispatch(clearBookingData());
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
                                availablePaymentMethods={getAvailablePaymentMethods()}
                                onPaymentMethodChange={setSelectedPaymentMethod}
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
                                availablePaymentMethods={getAvailablePaymentMethods()}
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

    // Show loading spinner while fetching settings
    if (settingsLoading) {
        return (
            <Layout style={{ backgroundColor: '#f0f2f5', minHeight: '100vh', marginTop: 64 }}>
                <Content style={{ padding: '24px' }}>
                    <div style={{ maxWidth: 1200, margin: '0 auto', textAlign: 'center', paddingTop: '100px' }}>
                        <Spin size="large" />
                        <div style={{ marginTop: 16 }}>Đang tải cấu hình thanh toán...</div>
                    </div>
                </Content>
            </Layout>
        );
    }

    // Show error if settings failed to load
    if (settingsError) {
        return (
            <Layout style={{ backgroundColor: '#f0f2f5', minHeight: '100vh', marginTop: 64 }}>
                <Content style={{ padding: '24px' }}>
                    <div style={{ maxWidth: 1200, margin: '0 auto' }}>
                        <Alert
                            message="Lỗi tải cấu hình thanh toán"
                            description={settingsError}
                            type="error"
                            showIcon
                            action={
                                <button
                                    onClick={() => window.location.reload()}
                                    className="ant-btn ant-btn-primary"
                                >
                                    Thử lại
                                </button>
                            }
                        />
                    </div>
                </Content>
            </Layout>
        );
    }

    return (
        <Layout style={{ backgroundColor: '#f0f2f5', minHeight: '100vh', marginTop: 64 }}>
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
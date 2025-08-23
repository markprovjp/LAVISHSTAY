import React, { useState, useEffect, useRef, useCallback } from 'react';
import {
    Modal,
    Steps,
    Button,
    List,
    Checkbox,
    InputNumber,
    Spin,
    Alert,
    Image,
    Typography,
    Card,
    Space,
    Row,
    Col,
    Statistic,
    Tag,
    message,
    Progress,
} from 'antd';
import {
    DollarOutlined,
    QrcodeOutlined,
    CheckCircleOutlined,
    ClockCircleOutlined,
    ReloadOutlined,
} from '@ant-design/icons';
import { default as api } from '../../utils/api';
import {
    ServicePaymentModalProps,
    ServicePaymentInfo,
    QRResponse,
    PaymentCheckResponse,
    ApiResponse,
} from '../../types/payment';

const { Title, Text } = Typography;
const { Step } = Steps;

interface ServicePaymentModalState {
    loading: boolean;
    currentStep: number;
    paymentInfo: ServicePaymentInfo | null;
    selectedServices: number[];
    customAmount: number | null;
    qrData: QRResponse | null;
    checkingPayment: boolean;
    pollAttempts: number;
    pollInterval: NodeJS.Timeout | null;
    timeRemaining: number;
    countdownInterval: NodeJS.Timeout | null;
    error: string | null;
    success: boolean;
    paymentResult: PaymentCheckResponse | null;
}

const MAX_POLL_ATTEMPTS = 12;
const POLL_INTERVAL = 5000; // 5 seconds
const MIN_PAYMENT_AMOUNT = 1000;

const ServicePaymentModal: React.FC<ServicePaymentModalProps> = ({
    bookingId,
    bookingCode,
    visible,
    onClose,
    onSuccess,
}) => {
    const [state, setState] = useState<ServicePaymentModalState>({
        loading: false,
        currentStep: 0,
        paymentInfo: null,
        selectedServices: [],
        customAmount: null,
        qrData: null,
        checkingPayment: false,
        pollAttempts: 0,
        pollInterval: null,
        timeRemaining: 0,
        countdownInterval: null,
        error: null,
        success: false,
        paymentResult: null,
    });

    const liveRegionRef = useRef<HTMLDivElement>(null);

    const announceToScreenReader = useCallback((message: string) => {
        if (liveRegionRef.current) {
            liveRegionRef.current.textContent = message;
        }
    }, []);

    const formatCurrency = useCallback((amount: number) => {
        return new Intl.NumberFormat('vi-VN').format(amount) + ' ₫';
    }, []);

    const clearIntervals = useCallback(() => {
        if (state.pollInterval) {
            clearInterval(state.pollInterval);
        }
        if (state.countdownInterval) {
            clearInterval(state.countdownInterval);
        }
    }, [state.pollInterval, state.countdownInterval]);

    const resetModal = useCallback(() => {
        clearIntervals();
        setState({
            loading: false,
            currentStep: 0,
            paymentInfo: null,
            selectedServices: [],
            customAmount: null,
            qrData: null,
            checkingPayment: false,
            pollAttempts: 0,
            pollInterval: null,
            timeRemaining: 0,
            countdownInterval: null,
            error: null,
            success: false,
            paymentResult: null,
        });
    }, [clearIntervals]);

    const fetchPaymentInfo = useCallback(async () => {
        setState(prev => ({ ...prev, loading: true, error: null }));

        try {
            const response = await api.get<ApiResponse<ServicePaymentInfo>>(
                `/reception/bookings/${bookingId}/services/payment-info`
            );

            if (response.data.success && response.data.data) {
                const paymentInfo = response.data.data;
                // Auto-select pending and partial services
                const autoSelected = paymentInfo.services
                    .filter(service => service.payment_status === 'pending' || service.payment_status === 'partial')
                    .map(service => service.booking_service_id);

                setState(prev => ({
                    ...prev,
                    loading: false,
                    paymentInfo,
                    selectedServices: autoSelected,
                }));
            } else {
                throw new Error(response.data.message || 'Failed to fetch payment info');
            }
        } catch (error: any) {
            setState(prev => ({
                ...prev,
                loading: false,
                error: error.response?.data?.message || error.message || 'Không thể tải thông tin thanh toán',
            }));
        }
    }, [bookingId]);

    const generateQR = useCallback(async () => {
        if (!state.paymentInfo || state.selectedServices.length === 0) return;

        const amount = state.customAmount || getSelectedOutstanding();
        if (amount < MIN_PAYMENT_AMOUNT) {
            message.error(`Số tiền tối thiểu là ${formatCurrency(MIN_PAYMENT_AMOUNT)}`);
            return;
        }

        setState(prev => ({ ...prev, loading: true, error: null }));

        try {
            const response = await api.post<ApiResponse<QRResponse>>(
                `/reception/bookings/${bookingId}/services/payment/qr`,
                {
                    amount,
                    service_ids: state.selectedServices,
                }
            );

            if (response.data.success && response.data.data) {
                const qrData = response.data.data;
                const expiresAt = new Date(qrData.expires_at);
                const now = new Date();
                const timeRemaining = Math.max(0, Math.floor((expiresAt.getTime() - now.getTime()) / 1000));

                setState(prev => ({
                    ...prev,
                    loading: false,
                    currentStep: 2,
                    qrData,
                    timeRemaining,
                    pollAttempts: 0,
                }));

                startCountdown(timeRemaining);
                startPolling();
                announceToScreenReader(`QR code generated for ${qrData.formatted_amount}`);
            } else {
                throw new Error(response.data.message || 'Failed to generate QR');
            }
        } catch (error: any) {
            setState(prev => ({
                ...prev,
                loading: false,
                error: error.response?.data?.message || error.message || 'Không thể tạo mã QR',
            }));
        }
    }, [state.paymentInfo, state.selectedServices, state.customAmount, bookingId, formatCurrency, announceToScreenReader]);

    const startCountdown = useCallback((initialTime: number) => {
        const interval = setInterval(() => {
            setState(prev => {
                const newTime = prev.timeRemaining - 1;
                if (newTime <= 0) {
                    clearInterval(interval);
                    return { ...prev, timeRemaining: 0, countdownInterval: null };
                }
                return { ...prev, timeRemaining: newTime };
            });
        }, 1000);

        setState(prev => ({ ...prev, countdownInterval: interval, timeRemaining: initialTime }));
    }, []);

    const startPolling = useCallback(() => {
        const interval = setInterval(() => {
            setState(prev => {
                if (prev.pollAttempts >= MAX_POLL_ATTEMPTS) {
                    clearInterval(interval);
                    return { ...prev, pollInterval: null };
                }
                checkPayment(true);
                return { ...prev, pollAttempts: prev.pollAttempts + 1 };
            });
        }, POLL_INTERVAL);

        setState(prev => ({ ...prev, pollInterval: interval }));
    }, []);

    const checkPayment = useCallback(async (isPolling = false) => {
        if (!state.qrData || (!isPolling && state.checkingPayment)) return;

        setState(prev => ({ ...prev, checkingPayment: true, error: null }));

        try {
            const amount = state.customAmount || getSelectedOutstanding();
            const response = await api.post<PaymentCheckResponse>(
                `/reception/bookings/services/payment/check`,
                {
                    payment_id: state.qrData.payment_id,
                    booking_code: bookingCode,
                    amount,
                    service_ids: state.selectedServices,
                }
            );

            if (response.data.payment_found) {
                clearIntervals();
                setState(prev => ({
                    ...prev,
                    checkingPayment: false,
                    success: true,
                    paymentResult: response.data,
                }));

                announceToScreenReader('Payment confirmed successfully');
                message.success('Thanh toán thành công!');

                // Refresh payment info
                await fetchPaymentInfo();

                // If backend indicates booking can be checked out, call checkout endpoint
                if (response.data?.can_checkout) {
                    try {
                        await api.post(`/reception/bookings/${bookingId}/checkout`, {
                            force_checkout: true,
                            final_payment_method: 'vietqr'
                        });
                        message.success('Check-out tự động hoàn tất');
                    } catch (err) {
                        // If checkout fails, still proceed but notify user
                        message.error('Không thể tự động check-out: ' + (err as any).message);
                    }
                }

                setTimeout(() => {
                    onSuccess?.(response.data);
                    handleClose();
                }, 2000);
            } else {
                setState(prev => ({
                    ...prev,
                    checkingPayment: false,
                }));

                if (!isPolling) {
                    message.info('Chưa tìm thấy giao dịch thanh toán');
                }
            }
        } catch (error: any) {
            setState(prev => ({
                ...prev,
                checkingPayment: false,
                error: error.response?.data?.message || error.message || 'Lỗi kiểm tra thanh toán',
            }));
        }
    }, [state.qrData, state.checkingPayment, state.customAmount, state.selectedServices, bookingCode, clearIntervals, announceToScreenReader, onSuccess, fetchPaymentInfo]);

    const getSelectedOutstanding = useCallback(() => {
        if (!state.paymentInfo) return 0;
        return state.paymentInfo.services
            .filter(service => state.selectedServices.includes(service.booking_service_id))
            .reduce((sum, service) => sum + service.outstanding_amount_vnd, 0);
    }, [state.paymentInfo, state.selectedServices]);

    const handleServiceSelection = useCallback((serviceId: number, checked: boolean) => {
        setState(prev => ({
            ...prev,
            selectedServices: checked
                ? [...prev.selectedServices, serviceId]
                : prev.selectedServices.filter(id => id !== serviceId),
            customAmount: null, // Reset custom amount when selection changes
        }));
    }, []);

    const handleNext = useCallback(() => {
        setState(prev => ({ ...prev, currentStep: prev.currentStep + 1 }));
    }, []);

    const handlePrev = useCallback(() => {
        setState(prev => ({ ...prev, currentStep: prev.currentStep - 1 }));
    }, []);

    const handleClose = useCallback(() => {
        clearIntervals();
        resetModal();
        onClose();
    }, [clearIntervals, resetModal, onClose]);

    const generateNewQR = useCallback(() => {
        setState(prev => ({
            ...prev,
            qrData: null,
            currentStep: 1,
            timeRemaining: 0,
            pollAttempts: 0,
            success: false,
            paymentResult: null,
        }));
        clearIntervals();
    }, [clearIntervals]);

    // Effects
    useEffect(() => {
        if (visible && !state.paymentInfo) {
            fetchPaymentInfo();
        }
    }, [visible, state.paymentInfo, fetchPaymentInfo]);

    useEffect(() => {
        return () => {
            clearIntervals();
        };
    }, [clearIntervals]);

    // Computed values
    const selectedOutstanding = getSelectedOutstanding();
    const canGenerateQR = state.selectedServices.length > 0 &&
        (state.customAmount ? state.customAmount >= MIN_PAYMENT_AMOUNT : selectedOutstanding >= MIN_PAYMENT_AMOUNT);
    const finalAmount = state.customAmount || selectedOutstanding;

    const renderStep1 = () => (
        <div>
            <Title level={4}>Chọn dịch vụ và số tiền thanh toán</Title>

            {state.paymentInfo && (
                <>
                    <Card className="mb-4">
                        <Row gutter={16}>
                            <Col span={6}>
                                <Statistic
                                    title="Tổng dịch vụ"
                                    value={state.paymentInfo.summary.total_service_amount}
                                    formatter={(value) => formatCurrency(Number(value))}
                                />
                            </Col>
                            <Col span={6}>
                                <Statistic
                                    title="Đã thanh toán"
                                    value={state.paymentInfo.summary.total_paid_amount}
                                    formatter={(value) => formatCurrency(Number(value))}
                                />
                            </Col>
                            <Col span={6}>
                                <Statistic
                                    title="Còn lại"
                                    value={state.paymentInfo.summary.total_outstanding}
                                    formatter={(value) => formatCurrency(Number(value))}
                                />
                            </Col>
                            <Col span={6}>
                                <Statistic
                                    title="Tỷ lệ"
                                    value={state.paymentInfo.summary.payment_percentage}
                                    suffix="%"
                                />
                            </Col>
                        </Row>
                    </Card>

                    <Title level={5}>Danh sách dịch vụ:</Title>
                    <List
                        dataSource={state.paymentInfo.services}
                        renderItem={(service) => (
                            <List.Item>
                                <List.Item.Meta
                                    avatar={
                                        <Checkbox
                                            checked={state.selectedServices.includes(service.booking_service_id)}
                                            onChange={(e) => handleServiceSelection(service.booking_service_id, e.target.checked)}
                                            disabled={service.payment_status === 'paid'}
                                        />
                                    }
                                    title={
                                        <Space>
                                            <Text strong>{service.service_name}</Text>
                                            <Tag color={
                                                service.payment_status === 'paid' ? 'green' :
                                                    service.payment_status === 'partial' ? 'orange' : 'red'
                                            }>
                                                {service.payment_status === 'paid' ? 'Đã thanh toán' :
                                                    service.payment_status === 'partial' ? 'Thanh toán một phần' : 'Chưa thanh toán'}
                                            </Tag>
                                        </Space>
                                    }
                                    description={
                                        <Space direction="vertical" size="small">
                                            <Text>Số lượng: {service.quantity} x {formatCurrency(Number(service.unit_price_vnd))}</Text>
                                            <Text>Tổng: {service.formatted_total_price}</Text>
                                            <Text>Đã thanh toán: {service.formatted_paid_amount}</Text>
                                            <Text strong>Còn lại: {service.formatted_outstanding}</Text>
                                        </Space>
                                    }
                                />
                            </List.Item>
                        )}
                    />

                    <Card className="mt-4">
                        <Title level={5}>Số tiền thanh toán:</Title>
                        <Space direction="vertical" className="w-full">
                            <Button
                                type="dashed"
                                block
                                onClick={() => setState(prev => ({ ...prev, customAmount: null }))}
                                disabled={selectedOutstanding === 0}
                            >
                                Thanh toán số tiền còn lại: {formatCurrency(selectedOutstanding)}
                            </Button>
                            <div>
                                <Text>Hoặc nhập số tiền tùy chỉnh:</Text>
                                <InputNumber
                                    className="w-full mt-2"
                                    value={state.customAmount}
                                    onChange={(value) => setState(prev => ({ ...prev, customAmount: value }))}
                                    min={MIN_PAYMENT_AMOUNT}
                                    step={1000}
                                    formatter={(value) => `${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')}
                                    parser={(value) => Number(value!.replace(/\$\s?|(,*)/g, ''))}
                                    addonAfter="₫"
                                    placeholder="Nhập số tiền"
                                />
                            </div>
                        </Space>
                    </Card>
                </>
            )}
        </div>
    );

    const renderStep2 = () => (
        <div>
            <Title level={4}>Xác nhận thông tin thanh toán</Title>

            {state.paymentInfo && (
                <>
                    <Card>
                        <Title level={5}>Dịch vụ được chọn:</Title>
                        <List
                            size="small"
                            dataSource={state.paymentInfo.services.filter(s =>
                                state.selectedServices.includes(s.booking_service_id)
                            )}
                            renderItem={(service) => (
                                <List.Item>
                                    <Text>{service.service_name} x {service.quantity}</Text>
                                    <Text strong>{service.formatted_outstanding}</Text>
                                </List.Item>
                            )}
                        />

                        <div className="mt-4 p-4 bg-gray-50 rounded">
                            <Row justify="space-between">
                                <Text strong>Tổng số tiền thanh toán:</Text>
                                <Text strong className="text-lg text-primary">
                                    {formatCurrency(finalAmount)}
                                </Text>
                            </Row>
                        </div>
                    </Card>
                </>
            )}
        </div>
    );

    const renderStep3 = () => (
        <div>
            {state.success ? (
                <div className="text-center">
                    <CheckCircleOutlined className="text-6xl text-green-500 mb-4" />
                    <Title level={3} className="text-green-600">Thanh toán thành công!</Title>
                    {state.paymentResult && (
                        <div>
                            <Text>Mã giao dịch: {state.paymentResult.payment?.transaction_id}</Text>
                        </div>
                    )}
                </div>
            ) : (
                <>
                    <Title level={4}>Quét mã QR để thanh toán</Title>

                    {state.qrData && (
                        <Card>
                            <div className="text-center">
                                <Image
                                    src={state.qrData.qr_url}
                                    alt={`VietQR for payment ${state.qrData.formatted_amount}`}
                                    width={200}
                                    height={200}
                                    className="mb-4"
                                />

                                <Title level={5}>Thông tin thanh toán:</Title>
                                <Space direction="vertical" className="w-full">
                                    <Text>Số tiền: <Text strong>{state.qrData.formatted_amount}</Text></Text>
                                    <Text>Nội dung: <Text code>{state.qrData.payment_content}</Text></Text>
                                    <Text>Ngân hàng: <Text strong>{state.qrData.bank_info.account_name}</Text></Text>
                                    <Text>Số tài khoản: <Text code>{state.qrData.bank_info.account_no}</Text></Text>
                                </Space>

                                {state.timeRemaining > 0 && (
                                    <div className="mt-4">
                                        <Progress
                                            type="circle"
                                            percent={Math.round((state.timeRemaining / 900) * 100)} // 15 minutes = 900 seconds
                                            format={() => `${Math.floor(state.timeRemaining / 60)}:${(state.timeRemaining % 60).toString().padStart(2, '0')}`}
                                            size={80}
                                        />
                                        <div className="mt-2">
                                            <ClockCircleOutlined /> Thời gian còn lại
                                        </div>
                                    </div>
                                )}

                                <Space className="mt-4">
                                    <Button
                                        type="primary"
                                        icon={<CheckCircleOutlined />}
                                        loading={state.checkingPayment}
                                        onClick={() => checkPayment(false)}
                                    >
                                        Kiểm tra thanh toán
                                    </Button>

                                    {state.timeRemaining === 0 && (
                                        <Button
                                            icon={<ReloadOutlined />}
                                            onClick={generateNewQR}
                                        >
                                            Tạo QR mới
                                        </Button>
                                    )}
                                </Space>
                            </div>
                        </Card>
                    )}
                </>
            )}
        </div>
    );

    const steps = [
        {
            title: 'Chọn dịch vụ',
            icon: <DollarOutlined />,
            content: renderStep1(),
        },
        {
            title: 'Xác nhận',
            icon: <QrcodeOutlined />,
            content: renderStep2(),
        },
        {
            title: 'Thanh toán',
            icon: <CheckCircleOutlined />,
            content: renderStep3(),
        },
    ];

    return (
        <Modal
            title={`Thanh toán dịch vụ phát sinh - ${bookingCode}`}
            open={visible}
            onCancel={handleClose}
            width={800}
            footer={null}
            destroyOnClose
        >
            <div
                ref={liveRegionRef}
                className="sr-only"
                aria-live="polite"
                aria-atomic="true"
            />

            <Spin spinning={state.loading}>
                {state.error && (
                    <Alert
                        message="Lỗi"
                        description={state.error}
                        type="error"
                        closable
                        className="mb-4"
                        action={
                            <Button size="small" onClick={fetchPaymentInfo}>
                                Thử lại
                            </Button>
                        }
                    />
                )}

                {state.paymentInfo && state.paymentInfo.services.length === 0 && (
                    <Alert
                        message="Không có dịch vụ"
                        description="Đơn đặt phòng này không có dịch vụ phát sinh nào."
                        type="info"
                        className="mb-4"
                    />
                )}

                {state.paymentInfo && state.paymentInfo.services.length > 0 && (
                    <>
                        <Steps current={state.currentStep} className="mb-6">
                            {steps.map((step, index) => (
                                <Step key={index} title={step.title} icon={step.icon} />
                            ))}
                        </Steps>

                        <div className="min-h-96">
                            {steps[state.currentStep]?.content}
                        </div>

                        <div className="flex justify-between mt-6">
                            {state.currentStep > 0 && !state.success && (
                                <Button onClick={handlePrev}>
                                    Quay lại
                                </Button>
                            )}

                            <div className="ml-auto">
                                {state.currentStep === 0 && (
                                    <Button
                                        type="primary"
                                        onClick={handleNext}
                                        disabled={!canGenerateQR}
                                    >
                                        Tiếp tục
                                    </Button>
                                )}

                                {state.currentStep === 1 && (
                                    <Button
                                        type="primary"
                                        icon={<QrcodeOutlined />}
                                        loading={state.loading}
                                        onClick={generateQR}
                                    >
                                        Tạo mã QR
                                    </Button>
                                )}

                                {state.currentStep === 2 && state.success && (
                                    <Button type="primary" onClick={handleClose}>
                                        Hoàn thành
                                    </Button>
                                )}
                            </div>
                        </div>
                    </>
                )}
            </Spin>
        </Modal>
    );
};

export default ServicePaymentModal;

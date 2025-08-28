import React, { useState, useEffect, useRef, useCallback } from 'react';
import {
    Modal,
    Steps,
    Button,
    Card,
    Space,
    Row,
    Col,
    Statistic,
    message,
    Progress,
    Image,
    Typography,
    Alert,
    Spin,
} from 'antd';
import {
    DollarOutlined,
    QrcodeOutlined,
    CheckCircleOutlined,
    ClockCircleOutlined,
    ReloadOutlined,
    ExclamationCircleOutlined,
} from '@ant-design/icons';
import { default as api } from '../../utils/api';

const { Title, Text } = Typography;
const { Step } = Steps;

interface PolicyInfo {
    message: string;
    reason: string;
    formula: string;
    policy: string;
    penalty: number;
    penalty_type: string;
    penalty_percentage: string;
    penalty_fixed_amount: number;
    booking_info: any;
    room_info: any;
    hotel_info: any;
}

interface QRResponse {
    qr_url: string;
    payment_id: string;
    formatted_amount: string;
    payment_content: string;
    expires_at: string;
    bank_info: {
        account_name: string;
        account_no: string;
    };
}

interface PaymentCheckResponse {
    payment_found: boolean;
    payment?: {
        transaction_id: string;
        amount: number;
    };
    can_checkout?: boolean;
}

interface BookingActionPaymentModalProps {
    visible: boolean;
    onClose: () => void;
    onSuccess: (result: any) => void;
    bookingId: number;
    bookingCode: string;
    actionType: 'cancel' | 'extend' | 'reschedule';
    policyInfo: PolicyInfo | null;
    actionParams?: any; // For extend: { newCheckOutDate }, for reschedule: { newCheckInDate, newCheckOutDate, newRoomId }
}

const BookingActionPaymentModal: React.FC<BookingActionPaymentModalProps> = ({
    visible,
    onClose,
    onSuccess,
    bookingId,
    bookingCode,
    actionType,
    policyInfo,
    actionParams,
}) => {
    const [loading, setLoading] = useState(false);
    const [currentStep, setCurrentStep] = useState(0);
    const [qrData, setQrData] = useState<QRResponse | null>(null);
    const [checkingPayment, setCheckingPayment] = useState(false);
    // polling attempt count is tracked only via internal interval logic; remove unused state
    const [pollInterval, setPollInterval] = useState<NodeJS.Timeout | null>(null);
    const [timeRemaining, setTimeRemaining] = useState(0);
    const [countdownInterval, setCountdownInterval] = useState<NodeJS.Timeout | null>(null);
    const [error, setError] = useState<string | null>(null);
    const [success, setSuccess] = useState(false);
    const [paymentResult, setPaymentResult] = useState<PaymentCheckResponse | null>(null);

    const liveRegionRef = useRef<HTMLDivElement>(null);

    const MAX_POLL_ATTEMPTS = 12;
    const POLL_INTERVAL = 5000; // 5 seconds

    const announceToScreenReader = useCallback((message: string) => {
        if (liveRegionRef.current) {
            liveRegionRef.current.textContent = message;
        }
    }, []);

    const formatCurrency = useCallback((amount: number) => {
        return new Intl.NumberFormat('vi-VN').format(amount) + ' ₫';
    }, []);

    const clearIntervals = useCallback(() => {
        if (pollInterval) {
            clearInterval(pollInterval);
        }
        if (countdownInterval) {
            clearInterval(countdownInterval);
        }
    }, [pollInterval, countdownInterval]);

    const resetModal = useCallback(() => {
        clearIntervals();
        setLoading(false);
        setCurrentStep(0);
        setQrData(null);
        setCheckingPayment(false);
        // setPollAttempts(0);
        setPollInterval(null);
        setTimeRemaining(0);
        setCountdownInterval(null);
        setError(null);
        setSuccess(false);
        setPaymentResult(null);
    }, [clearIntervals]);

    const getActionTitle = () => {
        switch (actionType) {
            case 'cancel': return 'Hủy đặt phòng';
            case 'extend': return 'Gia hạn đặt phòng';
            case 'reschedule': return 'Dời lịch đặt phòng';
            default: return 'Thao tác đặt phòng';
        }
    };

    const getActionEndpoint = () => {
        switch (actionType) {
            case 'cancel': return `/bookings/${bookingId}/cancel`;
            case 'extend': return `/bookings/${bookingId}/extend`;
            case 'reschedule': return `/bookings/${bookingId}/reschedule`;
            default: return '';
        }
    };

    const generateQR = useCallback(async () => {
        if (!policyInfo || policyInfo.penalty <= 0) return;

        setLoading(true);
        setError(null);

        try {
            // Ensure we have a booking code to send. Prefer explicit prop, fall back to policyInfo data.
            const bookingCodeToSend = bookingCode || policyInfo?.booking_info?.booking_code || '';

            if (!bookingCodeToSend) {
                setError('Mã booking không tồn tại. Vui lòng thử lại.');
                setLoading(false);
                return;
            }

            const response = await api.post<{ success: boolean; data: QRResponse }>(
                `/payment/create-action-fee-qr`,
                {
                    booking_id: bookingId,
                    action_type: actionType,
                    amount: policyInfo.penalty,
                    booking_code: bookingCodeToSend,
                    action_params: actionParams,
                }
            );

            if (response.data.success && response.data.data) {
                const qrData = response.data.data;
                const expiresAt = new Date(qrData.expires_at);
                const now = new Date();
                const timeRemaining = Math.max(0, Math.floor((expiresAt.getTime() - now.getTime()) / 1000));

                setQrData(qrData);
                setTimeRemaining(timeRemaining);
                // setPollAttempts(0);
                setCurrentStep(2);

                startCountdown(timeRemaining);
                startPolling();
                announceToScreenReader(`QR code generated for ${qrData.formatted_amount}`);
            } else {
                const msg = (response.data as any)?.message || 'Failed to generate QR';
                throw new Error(msg);
            }
        } catch (error: any) {
            setError(error.response?.data?.message || error.message || 'Không thể tạo mã QR');
        } finally {
            setLoading(false);
        }
    }, [policyInfo, bookingId, actionType, bookingCode, actionParams, announceToScreenReader]);

    const startCountdown = useCallback((initialTime: number) => {
        const interval = setInterval(() => {
            setTimeRemaining(prev => {
                const newTime = prev - 1;
                if (newTime <= 0) {
                    clearInterval(interval);
                    setCountdownInterval(null);
                    return 0;
                }
                return newTime;
            });
        }, 1000);

        setCountdownInterval(interval);
        setTimeRemaining(initialTime);
    }, []);

    const startPolling = useCallback(() => {
        // Use a closure-scoped attempts counter instead of missing state
        let attempts = 0;
        const interval = setInterval(() => {
            if (attempts >= MAX_POLL_ATTEMPTS) {
                clearInterval(interval);
                setPollInterval(null);
                return;
            }
            attempts += 1;
            checkPayment(true);
        }, POLL_INTERVAL);

        setPollInterval(interval);
    }, []);

    const checkPayment = useCallback(async (isPolling = false) => {
        if (!qrData || (!isPolling && checkingPayment)) return;

        setCheckingPayment(true);
        setError(null);

        try {
            const bookingCodeToSend = bookingCode || policyInfo?.booking_info?.booking_code || '';

            if (!bookingCodeToSend) {
                setError('Mã booking không tồn tại. Không thể kiểm tra thanh toán.');
                setCheckingPayment(false);
                return;
            }

            const response = await api.post<PaymentCheckResponse>(
                `/payment/check-action-fee`,
                {
                    payment_id: qrData.payment_id,
                    booking_code: bookingCodeToSend,
                    action_type: actionType,
                    booking_id: bookingId,
                }
            );

            if (response.data.payment_found) {
                clearIntervals();
                setSuccess(true);
                setPaymentResult(response.data);

                announceToScreenReader('Payment confirmed successfully');
                message.success('Thanh toán thành công!');

                // Now execute the actual action
                await executeAction();

                setTimeout(() => {
                    onSuccess(response.data);
                    handleClose();
                }, 2000);
            } else {
                setCheckingPayment(false);
                if (!isPolling) {
                    message.info('Chưa tìm thấy giao dịch thanh toán');
                }
            }
        } catch (error: any) {
            setCheckingPayment(false);
            setError(error.response?.data?.message || error.message || 'Lỗi kiểm tra thanh toán');
        }
    }, [qrData, checkingPayment, bookingCode, actionType, bookingId, clearIntervals, announceToScreenReader, onSuccess]);

    const executeAction = useCallback(async () => {
        const endpoint = getActionEndpoint();
        const payload: any = { confirm: true };

        if (actionType === 'extend' && actionParams?.newCheckOutDate) {
            payload.new_check_out_date = actionParams.newCheckOutDate;
        } else if (actionType === 'reschedule' && actionParams) {
            payload.new_check_in_date = actionParams.newCheckInDate;
            payload.new_check_out_date = actionParams.newCheckOutDate;
            payload.new_room_id = actionParams.newRoomId;
        }

        // Use PUT for cancel (backend defines cancel as PUT), POST for other actions
        if (actionType === 'cancel') {
            await api.put(endpoint, payload);
        } else {
            await api.post(endpoint, payload);
        }
    }, [actionType, actionParams, bookingId]);

    const executeActionDirectly = useCallback(async () => {
        setLoading(true);
        try {
            await executeAction();
            message.success(`${getActionTitle()} thành công!`);
            onSuccess({ direct_execution: true });
            handleClose();
        } catch (error: any) {
            setError(error.response?.data?.message || error.message || `Lỗi ${getActionTitle().toLowerCase()}`);
        } finally {
            setLoading(false);
        }
    }, [executeAction, onSuccess, getActionTitle]);

    const handleNext = useCallback(() => {
        setCurrentStep(prev => prev + 1);
    }, []);

    const handlePrev = useCallback(() => {
        setCurrentStep(prev => prev - 1);
    }, []);

    const handleClose = useCallback(() => {
        clearIntervals();
        resetModal();
        onClose();
    }, [clearIntervals, resetModal, onClose]);

    const generateNewQR = useCallback(() => {
        setQrData(null);
        setCurrentStep(1);
        setTimeRemaining(0);
        setSuccess(false);
        setPaymentResult(null);
        clearIntervals();
    }, [clearIntervals]);

    useEffect(() => {
        return () => {
            clearIntervals();
        };
    }, [clearIntervals]);

    const renderStep1 = () => (
        <div>
            <Title level={4}>Thông tin {getActionTitle().toLowerCase()}</Title>

            {policyInfo && (
                <Card>
                    <Alert
                        message={policyInfo.message}
                        description={policyInfo.reason}
                        type={policyInfo.penalty > 0 ? 'warning' : 'info'}
                        showIcon
                        className="mb-4"
                    />

                    <Row gutter={16}>
                        <Col span={12}>
                            <Statistic
                                title="Phí áp dụng"
                                value={policyInfo.penalty}
                                formatter={(value) => formatCurrency(Number(value))}
                                prefix={<DollarOutlined />}
                            />
                        </Col>
                        <Col span={12}>
                            <Statistic
                                title="Loại phí"
                                value={policyInfo.penalty_type === 'percentage' ?
                                    `${policyInfo.penalty_percentage}%` : 'Cố định'}
                            />
                        </Col>
                    </Row>

                    {policyInfo.formula && (
                        <div className="mt-4">
                            <Text strong>Công thức tính phí:</Text>
                            <div className="p-3 bg-gray-50 rounded mt-2">
                                <Text code>{policyInfo.formula}</Text>
                            </div>
                        </div>
                    )}
                </Card>
            )}
        </div>
    );

    const renderStep2 = () => (
        <div>
            <Title level={4}>Xác nhận {getActionTitle().toLowerCase()}</Title>

            {policyInfo && (
                <Card>
                    <div className="text-center">
                        {policyInfo.penalty > 0 ? (
                            <>
                                <ExclamationCircleOutlined className="text-4xl text-orange-500 mb-4" />
                                <Title level={5}>Xác nhận thanh toán phí {getActionTitle().toLowerCase()}</Title>
                                <div className="p-4 bg-orange-50 rounded">
                                    <Row justify="space-between">
                                        <Text strong>Số tiền cần thanh toán:</Text>
                                        <Text strong className="text-lg text-orange-600">
                                            {formatCurrency(policyInfo.penalty)}
                                        </Text>
                                    </Row>
                                </div>
                            </>
                        ) : (
                            <>
                                <CheckCircleOutlined className="text-4xl text-green-500 mb-4" />
                                <Title level={5}>Không có phí phát sinh</Title>
                                <Text>Bạn có thể thực hiện {getActionTitle().toLowerCase()} miễn phí.</Text>
                            </>
                        )}
                    </div>
                </Card>
            )}
        </div>
    );

    const renderStep3 = () => (
        <div>
            {success ? (
                <div className="text-center">
                    <CheckCircleOutlined className="text-6xl text-green-500 mb-4" />
                    <Title level={3} className="text-green-600">{getActionTitle()} thành công!</Title>
                    {paymentResult && paymentResult.payment && (
                        <div>
                            <Text>Mã giao dịch: {paymentResult.payment.transaction_id}</Text>
                        </div>
                    )}
                </div>
            ) : (
                <>
                    <Title level={4}>Quét mã QR để thanh toán phí</Title>

                    {qrData && (
                        <Card>
                            <div className="text-center">
                                <Image
                                    src={qrData.qr_url}
                                    alt={`VietQR for ${getActionTitle()} fee ${qrData.formatted_amount}`}
                                    width={200}
                                    height={200}
                                    className="mb-4"
                                />

                                <Title level={5}>Thông tin thanh toán:</Title>
                                <Space direction="vertical" className="w-full">
                                    <Text>Số tiền: <Text strong>{qrData.formatted_amount}</Text></Text>
                                    <Text>Nội dung: <Text code>{qrData.payment_content}</Text></Text>
                                    <Text>Ngân hàng: <Text strong>{qrData.bank_info.account_name}</Text></Text>
                                    <Text>Số tài khoản: <Text code>{qrData.bank_info.account_no}</Text></Text>
                                </Space>

                                {timeRemaining > 0 && (
                                    <div className="mt-4">
                                        <Progress
                                            type="circle"
                                            percent={Math.round((timeRemaining / 900) * 100)} // 15 minutes = 900 seconds
                                            format={() => `${Math.floor(timeRemaining / 60)}:${(timeRemaining % 60).toString().padStart(2, '0')}`}
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
                                        loading={checkingPayment}
                                        onClick={() => checkPayment(false)}
                                    >
                                        Kiểm tra thanh toán
                                    </Button>

                                    {timeRemaining === 0 && (
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
            title: 'Thông tin',
            icon: <ExclamationCircleOutlined />,
            content: renderStep1(),
        },
        {
            title: 'Xác nhận',
            icon: <DollarOutlined />,
            content: renderStep2(),
        },
        {
            title: 'Thanh toán',
            icon: <QrcodeOutlined />,
            content: renderStep3(),
        },
    ];

    if (!policyInfo) {
        return null;
    }

    return (
        <Modal
            title={`${getActionTitle()} - ${bookingCode}`}
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

            <Spin spinning={loading}>
                {error && (
                    <Alert
                        message="Lỗi"
                        description={error}
                        type="error"
                        closable
                        className="mb-4"
                        onClose={() => setError(null)}
                    />
                )}

                <Steps current={currentStep} className="mb-6">
                    {steps.map((step, index) => (
                        <Step key={index} title={step.title} icon={step.icon} />
                    ))}
                </Steps>

                <div className="min-h-96">
                    {steps[currentStep]?.content}
                </div>

                <div className="flex justify-between mt-6">
                    {currentStep > 0 && !success && (
                        <Button onClick={handlePrev}>
                            Quay lại
                        </Button>
                    )}

                    <div className="ml-auto">
                        {currentStep === 0 && (
                            <Button
                                type="primary"
                                onClick={handleNext}
                            >
                                Tiếp tục
                            </Button>
                        )}

                        {currentStep === 1 && (
                            <Space>
                                {policyInfo.penalty > 0 ? (
                                    <Button
                                        type="primary"
                                        icon={<QrcodeOutlined />}
                                        loading={loading}
                                        onClick={generateQR}
                                    >
                                        Tạo mã QR thanh toán
                                    </Button>
                                ) : (
                                    <Button
                                        type="primary"
                                        icon={<CheckCircleOutlined />}
                                        loading={loading}
                                        onClick={executeActionDirectly}
                                    >
                                        Xác nhận {getActionTitle().toLowerCase()}
                                    </Button>
                                )}
                            </Space>
                        )}

                        {currentStep === 2 && success && (
                            <Button type="primary" onClick={handleClose}>
                                Hoàn thành
                            </Button>
                        )}
                    </div>
                </div>
            </Spin>
        </Modal>
    );
};

export default BookingActionPaymentModal;

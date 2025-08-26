import React, { useState, useCallback, useEffect, useRef } from 'react';
import {
    Modal,
    Steps,
    Card,
    Typography,
    Button,
    Radio,
    Space,
    Spin,
    Result,
    Image,
    message,
    Divider,
    Row,
    Col,
    Tag,
    Statistic
} from 'antd';
import {
    DollarOutlined,
    QrcodeOutlined,
    CheckCircleOutlined,
    BankOutlined,
    ClockCircleOutlined,
    CopyOutlined
} from '@ant-design/icons';
import { receptionAPI, paymentAPI } from '../../utils/api';

const { Title, Text } = Typography;
const { Step } = Steps;

interface HotelPaymentModalProps {
    visible: boolean;
    booking: {
        booking_id: number;
        booking_code: string;
        guest_name: string;
        total_price_vnd: number;
        remaining_balance_vnd?: number;
        collected_cash_vnd?: number;
    } | null;
    onClose: () => void;
    onSuccess: () => void;
}

interface PaymentState {
    currentStep: number;
    paymentMethod: 'cash' | 'bank_transfer' | null;
    loading: boolean;
    qrData: {
        vietqr_url?: string;
        amount: number;
        payment_id?: number;
    } | null;
    checkingPayment: boolean;
    paymentSuccess: boolean;
    timeRemaining: number;
    pollInterval: NodeJS.Timeout | null;
    countdownInterval: NodeJS.Timeout | null;
    error: string | null;
}

const PAYMENT_TIMEOUT = 300; // 5 minutes
const POLL_INTERVAL = 3000; // 3 seconds

const HotelPaymentModal: React.FC<HotelPaymentModalProps> = ({
    visible,
    booking,
    onClose,
    onSuccess
}) => {
    const [state, setState] = useState<PaymentState>({
        currentStep: 0,
        paymentMethod: null,
        loading: false,
        qrData: null,
        checkingPayment: false,
        paymentSuccess: false,
        timeRemaining: PAYMENT_TIMEOUT,
        pollInterval: null,
        countdownInterval: null,
        error: null
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
            currentStep: 0,
            paymentMethod: null,
            loading: false,
            qrData: null,
            checkingPayment: false,
            paymentSuccess: false,
            timeRemaining: PAYMENT_TIMEOUT,
            pollInterval: null,
            countdownInterval: null,
            error: null
        });
    }, [clearIntervals]);

    const handleMethodSelection = useCallback((method: 'cash' | 'bank_transfer') => {
        setState(prev => ({ ...prev, paymentMethod: method }));
    }, []);

    const handleNext = useCallback(() => {
        if (state.currentStep === 0 && state.paymentMethod) {
            if (state.paymentMethod === 'cash') {
                // Skip to confirmation for cash payment
                processCashPayment();
            } else {
                // Generate QR for bank transfer
                generateQRCode();
            }
        }
    }, [state.currentStep, state.paymentMethod]);

    const processCashPayment = useCallback(async () => {
        if (!booking) return;

        setState(prev => ({ ...prev, loading: true, error: null }));

        try {
            const response = await receptionAPI.markCashPaid({
                booking_id: booking.booking_id,
                amount_vnd: Number(booking.remaining_balance_vnd ?? 0)
            });

            if (response && response.success) {
                setState(prev => ({
                    ...prev,
                    loading: false,
                    paymentSuccess: true,
                    currentStep: 2
                }));
                announceToScreenReader('Thanh toán tiền mặt thành công');
                message.success('Đã ghi nhận thanh toán tiền mặt!');

                setTimeout(() => {
                    onSuccess();
                    handleClose();
                }, 2000);
            } else {
                throw new Error(response?.message || 'Không thể xử lý thanh toán tiền mặt');
            }
        } catch (error: any) {
            console.error('Error processing cash payment:', error);
            setState(prev => ({
                ...prev,
                loading: false,
                error: error?.response?.data?.message || error?.message || 'Lỗi xử lý thanh toán tiền mặt'
            }));
        }
    }, [booking, announceToScreenReader, onSuccess]);

    const generateQRCode = useCallback(async () => {
        if (!booking) return;

        setState(prev => ({ ...prev, loading: true, error: null }));

        try {
            // Create VietQR similarly to other payment flows (do not force an unsupported payment_type)
            const payload: any = {
                booking_code: booking.booking_code,
                amount: Number(booking.remaining_balance_vnd ?? 0),
                description: `Thanh toán nốt tại khách sạn ${booking.booking_code}`
            };

            const response = await receptionAPI.createVietQR(payload);

            if (response && response.success) {
                setState(prev => ({
                    ...prev,
                    loading: false,
                    currentStep: 1,
                    qrData: {
                        vietqr_url: response.vietqr_url || response.vietqrUrl,
                        amount: Number(booking.remaining_balance_vnd ?? 0),
                        payment_id: response.payment_id
                    },
                    timeRemaining: PAYMENT_TIMEOUT
                }));

                startCountdown();
                startPolling();
                announceToScreenReader(`Mã QR đã được tạo cho ${formatCurrency(booking.remaining_balance_vnd ?? 0)}`);
            } else {
                throw new Error(response?.message || 'Không thể tạo mã QR');
            }
        } catch (error: any) {
            console.error('Error generating QR:', error);
            setState(prev => ({
                ...prev,
                loading: false,
                error: error?.response?.data?.message || error?.message || 'Không thể tạo mã QR thanh toán'
            }));
        }
    }, [booking, formatCurrency, announceToScreenReader]);

    const startCountdown = useCallback(() => {
        const interval = setInterval(() => {
            setState(prev => {
                const newTime = prev.timeRemaining - 1;
                if (newTime <= 0) {
                    clearInterval(interval);
                    return { ...prev, timeRemaining: 0, error: 'Mã QR đã hết hạn' };
                }
                return { ...prev, timeRemaining: newTime };
            });
        }, 1000);

        setState(prev => ({ ...prev, countdownInterval: interval }));
    }, []);

    const startPolling = useCallback(() => {
        const interval = setInterval(() => {
            checkPayment(true);
        }, POLL_INTERVAL);

        setState(prev => ({ ...prev, pollInterval: interval }));
    }, []);

    const checkPayment = useCallback(async (isPolling = false) => {
        if (!booking || !state.qrData || (!isPolling && state.checkingPayment)) return;

        setState(prev => ({ ...prev, checkingPayment: true }));

        try {
            const response = await receptionAPI.checkCPayPayment({
                booking_code: booking.booking_code,
                amount: Number(booking.remaining_balance_vnd ?? 0)
            });

            if (response && response.success && response.transaction) {
                // transaction found via cPay check
                const tx = response.transaction;

                // Try backend verification first to create a proper payment record
                try {
                    const verifyRes = await paymentAPI.verifyVietQRPayment({
                        booking_code: booking.booking_code,
                        transaction_id: tx.transaction_id || tx.transactionId || tx.id,
                        amount: Number(booking.remaining_balance_vnd ?? 0)
                    });

                    if (verifyRes && verifyRes.success) {
                        clearIntervals();
                        setState(prev => ({
                            ...prev,
                            checkingPayment: false,
                            paymentSuccess: true,
                            currentStep: 2
                        }));
                        announceToScreenReader('Thanh toán chuyển khoản thành công và đã tạo bản ghi thanh toán');
                        message.success('Thanh toán thành công và đã ghi nhận trên hệ thống!');

                        setTimeout(() => {
                            onSuccess();
                            handleClose();
                        }, 1500);
                        return;
                    }
                } catch (err) {
                    console.warn('verifyVietQR failed:', err);
                }

                // If verify failed, fallback to mark as cash-collected so UI reflects payment
                try {
                    const markRes = await receptionAPI.markCashPaid({
                        booking_id: booking.booking_id,
                        amount_vnd: Number(booking.remaining_balance_vnd ?? 0),
                        receipt_number: tx.transaction_id || tx.transactionId || tx.id,
                        notes: 'Auto-recorded after VietQR detection'
                    });

                    if (markRes && markRes.success) {
                        clearIntervals();
                        setState(prev => ({
                            ...prev,
                            checkingPayment: false,
                            paymentSuccess: true,
                            currentStep: 2
                        }));
                        announceToScreenReader('Thanh toán thành công và đã ghi nhận giống tiền mặt');
                        message.success('Thanh toán được ghi nhận như tiền mặt.');

                        setTimeout(() => {
                            onSuccess();
                            handleClose();
                        }, 1500);
                        return;
                    } else {
                        throw new Error(markRes?.message || 'markCashPaid failed');
                    }
                } catch (err) {
                    console.error('Fallback markCashPaid error:', err);
                    clearIntervals();
                    setState(prev => ({ ...prev, checkingPayment: false, error: 'Thanh toán thành công nhưng không thể ghi nhận vào hệ thống' }));
                    message.error('Thanh toán đã thực hiện, nhưng không thể ghi nhận vào hệ thống. Vui lòng liên hệ kỹ thuật.');
                    return;
                }
            } else {
                setState(prev => ({ ...prev, checkingPayment: false }));
                if (!isPolling) {
                    message.info('Chưa tìm thấy giao dịch thanh toán');
                }
            }
        } catch (error: any) {
            console.error('Error checking payment:', error);
            setState(prev => ({
                ...prev,
                checkingPayment: false,
                error: error?.response?.data?.message || error?.message || 'Lỗi kiểm tra thanh toán'
            }));
        }
    }, [booking, state.qrData, state.checkingPayment, clearIntervals, announceToScreenReader, onSuccess]);

    const handleClose = useCallback(() => {
        clearIntervals();
        resetModal();
        onClose();
    }, [clearIntervals, resetModal, onClose]);

    const formatTime = useCallback((seconds: number) => {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
    }, []);

    // Effects
    useEffect(() => {
        if (!visible) {
            resetModal();
        }
    }, [visible, resetModal]);

    useEffect(() => {
        return () => {
            clearIntervals();
        };
    }, [clearIntervals]);

    if (!booking) return null;

    const renderStep1 = () => (
        <div>
            <Title level={4}>Chọn phương thức thanh toán</Title>

            <Card className="mb-4" style={{ backgroundColor: '#f9f9f9' }}>
                <Row gutter={16}>
                    <Col span={8}>
                        <Text strong>Mã booking:</Text>
                        <br />
                        <Space>
                            <Text>{booking.booking_code}</Text>
                            <Button
                                size="small"
                                type="link"
                                icon={<CopyOutlined />}
                                onClick={() => {
                                    try {
                                        navigator.clipboard.writeText(String(booking.booking_code));
                                        message.success('Đã sao chép mã booking');
                                    } catch (e) {
                                        message.error('Không thể sao chép');
                                    }
                                }}
                            />
                        </Space>
                    </Col>
                    <Col span={8}>
                        <Text strong>Khách hàng:</Text>
                        <br />
                        <Text>{booking.guest_name}</Text>
                    </Col>
                    <Col span={8}>
                        <Text strong>Tổng tiền:</Text>
                        <br />
                        <Text style={{ color: '#f5222d', fontSize: 16, fontWeight: 'bold' }}>
                            {formatCurrency(booking.total_price_vnd)}
                        </Text>
                    </Col>
                </Row>
                <Divider />
                <Row gutter={16}>
                    <Col span={12}>
                        <Text strong>Đã thanh toán:</Text>
                        <br />
                        <Text style={{ color: '#52c41a', fontSize: 14 }}>
                            {formatCurrency(Number(booking.collected_cash_vnd ?? (booking.total_price_vnd - (booking.remaining_balance_vnd ?? 0))))}
                        </Text>
                    </Col>
                    <Col span={12}>
                        <Text strong>Cần thanh toán:</Text>
                        <br />
                        <Text style={{ color: '#fa8c16', fontSize: 18, fontWeight: 'bold' }}>
                            {formatCurrency(Number(booking.remaining_balance_vnd ?? 0))}
                        </Text>
                    </Col>
                </Row>
            </Card>

            <Title level={5}>Chọn phương thức thanh toán:</Title>
            <Radio.Group
                value={state.paymentMethod}
                onChange={(e) => handleMethodSelection(e.target.value)}
                style={{ width: '100%' }}
            >
                <Space direction="vertical" style={{ width: '100%' }}>
                    <Radio value="cash">
                        <Card hoverable style={{ cursor: 'pointer' }}>
                            <Space>
                                <BankOutlined style={{ fontSize: 24, color: '#52c41a' }} />
                                <div>
                                    <Text strong>Tiền mặt</Text>
                                    <br />
                                    <Text type="secondary">Khách hàng thanh toán trực tiếp bằng tiền mặt</Text>
                                </div>
                            </Space>
                        </Card>
                    </Radio>
                    <Radio value="bank_transfer">
                        <Card hoverable style={{ cursor: 'pointer' }}>
                            <Space>
                                <QrcodeOutlined style={{ fontSize: 24, color: '#1890ff' }} />
                                <div>
                                    <Text strong>Chuyển khoản</Text>
                                    <br />
                                    <Text type="secondary">Quét mã QR để chuyển khoản ngân hàng</Text>
                                </div>
                            </Space>
                        </Card>
                    </Radio>
                </Space>
            </Radio.Group>
        </div>
    );

    const renderStep2 = () => (
        <div className="text-center">
            <Title level={4}>Quét mã QR để thanh toán</Title>

            <div style={{ marginBottom: 12 }}>
                <Text strong>Mã booking:</Text>
                <Space style={{ marginLeft: 8 }}>
                    <Text>{booking.booking_code}</Text>
                    <Button
                        size="small"
                        type="link"
                        icon={<CopyOutlined />}
                        onClick={() => {
                            try {
                                navigator.clipboard.writeText(String(booking.booking_code));
                                message.success('Đã sao chép mã booking');
                            } catch (e) {
                                message.error('Không thể sao chép');
                            }
                        }}
                    />
                </Space>
            </div>

            {state.qrData && (
                <>
                    <Card style={{ marginBottom: 16 }}>
                        <Statistic
                            title="Số tiền cần thanh toán"
                            value={state.qrData.amount}
                            formatter={(value) => formatCurrency(Number(value))}
                            valueStyle={{ color: '#fa8c16', fontSize: 24 }}
                        />
                    </Card>

                    {state.qrData.vietqr_url && (
                        <div style={{ marginBottom: 16 }}>
                            <Image
                                src={state.qrData.vietqr_url}
                                alt="QR Code thanh toán"
                                style={{ maxWidth: 280 }}
                                preview={false}
                            />
                        </div>
                    )}

                    <Card>
                        <Space direction="vertical" align="center">
                            <ClockCircleOutlined style={{ fontSize: 24, color: state.timeRemaining <= 60 ? '#ff4d4f' : '#1890ff' }} />
                            <Text>Thời gian còn lại:</Text>
                            <Text
                                style={{
                                    fontSize: 18,
                                    fontWeight: 'bold',
                                    color: state.timeRemaining <= 60 ? '#ff4d4f' : '#52c41a'
                                }}
                            >
                                {formatTime(state.timeRemaining)}
                            </Text>
                        </Space>
                    </Card>

                    <Space style={{ marginTop: 16 }}>
                        <Button
                            type="primary"
                            onClick={() => checkPayment(false)}
                            loading={state.checkingPayment}
                        >
                            Kiểm tra thanh toán
                        </Button>
                        <Button onClick={generateQRCode}>
                            Tạo mã QR mới
                        </Button>
                    </Space>
                </>
            )}
        </div>
    );

    const renderStep3 = () => (
        <div className="text-center">
            <Result
                status="success"
                title="Thanh toán thành công!"
                subTitle={`Đã thanh toán ${formatCurrency(Number(booking.remaining_balance_vnd ?? 0))} cho booking ${booking.booking_code}`}
                extra={[
                    <Tag key="amount" color="green" style={{ fontSize: 16, padding: '8px 16px' }}>
                        {formatCurrency(Number(booking.remaining_balance_vnd ?? 0))}
                    </Tag>
                ]}
            />
        </div>
    );

    const steps = [
        {
            title: 'Phương thức',
            icon: <DollarOutlined />,
            content: renderStep1(),
        },
        {
            title: 'Thanh toán',
            icon: <QrcodeOutlined />,
            content: renderStep2(),
        },
        {
            title: 'Hoàn thành',
            icon: <CheckCircleOutlined />,
            content: renderStep3(),
        },
    ];

    return (
        <Modal
            title={`Thanh toán tại khách sạn - ${booking.booking_code}`}
            open={visible}
            onCancel={handleClose}
            width={700}
            footer={
                state.currentStep === 0 ? (
                    <Space>
                        <Button onClick={handleClose}>Hủy</Button>
                        <Button
                            type="primary"
                            onClick={handleNext}
                            disabled={!state.paymentMethod}
                            loading={state.loading}
                        >
                            {state.paymentMethod === 'cash' ? 'Xác nhận thanh toán' : 'Tạo mã QR'}
                        </Button>
                    </Space>
                ) : null
            }
            destroyOnClose
        >
            <div
                ref={liveRegionRef}
                className="sr-only"
                aria-live="polite"
                aria-atomic="true"
            />

            <Spin spinning={state.loading}>
                <Steps current={state.currentStep} style={{ marginBottom: 24 }}>
                    {steps.map(item => (
                        <Step key={item.title} title={item.title} icon={item.icon} />
                    ))}
                </Steps>

                {state.error && (
                    <Card style={{ marginBottom: 16, borderColor: '#ff4d4f' }}>
                        <Text type="danger">{state.error}</Text>
                    </Card>
                )}

                <div>
                    {steps[state.currentStep].content}
                </div>
            </Spin>
        </Modal>
    );
};

export default HotelPaymentModal;

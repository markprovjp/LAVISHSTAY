import React, { useState, useEffect, useRef } from 'react';
import { Card, Button, Alert, Progress, Typography, Space, Timeline, message } from 'antd';
import { CheckCircleOutlined, ClockCircleOutlined, ExclamationCircleOutlined } from '@ant-design/icons';
import { paymentService, PaymentTransaction } from '../../services/paymentService';

const { Text, Title } = Typography;

interface PaymentCheckProps {
    bookingCode: string;
    expectedAmount: number;
    onPaymentConfirmed: (transaction: PaymentTransaction) => void;
    onCancel: () => void;
    isVisible: boolean;
    autoStartImmediate?: boolean;
}

export const PaymentCheck: React.FC<PaymentCheckProps> = ({
    bookingCode,
    expectedAmount,
    onPaymentConfirmed,
    onCancel,
    isVisible
    , autoStartImmediate
}) => {
    const [isChecking, setIsChecking] = useState(false);
    const [checkProgress, setCheckProgress] = useState(0);
    const [currentAttempt, setCurrentAttempt] = useState(0);
    const [maxAttempts] = useState(6); // 6 attempts = 1 minute
    const [checkHistory, setCheckHistory] = useState<Array<{
        time: string;
        status: 'checking' | 'not_found' | 'found' | 'error';
        message: string;
    }>>([]);
    const [isAutoChecking, setIsAutoChecking] = useState(false);
    const autoIntervalRef = useRef<number | null>(null);
    const autoTimeoutRef = useRef<number | null>(null);
    const checkingNowRef = useRef(false);

    const formatVND = (amount: number) => {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND',
            minimumFractionDigits: 0,
        }).format(amount);
    };

    const addToHistory = (status: 'checking' | 'not_found' | 'found' | 'error', message: string) => {
        const newEntry = {
            time: new Date().toLocaleTimeString('vi-VN'),
            status,
            message
        };
        setCheckHistory(prev => [newEntry, ...prev.slice(0, 4)]); // Keep only last 5 entries
    };

    // Single check operation used by both manual and auto check
    const performSingleCheck = async () => {
        if (checkingNowRef.current) return null;
        checkingNowRef.current = true;
        try {
            addToHistory('checking', 'Đang kiểm tra giao dịch...');
            const result = await paymentService.findPaymentByBookingCode(bookingCode, expectedAmount);
            console.log('🔍 PaymentCheck single check result:', result);
            if (result.found && result.transaction) {
                addToHistory('found', 'Đã tìm thấy giao dịch thanh toán!');
                message.success('Thanh toán đã được xác nhận!');
                onPaymentConfirmed(result.transaction);
                return { found: true, transaction: result.transaction };
            } else {
                addToHistory('not_found', result.message || 'Không tìm thấy giao dịch');
                return { found: false };
            }
        } catch (error) {
            console.error('Payment check error:', error);
            addToHistory('error', 'Lỗi khi kiểm tra thanh toán');
            return { found: false };
        } finally {
            checkingNowRef.current = false;
        }
    };

    const checkPayment = async () => {
        // Manual immediate check
        if (isChecking) return;
        setIsChecking(true);
        setCheckProgress(0);
        setCurrentAttempt(prev => prev + 1);
        try {
            const r = await performSingleCheck();
            if (r?.found) {
                // stop auto if running
                stopAutoChecking();
            }
        } finally {
            setIsChecking(false);
            setCheckProgress(100);
        }
    };

    const startAutoChecking = (immediate: boolean = false) => {
        // clear any existing
        stopAutoChecking();
        if (immediate) {
            // start immediately and then poll every 5s
            setIsAutoChecking(true);
            performSingleCheck().then(res => {
                if (res?.found) {
                    stopAutoChecking();
                }
            });
            autoIntervalRef.current = window.setInterval(async () => {
                const res = await performSingleCheck();
                if (res?.found) {
                    stopAutoChecking();
                }
            }, 5000);
            return;
        }

        // initial delay 10s then every 5s
        autoTimeoutRef.current = window.setTimeout(() => {
            setIsAutoChecking(true);
            // perform immediate check after initial delay
            performSingleCheck().then(res => {
                if (res?.found) {
                    stopAutoChecking();
                }
            });
            autoIntervalRef.current = window.setInterval(async () => {
                const res = await performSingleCheck();
                if (res?.found) {
                    stopAutoChecking();
                }
            }, 5000);
        }, 10000);
    };

    const stopAutoChecking = () => {
        setIsAutoChecking(false);
        if (autoIntervalRef.current) {
            window.clearInterval(autoIntervalRef.current);
            autoIntervalRef.current = null;
        }
        if (autoTimeoutRef.current) {
            window.clearTimeout(autoTimeoutRef.current);
            autoTimeoutRef.current = null;
        }
    };

    const stopChecking = () => {
        setIsChecking(false);
        addToHistory('error', 'Đã dừng kiểm tra thanh toán');
    };

    useEffect(() => {
        if (isVisible) {
            // Auto start checking when component becomes visible: respect autoStartImmediate flag
            startAutoChecking(Boolean(autoStartImmediate));
        } else {
            // stop when hidden
            stopAutoChecking();
        }

        return () => {
            // cleanup any timers
            stopAutoChecking();
            setIsChecking(false);
        };
    }, [isVisible, autoStartImmediate]);

    // If parent asked for immediate start via prop, start immediately when visible
    useEffect(() => {
        if (isVisible && autoStartImmediate) {
            startAutoChecking(true);
        }
    }, [autoStartImmediate]);

    if (!isVisible) return null;

    const getStatusIcon = (status: string) => {
        switch (status) {
            case 'checking':
                return <ClockCircleOutlined style={{ color: '#1890ff' }} />;
            case 'found':
                return <CheckCircleOutlined style={{ color: '#52c41a' }} />;
            case 'error':
                return <ExclamationCircleOutlined style={{ color: '#ff4d4f' }} />;
            default:
                return <ClockCircleOutlined style={{ color: '#faad14' }} />;
        }
    };

    return (
        <Card
            title="Kiểm tra thanh toán VietQR"
            className="mt-4"
            extra={
                <Space>
                    {isChecking && (
                        <Button size="small" onClick={stopChecking}>
                            Dừng kiểm tra
                        </Button>
                    )}
                    <Button
                        size="small"
                        onClick={checkPayment}
                        disabled={isChecking}
                        loading={isChecking}
                    >
                        Kiểm tra lại
                    </Button>
                    {!isAutoChecking ? (
                        <Button size="small" type="default" onClick={() => startAutoChecking(false)}>
                            Bắt đầu tự động
                        </Button>
                    ) : (
                        <Button size="small" danger onClick={() => stopAutoChecking()}>
                            Dừng tự động
                        </Button>
                    )}
                </Space>
            }
        >
            <Space direction="vertical" style={{ width: '100%' }}>
                <Alert
                    message="Thông tin thanh toán"
                    description={
                        <div>
                            <p><strong>Mã đặt phòng:</strong> {bookingCode}</p>
                            <p><strong>Số tiền:</strong> {formatVND(expectedAmount)}</p>
                            <p><strong>Nội dung chuyển khoản:</strong> Thanh toan dat phong {bookingCode}</p>
                        </div>
                    }
                    type="info"
                    showIcon
                />

                {isAutoChecking ? (
                    <Text style={{ color: '#389e0d', fontWeight: 600 }}>Đang tự động kiểm tra (mỗi 5s)</Text>
                ) : (
                    <Text type="secondary">Chưa bật tự động kiểm tra</Text>
                )}

                {isChecking && (
                    <div>
                        <Text>Đang kiểm tra thanh toán... ({currentAttempt}/{maxAttempts})</Text>
                        <Progress
                            percent={checkProgress}
                            status={isChecking ? 'active' : 'normal'}
                            strokeColor={{
                                '0%': '#108ee9',
                                '100%': '#87d068',
                            }}
                        />
                    </div>
                )}

                {checkHistory.length > 0 && (
                    <div>
                        <Title level={5}>Lịch sử kiểm tra:</Title>
                        <Timeline
                            mode="left"
                            items={checkHistory.map((entry) => ({
                                dot: getStatusIcon(entry.status),
                                children: (
                                    <div>
                                        <Text strong>{entry.time}</Text>
                                        <br />
                                        <Text>{entry.message}</Text>
                                    </div>
                                ),
                            }))}
                        />
                    </div>
                )}

                <Space>
                    <Button onClick={onCancel} disabled={isChecking}>
                        Hủy và quay lại
                    </Button>
                    <Button
                        type="primary"
                        onClick={checkPayment}
                        loading={isChecking}
                        disabled={isChecking}
                    >
                        {isChecking ? 'Đang kiểm tra...' : 'Kiểm tra thanh toán'}
                    </Button>
                </Space>
            </Space>
        </Card>
    );
};

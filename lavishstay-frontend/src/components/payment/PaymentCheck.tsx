import React, { useState, useEffect } from 'react';
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
}

export const PaymentCheck: React.FC<PaymentCheckProps> = ({
    bookingCode,
    expectedAmount,
    onPaymentConfirmed,
    onCancel,
    isVisible
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

    const checkPayment = async () => {
        if (isChecking) return;

        setIsChecking(true);
        setCheckProgress(0);
        setCurrentAttempt(0);
        setCheckHistory([]);

        addToHistory('checking', 'Bắt đầu kiểm tra thanh toán...');

        try {
            for (let attempt = 1; attempt <= maxAttempts; attempt++) {
                setCurrentAttempt(attempt);
                setCheckProgress((attempt / maxAttempts) * 100);

                addToHistory('checking', `Lần kiểm tra ${attempt}/${maxAttempts}...`);

                const result = await paymentService.findPaymentByBookingCode(bookingCode, expectedAmount);

                console.log(`🔍 PaymentCheck attempt ${attempt}: result =`, result);

                if (result.found && result.transaction) {
                    addToHistory('found', 'Đã tìm thấy giao dịch thanh toán!');
                    message.success('Thanh toán đã được xác nhận!');
                    console.log('🎉 PaymentCheck: Payment found, calling onPaymentConfirmed with:', result.transaction);
                    onPaymentConfirmed(result.transaction);
                    setIsChecking(false);
                    return;
                } else {
                    console.log(`❌ PaymentCheck attempt ${attempt}: Payment not found`, result);
                    addToHistory('not_found', result.message);
                }

                // Wait 10 seconds before next check (except for last attempt)
                if (attempt < maxAttempts) {
                    for (let i = 10; i > 0; i--) {
                        await new Promise(resolve => setTimeout(resolve, 1000));
                        if (!isChecking) return; // Stop if cancelled
                    }
                }
            }

            addToHistory('not_found', 'Không tìm thấy giao dịch thanh toán sau tất cả các lần kiểm tra');
            message.warning('Không tìm thấy giao dịch thanh toán. Vui lòng kiểm tra lại hoặc liên hệ hỗ trợ.');

        } catch (error) {
            console.error('Payment check error:', error);
            addToHistory('error', 'Lỗi khi kiểm tra thanh toán');
            message.error('Lỗi khi kiểm tra thanh toán. Vui lòng thử lại.');
        } finally {
            setIsChecking(false);
            setCheckProgress(100);
        }
    };

    const stopChecking = () => {
        setIsChecking(false);
        addToHistory('error', 'Đã dừng kiểm tra thanh toán');
    };

    useEffect(() => {
        if (isVisible) {
            // Auto start checking when component becomes visible
            checkPayment();
        }

        return () => {
            setIsChecking(false);
        };
    }, [isVisible]);

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

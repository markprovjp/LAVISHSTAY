import React, { useState, useEffect } from 'react';
import { Card, Radio, Space, Row, Col, Alert, Button, Divider, Descriptions, Typography, Image } from 'antd';
import { QrcodeOutlined, BankOutlined, CreditCardOutlined } from '@ant-design/icons';
import { PaymentCheck } from './PaymentCheck';
import { paymentService } from '../../services/paymentService';
import { PaymentTransaction } from '../../services/paymentService';
import { usePaymentSettings } from '../../hooks/usePaymentSetting';

const { Text } = Typography;

interface PaymentMethod {
    id: string;
    name: string;
    description: string;
    icon: React.ReactNode;
    badge?: string;
}

interface PaymentStepProps {
    selectedPaymentMethod: string;
    onPaymentMethodChange: (method: string) => void;
    onBack: () => void;
    onConfirmPayment: (transaction?: PaymentTransaction) => void;
    isProcessing: boolean;
    bookingCode: string;
    totalAmount: number;
    countdown: number;
    formatTime: (seconds: number) => string;
    generateVietQRUrl: (amount: number, content: string) => string;
}

// Format VND Currency
const formatVND = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
};

// Bank name mapping
const getBankName = (bankId: string): string => {
    const bankNames: { [key: string]: string } = {
        'VCB': 'Vietcombank',
        'TCB': 'Techcombank',
        'MBBank': 'MB Bank',
        'VTB': 'Vietinbank',
        'BIDV': 'BIDV',
        'ACB': 'ACB',
        'TPB': 'TPBank',
        'STB': 'Sacombank'
    };
    return bankNames[bankId] || bankId;
};

const PaymentStep: React.FC<PaymentStepProps> = ({
    selectedPaymentMethod,
    onPaymentMethodChange,
    onBack,
    onConfirmPayment,
    isProcessing,
    bookingCode,
    totalAmount,
    countdown,
    formatTime,
    generateVietQRUrl
}) => {
    const [showPaymentCheck, setShowPaymentCheck] = useState(false);
    const [autoStartImmediate, setAutoStartImmediate] = useState(false);

    // Get payment settings from hook
    const { settings, loading: settingsLoading, error: settingsError } = usePaymentSettings();

    const handleConfirmPayment = () => {
        if (selectedPaymentMethod === 'vietqr') {
            // show payment check UI and attempt an immediate check once
            setAutoStartImmediate(true);
            setShowPaymentCheck(true);

            (async () => {
                try {
                    const res = await paymentService.findPaymentByBookingCode(bookingCode, totalAmount);
                    if (res?.found && res.transaction) {
                        // payment already found, confirm immediately
                        handlePaymentConfirmed(res.transaction);
                        return;
                    }
                    // else leave PaymentCheck open which will auto-start polling
                } catch (err) {
                    // ignore and let PaymentCheck handle polling
                    console.warn('Immediate payment check failed, will rely on auto polling', err);
                }
            })();
        } else {
            onConfirmPayment();
        }
    };

    const handlePaymentConfirmed = (transaction: PaymentTransaction) => {
        console.log('💰 PaymentStep: Payment confirmed, calling parent callback with:', transaction);
        setShowPaymentCheck(false);
        setAutoStartImmediate(false);
        onConfirmPayment(transaction);
    };

    const handleCancelCheck = () => {
        setShowPaymentCheck(false);
        setAutoStartImmediate(false);
    };

    const paymentMethods: PaymentMethod[] = [];
    if (settings?.vietqr?.enabled) {
        paymentMethods.push({
            id: 'vietqr',
            name: 'VietQR',
            description: 'Quét mã QR để thanh toán nhanh chóng',
            icon: <QrcodeOutlined style={{ fontSize: '20px', color: '#1890ff' }} />,
            badge: 'Khuyến nghị'
        });
    }
    if (settings?.vnpay?.enabled) {
        paymentMethods.push({
            id: 'vnpay',
            name: 'VNPay',
            description: 'Thanh toán qua cổng VNPay bằng thẻ ATM hoặc Internet Banking',
            icon: <CreditCardOutlined style={{ fontSize: '20px', color: '#f5222d' }} />
        });
    }
    if (settings?.pay_at_hotel?.enabled) {
        paymentMethods.push({
            id: 'pay_at_hotel',
            name: 'Thanh toán tại khách sạn',
            description: 'Thanh toán trực tiếp tại quầy lễ tân khi nhận phòng',
            icon: <BankOutlined style={{ fontSize: '20px', color: '#52c41a' }} />
        });
    }

    const generatePaymentContent = () => {
        return `LAVISHSTAY_${bookingCode}`;
    };

    // Show loading state while settings are being fetched
    if (settingsLoading) {
        return (
            <Card className="w-full max-w-6xl mx-auto">
                <div className="flex justify-center items-center py-8">
                    <div className="text-center">
                        <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-4"></div>
                        <Text>Đang tải cấu hình thanh toán...</Text>
                    </div>
                </div>
            </Card>
        );
    }

    // Show error state if settings failed to load
    if (settingsError) {
        return (
            <Card className="w-full max-w-6xl mx-auto">
                <Alert
                    message="Lỗi tải cấu hình thanh toán"
                    description={settingsError}
                    type="error"
                    showIcon
                    className="mb-4"
                />
            </Card>
        );
    }

    return (
        <div className="space-y-4">
            {/* Payment Method Selection */}
            <Card title="Phương thức thanh toán" className="mb-4">
                <Radio.Group
                    value={selectedPaymentMethod}
                    onChange={(e) => onPaymentMethodChange(e.target.value)}
                    className="w-full"
                >
                    <Space direction="vertical" className="w-full" size="middle">
                        {paymentMethods.map(method => (
                            <Radio key={method.id} value={method.id} className="w-full">
                                <Card
                                    size="small"
                                    className={`ml-6 ${selectedPaymentMethod === method.id ? 'border-blue-500 bg-blue-50' : ''}`}
                                    style={{ borderWidth: selectedPaymentMethod === method.id ? 2 : 1 }}
                                >
                                    <Row align="middle" justify="space-between">
                                        <Col>
                                            <Space>
                                                {method.icon}
                                                <div>
                                                    <Text strong>{method.name}</Text>
                                                    <br />
                                                    <Text type="secondary" className="text-sm">
                                                        {method.description}
                                                    </Text>
                                                </div>
                                            </Space>
                                        </Col>
                                        <Col>
                                            {method.badge && (
                                                <div className="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">
                                                    {method.badge}
                                                </div>
                                            )}
                                        </Col>
                                    </Row>
                                </Card>
                            </Radio>
                        ))}
                    </Space>
                </Radio.Group>
            </Card>

            {/* VietQR Payment */}
            {selectedPaymentMethod === 'vietqr' && (
                <Card title="Quét mã QR để thanh toán" className="shadow-sm">
                    <Row gutter={24} align="top">
                        <Col span={14}>
                            <div className="text-center">
                                <div className=" rounded-lg  inline-block">
                                    <Image
                                        src={generateVietQRUrl(totalAmount, generatePaymentContent())}
                                        alt="VietQR Payment Code"
                                        width={450}
                                        height={550}
                                        preview={false}
                                        style={{ borderRadius: 8 }}
                                        fallback="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMIAAADDCAYAAADQvc6UAAABRWlDQ1BJQ0MgUHJvZmlsZQAAKJFjYGASSSwoyGFhYGDIzSspCnJ3UoiIjFJgf8LAwSDCIMogwMCcmFxc4BgQ4ANUwgCjUcG3awyMIPqyLsis7PPOq3QdDFcvjV3jOD1boQVTPQrgSkktTgbSf4A4LbmgqISBgTEFyFYuLykAsTuAbJEioKOA7DkgdjqEvQHEToKwj4DVhAQ5A9k3gGyB5IxEoBmML4BsnSQk8XQkNtReEOBxcfXxUQg1Mjc0dyHgXNJBSWpFCYh2zi+oLMpMzyhRcASGUqqCZ16yno6CkYGRAQMDKMwhqj/fAIcloxgHQqxAjIHBEugw5sUIsSQpBobtQPdLciLEVJYzMPBHMDBsayhILEqEO4DxG0txmrERhM29nYGBddr//5/DGRjYNRkY/l7////39v///y4Dmn+LgeHANwDrkl1AuO+pmgAAADhlWElmTU0AKgAAAAgAAYdpAAQAAAABAAAAGgAAAAAAAqACAAQAAAABAAAAwqADAAQAAAABAAAAwwAAAAD9b/HnAAAHlklEQVR4Ae3dP3Ik1RnG4W+FgYxN"
                                    />
                                </div>
                            </div>
                        </Col>
                        <Col span={10}>
                            <div className="space-y-4">
                                <Alert
                                    message="Thông tin chuyển khoản"
                                    type="info"
                                    showIcon={false}
                                    className="mb-4"
                                    style={{
                                        backgroundColor: '#f6f8fa',
                                        border: '1px solid #e1e4e8',
                                        borderRadius: '8px'
                                    }}
                                />

                                <div className="space-y-3">
                                    <div className="flex justify-between items-center py-2 border-b border-gray-100">
                                        <Text className="text-gray-600">Ngân hàng:</Text>
                                        <Text strong>{getBankName(settings?.vietqr?.bank_id || 'MBBank')}</Text>
                                    </div>

                                    <div className="flex justify-between items-center py-2 border-b border-gray-100">
                                        <Text className="text-gray-600">Số tài khoản:</Text>
                                        <Text strong className="bg-blue-50 px-2 py-1 rounded text-blue-700 font-mono">
                                            {settings?.vietqr?.account_no || '0335920306'}
                                        </Text>
                                    </div>

                                    <div className="flex justify-between items-center py-2 border-b border-gray-100">
                                        <Text className="text-gray-600">Chủ tài khoản:</Text>
                                        <Text strong>{settings?.vietqr?.account_name || 'NGUYEN VAN QUYEN'}</Text>
                                    </div>

                                    <div className="flex justify-between items-center py-2 border-b border-gray-100">
                                        <Text className="text-gray-600">Số tiền:</Text>
                                        <Text strong className="text-red-600 text-lg">
                                            {formatVND(totalAmount)}
                                        </Text>
                                    </div>

                                    <div className="flex justify-between items-start py-2 border-b border-gray-100">
                                        <Text className="text-gray-600">Nội dung:</Text>
                                        <Text strong className="bg-green-50 px-2 py-1 rounded text-green-700 text-right font-mono">
                                            {generatePaymentContent()}
                                        </Text>
                                    </div>

                                    <div className="flex justify-between items-center py-2">
                                        <Text className="text-gray-600">Mã đặt phòng:</Text>
                                        <Text strong className="text-green-600 font-mono">
                                            {bookingCode}
                                        </Text>
                                    </div>
                                </div>
                                <Alert
                                    message={`Thời gian còn lại: ${formatTime(countdown)}`}
                                    description="Vui lòng hoàn tất thanh toán trong thời gian quy định"
                                    type="warning"
                                    showIcon
                                    className="mt-4"
                                />
                            </div>
                        </Col>
                    </Row>

                    <Divider />

                    <Space>
                        <Button onClick={onBack}>
                            Quay lại
                        </Button>
                        <Button
                            type="primary"
                            loading={isProcessing}
                            onClick={handleConfirmPayment}
                        >
                            Đã thanh toán
                        </Button>
                    </Space>

                    <PaymentCheck
                        bookingCode={bookingCode}
                        expectedAmount={totalAmount}
                        onPaymentConfirmed={handlePaymentConfirmed}
                        onCancel={handleCancelCheck}
                        isVisible={showPaymentCheck}
                        autoStartImmediate={autoStartImmediate}
                    />
                </Card>
            )}

            {/* VNPay Payment */}
            {selectedPaymentMethod === 'vnpay' && (
                <Card title="Thanh toán VNPay" className="shadow-sm">
                    <Alert
                        message="Thanh toán qua VNPay"
                        description="Bạn sẽ được chuyển hướng đến trang thanh toán VNPay để hoàn tất giao dịch bằng thẻ ATM hoặc Internet Banking."
                        type="info"
                        showIcon
                        className="mb-4"
                    />

                    <Descriptions column={1} size="small" className="mb-4">
                        <Descriptions.Item label="Mã đặt phòng">
                            <Text strong style={{ color: '#52c41a' }}>
                                {bookingCode}
                            </Text>
                        </Descriptions.Item>
                        <Descriptions.Item label="Tổng tiền cần thanh toán">
                            <Text strong style={{ color: '#f5222d', fontSize: '1.1em' }}>
                                {formatVND(totalAmount)}
                            </Text>
                        </Descriptions.Item>
                        <Descriptions.Item label="Phương thức">
                            <Text>Thẻ ATM, Internet Banking, Ví điện tử</Text>
                        </Descriptions.Item>
                    </Descriptions>

                    <Alert
                        message={`Thời gian còn lại: ${formatTime(countdown)}`}
                        description="Vui lòng hoàn tất thanh toán trong thời gian quy định"
                        type="warning"
                        showIcon
                        className="mb-4"
                    />

                    <Divider />

                    <Space>
                        <Button onClick={onBack}>
                            Quay lại
                        </Button>
                        <Button
                            type="primary"
                            loading={isProcessing}
                            onClick={handleConfirmPayment}
                        >
                            Thanh toán VNPay
                        </Button>
                    </Space>
                </Card>
            )}

            {/* Pay at Hotel */}
            {selectedPaymentMethod === 'pay_at_hotel' && (
                <Card title="Thanh toán tại khách sạn">
                    <Alert
                        message="Thanh toán tại khách sạn"
                        description="Bạn sẽ thanh toán trực tiếp tại quầy lễ tân khi nhận phòng. Vui lòng mang theo giấy tờ tùy thân và thông tin đặt phòng."
                        type="info"
                        showIcon
                        className="mb-4"
                    />

                    <Descriptions column={1} size="small" className="mb-4">
                        <Descriptions.Item label="Mã đặt phòng">
                            <Text strong style={{ color: '#52c41a' }}>
                                {bookingCode}
                            </Text>
                        </Descriptions.Item>
                        <Descriptions.Item label="Tổng tiền cần thanh toán">
                            <Text strong style={{ color: '#f5222d', fontSize: '1.1em' }}>
                                {formatVND(totalAmount)}
                            </Text>
                        </Descriptions.Item>
                        <Descriptions.Item label="Hình thức thanh toán">
                            <Text>Tiền mặt hoặc thẻ tín dụng/ghi nợ</Text>
                        </Descriptions.Item>
                    </Descriptions>

                    <Divider />

                    <Space>
                        <Button onClick={onBack}>
                            Quay lại
                        </Button>
                        <Button
                            type="primary"
                            loading={isProcessing}
                            onClick={handleConfirmPayment}
                        >
                            Xác nhận đặt phòng
                        </Button>
                    </Space>
                </Card>
            )}
        </div>
    );
};

export default PaymentStep;
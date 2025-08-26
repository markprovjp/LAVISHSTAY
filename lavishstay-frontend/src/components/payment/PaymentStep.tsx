import React, { useState, useEffect } from 'react';
import { Card, Radio, Space, Row, Col, Alert, Button, Divider, Descriptions, Typography, Image } from 'antd';
import { QrcodeOutlined, BankOutlined, CreditCardOutlined } from '@ant-design/icons';
import { PaymentCheck } from './PaymentCheck';
import { PaymentTransaction } from '../../services/paymentService';
import { usePaymentSettings } from '../../hooks/usePaymentSetting';

const { Text } = Typography;

interface PaymentStepProps {
    selectedPaymentMethod: string;
    onPaymentMethodChange: (method: string) => void;
    onBack: () => void;
    // onConfirmPayment may receive either a transaction or the full check result (with message/applied)
    onConfirmPayment: (result?: { found?: boolean; transaction?: PaymentTransaction; message?: string; applied?: boolean } | PaymentTransaction) => void;
    isProcessing: boolean;
    bookingCode: string;
    totalAmount: number;
    isDepositMode?: boolean;
    fullAmount?: number;
    countdown: number;
    formatTime: (seconds: number) => string;
    generateVietQRUrl: (amount: number, content: string) => string;
}

const formatVND = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
};

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
    isDepositMode = false,
    fullAmount,
    countdown,
    formatTime,
    generateVietQRUrl
}) => {
    const [showPaymentCheck, setShowPaymentCheck] = useState(false);
    const [autoStartImmediate, setAutoStartImmediate] = useState(false);

    const { settings, loading: settingsLoading, error: settingsError } = usePaymentSettings();

    const paymentMethods: Array<{ id: string; name: string; description: string; icon: React.ReactNode; badge?: string }> = [];
    if (settings?.vietqr?.enabled) paymentMethods.push({ id: 'vietqr', name: 'VietQR', description: 'Quét mã QR để thanh toán', icon: <QrcodeOutlined style={{ fontSize: 20, color: '#1890ff' }} />, badge: 'Khuyến nghị' });
    if (settings?.pay_at_hotel?.enabled) paymentMethods.push({ id: 'pay_at_hotel', name: 'Thanh toán tại khách sạn', description: 'Thanh toán trực tiếp tại quầy lễ tân (yêu cầu đặt cọc 50%)', icon: <BankOutlined style={{ fontSize: 20, color: '#52c41a' }} /> });

    const generatePaymentContent = () => `LAVISHSTAY_${bookingCode}`;

    // Determine the effective method to display: prefer parent's selection; if none, default to enabled VietQR
    const effectiveMethod = selectedPaymentMethod || (settings?.vietqr?.enabled ? 'vietqr' : selectedPaymentMethod);

    // If parent didn't provide a selected method, notify parent to set default to vietqr (only when enabled)
    useEffect(() => {
        if (!selectedPaymentMethod && settings && settings.vietqr && settings.vietqr.enabled) {
            onPaymentMethodChange('vietqr');
        }
    }, [settings, selectedPaymentMethod, onPaymentMethodChange]);

    const handleConfirmPayment = () => {
        if (selectedPaymentMethod === 'vietqr' || selectedPaymentMethod === 'pay_at_hotel') {
            setAutoStartImmediate(true);
            setShowPaymentCheck(true);
        } else {
            onConfirmPayment();
        }
    };

    const handlePaymentConfirmed = (result?: { found?: boolean; transaction?: PaymentTransaction; message?: string; applied?: boolean }) => {
        setShowPaymentCheck(false);
        setAutoStartImmediate(false);
        // Forward full result to parent so it can decide whether to call verify endpoint
        onConfirmPayment(result);
    };

    const handleCancelCheck = () => {
        setShowPaymentCheck(false);
        setAutoStartImmediate(false);
    };

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

    if (settingsError) {
        return (
            <Card className="w-full max-w-6xl mx-auto">
                <Alert message="Lỗi tải cấu hình thanh toán" description={settingsError} type="error" showIcon />
            </Card>
        );
    }

    return (
        <div className="space-y-4">
            <Card title="Phương thức thanh toán" className="mb-4">
                <Radio.Group value={effectiveMethod} onChange={(e) => onPaymentMethodChange(e.target.value)} className="w-full">
                    <Space direction="vertical" className="w-full" size="middle">
                        {paymentMethods.map(method => (
                            <Radio key={method.id} value={method.id} className="w-full">
                                <Card size="small" className={`ml-6 ${selectedPaymentMethod === method.id ? 'border-blue-500 bg-blue-50' : ''}`} style={{ borderWidth: selectedPaymentMethod === method.id ? 2 : 1 }}>
                                    <Row align="middle" justify="space-between">
                                        <Col>
                                            <Space>
                                                {method.icon}
                                                <div>
                                                    <Text strong>{method.name}</Text>
                                                    <br />
                                                    <Text type="secondary" className="text-sm">{method.description}</Text>
                                                </div>
                                            </Space>
                                        </Col>
                                        <Col>
                                            {method.badge && (
                                                <div className="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">{method.badge}</div>
                                            )}
                                        </Col>
                                    </Row>
                                </Card>
                            </Radio>
                        ))}
                    </Space>
                </Radio.Group>
            </Card>

            {(effectiveMethod === 'vietqr' || effectiveMethod === 'pay_at_hotel') && (
                <Card title={isDepositMode ? 'Cọc VietQR (50%)' : 'Quét mã QR để thanh toán'} className="shadow-sm">
                    <Row gutter={24} align="top">
                        <Col span={14}>
                            <div className="text-center">
                                <div className=" rounded-lg inline-block">
                                    <Image src={generateVietQRUrl(totalAmount, generatePaymentContent())} alt="VietQR Payment Code" width={360} preview={false} style={{ borderRadius: 8 }} />
                                </div>
                            </div>
                        </Col>
                        <Col span={10}>
                            <div className="space-y-4">
                                <Alert message="Thông tin chuyển khoản" type="info" showIcon={false} className="mb-4" style={{ backgroundColor: '#f6f8fa', border: '1px solid #e1e4e8', borderRadius: 8 }} />

                                <div className="space-y-3">
                                    <div className="flex justify-between items-center py-2 border-b border-gray-100">
                                        <Text className="text-gray-600">Ngân hàng:</Text>
                                        <Text strong>{getBankName(settings?.vietqr?.bank_id || 'MBBank')}</Text>
                                    </div>

                                    <div className="flex justify-between items-center py-2 border-b border-gray-100">
                                        <Text className="text-gray-600">Số tài khoản:</Text>
                                        <Text strong className="bg-blue-50 px-2 py-1 rounded text-blue-700 font-mono">{settings?.vietqr?.account_no || '0335920306'}</Text>
                                    </div>

                                    <div className="flex justify-between items-center py-2 border-b border-gray-100">
                                        <Text className="text-gray-600">Chủ tài khoản:</Text>
                                        <Text strong>{settings?.vietqr?.account_name || 'NGUYEN VAN QUYEN'}</Text>
                                    </div>

                                    <div className="flex justify-between items-center py-2 border-b border-gray-100">
                                        <Text className="text-gray-600">Số tiền:</Text>
                                        <Text strong className="text-red-600 text-lg">
                                            {formatVND(totalAmount)}
                                            {isDepositMode && (<div className="text-sm text-gray-500 font-normal">(Cọc 50% - Phần còn lại thanh toán tại khách sạn)</div>)}
                                        </Text>
                                    </div>

                                    <div className="flex justify-between items-start py-2 border-b border-gray-100">
                                        <Text className="text-gray-600">Nội dung:</Text>
                                        <Text strong className="bg-green-50 px-2 py-1 rounded text-green-700 text-right font-mono">{generatePaymentContent()}</Text>
                                    </div>

                                    <div className="flex justify-between items-center py-2">
                                        <Text className="text-gray-600">Mã đặt phòng:</Text>
                                        <Text strong className="text-green-600 font-mono">{bookingCode}</Text>
                                    </div>
                                </div>

                                <Alert message={`Thời gian còn lại: ${formatTime(countdown)}`} description="Vui lòng hoàn tất thanh toán trong thời gian quy định" type="warning" showIcon className="mt-4" />
                            </div>
                        </Col>
                    </Row>

                    <Divider />

                    <Space>
                        <Button onClick={onBack}>Quay lại</Button>
                        <Button type="primary" loading={isProcessing} onClick={handleConfirmPayment}>{effectiveMethod === 'pay_at_hotel' ? 'Đã thanh toán (cọc)' : 'Đã thanh toán'}</Button>
                    </Space>

                    <PaymentCheck bookingCode={bookingCode} expectedAmount={totalAmount} onPaymentConfirmed={handlePaymentConfirmed} onCancel={handleCancelCheck} isVisible={showPaymentCheck} autoStartImmediate={autoStartImmediate} />
                </Card>
            )}

            {selectedPaymentMethod === 'vnpay' && (
                <Card title="Thanh toán VNPay" className="shadow-sm">
                    <Alert message="Thanh toán qua VNPay" description="Bạn sẽ được chuyển hướng đến trang thanh toán VNPay để hoàn tất giao dịch bằng thẻ ATM hoặc Internet Banking." type="info" showIcon className="mb-4" style={{ backgroundColor: '#f6f8fa', border: '1px solid #e1e4e8', borderRadius: 8 }} />

                    <Descriptions column={1} size="small" className="mb-4">
                        <Descriptions.Item label="Mã đặt phòng"><Text strong style={{ color: '#52c41a' }}>{bookingCode}</Text></Descriptions.Item>
                        <Descriptions.Item label="Tổng tiền cần thanh toán"><Text strong style={{ color: '#f5222d', fontSize: '1.1em' }}>{formatVND(totalAmount)}</Text></Descriptions.Item>
                        <Descriptions.Item label="Phương thức"><Text>Thẻ ATM, Internet Banking, Ví điện tử</Text></Descriptions.Item>
                    </Descriptions>

                    <Alert message={`Thời gian còn lại: ${formatTime(countdown)}`} description="Vui lòng hoàn tất thanh toán trong thời gian quy định" type="warning" showIcon className="mb-4" />

                    <Divider />

                    <Space>
                        <Button onClick={onBack}>Quay lại</Button>
                        <Button type="primary" loading={isProcessing} onClick={handleConfirmPayment}>Thanh toán VNPay</Button>
                    </Space>
                </Card>
            )}
        </div>
    );
};

export default PaymentStep;
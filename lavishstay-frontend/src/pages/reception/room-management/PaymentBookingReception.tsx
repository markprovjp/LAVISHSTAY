import React, { useState } from 'react';
import { Card, Button, Radio, Space, Row, Col, Divider, Alert, Descriptions, Typography, Image, message } from 'antd';
import { QrcodeOutlined, CreditCardOutlined, BankOutlined } from '@ant-design/icons';
import { useLocation, useNavigate } from 'react-router-dom';
import dayjs from 'dayjs';

const { Text, Title } = Typography;

const formatVND = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
};

const generateVietQRUrl = (amount: number, content: string) => {
    // Replace with your real QR code generator
    return `https://img.vietqr.io/image/MB-0335920306-compact2.png?amount=${amount}&addInfo=${content}`;
};

const formatTime = (seconds: number) => {
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    return `${m}:${s.toString().padStart(2, '0')}`;
};

const PaymentBookingReception: React.FC = () => {
    const location = useLocation();
    const navigate = useNavigate();
    const { selectedRooms = [], subtotal = 0, representatives = {}, dateRange = [] } = location.state || {};
    const [selectedPaymentMethod, setSelectedPaymentMethod] = useState('vietqr');
    const [isProcessing, setIsProcessing] = useState(false);
    const [bookingCode] = useState(() => {
        // Generate a fake booking code for demo
        return 'RCPT' + Math.floor(Math.random() * 1000000);
    });
    const [countdown, setCountdown] = useState(900); // 15 minutes

    React.useEffect(() => {
        if (countdown > 0) {
            const timer = setTimeout(() => setCountdown(countdown - 1), 1000);
            return () => clearTimeout(timer);
        }
    }, [countdown]);

    const handleConfirmPayment = () => {
        setIsProcessing(true);
        setTimeout(() => {
            setIsProcessing(false);
            message.success('Thanh toán thành công!');
            navigate('/reception/success', { state: { bookingCode } });
        }, 1500);
    };

    const handleBack = () => {
        navigate(-1);
    };

    const generatePaymentContent = () => {
        return `LAVISHSTAY_${bookingCode}`;
    };

    return (
        <div className="p-6 max-w-3xl mx-auto">
            <Title level={2} className="mb-6">Thanh toán đặt phòng lễ tân</Title>

            <Card title="Thông tin đặt phòng" className="mb-4">
                <Descriptions column={1} size="small">
                    <Descriptions.Item label="Ngày nhận phòng">
                        {dateRange[0] ? dayjs(dateRange[0]).format('DD/MM/YYYY') : '-'}
                    </Descriptions.Item>
                    <Descriptions.Item label="Ngày trả phòng">
                        {dateRange[1] ? dayjs(dateRange[1]).format('DD/MM/YYYY') : '-'}
                    </Descriptions.Item>
                    <Descriptions.Item label="Số phòng">
                        {selectedRooms.length}
                    </Descriptions.Item>
                    <Descriptions.Item label="Khách đại diện">
                        {Object.values(representatives).map((rep: any, idx) => (
                            <div key={idx}>{rep.fullName} - {rep.phoneNumber}</div>
                        ))}
                    </Descriptions.Item>
                </Descriptions>
            </Card>

            <Card title="Phương thức thanh toán" className="mb-4">
                <Radio.Group
                    value={selectedPaymentMethod}
                    onChange={e => setSelectedPaymentMethod(e.target.value)}
                    className="w-full"
                >
                    <Space direction="vertical" className="w-full" size="middle">
                        <Radio value="vietqr" className="w-full">
                            <Card size="small" className={`ml-6 ${selectedPaymentMethod === 'vietqr' ? 'border-blue-500 bg-blue-50' : ''}`} style={{ borderWidth: selectedPaymentMethod === 'vietqr' ? 2 : 1 }}>
                                <Row align="middle" justify="space-between">
                                    <Col>
                                        <Space>
                                            <QrcodeOutlined style={{ fontSize: 20, color: '#1890ff' }} /> VietQR
                                        </Space>
                                    </Col>
                                    <Col>
                                        <Text type="success">Khuyến nghị</Text>
                                    </Col>
                                </Row>
                            </Card>
                        </Radio>
                        <Radio value="pay_at_hotel" className="w-full">
                            <Card size="small" className={`ml-6 ${selectedPaymentMethod === 'pay_at_hotel' ? 'border-blue-500 bg-blue-50' : ''}`} style={{ borderWidth: selectedPaymentMethod === 'pay_at_hotel' ? 2 : 1 }}>
                                <Row align="middle" justify="space-between">
                                    <Col>
                                        <Space>
                                            <BankOutlined style={{ fontSize: 20, color: '#52c41a' }} /> Thanh toán tại khách sạn
                                        </Space>
                                    </Col>
                                </Row>
                            </Card>
                        </Radio>
                    </Space>
                </Radio.Group>
            </Card>

            {selectedPaymentMethod === 'vietqr' && (
                <Card title="Quét mã QR để thanh toán" className="shadow-sm mb-4">
                    <Row gutter={24} align="top">
                        <Col span={14}>
                            <div className="text-center">
                                <div className="rounded-lg inline-block">
                                    <Image
                                        src={generateVietQRUrl(subtotal, generatePaymentContent())}
                                        alt="VietQR Payment Code"
                                        width={300}
                                        height={300}
                                        preview={false}
                                        style={{ borderRadius: 8 }}
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
                                    style={{ backgroundColor: '#f6f8fa', border: '1px solid #e1e4e8', borderRadius: '8px' }}
                                />
                                <div className="space-y-3">
                                    <div className="flex justify-between items-center py-2 border-b border-gray-100">
                                        <Text className="text-gray-600">Ngân hàng:</Text>
                                        <Text strong>MB Bank</Text>
                                    </div>
                                    <div className="flex justify-between items-center py-2 border-b border-gray-100">
                                        <Text className="text-gray-600">Số tài khoản:</Text>
                                        <Text strong className="bg-blue-50 px-2 py-1 rounded text-blue-700 font-mono">0335920306</Text>
                                    </div>
                                    <div className="flex justify-between items-center py-2 border-b border-gray-100">
                                        <Text className="text-gray-600">Chủ tài khoản:</Text>
                                        <Text strong>NGUYEN VAN QUYEN</Text>
                                    </div>
                                    <div className="flex justify-between items-center py-2 border-b border-gray-100">
                                        <Text className="text-gray-600">Số tiền:</Text>
                                        <Text strong className="text-red-600 text-lg">{formatVND(subtotal)}</Text>
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
                        <Button onClick={handleBack}>Quay lại</Button>
                        <Button type="primary" loading={isProcessing} onClick={handleConfirmPayment}>Đã thanh toán</Button>
                    </Space>
                </Card>
            )}

            {selectedPaymentMethod === 'pay_at_hotel' && (
                <Card title="Thanh toán tại khách sạn" className="shadow-sm mb-4">
                    <Alert
                        message="Thanh toán tại khách sạn"
                        description="Bạn sẽ thanh toán trực tiếp tại quầy lễ tân khi nhận phòng. Vui lòng mang theo giấy tờ tùy thân và thông tin đặt phòng."
                        type="info"
                        showIcon
                        className="mb-4"
                    />
                    <Descriptions column={1} size="small" className="mb-4">
                        <Descriptions.Item label="Mã đặt phòng">
                            <Text strong style={{ color: '#52c41a' }}>{bookingCode}</Text>
                        </Descriptions.Item>
                        <Descriptions.Item label="Tổng tiền cần thanh toán">
                            <Text strong style={{ color: '#f5222d', fontSize: '1.1em' }}>{formatVND(subtotal)}</Text>
                        </Descriptions.Item>
                        <Descriptions.Item label="Hình thức thanh toán">
                            <Text>Tiền mặt hoặc thẻ tín dụng/ghi nợ</Text>
                        </Descriptions.Item>
                    </Descriptions>
                    <Divider />
                    <Space>
                        <Button onClick={handleBack}>Quay lại</Button>
                        <Button type="primary" loading={isProcessing} onClick={handleConfirmPayment}>Xác nhận đặt phòng</Button>
                    </Space>
                </Card>
            )}
        </div>
    );
};

export default PaymentBookingReception;

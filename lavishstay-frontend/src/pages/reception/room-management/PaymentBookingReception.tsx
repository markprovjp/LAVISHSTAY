import React, { useState, useEffect } from 'react';
import { Card, Button, Radio, Space, Row, Col, Divider, Alert, Descriptions, Typography, Image, App, Table } from 'antd';
import { QrcodeOutlined, BankOutlined } from '@ant-design/icons';
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
    return `https://img.vietqr.io/image/MB-0335920306-compact2.png?amount=${amount}&addInfo=${encodeURIComponent(content)}`;
};

const PaymentBookingReception: React.FC = () => {
    const location = useLocation();
    const navigate = useNavigate();
    const { message } = App.useApp();

    // Lấy bookingCode từ state hoặc query
    const bookingCode = location.state?.bookingCode || new URLSearchParams(window.location.search).get('bookingCode');
    const [bookingInfo, setBookingInfo] = useState<any>(null);
    const [selectedPaymentMethod, setSelectedPaymentMethod] = useState('vietqr');
    const [isProcessing, setIsProcessing] = useState(false);
    const [countdown, setCountdown] = useState(900); // 15 minutes
    const [loading, setLoading] = useState(true);

    // Lấy thông tin booking thực tế từ backend
    useEffect(() => {
        if (!bookingCode) {
            message.error('Không tìm thấy mã đặt phòng.');
            navigate('/reception');
            return;
        }

        const fetchBookingInfo = async () => {
            setLoading(true);
            try {
                // Import paymentAPI dynamically
                const { paymentAPI } = await import('../../../utils/api');
                const data = await paymentAPI.getBookingInfo(bookingCode);

                if (data.success && data.booking) {
                    setBookingInfo(data.booking);
                    setCountdown(data.booking.countdown || 900);
                } else {
                    message.error('Không tìm thấy thông tin đặt phòng.');
                    navigate('/reception');
                }
            } catch (error) {
                message.error('Lỗi khi lấy thông tin đặt phòng.');
                navigate('/reception');
            } finally {
                setLoading(false);
            }
        };

        fetchBookingInfo();
    }, [bookingCode, navigate]);

    // Countdown
    useEffect(() => {
        if (countdown > 0) {
            const timer = setTimeout(() => setCountdown(countdown - 1), 1000);
            return () => clearTimeout(timer);
        }
    }, [countdown]);

    // Poll trạng thái thanh toán nếu chọn VietQR
    useEffect(() => {
        if (bookingInfo && selectedPaymentMethod === 'vietqr' && bookingInfo.status !== 'paid') {
            const interval = setInterval(async () => {
                try {
                    // Import paymentAPI dynamically
                    const { paymentAPI } = await import('../../../utils/api');
                    const data = await paymentAPI.getPaymentStatus(bookingCode);

                    if (data.success && data.payment_status === 'confirmed') {
                        setBookingInfo((prev: any) => ({ ...prev, status: 'paid' }));
                        message.success('Thanh toán đã được xác nhận!');
                        clearInterval(interval);
                    }
                } catch (error) {
                    console.error('Error checking payment status:', error);
                }
            }, 10000);
            return () => clearInterval(interval);
        }
    }, [bookingInfo, selectedPaymentMethod, bookingCode]);

    const handleConfirmPayment = () => {
        setIsProcessing(true);
        setTimeout(() => {
            setIsProcessing(false);
            message.success('Thanh toán thành công!');
            navigate('/reception/payment-success', { state: { bookingCode } });
        }, 1500);
    };

    const handleBack = () => {
        navigate(-1);
    };

    const formatTime = (seconds: number) => {
        const m = Math.floor(seconds / 60);
        const s = seconds % 60;
        return `${m}:${s.toString().padStart(2, '0')}`;
    };

    if (loading || !bookingInfo) {
        return <div className="p-6 text-center">Đang tải thông tin đặt phòng...</div>;
    }

    const { rooms = [], representatives = {}, total_amount = 0, check_in, check_out, status, payment_content } = bookingInfo;
    // Sử dụng payment_content từ backend, fallback là format chuẩn
    const paymentContent = payment_content || `LAVISHSTAY_${bookingCode}`;

    return (
        <div className="p-6 ">
            <Title level={2} className="mb-6">Thanh toán đặt phòng lễ tân</Title>

            {/* Thông tin đặt phòng dưới dạng bảng */}
            <Card title="Thông tin đặt phòng" className="mb-4">
                <Descriptions column={2} size="small" className="mb-4">
                    <Descriptions.Item label="Mã đặt phòng">
                        <Text strong style={{ color: '#52c41a' }}>{bookingCode}</Text>
                    </Descriptions.Item>
                    <Descriptions.Item label="Trạng thái">
                        <Text strong type={status === 'paid' ? 'success' : 'warning'}>
                            {status === 'paid' ? 'Đã thanh toán' : 'Chờ thanh toán'}
                        </Text>
                    </Descriptions.Item>
                    <Descriptions.Item label="Ngày nhận phòng">
                        {check_in ? dayjs(check_in).format('DD/MM/YYYY') : '-'}
                    </Descriptions.Item>
                    <Descriptions.Item label="Ngày trả phòng">
                        {check_out ? dayjs(check_out).format('DD/MM/YYYY') : '-'}
                    </Descriptions.Item>
                </Descriptions>
                <Table
                    columns={[
                        {
                            title: 'Số phòng',
                            dataIndex: 'name',
                            key: 'name',
                            render: (text: string) => <Text strong>{text}</Text>
                        },
                        {
                            title: 'Loại phòng',
                            dataIndex: ['room_type', 'name'],
                            key: 'roomType',
                            render: (_: any, room: any) => <Text>{room.room_type?.name}</Text>
                        },
                        {
                            title: 'Check-in',
                            dataIndex: 'checkIn',
                            key: 'checkIn',
                            render: (date: string) => date ? dayjs(date).format('DD/MM/YYYY') : '-'
                        },
                        {
                            title: 'Check-out',
                            dataIndex: 'checkOut',
                            key: 'checkOut',
                            render: (date: string) => date ? dayjs(date).format('DD/MM/YYYY') : '-'
                        },
                        {
                            title: 'Số đêm',
                            dataIndex: 'nights',
                            key: 'nights',
                            align: 'center' as const,
                        },
                        {
                            title: 'Giá/đêm',
                            dataIndex: ['room_type', 'adjusted_price'],
                            key: 'basePrice',
                            render: (_: any, room: any) => formatVND(room.room_type?.adjusted_price || 0)
                        },
                        {
                            title: 'Tổng tiền',
                            dataIndex: 'totalPrice',
                            key: 'totalPrice',
                            render: (value: number, room: any) => formatVND(value || (room.room_type?.adjusted_price * (room.nights || 1)))
                        },
                        {
                            title: 'Người đại diện',
                            key: 'representative',
                            render: (_: any, room: any) => (
                                representatives[room.id]
                                    ? <span>{representatives[room.id].fullName} - {representatives[room.id].phoneNumber}</span>
                                    : <span className="text-gray-400 italic">Chưa có</span>
                            )
                        }
                    ]}
                    dataSource={rooms}
                    rowKey="id"
                    pagination={false}
                    bordered
                    scroll={{ x: 900 }}
                />
            </Card>

            <Card title="Phương thức thanh toán" className="mb-4">
                <Radio.Group
                    value={selectedPaymentMethod}
                    onChange={e => setSelectedPaymentMethod(e.target.value)}
                    optionType="button"
                    buttonStyle="solid"
                    className="w-full"
                >
                    <Space direction="horizontal" className="w-full" size="middle">
                        <Radio.Button value="vietqr" style={{ minWidth: 180, display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8 }}>
                            <QrcodeOutlined style={{ fontSize: 18, color: '#1890ff' }} /> VietQR
                        </Radio.Button>
                        <Radio.Button value="pay_at_hotel" style={{ minWidth: 180, display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8 }}>
                            <BankOutlined style={{ fontSize: 18, color: '#52c41a' }} /> Thanh toán tại khách sạn
                        </Radio.Button>
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
                                        src={generateVietQRUrl(total_amount, paymentContent)}
                                        alt="VietQR Payment Code"
                                        width="100%"
                                        height="100%"
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
                                        <Text strong className="text-red-600 text-lg">{formatVND(total_amount)}</Text>
                                    </div>
                                    <div className="flex justify-between items-start py-2 border-b border-gray-100">
                                        <Text className="text-gray-600">Nội dung:</Text>
                                        <Text strong className="bg-green-50 px-2 py-1 rounded text-green-700 text-right font-mono">{paymentContent}</Text>
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
                        <Button type="primary" loading={isProcessing} onClick={handleConfirmPayment} disabled={status === 'paid'}>
                            {status === 'paid' ? 'Đã thanh toán' : 'Đã chuyển khoản'}
                        </Button>
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
                            <Text strong style={{ color: '#f5222d', fontSize: '1.1em' }}>{formatVND(total_amount)}</Text>
                        </Descriptions.Item>
                        <Descriptions.Item label="Hình thức thanh toán">
                            <Text>Tiền mặt hoặc thẻ tín dụng/ghi nợ</Text>
                        </Descriptions.Item>
                    </Descriptions>
                    <Divider />
                    <Space>
                        <Button onClick={handleBack}>Quay lại</Button>
                        <Button type="primary" loading={isProcessing} onClick={handleConfirmPayment} disabled={status === 'paid'}>
                            {status === 'paid' ? 'Đã thanh toán' : 'Xác nhận đặt phòng'}
                        </Button>
                    </Space>
                </Card>
            )}
        </div>
    );
};

export default PaymentBookingReception;
